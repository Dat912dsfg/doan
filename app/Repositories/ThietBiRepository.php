<?php
namespace App\Repositories;

use Config\KetNoi;

class ThietBiRepository {
    private $conn;
    private const DEFAULT_SENSOR_TYPES = [
        'temp' => ['ten' => 'Nhiệt độ', 'donVi' => '°C'],
        'hum' => ['ten' => 'Độ ẩm', 'donVi' => '%'],
        'gas' => ['ten' => 'Gas', 'donVi' => 'ppm'],
        'pir' => ['ten' => 'Chuyển động', 'donVi' => 'bool'],
    ];

    public function __construct() {
        $ketNoi = new KetNoi();
        $this->conn = $ketNoi->getConn();
    }

    public function layTatCaThietBi() {
        $sql = "SELECT tb.*, p.tenPhong
                FROM thiet_bi tb
                LEFT JOIN phong p ON p.idPhong = tb.idPhong
                ORDER BY tb.ngayCapNhat DESC, tb.idThietBi DESC";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function layTatCaPhong() {
        $sql = "SELECT p.*,
                       COUNT(tb.idThietBi) AS soThietBi
                FROM phong p
                LEFT JOIN thiet_bi tb ON tb.idPhong = p.idPhong
                GROUP BY p.idPhong
                ORDER BY p.ngayTao DESC, p.idPhong DESC";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function layDanhSachPhongChoForm() {
        $sql = "SELECT idPhong, tenPhong, trangThai
                FROM phong
                ORDER BY tenPhong ASC";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function layThietBiTheoId($id) {
        $id = intval($id);
        $sql = "SELECT * FROM thiet_bi WHERE idThietBi = $id LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function layPhongTheoId($id) {
        $id = intval($id);
        $sql = "SELECT p.*,
                       COUNT(tb.idThietBi) AS soThietBi
                FROM phong p
                LEFT JOIN thiet_bi tb ON tb.idPhong = p.idPhong
                WHERE p.idPhong = $id
                GROUP BY p.idPhong
                LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function themThietBi($data) {
        $sql = "INSERT INTO thiet_bi
                (idPhong, tenThietBi, loaiThietBi, diaChiIp, macAddress, topicMqtt, trangThaiKetNoi, lastHeartbeat, firmwareVersion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $idPhong = intval($data['idPhong']);
        $tenThietBi = $data['tenThietBi'];
        $loaiThietBi = $this->nullIfEmpty($data['loaiThietBi'] ?? null);
        $diaChiIp = $this->nullIfEmpty($data['diaChiIp'] ?? null);
        $macAddress = $this->nullIfEmpty($data['macAddress'] ?? null);
        $topicMqtt = $this->nullIfEmpty($data['topicMqtt'] ?? null);
        $trangThaiKetNoi = intval($data['trangThaiKetNoi'] ?? 0);
        $lastHeartbeat = $this->nullIfEmpty($data['lastHeartbeat'] ?? null);
        $firmwareVersion = $this->nullIfEmpty($data['firmwareVersion'] ?? null);

        $stmt->bind_param(
            'isssssiss',
            $idPhong,
            $tenThietBi,
            $loaiThietBi,
            $diaChiIp,
            $macAddress,
            $topicMqtt,
            $trangThaiKetNoi,
            $lastHeartbeat,
            $firmwareVersion
        );

        $this->conn->begin_transaction();

        try {
            if (!$stmt->execute()) {
                throw new \RuntimeException('Khong the them thiet bi.');
            }

            $deviceId = mysqli_insert_id($this->conn);
            $this->taoCamBienMacDinhChoThietBi($deviceId);
            $this->conn->commit();

            return $deviceId;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function capNhatThietBi($id, $data) {
        $sql = "UPDATE thiet_bi
                SET idPhong = ?,
                    tenThietBi = ?,
                    loaiThietBi = ?,
                    diaChiIp = ?,
                    macAddress = ?,
                    topicMqtt = ?,
                    trangThaiKetNoi = ?,
                    lastHeartbeat = ?,
                    firmwareVersion = ?
                WHERE idThietBi = ?";
        $stmt = $this->conn->prepare($sql);

        $id = intval($id);
        $idPhong = intval($data['idPhong']);
        $tenThietBi = $data['tenThietBi'];
        $loaiThietBi = $this->nullIfEmpty($data['loaiThietBi'] ?? null);
        $diaChiIp = $this->nullIfEmpty($data['diaChiIp'] ?? null);
        $macAddress = $this->nullIfEmpty($data['macAddress'] ?? null);
        $topicMqtt = $this->nullIfEmpty($data['topicMqtt'] ?? null);
        $trangThaiKetNoi = intval($data['trangThaiKetNoi'] ?? 0);
        $lastHeartbeat = $this->nullIfEmpty($data['lastHeartbeat'] ?? null);
        $firmwareVersion = $this->nullIfEmpty($data['firmwareVersion'] ?? null);

        $stmt->bind_param(
            'isssssissi',
            $idPhong,
            $tenThietBi,
            $loaiThietBi,
            $diaChiIp,
            $macAddress,
            $topicMqtt,
            $trangThaiKetNoi,
            $lastHeartbeat,
            $firmwareVersion,
            $id
        );

        return $stmt->execute();
    }

    public function capNhatHeartbeatTheoTopic($topicSensor, $ip = null, $firmware = null) {
        $topicSensor = trim((string) $topicSensor);
        if ($topicSensor === '') {
            return false;
        }

        $baseTopic = $topicSensor;
        if (substr($topicSensor, -7) === '/sensor') {
            $baseTopic = substr($topicSensor, 0, -7);
        }

        $controlTopic = rtrim($baseTopic, '/') . '/control';
        $ip = $this->nullIfEmpty($ip);
        $firmware = $this->nullIfEmpty($firmware);

        $sql = "UPDATE thiet_bi
                SET trangThaiKetNoi = 1,
                    lastHeartbeat = NOW(),
                    diaChiIp = COALESCE(?, diaChiIp),
                    firmwareVersion = COALESCE(?, firmwareVersion)
                WHERE topicMqtt IN (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('sssss', $ip, $firmware, $topicSensor, $baseTopic, $controlTopic);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function xoaThietBi($id) {
        $stmt = $this->conn->prepare("DELETE FROM thiet_bi WHERE idThietBi = ?");
        if (!$stmt) {
            return false;
        }

        $id = intval($id);
        $stmt->bind_param('i', $id);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function themPhong($data) {
        $sql = "INSERT INTO phong
                (tenPhong, loaiPhong, viTri, moTa, dienTich, trangThai, cheDoTuDong)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $tenPhong = $data['tenPhong'];
        $loaiPhong = $this->nullIfEmpty($data['loaiPhong'] ?? null);
        $viTri = $this->nullIfEmpty($data['viTri'] ?? null);
        $moTa = $this->nullIfEmpty($data['moTa'] ?? null);
        $dienTich = $this->nullIfEmpty($data['dienTich'] ?? null);
        $trangThai = intval($data['trangThai'] ?? 1);
        $cheDoTuDong = intval($data['cheDoTuDong'] ?? 0);

        $stmt->bind_param(
            'sssssii',
            $tenPhong,
            $loaiPhong,
            $viTri,
            $moTa,
            $dienTich,
            $trangThai,
            $cheDoTuDong
        );

        if ($stmt->execute()) {
            return mysqli_insert_id($this->conn);
        }

        return false;
    }

    public function capNhatPhong($id, $data) {
        $sql = "UPDATE phong
                SET tenPhong = ?,
                    loaiPhong = ?,
                    viTri = ?,
                    moTa = ?,
                    dienTich = ?,
                    trangThai = ?,
                    cheDoTuDong = ?
                WHERE idPhong = ?";
        $stmt = $this->conn->prepare($sql);

        $id = intval($id);
        $tenPhong = $data['tenPhong'];
        $loaiPhong = $this->nullIfEmpty($data['loaiPhong'] ?? null);
        $viTri = $this->nullIfEmpty($data['viTri'] ?? null);
        $moTa = $this->nullIfEmpty($data['moTa'] ?? null);
        $dienTich = $this->nullIfEmpty($data['dienTich'] ?? null);
        $trangThai = intval($data['trangThai'] ?? 1);
        $cheDoTuDong = intval($data['cheDoTuDong'] ?? 0);

        $stmt->bind_param(
            'sssssiii',
            $tenPhong,
            $loaiPhong,
            $viTri,
            $moTa,
            $dienTich,
            $trangThai,
            $cheDoTuDong,
            $id
        );

        return $stmt->execute();
    }

    public function xoaPhong($id) {
        $id = intval($id);
        if ($id <= 0) {
            return false;
        }

        $deleteDevicesStmt = $this->conn->prepare("DELETE FROM thiet_bi WHERE idPhong = ?");
        $deleteRoomStmt = $this->conn->prepare("DELETE FROM phong WHERE idPhong = ?");

        if (!$deleteDevicesStmt || !$deleteRoomStmt) {
            return false;
        }

        $this->conn->begin_transaction();

        try {
            $deleteDevicesStmt->bind_param('i', $id);
            if (!$deleteDevicesStmt->execute()) {
                throw new \RuntimeException('Khong the xoa node trong phong.');
            }

            $deleteRoomStmt->bind_param('i', $id);
            if (!$deleteRoomStmt->execute() || $deleteRoomStmt->affected_rows <= 0) {
                throw new \RuntimeException('Khong the xoa phong.');
            }

            $this->conn->commit();
            return true;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function layThietBiTheoTopic($topic) {
        $topic = trim((string) $topic);
        if ($topic === '') {
            return null;
        }

        $baseTopic = $this->extractBaseTopic($topic);
        $sensorTopic = rtrim($baseTopic, '/') . '/sensor';
        $controlTopic = rtrim($baseTopic, '/') . '/control';

        $stmt = $this->conn->prepare("
            SELECT *
            FROM thiet_bi
            WHERE topicMqtt IN (?, ?, ?)
            ORDER BY idThietBi ASC
            LIMIT 1
        ");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('sss', $baseTopic, $sensorTopic, $controlTopic);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    public function taoCamBienMacDinhChoThietBi($deviceId) {
        $deviceId = intval($deviceId);
        if ($deviceId <= 0) {
            return [];
        }

        $existing = $this->layBanDoCamBienTheoThietBi($deviceId);
        $stmt = $this->conn->prepare("
            INSERT INTO cam_bien (idThietBi, tenCamBien, loaiCamBien, donVi, trangThai)
            VALUES (?, ?, ?, ?, 1)
        ");

        if (!$stmt) {
            return $existing;
        }

        foreach (self::DEFAULT_SENSOR_TYPES as $type => $config) {
            if (isset($existing[$type])) {
                continue;
            }

            $sensorName = $config['ten'];
            $unit = $config['donVi'];
            $stmt->bind_param('isss', $deviceId, $sensorName, $type, $unit);
            if ($stmt->execute()) {
                $existing[$type] = mysqli_insert_id($this->conn);
            }
        }

        return $existing;
    }

    public function luuDuLieuCamBienTheoTopic($topic, $payload) {
        $device = $this->layThietBiTheoTopic($topic);
        if (!$device) {
            return false;
        }

        $deviceId = intval($device['idThietBi'] ?? 0);
        if ($deviceId <= 0) {
            return false;
        }

        $sensorMap = $this->taoCamBienMacDinhChoThietBi($deviceId);
        if (empty($sensorMap)) {
            return false;
        }

        $rows = [
            'temp' => $payload['nhiet_do'] ?? null,
            'hum' => $payload['do_am'] ?? null,
            'gas' => $payload['gas'] ?? null,
            'pir' => $payload['pir'] ?? null,
        ];

        $stmt = $this->conn->prepare('INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (?, ?)');
        if (!$stmt) {
            return false;
        }

        $this->conn->begin_transaction();

        try {
            foreach ($rows as $type => $value) {
                if (!isset($sensorMap[$type]) || !is_numeric($value)) {
                    continue;
                }

                $sensorId = intval($sensorMap[$type]);
                $sensorValue = (float) $value;
                $stmt->bind_param('id', $sensorId, $sensorValue);

                if (!$stmt->execute()) {
                    throw new \RuntimeException('Khong the luu du lieu cam bien.');
                }
            }

            $this->conn->commit();
            return $device;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function layLichSuCamBienTheoThietBi($deviceId, $hours = 24) {
        $deviceId = intval($deviceId);
        $hours = max(1, intval($hours));

        $sql = "
            SELECT
                DATE_FORMAT(dl.thoiGianDo, '%Y-%m-%d %H:%i:00') AS sample_time,
                MAX(CASE WHEN cb.loaiCamBien = 'temp' THEN dl.giaTri END) AS temp_value,
                MAX(CASE WHEN cb.loaiCamBien = 'hum' THEN dl.giaTri END) AS hum_value
            FROM du_lieu_cam_bien dl
            INNER JOIN cam_bien cb ON cb.idCamBien = dl.idCamBien
            WHERE dl.trangThai = 1
              AND cb.idThietBi = ?
              AND cb.loaiCamBien IN ('temp', 'hum')
              AND dl.thoiGianDo >= DATE_SUB(NOW(), INTERVAL ? HOUR)
            GROUP BY sample_time
            ORDER BY sample_time ASC
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('ii', $deviceId, $hours);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function toggleTrangThaiPhong($id) {
        $stmt = $this->conn->prepare("
            UPDATE phong
            SET trangThai = CASE WHEN trangThai = 1 THEN 0 ELSE 1 END
            WHERE idPhong = ?
        ");

        if (!$stmt) {
            return false;
        }

        $id = intval($id);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function toggleCheDoPhong($id) {
        $stmt = $this->conn->prepare("
            UPDATE phong
            SET cheDoTuDong = CASE WHEN cheDoTuDong = 1 THEN 0 ELSE 1 END
            WHERE idPhong = ?
        ");

        if (!$stmt) {
            return false;
        }

        $id = intval($id);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    private function nullIfEmpty($value) {
        return ($value === '' || $value === null) ? null : $value;
    }

    private function layBanDoCamBienTheoThietBi($deviceId) {
        $stmt = $this->conn->prepare("
            SELECT idCamBien, loaiCamBien
            FROM cam_bien
            WHERE idThietBi = ?
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $deviceId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['loaiCamBien'])) {
                $data[$row['loaiCamBien']] = intval($row['idCamBien']);
            }
        }

        return $data;
    }

    private function extractBaseTopic($topic) {
        $topic = rtrim(trim((string) $topic), '/');

        foreach (['/sensor', '/control'] as $suffix) {
            if (substr($topic, -strlen($suffix)) === $suffix) {
                return substr($topic, 0, -strlen($suffix));
            }
        }

        return $topic;
    }
}
