<?php
namespace App\Repositories;

use Config\KetNoi;

class CanhBaoRepository {
    private $conn;

    public function __construct() {
        $ketNoi = new KetNoi();
        $this->conn = $ketNoi->getConn();
    }

    public function layAlertHoatDong($idPhong = null) {
        $whereClause = $idPhong ? 'AND cb.idPhong = ' . intval($idPhong) : '';

        $sql = "
            SELECT cb.*,
                   c.tenCamBien,
                   c.loaiCamBien,
                   p.tenPhong,
                   nd.hoTen
            FROM canh_bao cb
            INNER JOIN cam_bien c ON c.idCamBien = cb.idCamBien
            INNER JOIN phong p ON p.idPhong = cb.idPhong
            LEFT JOIN nguoidung nd ON nd.idNguoiDung = cb.nguoiXuLy
            WHERE cb.trangThaiHoatDong = 1 $whereClause
            ORDER BY cb.thoiGianBatDau DESC, cb.idCanhBao DESC
        ";

        return $this->fetchAll($sql);
    }

    public function layLichSuCanhBao($idPhong = null, $limit = 100) {
        $whereClause = $idPhong ? 'AND cb.idPhong = ' . intval($idPhong) : '';
        $limit = max(1, intval($limit));

        $sql = "
            SELECT cb.*,
                   c.tenCamBien,
                   c.loaiCamBien,
                   p.tenPhong,
                   nd.hoTen
            FROM canh_bao cb
            INNER JOIN cam_bien c ON c.idCamBien = cb.idCamBien
            INNER JOIN phong p ON p.idPhong = cb.idPhong
            LEFT JOIN nguoidung nd ON nd.idNguoiDung = cb.nguoiXuLy
            WHERE 1 = 1 $whereClause
            ORDER BY cb.ngayTao DESC, cb.idCanhBao DESC
            LIMIT $limit
        ";

        return $this->fetchAll($sql);
    }

