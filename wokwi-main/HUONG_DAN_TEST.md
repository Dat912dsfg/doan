# 🚀 Hướng Dẫn Điều Khiển Wokwi Bằng Trang Web

## 📋 Tổng Quan
Trang web PHP sẽ điều khiển mô phỏng Wokwi thông qua MQTT Broker (broker.hivemq.com)

### Thiết Bị Kết Nối:
- **DHT22**: Cảm biến nhiệt độ & độ ẩm (Pin 15)
- **PIR Motion Sensor**: Cảm biến chuyển động (Pin 13)
- **MQ2 Gas Sensor**: Cảm biến khí gas (Pin 34)
- **Relay Module**: Điều khiển thiết bị (Pin 26)
- **Buzzer**: Còi báo động (Pin 27)

---

## ⚙️ Bước 1: Chạy Wokwi Simulation

### Tùy chọn A: Chạy Online (Recommended)
1. Truy cập: https://wokwi.com/
2. Tạo dự án mới → Chọn ESP32
3. Copy nội dung từ `diagram.json` vào Wokwi
4. Upload firmware từ `.pio/build/esp32dev/firmware.bin`
5. Nhấn "Play" để chạy simulation

### Tùy chọn B: Chạy Cục Bộ (Nếu có Wokwi CLI)
```bash
cd c:\Users\Dell\OneDrive\Documents\PlatformIO\Projects\qwer
wokwi-cli diagram.json
```

---

## 🌐 Bước 2: Chạy Trang Web

1. **Mở XAMPP** và bật Apache & MySQL
2. **Truy cập trang web**:
   ```
   http://localhost/php_nha_tro_iot-main/php_nha_tro_iot-main/public/
   ```
3. **Hoặc trực tiếp vào**:
   ```
   http://localhost/php_nha_tro_iot-main/php_nha_tro_iot-main/views/trangchu/index.php
   ```

---

## 🎮 Các Chế Độ Hoạt Động

### Mode AUTO (Mặc định)
```
Relay & Buzzer được điều khiển tự động:
- 🔴 Phát hiện chuyển động (PIR) → Bật Relay
- ⚠️ Phát hiện khí gas > 3000 ppm → Bật Buzzer
```

### Mode MANUAL
```
Bật switch "MANUAL 🎮" để:
- 🎮 Bật/Tắt Relay thủ công
- 🎮 Tắt Buzzer thủ công
- Xem dữ liệu cảm biến Real-time
```

---

## 📊 Thông Tin Gửi/Nhận

### 📤 Trang Web Gửi đến ESP32:
```json
{
  "mode": 1,                      // 1 = Manual, 0 = Auto
  "control": "relay_toggle",      // Bật/tắt relay
  "control": "buzzer_stop"        // Tắt buzzer
}
```

### 📥 ESP32 Gửi tới Trang Web:
```json
{
  "nhiet_do": 25.5,               // Nhiệt độ (°C)
  "do_am": 60.2,                  // Độ ẩm (%)
  "gas": 2500,                    // Nồng độ khí (ppm)
  "pir": 0,                       // Chuyển động (0=Không, 1=Có)
  "relay": 1,                     // Trạng thái relay (0=Tắt, 1=Bật)
  "buzzer": 0,                    // Trạng thái buzzer (0=Tắt, 1=Bật)
  "mode": 0                       // Chế độ (0=Auto, 1=Manual)
}
```

---

## 🔍 Kiểm Tra Kết Nối

### Trên Trình Duyệt (F12)
- Mở **Console** để xem logs
- Tìm: `✅ MQTT Connected`
- Tìm: `📥 RECEIVED` để xem dữ liệu

### Trên Wokwi Serial Monitor
- Xem các log từ ESP32
- Tìm: `[MQTT] ✓ CONNECTED`
- Tìm: `[PUBLISH]` để xem dữ liệu gửi đi

---

## 🧪 Test Cases

| Hành Động | Kết Quả Mong Đợi |
|-----------|-----------------|
| Bật Mode MANUAL | Switch chuyển, button BẬT/TẮT được kích hoạt |
| Nhấn BẬT/TẮT Relay | Relay trong Wokwi chuyển đổi trạng thái |
| Nhấn TẮT CÒI | Buzzer trong Wokwi tắt |
| Bật Mode AUTO | Switch chuyển, tất cả button bị vô hiệu hóa |
| Giả lập PIR phát hiện | Relay tự bật (ở mode AUTO) |
| Giả lập Gas > 3000 | Buzzer tự bật (ở mode AUTO) |

---

## 🐛 Troubleshooting

### ❌ MQTT Not Connected
- Kiểm tra internet connection
- Broker bruker.hivemq.com có hoạt động không
- Restart trình duyệt

### ❌ Không nhận dữ liệu từ ESP32
- Kiểm tra Serial Monitor của Wokwi
- Kiểm tra topic: `nhatro123/room1/sensor`
- Kiểm tra firewall

### ❌ Button không response
- Đảm bảo Mode là MANUAL
- Kiểm tra console logs có lỗi gì không
- Kiểm tra MQTT broker connection

---

## 📝 MQTT Topic

**Subscribe (Nhận)**: `nhatro123/room1/sensor`
**Publish (Gửi)**: `nhatro123/room1/control`

---

**Lưu ý**: Cả trang web và ESP32 phải kết nối đến cùng một MQTT Broker để hoạt động!

