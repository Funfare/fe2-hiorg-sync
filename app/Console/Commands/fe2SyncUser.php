<?php

namespace App\Console\Commands;

use App\Helpers\Sync\Factory;
use App\Helpers\Sync\Generic;
use App\Models\Organization;
use App\Models\Sync;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use League\OAuth2\Client\Provider\GenericProvider;

class fe2SyncUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fe2-sync-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(GenericProvider $provider, Client $client)
    {

        $orgs = Organization::all();
        $orgs->each(function(Organization $org) use ($provider, $client) {
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

            $helper =  Factory::make($org);
            $sync = [
                'source' => 'JUH WÜ FE2 Sync',
                'personList' => [],
            ];

            foreach ($data['data'] as $record) {
                if(!$helper->isValid($record)) {
                    continue;
                }
                $sync['personList'][] = $helper->getDataFromRecord($record);
            }
            $res = $client->post($org->fe2_link.'/rest/addressbook/sync', [
                'json' => $sync,
                'headers' => [
                    'Authorization' => $org->fe2_sync_token
                ]
            ]);

            Sync::create([
                'organization_id' => $org->id,
                'type' => 'sync',
                'data' => $sync,
            ]);
        });





    }
}