    public function layCanhBaoTheoId($idCanhBao) {
        $idCanhBao = intval($idCanhBao);
        $sql = "
            SELECT cb.*,
                   c.tenCamBien,
                   c.loaiCamBien,
                   p.tenPhong,
                   nd.hoTen
            FROM canh_bao cb
            INNER JOIN cam_bien c ON c.idCamBien = cb.idCamBien
            INNER JOIN phong p ON p.idPhong = cb.idPhong
            LEFT JOIN nguoidung nd ON nd.idNguoiDung = cb.nguoiXuLy
            WHERE cb.idCanhBao = $idCanhBao
            LIMIT 1
        ";

        $result = mysqli_query($this->conn, $sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    public function themCanhBao($data) {
        $idPhong = intval($data['idPhong'] ?? 0);
        $idCamBien = intval($data['idCamBien'] ?? 0);
        $tenCanhBao = mysqli_real_escape_string($this->conn, $data['tenCanhBao'] ?? '');
        $noiDung = mysqli_real_escape_string($this->conn, $data['noiDung'] ?? '');
        $loaiCanhBao = mysqli_real_escape_string($this->conn, $data['loaiCanhBao'] ?? 'threshold');
        $mucDo = mysqli_real_escape_string($this->conn, $data['mucDo'] ?? 'warning');
        $dieuKien = mysqli_real_escape_string($this->conn, $data['dieuKien'] ?? '');
        $giaTriDo = $this->toSqlFloat($data['giaTriDo'] ?? null);
        $nguongViPham = $this->toSqlFloat($data['nguongViPham'] ?? null);

        $sql = "
            INSERT INTO canh_bao
            (idPhong, idCamBien, tenCanhBao, noiDung, loaiCanhBao, mucDo, dieuKien, giaTriDo, nguongViPham, trangThaiXuLy, trangThaiHoatDong, thoiGianBatDau)
            VALUES
            ($idPhong, $idCamBien, '$tenCanhBao', '$noiDung', '$loaiCanhBao', '$mucDo', '$dieuKien', $giaTriDo, $nguongViPham, 0, 1, NOW())
        ";

        if (mysqli_query($this->conn, $sql)) {
            return mysqli_insert_id($this->conn);
        }

        return false;
    }

    public function taoHoacCapNhatCanhBaoNguong($data) {
        $idPhong = intval($data['idPhong'] ?? 0);
        $idCamBien = intval($data['idCamBien'] ?? 0);
        $dieuKien = trim((string) ($data['dieuKien'] ?? ''));

        $hienTai = $this->layCanhBaoDangHoatDongTheoDieuKien($idPhong, $idCamBien, $dieuKien);
        if (!$hienTai) {
            return $this->themCanhBao($data);
        }

        $idCanhBao = intval($hienTai['idCanhBao']);
        $tenCanhBao = mysqli_real_escape_string($this->conn, $data['tenCanhBao'] ?? $hienTai['tenCanhBao']);
        $noiDung = mysqli_real_escape_string($this->conn, $data['noiDung'] ?? $hienTai['noiDung']);
        $mucDo = mysqli_real_escape_string($this->conn, $data['mucDo'] ?? $hienTai['mucDo']);
        $giaTriDo = $this->toSqlFloat($data['giaTriDo'] ?? null);
        $nguongViPham = $this->toSqlFloat($data['nguongViPham'] ?? null);

        $sql = "
            UPDATE canh_bao
            SET tenCanhBao = '$tenCanhBao',
                noiDung = '$noiDung',
                mucDo = '$mucDo',
                giaTriDo = $giaTriDo,
                nguongViPham = $nguongViPham,
                trangThaiHoatDong = 1,
                ngayCapNhat = NOW()
            WHERE idCanhBao = $idCanhBao
        ";

        return mysqli_query($this->conn, $sql) ? $idCanhBao : false;
    }

    public function dongCanhBaoTheoDieuKien($idPhong, $idCamBien, $dieuKien, $ghiChu = '') {
        $idPhong = intval($idPhong);
        $idCamBien = intval($idCamBien);
        $dieuKien = mysqli_real_escape_string($this->conn, trim((string) $dieuKien));
        $ghiChu = mysqli_real_escape_string($this->conn, $ghiChu);

        $sql = "
            UPDATE canh_bao
            SET trangThaiXuLy = CASE WHEN trangThaiXuLy = 0 THEN 2 ELSE trangThaiXuLy END,
                trangThaiHoatDong = 0,
                ghiChu = CASE WHEN '$ghiChu' = '' THEN ghiChu ELSE '$ghiChu' END,
                thoiGianKhoaPhuc = NOW(),
                ngayCapNhat = NOW()
            WHERE idPhong = $idPhong
              AND idCamBien = $idCamBien
              AND dieuKien = '$dieuKien'
              AND trangThaiHoatDong = 1
        ";

        return mysqli_query($this->conn, $sql);
    }

    public function capNhatTrangThaiCanhBao($idCanhBao, $trangThaiXuLy, $trangThaiHoatDong = null) {
        $idCanhBao = intval($idCanhBao);
        $trangThaiXuLy = intval($trangThaiXuLy);
        $parts = ["trangThaiXuLy = $trangThaiXuLy"];

        if ($trangThaiHoatDong !== null) {
            $parts[] = 'trangThaiHoatDong = ' . intval($trangThaiHoatDong);
            if (intval($trangThaiHoatDong) === 0) {
                $parts[] = 'thoiGianKhoaPhuc = NOW()';
            }
        }

        $sql = 'UPDATE canh_bao SET ' . implode(', ', $parts) . " WHERE idCanhBao = $idCanhBao";
        return mysqli_query($this->conn, $sql);
    }

    public function xacNhanCanhBao($idCanhBao, $idNguoiDung, $ghiChu = '') {
        $idCanhBao = intval($idCanhBao);
        $idNguoiDung = intval($idNguoiDung);
        $ghiChu = mysqli_real_escape_string($this->conn, $ghiChu);

        $sql = "
            UPDATE canh_bao
            SET trangThaiXuLy = 1,
                nguoiXuLy = $idNguoiDung,
                ghiChu = '$ghiChu',
                ngayCapNhat = NOW()
            WHERE idCanhBao = $idCanhBao
        ";

        return mysqli_query($this->conn, $sql);
    }

    public function giaiQuyetCanhBao($idCanhBao, $ghiChu = '') {
        $idCanhBao = intval($idCanhBao);
        $ghiChu = mysqli_real_escape_string($this->conn, $ghiChu);

        $sql = "
            UPDATE canh_bao
            SET trangThaiXuLy = 2,
                trangThaiHoatDong = 0,
                thoiGianKhoaPhuc = NOW(),
                ghiChu = '$ghiChu',
                ngayCapNhat = NOW()
            WHERE idCanhBao = $idCanhBao
        ";

        return mysqli_query($this->conn, $sql);
    }

    public function xoaCanhBao($idCanhBao) {
        $idCanhBao = intval($idCanhBao);
        return mysqli_query($this->conn, "DELETE FROM canh_bao WHERE idCanhBao = $idCanhBao");
    }

    public function xoaTatCaCanhBao() {
        return mysqli_query($this->conn, 'TRUNCATE TABLE canh_bao');
    }

    public function layThongKeAlerts($idPhong = null) {
        $whereClause = $idPhong ? 'WHERE cb.idPhong = ' . intval($idPhong) : '';
        $sql = "
            SELECT
                COUNT(CASE WHEN cb.trangThaiHoatDong = 1 THEN 1 END) AS soAlertHoatDong,
                COUNT(CASE WHEN cb.trangThaiXuLy = 0 THEN 1 END) AS soChaMo,
                COUNT(CASE WHEN cb.trangThaiXuLy = 1 THEN 1 END) AS soXacNhan,
                COUNT(CASE WHEN cb.mucDo = 'critical' AND cb.trangThaiHoatDong = 1 THEN 1 END) AS soAlertCritical,
                COUNT(*) AS tongCong
            FROM canh_bao cb
            $whereClause
        ";

        $result = mysqli_query($this->conn, $sql);
        return $result ? mysqli_fetch_assoc($result) : [
            'soAlertHoatDong' => 0,
            'soChaMo' => 0,
            'soXacNhan' => 0,
            'soAlertCritical' => 0,
            'tongCong' => 0,
        ];
    }

    private function layCanhBaoDangHoatDongTheoDieuKien($idPhong, $idCamBien, $dieuKien) {
        $idPhong = intval($idPhong);
        $idCamBien = intval($idCamBien);
        $dieuKien = mysqli_real_escape_string($this->conn, $dieuKien);

        $sql = "
            SELECT *
            FROM canh_bao
            WHERE idPhong = $idPhong
              AND idCamBien = $idCamBien
              AND dieuKien = '$dieuKien'
              AND trangThaiHoatDong = 1
            ORDER BY idCanhBao DESC
            LIMIT 1
        ";

        $result = mysqli_query($this->conn, $sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    private function fetchAll($sql) {
        $result = mysqli_query($this->conn, $sql);
        $data = [];

        if (!$result) {
            return $data;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    private function toSqlFloat($value) {
        if ($value === '' || $value === null || !is_numeric($value)) {
            return 'NULL';
        }

        return (string) (float) $value;
    }
}
