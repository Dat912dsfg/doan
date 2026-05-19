#include <WiFi.h>
#include <PubSubClient.h>
#include <DHT.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <ArduinoJson.h>
#include <time.h>

#define DHTPIN 15
#define DHTTYPE DHT22

#define PIR_PIN 13
#define MQ2_PIN 34
#define BUZZER 27
#define RELAY_PIN 26
#define BUZZER_FREQ 2000

DHT dht(DHTPIN, DHTTYPE);

const char* ssid = "Wokwi-GUEST";
const char* password = "";
const char* mqtt_server = "broker.hivemq.com";

// Update only this origin when the public tunnel changes.
const char* public_tunnel_origin = "https://shark-cant-concerts-rocket.trycloudflare.com";
const char* api_path_prefix = "/doan-main/public/api";

WiFiClient espClient;
PubSubClient client(espClient);

#define TOPIC_DATA "nhatro123/room2/sensor"
#define TOPIC_CONTROL "nhatro123/room2/control"
#define TOPIC_NOTIFICATION "nhatro123/room2/notification"

bool manualMode = false;
bool lastManualMode = false;
bool buzzerState = false;

#define MAX_RULES 20
struct Rule {
  String condition;
  String action;
};

Rule rules[MAX_RULES];
int ruleCount = 0;
String lastSyncVersion = "";
unsigned long lastSyncAt = 0;
unsigned long lastSensorReadAt = 0;
unsigned long lastPublishAt = 0;
unsigned long lastHeartbeatPostAt = 0;
unsigned long lastReconnectAttemptAt = 0;
unsigned long lastWiFiCheckAt = 0;
bool forceSyncRequested = false;
const unsigned long syncIntervalMs = 300000;  // 5 minutes instead of 30s
const unsigned long heartbeatIntervalMs = 30000;  // Reduced from 2s to 30s to prevent blocking
const unsigned long sensorReadIntervalMs = 2000;
const unsigned long publishIntervalMs = 1000;
const unsigned long mqttReconnectRetryIntervalMs = 2000;
const unsigned long wifiCheckIntervalMs = 10000;  // Check WiFi every 10s
const uint16_t httpRequestTimeoutMs = 1500;  // Reduced from 2500ms to prevent blocking
const uint16_t mqttConnectionTimeoutMs = 500;

float lastTemp = NAN;
float lastHum = NAN;
int lastPir = 0;
int lastGas = 0;
bool hasSensorData = false;

String extractHostFromUrl(const String& url) {
  int schemePos = url.indexOf("://");
  int hostStart = schemePos >= 0 ? schemePos + 3 : 0;
  int hostEnd = url.indexOf('/', hostStart);

  if (hostEnd < 0) {
    hostEnd = url.length();
  }

  return url.substring(hostStart, hostEnd);
}

String buildApiUrl(const String& pathAndQuery) {
  return String(public_tunnel_origin) + String(api_path_prefix) + pathAndQuery;
}

String sanitizeJsonForEsp(const String& raw) {
  int start = raw.indexOf('{');
  int end = raw.lastIndexOf('}');
  String json = raw;

  if (start >= 0 && end > start) {
    json = raw.substring(start, end + 1);
  }

  String clean;
  clean.reserve(json.length());

  for (size_t i = 0; i < json.length(); i++) {
    uint8_t b = (uint8_t)json[i];

    // Keep ASCII printable chars and common whitespace only.
    if ((b >= 32 && b <= 126) || b == '\n' || b == '\r' || b == '\t') {
      clean += (char)b;
    }
  }

  return clean;
}
String extractField(const String& source, const String& key) {
  String token = key + "=";
  int start = source.indexOf(token);
  if (start < 0) return "";

  start += token.length();
  int end = source.indexOf('|', start);
  if (end < 0) end = source.length();

  String out = source.substring(start, end);
  out.trim();
  return out;
}

bool compareValues(float lhs, const String& op, float rhs) {
  if (op == ">") return lhs > rhs;
  if (op == "<") return lhs < rhs;
  if (op == "=") return fabs(lhs - rhs) < 0.001f;
  return false;
}

int parseMinutes(const String& hhmm) {
  if (hhmm.length() < 5) return -1;

  int hh = hhmm.substring(0, 2).toInt();
  int mm = hhmm.substring(3, 5).toInt();
  if (hh < 0 || hh > 23 || mm < 0 || mm > 59) return -1;

  return hh * 60 + mm;
}

