<?php
namespace App\Repositories;
use App\Models\Nhom;
use Config\KetNoi;

class NhomRepository{
    private $db;

    public function __construct()
    {
        $this->db = new KetNoi();
    }

    public function layTatCaNhom(){
        $sql = "SELECT n.*, COUNT(u.idNguoiDung) AS soThanhVien 
                FROM nhomnguoidung n 
                LEFT JOIN nguoidung u ON u.idNhom = n.idNhom
                GROUP BY n.idNhom";

        $kq = $this->db->truyVan($sql);

        $nhom = [];
        if($kq && $kq->num_rows > 0){
            while ($row = $kq->fetch_assoc()){
                $nhom[] = (object)$row;
            }
        }
        return $nhom;
    }

    public function timNhomTheoId($id) {
        $sql = "SELECT * 
                FROM nhomnguoidung 
                WHERE idNhom = ?";
        
        $result = $this->db->truyVan($sql, [$id]);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return (object)$row;
        }
        
        return null;
    }

    public function insertNhom($data) {
        $sql = "INSERT INTO nhomnguoidung (maNhom, tenNhom, moTa) 
                VALUES (?, ?, ?)";
        
        return $this->db->capNhat($sql, [
            $data['maNhom'],
            $data['tenNhom'],
            $data['moTa'],
        ]);
    }

    public function deleteNhom($id) {
        $sql = "DELETE FROM nhomnguoidung WHERE idNhom = ?";
        return $this->db->capNhat($sql, [$id]);
    }

    public function updateNhom($id, $data) {
        $sql = "UPDATE nhomnguoidung SET tenNhom = ?, moTa = ? WHERE idNhom = ?";
        return $this->db->capNhat($sql, [
            $data['tenNhom'],
            $data['moTa'],
            $id
        ]);
    }

    public function clearQuyen($idNhom) {
        $sql = "DELETE FROM nhomnguoidung_quyen WHERE idNhom = ?";
        return $this->db->capNhat($sql, [$idNhom]);
    }

    public function insertQuyen($idNhom, $idQuyens) {
        foreach ($idQuyens as $idQuyen) {
            $sql = "INSERT INTO nhomnguoidung_quyen (idNhom, idQuyen) VALUES (?, ?)";
            $this->db->capNhat($sql, [$idNhom, $idQuyen]);
        }
        return true;
    }
}
?>