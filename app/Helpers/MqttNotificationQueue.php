<?php
namespace App\Helpers;

/**
 * Async MQTT notification queue
 * Saves notifications to file for later processing via CLI/cron
 */
class MqttNotificationQueue {
    private static function getQueueFile() {
        return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'mqtt_notifications.queue';
    }

    public static function enqueue($topic, $message) {
        try {
            $notification = [
                'timestamp' => time(),
                'topic' => $topic,
                'message' => $message,
            ];

            $line = json_encode($notification) . "\n";
            
            // Atomic append to queue file
            file_put_contents(
                self::getQueueFile(),
                $line,
                FILE_APPEND | LOCK_EX
            );

            error_log('[MqttQueue] Enqueued: ' . $topic);
            return true;
        } catch (\Throwable $e) {
            error_log('[MqttQueue] Enqueue failed: ' . $e->getMessage());
            return false;
        }
    }

    public static function processQueue() {
        try {
            $queueFile = self::getQueueFile();

            if (!file_exists($queueFile)) {
                return 0;
            }

            $lines = file($queueFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (empty($lines)) {
                return 0;
            }

            $mqtt = MqttHelper::getInstance();
            $processed = 0;

            foreach ($lines as $line) {
                try {
                    $notification = json_decode($line, true);
                    if (!$notification) continue;

                    $topic = $notification['topic'] ?? null;
                    $message = $notification['message'] ?? null;
                    
                    if ($topic && $message) {
                        if ($mqtt->publish($topic, $message)) {
                            $processed++;
                        }
                    }
                } catch (\Throwable $e) {
                    error_log('[MqttQueue] Process error: ' . $e->getMessage());
                }
            }

            // Clear processed queue
            @unlink($queueFile);
            error_log('[MqttQueue] Processed ' . $processed . ' notifications');
            return $processed;
        } catch (\Throwable $e) {
            error_log('[MqttQueue] processQueue failed: ' . $e->getMessage());
            return 0;
        }
    }
}