int currentMinutesLocal() {
  struct tm timeinfo;
  if (!getLocalTime(&timeinfo, 100)) {
    return -1;
  }

  return (timeinfo.tm_hour * 60) + timeinfo.tm_min;
}

String currentDayToken() {
  struct tm timeinfo;
  if (!getLocalTime(&timeinfo, 100)) {
    return "";
  }

  switch (timeinfo.tm_wday) {
    case 0: return "sun";
    case 1: return "mon";
    case 2: return "tue";
    case 3: return "wed";
    case 4: return "thu";
    case 5: return "fri";
    case 6: return "sat";
    default: return "";
  }
}

bool isDayAllowed(const String& csvDays) {
  String current = currentDayToken();
  if (current == "") return false;
  if (csvDays.length() == 0) return true;

  String haystack = "," + csvDays + ",";
  haystack.toLowerCase();
  String needle = "," + current + ",";

  return haystack.indexOf(needle) >= 0;
}

bool evaluateCondition(const String& condition, float temp, float hum, int gas, int pir) {
  if (condition.startsWith("sensor|")) {
    String type = extractField(condition, "type");
    String op = extractField(condition, "op");
    float value = extractField(condition, "value").toFloat();

    if (type == "temp") return compareValues(temp, op, value);
    if (type == "hum") return compareValues(hum, op, value);
    if (type == "gas") return compareValues((float)gas, op, value);
    if (type == "pir") return compareValues((float)pir, op, value);
    return false;
  }

  if (condition.startsWith("timer|")) {
    int nowMins = currentMinutesLocal();
    if (nowMins < 0) return false;

    int start = parseMinutes(extractField(condition, "start"));
    int end = parseMinutes(extractField(condition, "end"));
    String days = extractField(condition, "days");
    if (start < 0 || end < 0) return false;
    if (!isDayAllowed(days)) return false;

    if (start <= end) {
      return nowMins >= start && nowMins <= end;
    }

    return nowMins >= start || nowMins <= end;
  }

  return false;
}

void setBuzzerState(bool enabled) {
  if (buzzerState == enabled) return;

  buzzerState = enabled;

  if (enabled) {
    tone(BUZZER, BUZZER_FREQ);
  } else {
    noTone(BUZZER);
  }
}

void publishSensorData() {
  if (!client.connected() || !hasSensorData) return;

  String payload = "{";
  payload += "\"nhiet_do\":" + String(lastTemp, 2) + ",";
  payload += "\"do_am\":" + String(lastHum, 2) + ",";
  payload += "\"gas\":" + String(lastGas) + ",";
  payload += "\"pir\":" + String(lastPir) + ",";
  payload += "\"relay\":" + String(digitalRead(RELAY_PIN)) + ",";
  payload += "\"buzzer\":" + String(buzzerState ? 1 : 0) + ",";
  payload += "\"mode\":" + String(manualMode ? 1 : 0);
  payload += "}";

  client.publish(TOPIC_DATA, payload.c_str());
  Serial.println("[PUBLISH] " + payload);
}

void postDeviceHeartbeat() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[HEARTBEAT] Skipped - WiFi not connected");
    return;
  }

  HTTPClient http;
  int code = -1;
  String heartbeatUrl = buildApiUrl("/device_heartbeat.php");

  String payload = "{";
  payload += "\"topic\":\"" + String(TOPIC_DATA) + "\",";
  payload += "\"ip\":\"" + WiFi.localIP().toString() + "\",";
  payload += "\"firmwareVersion\":\"wokwi-sim\"";
  payload += "}";

  if (heartbeatUrl.startsWith("https://")) {
    static WiFiClientSecure secureClient;
    secureClient.setInsecure();

    http.setTimeout(httpRequestTimeoutMs);  // Reduced timeout
    http.setReuse(false);
    http.setConnectTimeout(1000);  // Add connection timeout
    if (!http.begin(secureClient, heartbeatUrl)) {
      Serial.println("[HEARTBEAT] http.begin failed (https)");
      return;
    }
  } else {
    http.setTimeout(httpRequestTimeoutMs);  // Reduced timeout
    http.setConnectTimeout(1000);  // Add connection timeout
    if (!http.begin(heartbeatUrl)) {
      Serial.println("[HEARTBEAT] http.begin failed (http)");
      return;
    }
  }

  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");
  code = http.POST(payload);

  if (code > 0) {
    Serial.println("[HEARTBEAT] POST code=" + String(code));
  } else {
    Serial.println("[HEARTBEAT] POST failed: " + http.errorToString(code));
  }

  http.end();
}

