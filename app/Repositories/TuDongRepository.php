<?php
namespace App\Repositories;

use Config\KetNoi;

class TuDongRepository {
    private $conn;

    public function __construct() {
        $ketNoi = new KetNoi();
        $this->conn = $ketNoi->getConn();
    }

    public function layTatCaKichBan() {
        $sql = "
            SELECT kb.*,
                   COUNT(DISTINCT pkb.idPhong) AS soPhongApDung,
                   GROUP_CONCAT(DISTINCT pkb.idPhong ORDER BY pkb.idPhong SEPARATOR ',') AS danhSachPhong,
                   GROUP_CONCAT(DISTINCT p.tenPhong ORDER BY p.tenPhong SEPARATOR ', ') AS tenPhongApDung,
                   COUNT(DISTINCT kbhd.idHanhDong) AS tongHanhDong
            FROM kich_ban_tu_dong kb
            LEFT JOIN phong_kich_ban pkb ON pkb.idKichBan = kb.idKichBan AND pkb.trangThai = 1
            LEFT JOIN phong p ON p.idPhong = pkb.idPhong
            LEFT JOIN kich_ban_hanh_dong kbhd ON kbhd.idKichBan = kb.idKichBan AND kbhd.trangThai = 1
            GROUP BY kb.idKichBan
            ORDER BY kb.ngayCapNhat DESC, kb.idKichBan DESC
        ";

        return $this->fetchAll($sql);
    }

    public function layKichBanTheoId($id) {
        $id = intval($id);
        $sql = "
            SELECT kb.*,
                   GROUP_CONCAT(DISTINCT pkb.idPhong ORDER BY pkb.idPhong SEPARATOR ',') AS danhSachPhong,
                   GROUP_CONCAT(DISTINCT p.tenPhong ORDER BY p.tenPhong SEPARATOR ', ') AS tenPhongApDung
            FROM kich_ban_tu_dong kb
            LEFT JOIN phong_kich_ban pkb ON pkb.idKichBan = kb.idKichBan AND pkb.trangThai = 1
            LEFT JOIN phong p ON p.idPhong = pkb.idPhong
            WHERE kb.idKichBan = $id
            GROUP BY kb.idKichBan
            LIMIT 1
        ";

        $result = mysqli_query($this->conn, $sql);
        $row = $result ? mysqli_fetch_assoc($result) : null;
        if (!$row) {
            return null;
        }

        $row['phongIds'] = $this->parseCsvIds($row['danhSachPhong'] ?? '');
        $row['actions'] = $this->layHanhDongKichBan($id);

        return $row;
    }

    public function layKichBanDangKichHoat($filters = []) {
        $where = ['kb.kichHoat = 1'];

        if (!empty($filters['idPhong'])) {
            $where[] = 'pkb.idPhong = ' . intval($filters['idPhong']);
        }

        $sql = "
            SELECT kb.*,
                   pkb.idPhong,
                   kbhd.idHanhDong,
                   kbhd.thuTuChay,
                   kbhd.loaiHanhDong,
                   kbhd.idThietBi,
                   kbhd.lenh,
                   kbhd.giaTriLenh,
                   kbhd.moTa AS moTaHanhDong,
                   tb.topicMqtt,
                   tb.tenThietBi
            FROM kich_ban_tu_dong kb
            LEFT JOIN phong_kich_ban pkb ON pkb.idKichBan = kb.idKichBan AND pkb.trangThai = 1
            LEFT JOIN kich_ban_hanh_dong kbhd ON kbhd.idKichBan = kb.idKichBan AND kbhd.trangThai = 1
            LEFT JOIN thiet_bi tb ON tb.idThietBi = kbhd.idThietBi
            WHERE " . implode(' AND ', $where) . "
            ORDER BY kb.idKichBan ASC, kbhd.thuTuChay ASC, kbhd.idHanhDong ASC
        ";

        return $this->normalizeScriptsForSync($this->fetchAll($sql));
    }

