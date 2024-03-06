<?php

namespace App\Http\Controllers;

use App\Helpers\Hiorg;
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


    public function preview(GenericProvider $provider, Client $client)
    {
        $org = \Auth::user()->organization;
        $sourceFields = SourceField::all()->keyBy('id');
        $fe2Fields = DestinationField::orderBy('name')->get();
        $fields = $fe2Fields->pluck('key')->flip()->map(fn($i) => null)->toArray();
        $hiorg = app(Hiorg::class, compact('client', 'provider', 'org'));
        $data = $hiorg->getUsers();

        $sync = [];
        $org->load([
            'ruleSets' => fn($q) => $q->orderBy('execute_at_end')->orderBy('order')->where('type', '!=', 'spacer'),
            'ruleSets.rules.sourceField',
            'ruleSets.destinationField',
        ]);
        $syncService = new \App\Services\Sync($sourceFields, $fields);
        foreach($data['data'] as $row) {
            $user = $syncService->getDataFromHiorgUser($row, $org->ruleSets);
            if($user !== false) {
                $sync[] = $user;
            }
        }
        return view('organizations.rules.preview', compact('sync','fe2Fields'));
    }
}
