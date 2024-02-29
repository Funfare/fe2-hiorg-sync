<?php

namespace App\Helpers;

use App\Models\Organization;
use GuzzleHttp\Client;

class FE2
{
    protected $token = null;

    public function __construct(protected Client $client, protected Organization $org)
    {
    }
    public function getToken()
    {
        if($this->token !== null) {
            return $this->token;
        }
        $login = $this->client->post($this->org->fe2_link.'/rest/login', [
            'json' => [
                'username' => $this->org->fe2_user,
                'password' => $this->org->fe2_pass,
                'source' => "WEB"
            ]
        ]);
        $this->token = json_decode($login->getBody()->getContents())->token;
        return $this->token;
    }

    public function syncUsers($data)
    {
        return $this->client->post($this->org->fe2_link.'/rest/addressbook/sync', [
        'json' => $data,
        'headers' => [
            'Authorization' => $this->org->fe2_sync_token
        ]
    ]);

    }

    public function getUserProvisionings()
    {
        $userRequest = $this->client->get($this->org->fe2_link.'/rest/addressbook/paginated/assignProvisionings/simple?page=0&ordering=ASC&limit=10000&field=aPagerPro&filterShared=true', [
            'headers' => [
                'Authorization' => 'JWT '.$this->getToken()
            ]
        ]);
        return collect(json_decode($userRequest->getBody()->getContents())->content);
    }

    public function getProvisionings()
    {
        $userRequest = $this->client->get($this->org->fe2_link.'/rest/apager/provisionings', [
            'headers' => [
                'Authorization' => 'JWT '.$this->getToken()
            ]
        ]);
        return collect(json_decode($userRequest->getBody()->getContents()));
    }

    public function assignProvisioning($user, $provision)
    {
        dump($user, $provision);
        return true;
        return $this->client->get($this->org->fe2_link. '/rest/apager/provisioningToSingleDevice/'.$user.'?provId='.$provision.'&useAutoMode=false', [
            'headers' => [
                'Authorization' => 'JWT '.$this->getToken()
            ]
        ]);
    }
}
