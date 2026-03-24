<?php
require 'vendor/autoload.php';

use PhpMqtt\Client\MqttClient;

$server = 'broker.hivemq.com';
$port = 1883;
$clientId = 'php-sub-' . rand();

$mqtt = new MqttClient($server, $port, $clientId);
$mqtt->connect();

$conn = new mysqli("localhost", "root", "", "qlnt");

$mqtt->subscribe('nhatro123/room1/sensor', function ($topic, $message) use ($conn) {

    echo "Nhan: $message\n";

    $data = json_decode($message, true);

    if (!$data) {
        echo "JSON ERROR\n";
        return;
    }

    $temp = $data['temp'];
    $hum  = $data['hum'];
    $gas  = $data['gas'];
    $pir  = $data['pir'];

    // Lưu DB
    $conn->query("INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (1, $temp)");
    $conn->query("INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (2, $hum)");
    $conn->query("INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (3, $gas)");
    $conn->query("INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (4, $pir)");

}, 0);

$mqtt->loop(true);