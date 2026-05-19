<?php
$conn = new mysqli("localhost", "root", "", "qlnt");

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
?>