<?php
namespace App\Models;

class User {
    public $idNguoiDung;
    public $tenDangNhap;
    public $matKhau;
    public $hoTen;
    public $idNhom;
    public $tenNhom;      
    public $permissions; 
    public $googleId;
    public $anhDaiDien;

    public function __construct($data = []) {
        $this->idNguoiDung = $data['idNguoiDung'] ?? null;
        $this->tenDangNhap = $data['tenDangNhap'] ?? null;
        $this->matKhau     = $data['matKhau'] ?? null;
        $this->hoTen       = $data['hoTen'] ?? null;
        $this->idNhom      = $data['idNhom'] ?? null;
        $this->tenNhom     = $data['role_name'] ?? ($data['tenNhom'] ?? null);
        $this->permissions = $data['permissions'] ?? [];
        $this->googleId    = $data['google_id'] ?? null;
        $this->anhDaiDien  = $data['avatar'] ?? null;
    }
}