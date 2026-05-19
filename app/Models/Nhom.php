<?php
namespace App\Models;
class Nhom{
    public $idNhom;
    public $maNhom;
    public $tenNhom;
    public $moTa;
    public $soThanhVien;

    public function __construct($data = []){
        $this->idNhom = $data['idNhom'] ?? null;
        $this->maNhom = $data['maNhom'] ?? null;
        $this->tenNhom = $data['tenNhom'] ?? null;
        $this->moTa = $data['moTa'] ?? null;
        $this->soThanhVien = $data['soThanhVien'] ?? null;
    }
}
?>