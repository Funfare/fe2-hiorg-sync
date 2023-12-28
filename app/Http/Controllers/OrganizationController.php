<?php

namespace App\Http\Controllers;

use App\Helpers\Sync\Factory;
use App\Helpers\Sync\Generic;
use App\Models\Organization;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;

class OrganizationController extends Controller
{
    public function home(GenericProvider $provider)
    {
        $org = \Auth::user()->organization;
        $syncs = $org->syncs()->latest()->paginate(20);
        return view('organizations.show', compact('org', 'syncs'));
    }

    public function me(GenericProvider $provider, Client $client)
    {
        $user = \Auth::user();
        $data = \Cache::remember('hiorg-self-user-' . $user->id, now()->addHour(), function () use ($provider, $client, $user) {
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
        $record = $data['data'];
        $helper = Factory::make($user->organization);
        $valid = $helper->isValid($record);
        $fe2 = $helper->getDataFromRecord($record);

        return view('organizations.me', compact('valid', 'fe2'));
    }

    public function edit()
    {
        $org = \Auth::user()->organization;
        return view('organizations.edit', compact('org'));
    }

    public function update(Request $request)
    {
        $org = \Auth::user()->organization;
        $data = $request->validate([
            'fe2_link' => 'nullable',
            'fe2_user' => 'nullable',
            'fe2_pass' => 'nullable',
            'fe2_sync_token' => 'nullable',
            'fe2_provisioning_user' => 'nullable',
            'fe2_provisioning_leader' => 'nullable',
        ]);
        $org->update($data);
        return redirect()->route('settings')->with('message', 'Daten gespeichert');
    }

    public function setAdmin(GenericProvider $provider)
    {
        session()->put('setAdminUser', true);
        $url = $provider->getAuthorizationUrl(['scope' => 'openid personal:read']);
        return redirect($url);
    }

    public function provisioning(Client $client)
    {
        $org = \Auth::user()->organization;
        $login = $client->post($org->fe2_link.'/rest/login', [
            'json' => [
                'username' => $org->fe2_user,
                'password' => $org->fe2_pass,
                'source' => "WEB"
            ]
        ]);
        $token = json_decode($login->getBody()->getContents())->token;
        $userInfoRequest = $client->get($org->fe2_link.'/rest/addressbook/paginated/simple?page=0&filter=&ordering=ASC&limit=5000', [
            'headers' => [
                'Authorization' => 'JWT '.$token
            ]
        ]);
        $userInfo = collect(json_decode($userInfoRequest->getBody()->getContents())->persons);
        $users = json_decode($userRequest->getBody()->getContents());
        $info = $userInfo->where('aPagerPro', $user->id)->first();
        $provId = in_array('Führung', $info->groups ?? []) ? $org->fe2_provisioning_leader : $org->fe2_provisioning_user;
    }

}
