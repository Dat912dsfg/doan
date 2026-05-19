<?php
namespace App\Repositories;
use App\Models\Quyen;
use Config\KetNoi;

class QuyenRepository {
    private $db;

    public function __construct() {
        $this->db = new KetNoi();
    }

    public function layTatCaQuyen() {
        $sql = "SELECT q.*, COUNT(nq.idNhom) as soNhom 
                FROM quyen q 
                LEFT JOIN nhomnguoidung_quyen nq ON q.idQuyen = nq.idQuyen 
                GROUP BY q.idQuyen";

        $kq = $this->db->truyVan($sql);

        $quyen = [];
        if ($kq && $kq->num_rows > 0) {
            while ($row = $kq->fetch_assoc()) {
                $quyen[] = (object)$row;
            }
        }
        return $quyen;
    }

    public function layQuyenCuaNhom($idNhom) {
        $sql = "SELECT idQuyen FROM nhomnguoidung_quyen WHERE idNhom = ?";
        $kq = $this->db->truyVan($sql, [$idNhom]);
        
        $ids = [];
        if ($kq && $kq->num_rows > 0) {
            while ($row = $kq->fetch_assoc()) {
                $ids[] = $row['idQuyen'];
            }
        }
        return $ids;
    }

    public function layQuyenTheoId($id){
        $sql = "SELECT * FROM quyen
                WHERE idQuyen = ?";
        
        $kq = $this->db->truyVan($sql, [$id]);
        
        if ($kq && $kq->num_rows > 0) {
            $row = $kq->fetch_assoc();
            return (object)$row;
        }
        
        return null;
    }

    public function insertQuyen($data) {
        $sql = "INSERT INTO quyen (maQuyen, tenQuyen) 
                VALUES (?, ?)";
        
        return $this->db->capNhat($sql, [
            $data['maQuyen'],
            $data['tenQuyen']
        ]);
    }

    public function updateQuyen($id, $data) {
        $sql = "UPDATE quyen 
                SET tenQuyen = ? 
                WHERE idQuyen = ?";
        
        return $this->db->capNhat($sql, [
            $data['tenQuyen'],
            $id
        ]);
    }

    public function deleteQuyen($id) {
        $sql = "DELETE FROM quyen WHERE idQuyen = ?";
        return $this->db->capNhat($sql, [$id]);
    }
}
?>