<?php
include "connect.php";

$sql = "SELECT giaTri, thoiGianDo 
        FROM du_lieu_cam_bien 
        ORDER BY thoiGianDo DESC 
        LIMIT 10";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>