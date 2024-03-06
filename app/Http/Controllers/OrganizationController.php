<?php

namespace App\Http\Controllers;

use App\Helpers\Hiorg;
use App\Helpers\Sync\Factory;
use App\Helpers\Sync\Generic;
use App\Models\DestinationField;
use App\Models\Organization;
use App\Models\SourceField;
use App\Models\User;
use App\Models\Sync;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;

class OrganizationController extends Controller
{
    public function home(Client $client, GenericProvider $provider)
    {
        $user = \Auth::user();
        $org = $user->organization;
        $fe2 = $this->getSyncData($client, $provider);
        $valid = $fe2 !== false;
        $syncs = $org->syncs()->latest()->paginate(20);
        return view('organizations.show', compact('org', 'syncs', 'valid'));
    }

    public function me(GenericProvider $provider, Client $client)
    {
        $fe2 = $this->getSyncData($client, $provider);
        $valid = $fe2 !== false;

        return view('organizations.me', compact('valid', 'fe2'));
    }

    public function faq()
    {
        return view('organizations.faq');
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

    public function provisioning(Client $client, GenericProvider $provider)
    {
        $user = \Auth::user();
        $org = $user->organization;
        $data = $this->getHiorgData($user, $provider, $client);
        $record = $data['data'];
        $helper = Factory::make($user->organization);
        $valid = $helper->isValid($record);
        $fe2 = $helper->getDataFromRecord($record);
        abort_if(!$valid, 403);

        $login = $client->post($org->fe2_link . '/rest/login', [
            'json' => [
                'username' => $org->fe2_user,
                'password' => $org->fe2_pass,
                'source' => "WEB"
            ]
        ]);
        $token = json_decode($login->getBody()->getContents())->token;

        $provId = in_array('Führung', $fe2['alarmGroups'] ?? []) ? $org->fe2_provisioning_leader : $org->fe2_provisioning_user;
        $userInfoRequest = $client->get($org->fe2_link . '/rest/addressbook/paginated/simple?page=0&filter=&ordering=ASC&limit=5000', [
            'headers' => [
                'Authorization' => 'JWT ' . $token
            ]
        ]);
        $userInfo = collect(json_decode($userInfoRequest->getBody()->getContents())->persons);
        $userData = $userInfo->where('apagerPro', $fe2['aPagerPro'])->first();
        $client->get($org->fe2_link . '/rest/apager/provisioningToSingleDevice/' . $userData->personID . '?provId=' . $provId . '&useAutoMode=false', [
            'headers' => [
                'Authorization' => 'JWT ' . $token
            ]
        ]);
        $assignedProvision[] = [
            'name' => $user->name,
            'reason' => 'user_request',
            'provision' => $provId
        ];
        Sync::create([
            'organization_id' => $org->id,
            'type' => 'prov',
            'data' => $assignedProvision,
        ]);
        return redirect()->route('home')->with('message', 'Provisionierung wurde an ' . $fe2['aPagerPro'] . ' gesendet.');
    }

    public function alarm($type, Client $client)
    {
        $user = \Auth::user();
        $org = $user->organization;
        $alarm = $type == 'alarm' ? 'ALARM' : 'INFO';
        $userInfoRequest = $client->post($org->fe2_link . '/rest/addressbook/external/person/' . $user->key . '/alarm', [
            'headers' => [
                'Authorization' => $org->fe2_sync_token
            ],
            'json' => [
                "type" => $alarm, // INFO
                "title" => "Test-Alarmierung",
                "color" => "#FE2E2E",
                "message" => "Testalarmierung aus dem Self-Service-Portal",
                "group" => "JUH Wü SEG Behandlung",
                "withFeedback" => true,
                "expiresHours" => 1

            ]
        ]);
        $assignedProvision[] = [
            'name' => $user->name,
            'reason' => 'user_request',
            'alarm' => $type
        ];
        Sync::create([
            'organization_id' => $org->id,
            'type' => 'alarm',
            'data' => $assignedProvision,
        ]);
        return redirect()->route('home')->with('message', 'Testalarm wurde ausgelöst');
    }

    private function getSyncData(Client $client, GenericProvider $provider) {
        $user = \Auth::user();
        $org = $user->organization;
        $sourceFields = SourceField::all()->keyBy('id');
        $fe2Fields = DestinationField::orderBy('name')->get();
        $fields = $fe2Fields->pluck('key')->flip()->map(fn($i) => null)->toArray();
        $hiorg = app(Hiorg::class, compact('client', 'provider', 'org'));
        $data = $hiorg->getUser($user);

        $org->load([
            'ruleSets' => fn($q) => $q->orderBy('execute_at_end')->orderBy('order')->where('type', '!=', 'spacer'),
            'ruleSets.rules.sourceField',
            'ruleSets.destinationField',
        ]);
        $syncService = new \App\Services\Sync($sourceFields, $fields);
        return $syncService->getDataFromHiorgUser($data['data'], $org->ruleSets);
    }

}
