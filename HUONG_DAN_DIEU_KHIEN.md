# Hướng Dẫn Điều Khiển Hệ Thống IoT Nha Trọ

## 📋 Tổng Quan Hệ Thống

Hệ thống gồm:
- **Web Dashboard** (PHP): Giao diện điều khiển trên browser
- **ESP32 Wokwi**: Board nạp điều khiển thiết bị cảm biến
- **MQTT Broker**: Kết nối giữa web và ESP32 (broker.hivemq.com - công khai)

---

## 🔌 Cấu Hình MQTT

### Thông Số Kết Nối
```
Broker: broker.hivemq.com (Public HiveMQ Broker)
Port: 1883 (TCP) / 8000 (WebSocket)
Topic nhận dữ liệu: nhatro123/room1/sensor
Topic gửi lệnh: nhatro123/room1/control
```

### Đặc Điểm
- ✅ Không cần tài khoản đăng nhập (public broker)
- ✅ Tự động kết nối lại khi mất kết nối
- ✅ Hỗ trợ WebSocket trên web browser
- ❌ Không bảo mật (chỉ phù hợp demo/phát triển)

---

## 🎮 Các Chế Độ Hoạt Động

### 1. **AUTO Mode** (Tự Động)
- Buzzer bật tự động khi phát hiện **khí gas > 3000 ppm**
- Relay bật tự động khi phát hiện **chuyển động (PIR)**
- Nút bấm trên web bị vô hiệu hóa (xám)

```json
{
  "mode": 0
}
```

### 2. **MANUAL Mode** (Thủ Công)
- Điều khiển thiết bị bằng nút bấm trên web
- Các nút chuyển sang màu xanh (kích hoạt)
- ESP32 bỏ qua logic tự động

```json
{
  "mode": 1
}
```

---

## 📤 Định Dạng Lệnh Điều Khiển

Tất cả lệnh gửi qua topic `nhatro123/room1/control` dưới dạng JSON:

### Chế Độ
```json
// Chuyển sang MANUAL
{"mode": 1}

// Chuyển sang AUTO
{"mode": 0}
```

### Relay (Công Tắc Điện)
```json
// Bật Relay
{"control": "relay_on"}

// Tắt Relay
{"control": "relay_off"}

// Chuyển trạng thái (bật → tắt, tắt → bật)
{"control": "relay_toggle"}
```

### Buzzer (Còi Báo Động)
```json
// Bật Buzzer
{"control": "buzzer_on"}

// Tắt Buzzer
{"control": "buzzer_stop"}
```

---

## 📊 Định Dạng Dữ Liệu Cảm Biến

ESP32 gửi dữ liệu thường xuyên qua topic `nhatro123/room1/sensor`:

```json
{
  "nhiet_do": 28.50,      // Nhiệt độ (°C) từ DHT22
  "do_am": 65.20,         // Độ ẩm (%) từ DHT22
  "gas": 2500,            // Nồng độ khí gas (ppm) từ MQ-2
  "pir": 0,               // Cảm biến chuyển động: 1=phát hiện, 0=bình thường
  "relay": 1,             // Trạng thái Relay: 1=bật, 0=tắt
  "buzzer": 0,            // Trạng thái Buzzer: 1=kêu, 0=tắt
  "mode": 0               // Chế độ: 0=AUTO, 1=MANUAL
}
```

---

## 🖥️ Giao Diện Web Dashboard

### Khu Vực Cảm Biến (Trên)
- Hiển thị **Nhiệt độ, Độ ẩm, Khí gas, PIR** theo thời gian thực
- Nhập dữ liệu từ ESP32 via MQTT

### Khu Vực Điều Khiển (Dưới Trái)
- **Chuyển đổi chế độ**: Tắt=AUTO (xám), Bật=MANUAL (xanh)
- **Nút BẬT/TẮT Relay**: Điều khiển công tắc điện
- **Nút TẮT CÒI**: Dừng buzzer khi kêu

### Biểu Đồ Xu Hướng (Dưới Phải)
- Hiển thị **đường cong nhiệt độ & độ ẩm 24 giờ**
- Cập nhật theo dữ liệu thực từ cảm biến

---

## 🔧 Lỏi Thường Gặp & Giải Pháp

| Vấn Đề | Nguyên Nhân | Giải Pháp |
|--------|-----------|----------|
| "Ngoại tuyến" liên tục | ESP32 chưa kết nối MQTT | Kiểm tra WiFi Wokwi, Serial Monitor |
| Nút bấm không hoạt động | Chế độ AUTO đang bật | Tắt chế độ AUTO (chuyển MANUAL) |
| Dữ liệu cảm biến không cập nhật | MQTT không nhận | Kiểm tra kết nối web, xem console browser |
| Relay không bật khi gas cao (AUTO) | Threshold gas quá cao | Giảm giá trị từ 3000 xuống 2000 ở main.cpp |

---

## 📱 Kiểm Tra Kết Nối MQTT

### Trên Web (Browser)
- Mở **DevTools** (F12)
- Tab **Console** xem log [MQTT] Connect/Subscribe
- Kiểm tra tin nhắn được nhận

### Trên Wokwi
- Xem **Serial Monitor** của ESP32
- Kiểm tra dòng `[MQTT] Subscribed to: nhatro123/room1/control`

### MQTT Explorer (Tùy Chọn)
```bash
# Windows
# Tải từ: http://mqtt-explorer.com/

# Cấu hình
Broker: broker.hivemq.com
Port: 1883
# Sau đó subscribe topic "nhatro123/room1/#" để xem all msgs
```

---

## ⚙️ Tùy Chỉnh Advanced

### Thay Đổi Threshold Gas (main.cpp)
```cpp
// Hiện tại: 3000 ppm
if (gas > 3000) {  // ← Thay số này

// Ví dụ: Nhạy hơn
if (gas > 2000) {  // Cảnh báo sớm hơn
```

### Thay Đổi Tần Suất Gửi Dữ Liệu (main.cpp)
```cpp
// Hiện tại: 3000 ms (3 giây)
delay(3000);  // ← Thay số này (milliseconds)

// Ví dụ: Gửi 1 lần/5 giây
delay(5000);
```

### Sử Dụng MQTT Broker Khác
1. Cập nhật **index.php**:
   ```javascript
   const mqtt_broker = "your-broker.com";  // Thay broker
   const mqtt_port = 8000;  // Thay port (WebSocket)
   ```

2. Cập nhật **main.cpp**:
   ```cpp
   const char* mqtt_server = "your-broker.com";
   ```

---

## ✅ Checklist Khởi Động Hệ Thống

- [ ] Wokwi ESP32 đang chạy
- [ ] PHP web server chạy (XAMPP bật)
- [ ] Mở lên http://localhost/php_nha_tro_iot-main/public/
- [ ] Kiểm tra Console: "[MQTT] Connected successfully"
- [ ] Dữ liệu cảm biến hiển thị (không phải "--")
- [ ] Thử chuyển MANUAL mode, bấm nút kiểm tra
- [ ] Serial Monitor Wokwi hiển thị lệnh điều khiển nhận được

---

## 📞 Hỗ Trợ Thêm

Nếu gặp lỗi, kiểm tra:
1. **Dòng lệnh trong Serial Monitor** (Wokwi)
2. **Console browser DevTools** (Web)
3. **MQTT Topic & JSON format** (kiểm tra cấu trúc)
4. **Kết nối WiFi Wokwi** (SSID: "Wokwi-GUEST" không mật khẩu)

---

**Chúc bạn điều khiển thành công!** 🚀
