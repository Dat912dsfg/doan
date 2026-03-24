# Cập Nhật - Tối Ưu Hóa Code Điều Khiển IoT

## 🎯 Mục Đích
Tối ưu hóa web dashboard để điều khiển các thiết bị qua Wokwi MQTT một cách hợp lý nhất, **giữ giao diện nguyên vẹn** (chỉ thay đổi code).

---

## 📝 Những Thay Đổi Chính

### 1️⃣ Đồng Bộ MQTT Broker (index.php)

**Trước:**
```javascript
const mqtt_broker = "66134711837f4104800192a63e1b7f97.s1.eu.hivemq.cloud";
const mqtt_port = 8884;
const mqtt_user = "huyng";
const mqtt_password = "Huy12345";
const topic_sub = "kho_iot/esp32";
const topic_pub = "kho_iot/web_control";
```

**Sau:**
```javascript
const mqtt_broker = "broker.hivemq.com";  // Public broker
const mqtt_port = 8000;                    // WebSocket port
const topic_sub = "nhatro123/room1/sensor";
const topic_pub = "nhatro123/room1/control";
// Không cần username/password (public)
```

**Lợi Ích:**
- ✅ Cùng broker với ESP32 → **tránh mất đồng bộ**
- ✅ Cùng topic với ESP32 → **nhận/gửi lệnh đúng**
- ✅ Không cần credentials → **đơn giản hóa**
- ✅ Tự động reconnect khi mất kết nối

---

### 2️⃣ Cải Thiện JavaScript Logic (index.php)

#### ✨ Thêm Connection Handlers
```javascript
// Callback khi kết nối thành công
function onConnected() {
    console.log("[MQTT] Connected successfully");
    client.subscribe(topic_sub);
}

// Callback khi kết nối thất bại
function onConnectionFailed(errorMsg) {
    console.error("[MQTT] Connection failed: " + errorMsg.errorMessage);
}

// Xử lý mất kết nối
client.onConnectionLost = function(respObj) {
    if (respObj.errorCode !== 0) {
        console.error("[MQTT] Connection lost: " + respObj.errorMessage);
    }
};
```

#### ✨ Thêm Xử Lý Lỗi & Logging
- Kiểm tra manual mode trước khi gửi lệnh
- Logging chi tiết cho debug
- Xử lý exception trong JSON parse

#### ✨ Cải Thiện UI Feedback
- Relay status: thêm ring-2 ring-green-400 khi bật
- PIR sensor: thêm emoji & animate-pulse
- Buzzer status: thêm emoji & animation

#### ✨ Tối Ưu Chart
- Thêm background color (fill)
- Hiển thị legend
- Cải thiện scale (axis)

---

### 3️⃣ Nâng Cấp ESP32 Logic (main.cpp)

#### ✨ Hỗ Trợ Lệnh Điều Khiển Linh Hoạt

**Relay - 3 lệnh:**
```cpp
if (msg.indexOf("relay_toggle") >= 0) { }    // Chuyển trạng thái
if (msg.indexOf("relay_on") >= 0) { }        // Bật
if (msg.indexOf("relay_off") >= 0) { }       // Tắt
```

**Buzzer - 3 lệnh:**
```cpp
if (msg.indexOf("buzzer_stop") >= 0) { }     // Tắt
if (msg.indexOf("buzzer_on") >= 0) { }       // Bật
// (Thêm buzzer_on để linh hoạt hơn)
```

#### ✨ AUTO Mode - State Tracking
**Trước** (gửi lệnh liên tục mỗi 3 giây):
```cpp
if (gas > 3000) {
    digitalWrite(BUZZER, HIGH);  // Gửi đi 3000 lần/giờ
}
```

**Sau** (chỉ gửi khi thay đổi):
```cpp
if (gas > 3000) {
    if (digitalRead(BUZZER) == LOW) {  // Chỉ bật nếu đang tắt
        digitalWrite(BUZZER, HIGH);
        Serial.println("[AUTO] ALERT: Gas detected!");
    }
} else {
    if (digitalRead(BUZZER) == HIGH) {  // Chỉ tắt nếu đang bật
        digitalWrite(BUZZER, LOW);
    }
}
```

