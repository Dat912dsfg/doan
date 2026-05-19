<?php
namespace App\Services;

class GoogleAuthService {
    private $firebaseApiKey;

    public function __construct() {
        $this->firebaseApiKey = "AIzaSyAj6ByNQyVEN4gfCe7lYFre1B56DSvWdt0";
    }

    public function verifyIdToken($idToken) {
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=" . $this->firebaseApiKey;
        
        $postData = json_encode(['idToken' => $idToken]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return null;
        }
        
        $payload = json_decode($response, true);
        
        if (!isset($payload['users'][0])) {
            return null;
        }
        
        $user = $payload['users'][0];
        
        return [
            'sub' => $user['localId'] ?? '',
            'email' => $user['email'] ?? '',
            'name' => $user['displayName'] ?? '',
            'picture' => $user['photoUrl'] ?? ''
        ];
    }
}
?>