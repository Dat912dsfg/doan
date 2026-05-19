<?php
namespace App\Models;

class Quyen{
    public $idQuyen;
    public $maQuyen;
    public $tenQuyen;
    public $soNhom;

    public function __construct($data = [])
    {
        $this->idQuyen = $data['idQuyen'] ?? null;
        $this->maQuyen = $data['maQuyen'] ?? null;
        $this->tenQuyen = $data['tenQuyen'] ?? null;
        $this->soNhom = $data['soNhom'] ?? null;
    }
}
?>