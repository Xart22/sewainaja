<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

class FcmService
{
    protected $fcm_project_id;

    public function __construct()
    {
        $this->fcm_project_id = env('FCM_PROJECT_ID', 'disewainaja');
    }

    public function sendNotification($fcm_token, $title, $body, $data = [])
    {
        $access_token = $this->getAccessToken();

        $headers = [
            "Authorization" => "Bearer $access_token",
            "Content-Type" => "application/json",
        ];

        $dataMessage = [
            'message' => [
                'token' => $fcm_token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,

                ],
                "android" => [
                    "notification" => [
                        "sound" => "notification",
                        "channel_id" => "instant_notification_channel_id"
                    ]
                ],
            ],
        ];

        $response = Http::withHeaders($headers)
            ->post('https://fcm.googleapis.com/v1/projects/' . $this->fcm_project_id . '/messages:send', $dataMessage);

        if ($response->failed()) {
            throw new \Exception('Failed to send notification');
        }

        return $response->json();
    }



    private function getAccessToken()
    {
        $credentialsFilePath = Storage::disk('local')->path('firebase/disewainaja-firebase-adminsdk-i4pdh-7fd46b763a.json');

        if (!file_exists($credentialsFilePath)) {
            throw new \Exception('Firebase credentials file not found.');
        }

        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();

        $token = $client->fetchAccessTokenWithAssertion();

        if (isset($token['error'])) {
            throw new \Exception('Error fetching access token: ' . $token['error']);
        }

        return $token['access_token'];
    }
}
