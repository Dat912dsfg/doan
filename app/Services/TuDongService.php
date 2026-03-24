<?php
namespace App\Services;

use App\Repositories\TuDongRepository;

class TuDongService {
    private $tuDongRepo;

    public function __construct() {
        $this->tuDongRepo = new TuDongRepository();
    }

    /**
     * Lấy tất cả kịch bản.
     */
    public function layTatCaKichBan() {
        $kichBans = $this->tuDongRepo->layTatCaKichBan();

        foreach ($kichBans as &$kb) {
            $kb['trangThaiText'] = !empty($kb['kichHoat']) ? 'Đang bật' : 'Đã tắt';
        }

        return $kichBans;
    }

    /**
     * Dữ liệu đồng bộ cho Wokwi/thiết bị.
     */
    public function layDuLieuDongBo() {
        $kichBans = $this->tuDongRepo->layKichBanDangKichHoat();

        $normalized = array_map(function ($kb) {
            return [
                'idKichBan' => intval($kb['idKichBan']),
                'tenKichBan' => (string) ($kb['tenKichBan'] ?? ''),
                'dieuKien' => (string) ($kb['dieuKien'] ?? ''),
                'hanhDong' => (string) ($kb['hanhDong'] ?? ''),
                'gioBatDau' => $kb['gioBatDau'] ?: null,
                'gioKetThuc' => $kb['gioKetThuc'] ?: null,
                'kichHoat' => intval($kb['kichHoat'] ?? 0),
            ];
        }, $kichBans);

        $version = md5(json_encode($normalized, JSON_UNESCAPED_UNICODE));

        return [
            'version' => $version,
            'count' => count($normalized),
            'scripts' => $normalized,
        ];
    }

    /**
     * Lấy kịch bản theo ID.
     */
    public function layKichBanTheoId($id) {
        return $this->tuDongRepo->layKichBanTheoId($id);
    }

    /**
     * Thêm kịch bản mới.
     */
    public function themKichBan($data) {
        if (empty($data['tenKichBan']) || empty($data['dieuKien']) || empty($data['hanhDong'])) {
            return [
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin: Tên, Điều kiện, Hành động',
            ];
        }

        $id = $this->tuDongRepo->themKichBan($data);

        if ($id) {
            return [
                'success' => true,
                'message' => 'Thêm kịch bản thành công',
                'id' => $id,
            ];
        }

        return [
            'success' => false,
            'message' => 'Lỗi khi thêm kịch bản',
        ];
    }

    /**
     * Cập nhật kịch bản.
     */
    public function suaKichBan($id, $data) {
        if (empty($data['tenKichBan']) || empty($data['dieuKien']) || empty($data['hanhDong'])) {
            return [
                'success' => false,
                'message' => 'Vui lòng điền đầy đủ thông tin: Tên, Điều kiện, Hành động',
            ];
        }

        $result = $this->tuDongRepo->updateKichBan($id, $data);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Cập nhật kịch bản thành công',
            ];
        }

        return [
            'success' => false,
            'message' => 'Lỗi khi cập nhật kịch bản',
        ];
    }

    /**
     * Xóa kịch bản.
     */
    public function xoaKichBan($id) {
        $result = $this->tuDongRepo->xoaKichBan($id);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Xóa kịch bản thành công',
            ];
        }

        return [
            'success' => false,
            'message' => 'Lỗi khi xóa kịch bản',
        ];
    }

    /**
     * Bật/tắt kịch bản.
     */
    public function toggleKichHoat($id) {
        $result = $this->tuDongRepo->toggleKichHoat($id);

        if ($result) {
            $kb = $this->tuDongRepo->layKichBanTheoId($id);
            return [
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'kichHoat' => intval($kb['kichHoat'] ?? 0),
            ];
        }

        return [
            'success' => false,
            'message' => 'Lỗi khi cập nhật trạng thái',
        ];
    }
}
