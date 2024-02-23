<?php

namespace App\Services;

use App\Models\SourceField;

class Sync
{
    public function __construct(protected $sourceFields, protected $destinationFields)
    {

    }
    public function getDataFromHiorgUser($data, $ruleSets)
    {
        $user = $this->destinationFields;
        foreach($ruleSets as $ruleSet) {
            $user = $this->applyRuleSet($data, $ruleSet, $user);
            if($user === false) {
                return false;
            }
        }
        return $user;
    }

    protected function applyRuleSet($data, $ruleSet, $fields) {
        if(!$this->isValid($data, $ruleSet)) {
            return $fields;
        }
        return $this->getDestinationValue($data, $ruleSet, $fields);
    }

    protected function isValid($data, $ruleSet) {
        $result = false;
        if($ruleSet->rules->count() == 0) {
            return true;
        }
        $allResults = collect();
        foreach($ruleSet->rules as $rule) {
            $comparer = new ("\\App\\Compares\\" . $rule->compare_class);
            $value = $this->getSourceValue($data, $rule);
            $success = $comparer->valid($value, $rule->compare_value);
            if($rule->not) {
                $success = !$success;
            }
            $allResults[] = $success;
            if($ruleSet->operation == 'or' && $success) {
                $result = true;
                break;
            }
            if($ruleSet->operation == 'and' && !$success) {
                break;
            }

        }
        if($ruleSet->operation == 'and'
            && $allResults->filter(fn($i) => $i === false)->count() == 0) {
            $result = true;
        }
        return $result;
    }

    protected function getSourceValue($data, $rule) {
        $source = \Arr::get($data, $rule->sourceField->key);
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
        return $source;
    }

    protected function getDestinationValue($data, $ruleSet, $fields) {
        $destinationValue = $ruleSet->set_value;
        if($ruleSet->set_value_type === 'field') {
            $destinationValue = \Arr::get($data,$this->sourceFields->get($ruleSet->set_value)->key);
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
                $destinationValue = $tmp[$value] ?? '';
            }
        } elseif(str_starts_with($ruleSet->set_value_type, 'qualification:')) {
            $destinationValue = \Arr::get($data,'attributes.qualifikationen');
            $tmp = \Arr::first($destinationValue, function($item) use ($ruleSet) {
                return \Arr::has($item, 'liste') && $item['liste'] == $ruleSet->set_value;
            });
            if($ruleSet->set_value_type == 'qualification:name') {
                $destinationValue = $tmp['name'] ?? 'unknown';
            } elseif($ruleSet->set_value_type == 'qualification:name_short') {
                $destinationValue = $tmp['name_kurz'] ?? 'unknown';
            }
        } elseif(str_starts_with($ruleSet->set_value_type, 'phone:formatted')) {
            $destinationValue = \Arr::get($data,$this->sourceFields->get($ruleSet->set_value)->key);
            if(str_starts_with($destinationValue, '+')) {
                $destinationValue = '00'.substr($destinationValue, 1);
            }
            if(!str_starts_with($destinationValue, '00')) {
                $destinationValue = '0049'.substr($destinationValue, 1);
            }
            $destinationValue = str_replace([' ', '/', '-', '+'], '', $destinationValue);
        }
        if(is_array($destinationValue)) {
            $destinationValue = implode(',', $destinationValue);
        }

        if($ruleSet->type == 'abort') {
            return false;
        } elseif($ruleSet->type == 'set' && empty($fields[$ruleSet->destinationField->key])) {
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

        }elseif($ruleSet->type == 'remove') {
            if($ruleSet->destinationField->type == 'array') {
                if(is_array($fields[$ruleSet->destinationField->key])) {
                    $index = array_search($destinationValue, $fields[$ruleSet->destinationField->key]);
                    if($index !== false) {
                        unset($fields[$ruleSet->destinationField->key][$index]);
                        $fields[$ruleSet->destinationField->key] = array_values($fields[$ruleSet->destinationField->key]);
                    }
                }
            } elseif ($ruleSet->destinationField->type == 'string') {
                $fields[$ruleSet->destinationField->key] = str_replace($destinationValue, '',$fields[$ruleSet->destinationField->key]);
            }

        }
        return $fields;
    }

}
