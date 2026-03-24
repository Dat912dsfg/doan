<?php
namespace App\Repositories;

use Config\KetNoi;

class TuDongRepository {
    private $conn;

    public function __construct() {
        $ketNoi = new KetNoi();
        $this->conn = $ketNoi->getConn();
    }

    /**
     * Lấy tất cả kịch bản tự động.
     */
    public function layTatCaKichBan() {
        $sql = "SELECT * FROM `kich_ban_tu_dong` ORDER BY ngayTao DESC";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Lấy kịch bản theo ID.
     */
    public function layKichBanTheoId($id) {
        $id = intval($id);
        $sql = "SELECT * FROM `kich_ban_tu_dong` WHERE idKichBan = $id";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }

    /**
     * Thêm kịch bản mới.
     */
    public function themKichBan($data) {
        $tenKichBan = mysqli_real_escape_string($this->conn, $data['tenKichBan']);
        $dieuKien = mysqli_real_escape_string($this->conn, $data['dieuKien']);
        $hanhDong = mysqli_real_escape_string($this->conn, $data['hanhDong']);
        $moTa = mysqli_real_escape_string($this->conn, $data['moTa'] ?? '');
        $kichHoat = intval($data['kichHoat'] ?? 1);
        $gioBatDau = $data['gioBatDau'] ?? null;
        $gioKetThuc = $data['gioKetThuc'] ?? null;

        $gioBatDauVal = $gioBatDau ? "'" . mysqli_real_escape_string($this->conn, $gioBatDau) . "'" : "NULL";
        $gioKetThucVal = $gioKetThuc ? "'" . mysqli_real_escape_string($this->conn, $gioKetThuc) . "'" : "NULL";

        $sql = "INSERT INTO `kich_ban_tu_dong`
                (tenKichBan, dieuKien, hanhDong, moTa, kichHoat, gioBatDau, gioKetThuc)
                VALUES
                ('$tenKichBan', '$dieuKien', '$hanhDong', '$moTa', $kichHoat, $gioBatDauVal, $gioKetThucVal)";

        if (mysqli_query($this->conn, $sql)) {
            return mysqli_insert_id($this->conn);
        }

        return false;
    }

    /**
     * Cập nhật kịch bản.
     */
    public function updateKichBan($id, $data) {
        $id = intval($id);
        $tenKichBan = mysqli_real_escape_string($this->conn, $data['tenKichBan']);
        $dieuKien = mysqli_real_escape_string($this->conn, $data['dieuKien']);
        $hanhDong = mysqli_real_escape_string($this->conn, $data['hanhDong']);
        $moTa = mysqli_real_escape_string($this->conn, $data['moTa'] ?? '');
        $kichHoat = intval($data['kichHoat'] ?? 1);
        $gioBatDau = $data['gioBatDau'] ?? null;
        $gioKetThuc = $data['gioKetThuc'] ?? null;

        $gioBatDauVal = $gioBatDau ? "'" . mysqli_real_escape_string($this->conn, $gioBatDau) . "'" : "NULL";
        $gioKetThucVal = $gioKetThuc ? "'" . mysqli_real_escape_string($this->conn, $gioKetThuc) . "'" : "NULL";

        $sql = "UPDATE `kich_ban_tu_dong`
                SET tenKichBan = '$tenKichBan',
                    dieuKien = '$dieuKien',
                    hanhDong = '$hanhDong',
                    moTa = '$moTa',
                    kichHoat = $kichHoat,
                    gioBatDau = $gioBatDauVal,
                    gioKetThuc = $gioKetThucVal
                WHERE idKichBan = $id";

        return mysqli_query($this->conn, $sql);
    }

    /**
     * Xóa kịch bản.
     */
    public function xoaKichBan($id) {
        $id = intval($id);
        $sql = "DELETE FROM `kich_ban_tu_dong` WHERE idKichBan = $id";
        return mysqli_query($this->conn, $sql);
    }

    /**
     * Bật/tắt trạng thái kịch bản.
     */
    public function toggleKichHoat($id) {
        $id = intval($id);
        $sql = "UPDATE `kich_ban_tu_dong` SET kichHoat = !kichHoat WHERE idKichBan = $id";
        return mysqli_query($this->conn, $sql);
    }

    /**
     * Lấy danh sách kịch bản đang kích hoạt để đồng bộ thiết bị.
     */
    public function layKichBanDangKichHoat() {
        $sql = "SELECT * FROM `kich_ban_tu_dong` WHERE kichHoat = 1 ORDER BY idKichBan ASC";
        $result = mysqli_query($this->conn, $sql);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
}