bool readSensors() {
  float temp = dht.readTemperature();
  float hum = dht.readHumidity();
  int pir = digitalRead(PIR_PIN);
  int gas = analogRead(MQ2_PIN);

  if (isnan(temp) || isnan(hum)) {
    Serial.println("[ERROR] DHT sensor read failed");
    return false;
  }

  lastTemp = temp;
  lastHum = hum;
  lastPir = pir;
  lastGas = gas;
  hasSensorData = true;
  return true;
}

void applyAction(const String& action, bool triggered, bool& desiredRelay, bool& desiredBuzzer) {
  if (!triggered) return;

  if (!action.startsWith("action|")) {
    return;
  }

  String device = extractField(action, "device");
  String state = extractField(action, "state");
  state.toUpperCase();  // Modifies in-place

  bool isOn = (state == "ON");
  
  Serial.println("[ACTION] Parsing action: device=" + device + " state=" + state + " isOn=" + String(isOn));

  if (device == "relay") {
    desiredRelay = isOn;
    Serial.println("[ACTION] Set relay to " + String(isOn ? "ON" : "OFF"));
  } else if (device == "buzzer") {
    desiredBuzzer = isOn;
    Serial.println("[ACTION] Set buzzer to " + String(isOn ? "ON" : "OFF"));
  } else if (device == "all") {
    desiredRelay = isOn;
    desiredBuzzer = isOn;
    Serial.println("[ACTION] Set ALL to " + String(isOn ? "ON" : "OFF"));
  }
}

void syncRulesFromApi() {
  if (WiFi.status() != WL_CONNECTED) return;

  String syncUrl = buildApiUrl("/tudong.php?action=sync");
  String syncHost = extractHostFromUrl(syncUrl);
  IPAddress resolvedIp;
  if (syncHost.length() == 0 || !WiFi.hostByName(syncHost.c_str(), resolvedIp)) {
    Serial.println("[SYNC] DNS resolve failed for host: " + syncHost);
    return;
  }

  Serial.println("[SYNC] Host resolved: " + resolvedIp.toString());
  Serial.println("[SYNC] Connecting: " + syncUrl);

  HTTPClient http;
  int code = -1;

  if (syncUrl.startsWith("https://")) {
    static WiFiClientSecure secureClient;
    secureClient.setInsecure();

    http.setTimeout(httpRequestTimeoutMs);
    http.setReuse(false);
    http.useHTTP10(true);
    if (!http.begin(secureClient, syncUrl)) {
      Serial.println("[SYNC] http.begin failed (https)");
      return;
    }
    http.addHeader("ngrok-skip-browser-warning", "1");
    http.addHeader("Accept", "application/json");
    http.addHeader("Connection", "close");
    code = http.GET();
  } else {
    http.setTimeout(httpRequestTimeoutMs);
    http.useHTTP10(true);
    if (!http.begin(syncUrl)) {
      Serial.println("[SYNC] http.begin failed (http)");
      return;
    }
    code = http.GET();
  }

  if (code != HTTP_CODE_OK) {
    Serial.println("[SYNC] URL failed: " + syncUrl + " | code=" + String(code) + " | err=" + http.errorToString(code));
    http.end();
    return;
  }

  String body = http.getString();
  http.end();

  if (body.length() == 0) {
    Serial.println("[SYNC] Empty response body");
    return;
  }

  String safeJson = sanitizeJsonForEsp(body);
  JsonDocument doc;
  DeserializationError err = deserializeJson(doc, safeJson);
  if (err) {
    Serial.println("[SYNC] JSON parse failed | err=" + String(err.c_str()));
    Serial.println("[SYNC] Sanitized length: " + String(safeJson.length()));
    Serial.println("[SYNC] Body length: " + String(body.length()));
    int clip = body.length() > 180 ? 180 : body.length();
    Serial.println("[SYNC] Body head: " + body.substring(0, clip));
    return;
  }

  bool success = doc["success"] | false;
  if (!success) {
    String message = String((const char*)doc["message"]);
    Serial.println("[SYNC] API success=false | msg=" + message);
    return;
  }

  String version = String((const char*)doc["version"]);
  int count = doc["count"] | 0;

  if (version == lastSyncVersion) {
    Serial.println("[SYNC] No changes | count=" + String(count));
    return;
  }

  JsonArray data = doc["data"].as<JsonArray>();
  int idx = 0;
  for (JsonObject item : data) {
    if (idx >= MAX_RULES) break;

    String cond = String((const char*)item["dieuKien"]);
    String act = String((const char*)item["hanhDong"]);

    bool validCond = cond.startsWith("sensor|") || cond.startsWith("timer|");
    bool validAct = act.startsWith("action|");

    if (!validCond || !validAct) {
      Serial.println("[SYNC] Skip invalid rule: IF=" + cond + " THEN=" + act);
      continue;
    }

    rules[idx].condition = cond;
    rules[idx].action = act;
    idx++;
  }

  ruleCount = idx;
  lastSyncVersion = version;

  Serial.println("[SYNC] Rules updated. Count: " + String(ruleCount));
  for (int i = 0; i < ruleCount; i++) {
    Serial.println("[SYNC] Rule " + String(i + 1) + ": IF=" + rules[i].condition + " THEN=" + rules[i].action);
  }

}

