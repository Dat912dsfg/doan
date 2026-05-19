<?php
/**
 * Process MQTT notification queue
 * Usage: php process_mqtt_queue.php
 * 
 * Run this from cron every minute:
 * * * * * * /usr/bin/php /path/to/process_mqtt_queue.php >> /tmp/mqtt_queue.log 2>&1
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Helpers\MqttNotificationQueue;

try {
    $count = MqttNotificationQueue::processQueue();
    echo "[" . date('Y-m-d H:i:s') . "] Processed $count notifications\n";
} catch (\Throwable $e) {
    echo "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . "\n";
    exit(1);
}
