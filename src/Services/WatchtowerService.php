<?php

namespace Wlhrtr\Watchtower\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class WatchtowerService
{
    private $token;

    private string $url;
    private string $clientId;
    private string $clientSecret;

    private $client;

    public function __construct(string $url, string $clientId, string $clientSecret)
    {
        $this->url = $url;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->client = new Client(['base_uri' => $url]);

        $this->auth();
    }

    public function send(array $data): bool
    {
        $response = $this->client->post($this->url . '/api/notify', [
            'json' => $data,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$this->token}",
            ]
        ]);

        if ($response->getStatusCode() === 401) {
            $this->auth();
            $this->send($data);
        }

        return $response->getStatusCode() === 200 ? true : false;
    }

    private function auth(): void
    {
        $response = $this->client->post($this->url . '/oauth/token', [
            'json' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]
        ]);

        match ($response->getStatusCode()) {
            404 => throw new Exception('Auth URL not found. Check ENV vars.'),
            401 => throw new Exception('Auth failed. Please check your credentials.'),
            default => null
        };

        $data = json_decode((string)$response->getBody());

        $this->token = $data->access_token;
    }
}
