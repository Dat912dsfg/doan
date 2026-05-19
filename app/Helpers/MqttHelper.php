<?php
namespace App\Helpers;

use PhpMqtt\Client\MqttClient;

class MqttHelper {
    private static $instance = null;
    private $client = null;
    private const BROKER_HOST = 'broker.hivemq.com';
    private const BROKER_PORT = 1883;
    private const CONNECTION_TIMEOUT = 2;  // 2 seconds timeout
    private const PUBLISH_TIMEOUT = 1;     // 1 second for publish

    private function __construct() {
        // Private constructor for singleton
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        if ($this->client === null) {
            try {
                $clientId = 'php-publish-' . uniqid();
                $this->client = new MqttClient(self::BROKER_HOST, self::BROKER_PORT, $clientId);
                
                // Set timeout to prevent blocking
                $this->client->setSocketTimeout(self::CONNECTION_TIMEOUT, 0);
                
                $this->client->connect();
                error_log('[MQTT] Connected successfully');
            } catch (\Throwable $e) {
                error_log('[MQTT] Connection failed: ' . $e->getMessage());
                $this->client = null;
                return false;
            }
        }
        return true;
    }

    public function publish($topic, $message, $qos = 0) {
        try {
            if (!$this->connect()) {
                error_log('[MQTT] Failed to publish - connection error');
                return false;
            }

            $this->client->publish($topic, $message, $qos);
            error_log('[MQTT] Published to ' . $topic . ': ' . $message);
            return true;
        } catch (\Throwable $e) {
            error_log('[MQTT] Publish failed: ' . $e->getMessage());
            $this->disconnect();
            return false;
        }
    }

    public function disconnect() {
        if ($this->client !== null) {
            try {
                $this->client->disconnect();
            } catch (\Throwable $e) {
                error_log('[MQTT] Disconnect error: ' . $e->getMessage());
            }
            $this->client = null;
        }
    }

    public function __destruct() {
        $this->disconnect();
    }
}
