<?php

namespace App\Http\Controllers;

use App\Models\DestinationField;
use App\Models\SourceField;
use App\Models\Tab;
use App\Models\User;
use App\Services\Sync;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\GenericProvider;

class RuleController extends Controller
{
    public function show(Tab $tab = null)
    {
        $org = \Auth::user()->organization;

        return view('organizations.rules.show', compact('org', 'tab'));
    }


    public function test(GenericProvider $provider, Client $client)
    {
        $user = \Auth::user();
//        $data = $this->getHiorgData($user, $provider, $client)['data'];
        $sourceFields = SourceField::all()->keyBy('id');
        $fields = DestinationField::pluck('key')->flip()->map(fn($i) => null)->toArray();
        $org = $user->organization;

        $data = \Cache::remember('test-hiorg-data', now()->addHour(), function() use ($org, $provider, $client){
            $accessToken = new \League\OAuth2\Client\Token\AccessToken($org->hiorg_token);
            if($accessToken->hasExpired()) {
                $accessToken = $provider->getAccessToken('refresh_token', [
                    'refresh_token' => $accessToken->getRefreshToken()
                ]);
                $org->hiorg_token = $accessToken->jsonSerialize();
                $org->save();
            }

            $response = $client->get('https://api.hiorg-server.de/core/v1/personal', [
                'headers' => [
                    'Authorization' => $accessToken->getToken()
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
            return $data;
        });
         $org->load([
             'ruleSets' => fn($q) => $q->orderBy('order')->where('type', '!=', 'spacer'),
             'ruleSets.rules.sourceField',
             'ruleSets.destinationField',
         ]);
        $sync = new Sync($sourceFields, $fields);
        $users = [];
        $provision = [];
        foreach($data['data'] as $row) {
            $user = $sync->getDataFromHiorgUser($row, $org->ruleSets);
            if($user !== false) {
                $users[] = \Arr::except($user, ['provisioning']);
                $provision[$user['externalDbId']] = $user['provisioning'];
            }
        }

        dd($users, $provision);

    }

    private function getHiorgData(User $user, $provider, $client)
    {
        return \Cache::remember('hiorg-self-user-' . $user->id, now()->addHour(), function () use ($provider, $client, $user) {
            $accessToken = new \League\OAuth2\Client\Token\AccessToken($user->hiorg_token);
            if ($accessToken->hasExpired()) {
                $accessToken = $provider->getAccessToken('refresh_token', [
                    'refresh_token' => $accessToken->getRefreshToken()
                ]);
                $user->hiorg_token = $accessToken->jsonSerialize();
                $user->save();
            }

            $response = $client->get('https://api.hiorg-server.de/core/v1/personal/selbst', [
                'headers' => [
                    'Authorization' => $accessToken->getToken()
                ]
            ]);
            return json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        });
    }
}
