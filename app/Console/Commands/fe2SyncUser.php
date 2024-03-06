<?php

namespace App\Console\Commands;

use App\Helpers\FE2;
use App\Helpers\Hiorg;
use App\Helpers\Sync\Factory;
use App\Helpers\Sync\Generic;
use App\Models\DestinationField;
use App\Models\Organization;
use App\Models\SourceField;
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
        $sourceFields = SourceField::all()->keyBy('id');
        $fields = DestinationField::pluck('key')->flip()->map(fn($i) => null)->toArray();
        $orgs = Organization::all();
        $orgs->each(function(Organization $org) use ($provider, $client, $sourceFields, $fields) {
            $hiorg = app(Hiorg::class, compact('client', 'provider', 'org'));
            $data = $hiorg->getUsers();

            $sync = [
                'source' => 'JUH WÜ FE2 Sync',
                'personList' => [],
            ];
            $provisions = [];
            $org->load([
                'ruleSets' => fn($q) => $q->orderBy('execute_at_end')->orderBy('order')->where('type', '!=', 'spacer'),
                'ruleSets.rules.sourceField',
                'ruleSets.destinationField',
            ]);
            $syncService = new \App\Services\Sync($sourceFields, $fields);
            foreach($data['data'] as $row) {
                $user = $syncService->getDataFromHiorgUser($row, $org->ruleSets);
                if($user !== false) {
                    $sync['personList'][] = \Arr::except($user, ['provisioning']);
                    $provisions[$user['aPagerPro']] = $user['provisioning'];
                }
            }
            $fe2 = app(FE2::class, compact('client', 'org'));
            $fe2->syncUsers($sync);
            Sync::create([
                'organization_id' => $org->id,
                'type' => 'sync',
                'data' => $sync,
            ]);

            sleep(1);

            $assignedProvision = [];
            $allProvisionings = $fe2->getProvisionings();
            $allUserProvisionings = $fe2->getUserProvisionings();
            foreach ($provisions as $aPager => $provision) {
                if(!\Str::contains($aPager, '@')) {
                    // Tokens werden in der Provision-API klein geschrieben
                    $aPager = mb_strtolower($aPager);
                }
                $user = $allUserProvisionings->where('apager', $aPager)->first();
                $provId = $allProvisionings->where('name', $provision)->first()->id ?? null;
                if($provId == null) {
                    $assignedProvision[] = [
                        'name' => $user->displayName,
                        'reason' => 'err_prov_not_found',
                        'provision' => $provision
                    ];
                    continue;
                }
                if($user == null) {
                    $assignedProvision[] = [
                        'name' => $aPager,
                        'reason' => 'err_user_not_found',
                        'provision' => $provision
                    ];
                    continue;
                }
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
                    $fe2->assignProvisioning($user->id, $provId);
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
