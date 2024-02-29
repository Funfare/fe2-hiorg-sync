<?php

namespace App\Console\Commands;

use App\Models\Organization;
use App\Models\Sync;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class fe2Provisioning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fe2-provisioning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Client $client)
    {
        $orgs = Organization::all();
        $orgs->each(function(Organization $org) use ($client) {
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
            $userRequest = $client->get($org->fe2_link.'/rest/addressbook/paginated/assignProvisionings/simple?page=0&ordering=ASC&limit=10000&field=aPagerPro&filterShared=true', [
                'headers' => [
                    'Authorization' => 'JWT '.$token
                ]
            ]);
            $users = json_decode($userRequest->getBody()->getContents());
            $assignedProvision = [];
            foreach ($users->content as $user) {
                $info = $userInfo->where('personID', $user->id)->first();
                $provId = in_array('Führung', $info->groups ?? []) ? $org->fe2_provisioning_leader : $org->fe2_provisioning_user;
                $update = false;
                if(empty($user->apagerPersonData) || $user->apagerPersonData->version == 0) {
                    $update = true;
                    $reason = 'no_provisioning';
                } elseif($user->apagerPersonData->provisioningId !== $provId) {
                    $update = true;
                    $reason = 'change_provisioning';
                } elseif($user->apagerPersonData->provisioningId == $provId && $user->apagerPersonData->version < $user->apagerPersonData->provisioningVersion) {
                    $update = true;
                    $reason = 'update_provisioning';
                }
                if($update) {
                    // assignProvisioning here
                    $assignedProvision[] = [
                        'name' => $user->displayName,
                        'reason' => $reason,
                        'provision' => $provId
                    ];
                }
            }
            Sync::create([
                'organization_id' => $org->id,
                'type' => 'prov',
                'data' => $assignedProvision,
            ]);

        });

    }
}