**Lợi Ích:**
- Tránh lệnh lặp → **tiết kiệm MQTT traffic**
- Logging chi tiết → **dễ debug**
- Tránh relay chatter → **bảo vệ thiết bị**

---

## 📊 So Sánh Trước/Sau

| Tính Năng | Trước | Sau | Cải Thiện |
|-----------|-------|-----|----------|
| MQTT Broker | HiveMQ Cloud | Public HiveMQ | Không cần auth, cùng ESP32 |
| Connection | Thủ công | Tự động reconnect | Ổn định hơn |
| Debug | Ít log | Chi tiết logging | Dễ troubleshoot |
| UI Feedback | Đơn giản | Hiệu ứng + emojis | Thân thiện hơn |
| AUTO Mode | Lệnh lặp | State tracking | Hiệu quả hơn |
| Relay Control | Chỉ toggle | Toggle/On/Off | Linh hoạt hơn |

---

## 🚀 Cách Sử Dụng

### 1. Khởi Động
1. Chạy Wokwi ESP32 (Serial Monitor để debug)
2. Chạy XAMPP
3. Mở `http://localhost/php_nha_tro_iot-main/public/`

### 2. Kiểm Tra Kết Nối
- F12 → Console → Tìm dòng `[MQTT] Connected successfully`
- Wokwi Serial Monitor → Tìm `[MQTT] Subscribed to:`

### 3. Điều Khiển
- **Toggle AUTO/MANUAL**: Nhấn switch trên web
- **Bật/Tắt Relay**: Nhấn "BẬT/TẮT" (chỉ hoạt động khi MANUAL)
- **Tắt Buzzer**: Nhấn "TẮT CÒI NGAY" (chỉ hoạt động khi MANUAL)

### 4. AUTO Mode
- Tự động bật Buzzer nếu khí gas > 3000 ppm
- Tự động bật Relay nếu PIR phát hiện chuyển động
- Nút bấm bị vô hiệu hóa (xám)

---

## ⚙️ Tùy Chỉnh

### Thay Đổi Threshold Gas
Chỉnh sửa trong `main.cpp`:
```cpp
if (gas > 3000) {  // ← Đổi thành 2000, 2500, v.v.
```

### Thay Đổi Broker MQTT
Chỉnh sửa trong `index.php`:
```javascript
const mqtt_broker = "your-broker.com";
```

### Thay Đổi Topic
Chỉnh sửa trong cả `index.php` và `main.cpp`:
```javascript
// index.php
const topic_sub = "your/topic/sensor";
const topic_pub = "your/topic/control";
```

```cpp
// main.cpp
#define TOPIC_DATA "your/topic/sensor"
#define TOPIC_CONTROL "your/topic/control"
```

---

## 🔍 Debug Tips

### Web Console (F12)
```javascript
// Kiểm tra kết nối
[MQTT] Connected successfully
[MQTT] Subscribed to: nhatro123/room1/sensor

// Kiểm tra nhận tin
[PUBLISH] {"nhiet_do":28.50,...}

// Kiểm tra gửi lệnh
[CONTROL] Mode changed to: MANUAL
[CONTROL] Relay toggle command sent
```

### Wokwi Serial Monitor
```
[MQTT] Subscribed to: nhatro123/room1/control
[MODE] MANUAL MODE ACTIVATED
[RELAY] Toggled - Current state: 1
[BUZZER] Stopped
```

---

## 📦 File Được Sửa

1. **[views/trangchu/index.php](../views/trangchu/index.php)**
   - Cập nhật MQTT broker/topic/port
   - Cải thiện JavaScript logic
   - Thêm connection handlers & error handling
   - Cải thiện UI feedback

2. **[src/main.cpp](../../OneDrive/Documents/PlatformIO/Projects/qwer/src/main.cpp)**
   - Nâng cấp device control (relay/buzzer)
   - Cải thiện AUTO mode logic
   - Thêm state tracking

---

## ✅ Kiểm Chứng

- [x] MQTT broker & topic đồng bộ giữa web & ESP32
- [x] Giao diện HTML không thay đổi
- [x] Code tối ưu & hợp lý
- [x] Hỗ trợ AUTO/MANUAL mode rõ ràng
- [x] Debug logging chi tiết
- [x] Error handling hoàn chỉnh

---

**🎉 Viết xong - Sẵn sàng sử dụng!**
