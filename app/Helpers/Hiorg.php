<?php

namespace App\Helpers;

use App\Models\Organization;
use App\Models\User;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\GenericProvider;

class Hiorg
{
    public function __construct(protected Client $client, protected GenericProvider $provider, protected Organization $org)
    {
    }

    public function getToken()
    {
        $token = $this->org->hiorg_token;
        $accessToken = new \League\OAuth2\Client\Token\AccessToken($token);
        if($accessToken->hasExpired()) {
            $accessToken = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $accessToken->getRefreshToken()
            ]);
            $this->org->hiorg_token = $accessToken->jsonSerialize();
            $this->org->save();
        }
        return $accessToken;
    }

    public function getUserToken(User $user)
    {
        $token = $user->hiorg_token;
        $accessToken = new \League\OAuth2\Client\Token\AccessToken($token);
        if($accessToken->hasExpired()) {
            $accessToken = $this->provider->getAccessToken('refresh_token', [
                'refresh_token' => $accessToken->getRefreshToken()
            ]);
            $user->hiorg_token = $accessToken->jsonSerialize();
            $user->save();
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

    public function getUser(User $user, $forceFetch = false)
    {
        $key = 'hiorg-user-'.$user->id;
        if($forceFetch) {
            \Cache::forget($key);
        }
        return \Cache::remember($key, now()->addHour(), function() use ($user) {
            $accessToken = $this->getUserToken($user);
            $response = $this->client->get('https://api.hiorg-server.de/core/v1/personal/selbst', [
                'headers' => [
                    'Authorization' => $accessToken->getToken()
                ]
            ]);
            return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        });

    }
}
