<?php
require 'vendor/autoload.php';

use PhpMqtt\Client\MqttClient;

$server = 'broker.hivemq.com';
$port = 1883;
$clientId = 'php-sub-' . rand();

$mqtt = new MqttClient($server, $port, $clientId);
$mqtt->connect();

$conn = new mysqli("localhost", "root", "", "qlnt");

function getSensorValue(array $data, array $keys) {
    foreach ($keys as $key) {
        if (array_key_exists($key, $data) && is_numeric($data[$key])) {
            return (float) $data[$key];
        }
    }

    return null;
}

$mqtt->subscribe('nhatro123/room1/sensor', function ($topic, $message) use ($conn) {

    echo "Nhan: $message\n";

    $data = json_decode($message, true);

    if (!$data) {
        echo "JSON ERROR\n";
        return;
    }

    $temp = getSensorValue($data, ['nhiet_do', 'temp']);
    $hum  = getSensorValue($data, ['do_am', 'hum']);
    $gas  = getSensorValue($data, ['gas']);
    $pir  = getSensorValue($data, ['pir']);

    if ($temp === null || $hum === null || $gas === null || $pir === null) {
        echo "THIEU DU LIEU CAM BIEN\n";
        return;
    }

    // Lưu DB
    $conn->query("INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (1, $temp)");
    $conn->query("INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (2, $hum)");
    $conn->query("INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (3, $gas)");
    $conn->query("INSERT INTO du_lieu_cam_bien (idCamBien, giaTri) VALUES (4, $pir)");

}, 0);

$mqtt->loop(true);
