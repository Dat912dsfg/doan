<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "iot_db");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status"=>"error"]);
    exit;
}

// insert từng loại
$conn->query("INSERT INTO dulieu (loaiCamBien, giaTri) VALUES (1, ".$data['temp'].")");
$conn->query("INSERT INTO dulieu (loaiCamBien, giaTri) VALUES (2, ".$data['hum'].")");
$conn->query("INSERT INTO dulieu (loaiCamBien, giaTri) VALUES (3, ".$data['gas'].")");

echo json_encode(["status"=>"ok"]);
?>