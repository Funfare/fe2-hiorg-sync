<?php

namespace App\Http\Controllers;

use App\Models\DestinationField;
use App\Models\SourceField;
use App\Models\Tab;
use App\Models\User;
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
        $data = $this->getHiorgData($user, $provider, $client)['data'];
        $data['attributes']['benutzerdefinierte_felder'][3]['value'] = 'abweichend@apager.de';
        $data['attributes']['qualifikationen'][1]['name_kurz'] = 'IuK';
        $sourceFields = SourceField::all()->keyBy('id');
        $fields = DestinationField::pluck('key')->flip()->map(fn($i) => null)->toArray();
        $org = $user->organization;
        $org->load([
            'ruleSets' => fn($q) => $q->orderBy('order')->where('type', '!=', 'spacer'),
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
                $source = \Arr::get($data, $rule->sourceField->key);
                $comparer = new $class;
                if($rule->sourceField->needs_extra_value) {
                    if($rule->sourceField->key == 'attributes.qualifikationen') {
                        $key = 'liste';
                        $value = 'name_kurz';
                    } elseif($rule->sourceField->key == 'attributes.benutzerdefinierte_felder') {
                        $key = 'name';
                        $value = 'value';
                    } else {
                        $key = 'name';
                        $value = 'value';
                    }
                    $tmp = \Arr::first($source, function($item) use ($key, $value, $rule) {
                        return \Arr::has($item, $key) && $item[$key] == $rule->source_field_extra_name;
                    });
                    $source = $tmp[$value];
                }
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
                $sourceField = SourceField::find($ruleSet->set_value);
                if($sourceField->needs_extra_value) {
                    if($sourceField->key == 'attributes.qualifikationen') {
                        $key = 'liste';
                        $value = 'name_kurz';
                    } elseif($sourceField->key == 'attributes.benutzerdefinierte_felder') {
                        $key = 'name';
                        $value = 'value';
                    } else {
                        $key = 'name';
                        $value = 'value';
                    }
                    $tmp = \Arr::first($destinationValue, function($item) use ($key, $value, $ruleSet) {
                        return \Arr::has($item, $key) && $item[$key] == $ruleSet->source_field_extra_name;
                    });
                    $destinationValue = $tmp[$value];
                }
            } elseif(str_starts_with($ruleSet->set_value_type, 'qualification:')) {
                $destinationValue = \Arr::get($data,'attributes.qualifikationen');
                $tmp = \Arr::first($destinationValue, function($item) use ($ruleSet) {
                    return \Arr::has($item, 'liste') && $item['liste'] == $ruleSet->set_value;
                });
                if($ruleSet->set_value_type == 'qualification:name') {
                    $destinationValue = $tmp['name'];
                } elseif($ruleSet->set_value_type == 'qualification:name_short') {
                    $destinationValue = $tmp['name_kurz'];
                }
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
        $provisioning = $fields['provisioning'];
        $apiFields = \Arr::except($fields,'provisioning');
        dd($apiFields, $provisioning);
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