void callback(char* topic, byte* payload, unsigned int length) {
  String msg;
  for (unsigned int i = 0; i < length; i++) {
    msg += (char)payload[i];
  }

  Serial.println("[MQTT] Received: " + msg);

  // Handle notification to force sync immediately
  if (String(topic) == TOPIC_NOTIFICATION) {
    if (msg.indexOf("sync_now") >= 0) {
      Serial.println("[NOTIFICATION] Sync request received - forcing immediate sync");
      forceSyncRequested = true;
      return;
    }
  }

  if (msg.indexOf("\"mode\":1") >= 0) {
    manualMode = true;
    Serial.println("[MODE] MANUAL CONTROL ENABLED - automation paused");
  }

  if (msg.indexOf("\"mode\":0") >= 0) {
    manualMode = false;
    lastManualMode = true;
    Serial.println("[MODE] MANUAL CONTROL DISABLED - automation resumed");
  }

  if (manualMode) {
    bool stateChanged = false;

    if (msg.indexOf("\"control\":\"relay_toggle\"") >= 0 || msg.indexOf("relay_toggle") >= 0) {
      digitalWrite(RELAY_PIN, !digitalRead(RELAY_PIN));
      stateChanged = true;
      Serial.println("[RELAY] Toggled - Current state: " + String(digitalRead(RELAY_PIN)));
    }

    if (msg.indexOf("\"control\":\"relay_on\"") >= 0 || msg.indexOf("relay_on") >= 0) {
      digitalWrite(RELAY_PIN, HIGH);
      stateChanged = true;
      Serial.println("[RELAY] Turned ON");
    }

    if (msg.indexOf("\"control\":\"relay_off\"") >= 0 || msg.indexOf("relay_off") >= 0) {
      digitalWrite(RELAY_PIN, LOW);
      stateChanged = true;
      Serial.println("[RELAY] Turned OFF");
    }

    if (msg.indexOf("\"control\":\"buzzer_stop\"") >= 0 || msg.indexOf("buzzer_stop") >= 0) {
      setBuzzerState(false);
      stateChanged = true;
      Serial.println("[BUZZER] Stopped");
    }

    if (msg.indexOf("\"control\":\"buzzer_on\"") >= 0 || msg.indexOf("buzzer_on") >= 0) {
      setBuzzerState(true);
      stateChanged = true;
      Serial.println("[BUZZER] Turned ON");
    }

    if (stateChanged) {
      publishSensorData();
    }
  } else {
    if (msg.indexOf("relay") >= 0 || msg.indexOf("buzzer") >= 0) {
      Serial.println("[WARNING] Manual control ignored because automation is active");
    }
  }
}

void reconnect() {
  Serial.print("[MQTT] Attempting connection... ");

  if (client.connect("ESP32IoT_Room1")) {
    Serial.println("CONNECTED");
    client.subscribe(TOPIC_CONTROL);
    client.subscribe(TOPIC_NOTIFICATION);
    Serial.println("[MQTT] Subscribed to: " + String(TOPIC_CONTROL));
    Serial.println("[MQTT] Subscribed to: " + String(TOPIC_NOTIFICATION));
    publishSensorData();
  } else {
    Serial.print("FAILED (Code: ");
    Serial.print(client.state());
    Serial.println(")");
  }
}

