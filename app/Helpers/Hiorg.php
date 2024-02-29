<?php

namespace App\Helpers;

use App\Models\Organization;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\GenericProvider;

class Hiorg
{
    public function __construct(protected Client $client, protected GenericProvider $provider, protected Organization $org)
    {
    }

    public function getToken()
    {
        $accessToken = new \League\OAuth2\Client\Token\AccessToken($this->org->hiorg_token);
        if($accessToken->hasExpired()) {
            $accessToken = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $accessToken->getRefreshToken()
            ]);
            $this->org->hiorg_token = $accessToken->jsonSerialize();
            $this->org->save();
        }
        return $accessToken;
    }

    public function getUsers($forceFetch = false)
    {
        $key = 'hiorg-users-'.$this->org->id;
        if($forceFetch) {
            \Cache::forget($key);
        }
        return \Cache::remember($key, now()->addHour(), function() {
            $accessToken = $this->getToken();
            $response = $this->client->get('https://api.hiorg-server.de/core/v1/personal', [
                'headers' => [
                    'Authorization' => $accessToken->getToken()
                ]
            ]);
            return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        });

    }
}
