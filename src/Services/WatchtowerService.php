<?php

namespace Wlhrtr\Watchtower\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class WatchtowerService
{
    private $token;

    private string $url;
    private string $clientId;
    private string $clientSecret;


    public function __construct(string $url, string $clientId, string $clientSecret)
    {
        $this->url = $url;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->auth();
    }

    public function send(array $data): void
    {
        $response = Http::acceptJson()
            ->withToken($this->token)
            ->post($this->url . '/api/notify', $data);

        if ($response->status() === 401) {
            $this->auth();
            $this->send($data);
        }

        dd($response->status(), (string)$response);
    }

    private function auth()
    {
        $response = Http::acceptJson()->post($this->url . '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ]);

        $data = json_decode((string)$response);

        $this->token = $data->access_token;
    }
}