    public function themKichBan($data) {
        $tenKichBan = mysqli_real_escape_string($this->conn, $data['tenKichBan']);
        $moTa = mysqli_real_escape_string($this->conn, $data['moTa'] ?? '');
        $dieuKien = mysqli_real_escape_string($this->conn, $data['dieuKien']);
        $hanhDong = mysqli_real_escape_string($this->conn, $data['hanhDong']);
        $loaiKichBan = mysqli_real_escape_string($this->conn, $data['loaiKichBan'] ?? 'event');
        $kichHoat = intval($data['kichHoat'] ?? 1);
        $gioBatDau = $this->toSqlNullableString($data['gioBatDau'] ?? null);
        $gioKetThuc = $this->toSqlNullableString($data['gioKetThuc'] ?? null);
        $ngayApDung = $this->toSqlNullableString($data['ngayApDung'] ?? null);
        $thoiGianChoChay = intval($data['thoiGianChoChay'] ?? 0);
        $phongIds = $this->normalizeIdList($data['phongIds'] ?? []);
        $actions = is_array($data['actions'] ?? null) ? $data['actions'] : [];

        $this->conn->begin_transaction();

        try {
            $sql = "
                INSERT INTO kich_ban_tu_dong
                (tenKichBan, moTa, dieuKien, hanhDong, loaiKichBan, kichHoat, gioBatDau, gioKetThuc, ngayApDung, thoiGianChoChay)
                VALUES
                ('$tenKichBan', '$moTa', '$dieuKien', '$hanhDong', '$loaiKichBan', $kichHoat, $gioBatDau, $gioKetThuc, $ngayApDung, $thoiGianChoChay)
            ";

            if (!mysqli_query($this->conn, $sql)) {
                throw new \RuntimeException('Khong the them kich ban.');
            }

            $idKichBan = mysqli_insert_id($this->conn);
            $this->dongBoPhongKichBan($idKichBan, $phongIds);
            $this->dongBoHanhDongKichBan($idKichBan, $actions);

            $this->conn->commit();
            return $idKichBan;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function updateKichBan($id, $data) {
        $id = intval($id);
        $tenKichBan = mysqli_real_escape_string($this->conn, $data['tenKichBan']);
        $moTa = mysqli_real_escape_string($this->conn, $data['moTa'] ?? '');
        $dieuKien = mysqli_real_escape_string($this->conn, $data['dieuKien']);
        $hanhDong = mysqli_real_escape_string($this->conn, $data['hanhDong']);
        $loaiKichBan = mysqli_real_escape_string($this->conn, $data['loaiKichBan'] ?? 'event');
        $kichHoat = intval($data['kichHoat'] ?? 1);
        $gioBatDau = $this->toSqlNullableString($data['gioBatDau'] ?? null);
        $gioKetThuc = $this->toSqlNullableString($data['gioKetThuc'] ?? null);
        $ngayApDung = $this->toSqlNullableString($data['ngayApDung'] ?? null);
        $thoiGianChoChay = intval($data['thoiGianChoChay'] ?? 0);
        $phongIds = $this->normalizeIdList($data['phongIds'] ?? []);
        $actions = is_array($data['actions'] ?? null) ? $data['actions'] : [];

        $this->conn->begin_transaction();

        try {
            $sql = "
                UPDATE kich_ban_tu_dong
                SET tenKichBan = '$tenKichBan',
                    moTa = '$moTa',
                    dieuKien = '$dieuKien',
                    hanhDong = '$hanhDong',
                    loaiKichBan = '$loaiKichBan',
                    kichHoat = $kichHoat,
                    gioBatDau = $gioBatDau,
                    gioKetThuc = $gioKetThuc,
                    ngayApDung = $ngayApDung,
                    thoiGianChoChay = $thoiGianChoChay,
                    phienBan = phienBan + 1
                WHERE idKichBan = $id
            ";

            if (!mysqli_query($this->conn, $sql)) {
                throw new \RuntimeException('Khong the cap nhat kich ban.');
            }

            mysqli_query($this->conn, "DELETE FROM phong_kich_ban WHERE idKichBan = $id");
            mysqli_query($this->conn, "DELETE FROM kich_ban_hanh_dong WHERE idKichBan = $id");

            $this->dongBoPhongKichBan($id, $phongIds);
            $this->dongBoHanhDongKichBan($id, $actions);

            $this->conn->commit();
            return true;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function xoaKichBan($id) {
        $id = intval($id);
        return mysqli_query($this->conn, "DELETE FROM kich_ban_tu_dong WHERE idKichBan = $id");
    }

    public function toggleKichHoat($id) {
        $id = intval($id);
        $sql = "UPDATE kich_ban_tu_dong SET kichHoat = CASE WHEN kichHoat = 1 THEN 0 ELSE 1 END WHERE idKichBan = $id";
        return mysqli_query($this->conn, $sql);
    }

    public function layKichBanTheoPhong($idPhong) {
        return $this->layKichBanDangKichHoat(['idPhong' => intval($idPhong)]);
    }

    public function ganKichBanVaoPhong($idPhong, $idKichBan) {
        $idPhong = intval($idPhong);
        $idKichBan = intval($idKichBan);

        $sql = "
            INSERT INTO phong_kich_ban (idPhong, idKichBan, trangThai)
            VALUES ($idPhong, $idKichBan, 1)
            ON DUPLICATE KEY UPDATE trangThai = 1
        ";

        return mysqli_query($this->conn, $sql);
    }

    public function boKichBanKhoiPhong($idPhong, $idKichBan) {
        $idPhong = intval($idPhong);
        $idKichBan = intval($idKichBan);
        return mysqli_query($this->conn, "DELETE FROM phong_kich_ban WHERE idPhong = $idPhong AND idKichBan = $idKichBan");
    }

    public function themHanhDongVaoKichBan($idKichBan, $data) {
        $idKichBan = intval($idKichBan);
        $thuTuChay = max(1, intval($data['thuTuChay'] ?? 1));
        $loaiHanhDong = mysqli_real_escape_string($this->conn, $data['loaiHanhDong'] ?? 'device_control');
        $idThietBi = $this->toSqlNullableInt($data['idThietBi'] ?? null);
        $lenh = mysqli_real_escape_string($this->conn, $data['lenh'] ?? '');
        $giaTriLenh = $this->toSqlNullableString($data['giaTriLenh'] ?? null);
        $moTa = $this->toSqlNullableString($data['moTa'] ?? null);

        $sql = "
            INSERT INTO kich_ban_hanh_dong
            (idKichBan, thuTuChay, loaiHanhDong, idThietBi, lenh, giaTriLenh, moTa)
            VALUES
            ($idKichBan, $thuTuChay, '$loaiHanhDong', $idThietBi, '$lenh', $giaTriLenh, $moTa)
        ";

        if (mysqli_query($this->conn, $sql)) {
            return mysqli_insert_id($this->conn);
        }

        return false;
    }

    public function layHanhDongKichBan($idKichBan) {
        $idKichBan = intval($idKichBan);
        $sql = "
            SELECT kbhd.*,
                   tb.tenThietBi,
                   tb.topicMqtt
            FROM kich_ban_hanh_dong kbhd
            LEFT JOIN thiet_bi tb ON tb.idThietBi = kbhd.idThietBi
            WHERE kbhd.idKichBan = $idKichBan
              AND kbhd.trangThai = 1
            ORDER BY kbhd.thuTuChay ASC, kbhd.idHanhDong ASC
        ";

        return $this->fetchAll($sql);
    }

    public function capNhatThoiGianChay($idKichBan) {
        $idKichBan = intval($idKichBan);
        $sql = "
            UPDATE kich_ban_tu_dong
            SET soLanDaChay = soLanDaChay + 1,
                thoiGianChayLanCuoi = NOW()
            WHERE idKichBan = $idKichBan
        ";

        return mysqli_query($this->conn, $sql);
    }

    public function layDanhSachPhongApDung() {
        return $this->fetchAll("
            SELECT idPhong, tenPhong, cheDoTuDong, trangThai
            FROM phong
            ORDER BY tenPhong ASC, idPhong ASC
        ");
    }

    public function layTatCaCauHinh() {
        $sql = "
            SELECT p.idPhong,
                   p.tenPhong,
                   p.cheDoTuDong,
                   ch.idCauHinh,
                   ch.nhietDoMin,
                   ch.nhietDoMax,
                   ch.doAmMin,
                   ch.doAmMax,
                   ch.gasMax,
                   ch.khoiBaoChay,
                   ch.moTa,
                   ch.ngayCapNhat
            FROM phong p
            LEFT JOIN cau_hinh ch ON ch.idPhong = p.idPhong
            ORDER BY p.tenPhong ASC, p.idPhong ASC
        ";

        return $this->fetchAll($sql);
    }

    public function layCauHinhTheoPhong($idPhong) {
        $idPhong = intval($idPhong);
        $sql = "
            SELECT p.idPhong,
                   p.tenPhong,
                   p.cheDoTuDong,
                   ch.*
            FROM phong p
            LEFT JOIN cau_hinh ch ON ch.idPhong = p.idPhong
            WHERE p.idPhong = $idPhong
            LIMIT 1
        ";

        $result = mysqli_query($this->conn, $sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    public function luuCauHinhPhong($idPhong, $data) {
        $idPhong = intval($idPhong);
        $nhietDoMin = $this->toSqlFloat($data['nhietDoMin'] ?? null);
        $nhietDoMax = $this->toSqlFloat($data['nhietDoMax'] ?? null);
        $doAmMin = $this->toSqlFloat($data['doAmMin'] ?? null);
        $doAmMax = $this->toSqlFloat($data['doAmMax'] ?? null);
        $gasMax = $this->toSqlFloat($data['gasMax'] ?? null);
        $khoiBaoChay = $this->toSqlFloat($data['khoiBaoChay'] ?? null);
        $moTa = $this->toSqlNullableString($data['moTa'] ?? null);

        $idCauHinh = $this->layIdCauHinhTheoPhong($idPhong);

        if ($idCauHinh > 0) {
            $sql = "
                UPDATE cau_hinh
                SET nhietDoMin = $nhietDoMin,
                    nhietDoMax = $nhietDoMax,
                    doAmMin = $doAmMin,
                    doAmMax = $doAmMax,
                    gasMax = $gasMax,
                    khoiBaoChay = $khoiBaoChay,
                    moTa = $moTa,
                    ngayCapNhat = CURRENT_TIMESTAMP
                WHERE idCauHinh = $idCauHinh
            ";
        } else {
            $sql = "
                INSERT INTO cau_hinh
                (idPhong, nhietDoMin, nhietDoMax, doAmMin, doAmMax, gasMax, khoiBaoChay, moTa)
                VALUES
                ($idPhong, $nhietDoMin, $nhietDoMax, $doAmMin, $doAmMax, $gasMax, $khoiBaoChay, $moTa)
            ";
        }

        return mysqli_query($this->conn, $sql);
    }

    public function layThongTinCamBien($idCamBien) {
        $idCamBien = intval($idCamBien);
        $sql = "
            SELECT cb.idCamBien,
                   cb.idThietBi,
                   cb.tenCamBien,
                   cb.loaiCamBien,
                   cb.donVi,
                   tb.idPhong,
                   tb.tenThietBi,
                   tb.topicMqtt,
                   p.tenPhong,
                   p.cheDoTuDong
            FROM cam_bien cb
            INNER JOIN thiet_bi tb ON tb.idThietBi = cb.idThietBi
            LEFT JOIN phong p ON p.idPhong = tb.idPhong
            WHERE cb.idCamBien = $idCamBien
            LIMIT 1
        ";

        $result = mysqli_query($this->conn, $sql);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    private function normalizeScriptsForSync($rows) {
        $scripts = [];
        $indexes = [];

        foreach ($rows as $row) {
            $id = intval($row['idKichBan'] ?? 0);
            if ($id <= 0) {
                continue;
            }

            if (!isset($indexes[$id])) {
                $indexes[$id] = count($scripts);
                $scripts[] = [
                    'idKichBan' => $id,
                    'tenKichBan' => (string) ($row['tenKichBan'] ?? ''),
                    'moTa' => (string) ($row['moTa'] ?? ''),
                    'dieuKien' => (string) ($row['dieuKien'] ?? ''),
                    'hanhDong' => (string) ($row['hanhDong'] ?? ''),
                    'loaiKichBan' => (string) ($row['loaiKichBan'] ?? 'event'),
                    'kichHoat' => intval($row['kichHoat'] ?? 1),
                    'gioBatDau' => $row['gioBatDau'] ?: null,
                    'gioKetThuc' => $row['gioKetThuc'] ?: null,
                    'ngayApDung' => $row['ngayApDung'] ?: null,
                    'thoiGianChoChay' => intval($row['thoiGianChoChay'] ?? 0),
                    'phienBan' => intval($row['phienBan'] ?? 1),
                    'phongIds' => [],
                    'actions' => [],
                ];
            }

            $index = $indexes[$id];

            if (!empty($row['idPhong'])) {
                $phongId = intval($row['idPhong']);
                if (!in_array($phongId, $scripts[$index]['phongIds'], true)) {
                    $scripts[$index]['phongIds'][] = $phongId;
                }
            }

            if (!empty($row['idHanhDong'])) {
                $scripts[$index]['actions'][] = [
                    'idHanhDong' => intval($row['idHanhDong']),
                    'thuTuChay' => intval($row['thuTuChay'] ?? 1),
                    'loaiHanhDong' => (string) ($row['loaiHanhDong'] ?? ''),
                    'idThietBi' => !empty($row['idThietBi']) ? intval($row['idThietBi']) : null,
                    'tenThietBi' => (string) ($row['tenThietBi'] ?? ''),
                    'topicMqtt' => (string) ($row['topicMqtt'] ?? ''),
                    'lenh' => (string) ($row['lenh'] ?? ''),
                    'giaTriLenh' => (string) ($row['giaTriLenh'] ?? ''),
                    'moTa' => (string) ($row['moTaHanhDong'] ?? ''),
                ];
            }
        }

        return $scripts;
    }

    private function dongBoPhongKichBan($idKichBan, $phongIds) {
        foreach ($phongIds as $idPhong) {
            $this->ganKichBanVaoPhong($idPhong, $idKichBan);
        }
    }

    private function dongBoHanhDongKichBan($idKichBan, $actions) {
        foreach ($actions as $action) {
            $this->themHanhDongVaoKichBan($idKichBan, $action);
        }
    }

    private function parseCsvIds($value) {
        if (trim((string) $value) === '') {
            return [];
        }

        $parts = explode(',', (string) $value);
        $ids = [];

        foreach ($parts as $part) {
            $id = intval($part);
            if ($id > 0 && !in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    private function normalizeIdList($value) {
        if (!is_array($value)) {
            $value = [$value];
        }

        $ids = [];
        foreach ($value as $item) {
            $id = intval($item);
            if ($id > 0 && !in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    private function toSqlNullableString($value) {
        $value = is_string($value) ? trim($value) : $value;
        if ($value === '' || $value === null) {
            return 'NULL';
        }

        return "'" . mysqli_real_escape_string($this->conn, (string) $value) . "'";
    }

    private function toSqlNullableInt($value) {
        if ($value === '' || $value === null || intval($value) <= 0) {
            return 'NULL';
        }

        return (string) intval($value);
    }

    private function toSqlFloat($value) {
        if ($value === '' || $value === null || !is_numeric($value)) {
            return 'NULL';
        }

        return (string) (float) $value;
    }

    private function layIdCauHinhTheoPhong($idPhong) {
        $idPhong = intval($idPhong);
        $result = mysqli_query(
            $this->conn,
            "SELECT idCauHinh FROM cau_hinh WHERE idPhong = $idPhong ORDER BY idCauHinh ASC LIMIT 1"
        );

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);
        return intval($row['idCauHinh'] ?? 0);
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
}