void setup() {
  Serial.begin(115200);
  delay(1000);

  pinMode(PIR_PIN, INPUT);
  pinMode(MQ2_PIN, INPUT);
  pinMode(BUZZER, OUTPUT);
  pinMode(RELAY_PIN, OUTPUT);

  noTone(BUZZER);
  digitalWrite(RELAY_PIN, LOW);

  dht.begin();

  Serial.print("[WiFi] Connecting to: ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 30) {
    delay(500);
    Serial.print(".");
    attempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WiFi] Connected");
    Serial.println("[WiFi] IP: " + WiFi.localIP().toString());
  } else {
    Serial.println("\n[WiFi] Connect timeout");
  }

  // UTC+7
  configTime(7 * 3600, 0, "pool.ntp.org", "time.nist.gov");

  client.setServer(mqtt_server, 1883);
  client.setCallback(callback);

  syncRulesFromApi();
  readSensors();
  postDeviceHeartbeat();
  lastSyncAt = millis();
  lastSensorReadAt = millis();
  lastPublishAt = millis();
  lastHeartbeatPostAt = millis();
}

void loop() {
  unsigned long now = millis();

  // Check WiFi connection frequently
  if (now - lastWiFiCheckAt >= wifiCheckIntervalMs) {
    lastWiFiCheckAt = now;
    if (WiFi.status() != WL_CONNECTED) {
      Serial.println("[WiFi] Disconnected! Reconnecting...");
      WiFi.reconnect();
    }
  }

  // MQTT reconnect with backoff
  if (!client.connected() && now - lastReconnectAttemptAt >= mqttReconnectRetryIntervalMs) {
    reconnect();
    lastReconnectAttemptAt = now;
  }

  // MQTT loop - PRIORITY: never skip this
  if (client.connected()) {
    client.loop();
  }

  // Sync rules (less frequent now)
  if (!manualMode && (forceSyncRequested || now - lastSyncAt >= syncIntervalMs)) {
    syncRulesFromApi();
    lastSyncAt = now;
    forceSyncRequested = false;
  }

  // Post heartbeat (less frequent now: every 30s instead of 2s)
  if (now - lastHeartbeatPostAt >= heartbeatIntervalMs) {
    postDeviceHeartbeat();
    lastHeartbeatPostAt = now;
  }

  if (now - lastSensorReadAt >= sensorReadIntervalMs) {
    bool sensorOk = readSensors();
    lastSensorReadAt = now;

    if (sensorOk && !manualMode) {
      Serial.println("[SENSOR] Temp=" + String(lastTemp, 1) + "C Hum=" + String(lastHum, 1) + "% Gas=" + String(lastGas) + " PIR=" + String(lastPir));
      
      if (lastManualMode) {
        Serial.println("[AUTO] Re-evaluating rules after manual control");
        lastManualMode = false;
      }

      bool desiredRelay = false;
      bool desiredBuzzer = false;

      if (ruleCount > 0) {
        for (int i = 0; i < ruleCount; i++) {
          bool match = evaluateCondition(rules[i].condition, lastTemp, lastHum, lastGas, lastPir);
          if (match) {
            Serial.println("[RULE] Rule " + String(i + 1) + " MATCHED. Applying action: " + rules[i].action);
          }
          applyAction(rules[i].action, match, desiredRelay, desiredBuzzer);
        }
      } else {
        Serial.println("[RULE] No rules loaded. Using fallback...");
        // fallback when no rule exists
        desiredBuzzer = lastGas > 3000;
        desiredRelay = lastPir == 1;
      }

      bool relayChanged = digitalRead(RELAY_PIN) != (desiredRelay ? HIGH : LOW);
      bool buzzerChanged = buzzerState != desiredBuzzer;

      digitalWrite(RELAY_PIN, desiredRelay ? HIGH : LOW);
      setBuzzerState(desiredBuzzer);

      if (relayChanged || buzzerChanged) {
        publishSensorData();
        lastPublishAt = now;
      }
    }
  }

  if (hasSensorData && now - lastPublishAt >= publishIntervalMs) {
    publishSensorData();
    lastPublishAt = now;
  }
}
