<?php

namespace App\Http\Controllers;

use App\Models\DestinationField;
use App\Models\SourceField;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\GenericProvider;

class RuleController extends Controller
{
    public function show()
    {
        $org = \Auth::user()->organization;

        return view('organizations.rules.show', compact('org'));
    }


    public function test(GenericProvider $provider, Client $client)
    {
        $user = \Auth::user();
        $data = $this->getHiorgData($user, $provider, $client)['data'];
        $sourceFields = SourceField::all()->keyBy('id');
        $fields = DestinationField::all()->pluck('key')->flip()->map(fn($i) => null)->toArray();
        $org = $user->organization;
        $org->load([
            'ruleSets' => fn($q) => $q->orderBy('order'),
            'ruleSets.rules.sourceField',
            'ruleSets.destinationField',
        ]);
        foreach($org->ruleSets as $ruleSet) {
            $result = false;
            $allResults = collect();
            if($ruleSet->rules->count() == 0) {
                $result = true;
            }
            foreach($ruleSet->rules as $rule) {
                $class = "\\App\\Compares\\" . $rule->compare_class;
                $comparer = new $class;
                $source = \Arr::get($data, $rule->sourceField->key);
                $success = $comparer->valid($source, $rule->compare_value);
                if($rule->not) {
                    $success = !$success;
                }
                $allResults[] = $success;
                if($ruleSet->operation == 'or' && $success) {
                    $result = true;
                    break;
                }
                if($ruleSet->operation == 'and' && !$success) {
                    $result = false;
                    break;
                }

            }
            dump($allResults);
            if($ruleSet->operation == 'and'
                && $allResults->filter(fn($i) => $i === false)->count() == 0) {
                $result = true;
            }
            if(!$result) {
                continue;
            }
            $destinationValue = $ruleSet->set_value;
            if($ruleSet->set_value_type === 'field') {
                $destinationValue = \Arr::get($data,$sourceFields->get($ruleSet->set_value)->key);
            }
            if($ruleSet->type == 'set' && empty($fields[$ruleSet->destinationField->key])) {
                if($ruleSet->destinationField->type == 'array') {
                    $fields[$ruleSet->destinationField->key] = [$destinationValue];
                } elseif ($ruleSet->destinationField->type == 'string') {
                    $fields[$ruleSet->destinationField->key] = $destinationValue;
                }
            } elseif($ruleSet->type == 'replace') {
                if($ruleSet->destinationField->type == 'array') {
                    $fields[$ruleSet->destinationField->key] = [$destinationValue];
                } elseif ($ruleSet->destinationField->type == 'string') {
                    $fields[$ruleSet->destinationField->key] = $destinationValue;
                }
            } elseif($ruleSet->type == 'add') {
                if($ruleSet->destinationField->type == 'array') {
                    $fields[$ruleSet->destinationField->key][] = $destinationValue;
                } elseif ($ruleSet->destinationField->type == 'string') {
                    $fields[$ruleSet->destinationField->key] .= $destinationValue;
                }

            }


        }
        dd($fields);
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
