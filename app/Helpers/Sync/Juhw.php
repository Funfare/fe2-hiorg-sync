<?php

namespace App\Helpers\Sync;

class Juhw extends Generic implements Contract
{

    public function isValid($record)
    {
        $attributes = collect($record['attributes']);
        if(!in_array('Handyalarmierung', $attributes['gruppen_namen'])) {
           return false;
        }
        return true;
    }
    public function getDataFromRecord($record)
    {
        $attributes = collect($record['attributes']);
        $groups = $this->getGroups($attributes);
        $email = collect($attributes['benutzerdefinierte_felder'])->where('name', 'aPager E-Mail')->first()['value'];
        if(!app()->environment('production')) {
            $email = 'fe2-test-'.$record['id'].'@re-gister.com';
        }
        return [
            "externalDbId" => $record['id'],
            "firstName" => $attributes['vorname'],
            "lastName" => $attributes['nachname'],
            "note" => $attributes['bemerkung'],
            "osFunctions" => $this->getFunctions($attributes),
            "osGroups" => $groups,
            "alarmGroups" => $groups,
            "issi" => "",
            "xmpp" => "",
            "aPagerPro" => !empty($email) ? $email : $attributes['email'],
            "email" => $attributes['email'],
            "mobil" => $this->getPhoneNumber($attributes),
            "aPagerProFieldMode" => "LEGACY"
        ];

    }


    public function getGroups($record) {
        $return = ['SEG Behandlung'];
        $groups = $record['gruppen_namen'];
        $license = $record['fahrerlaubnis']['klassen'] ?? [];
        $qualis = (new \Illuminate\Support\Collection($record['qualifikationen']))->pluck('name_kurz', 'liste');
        $med = $qualis->get('Medizinische Qualifikation');
        $tactic = $qualis->get('Führungsqualifikation');
        $tactic = in_array($tactic, ['H', 'G1']) ? null : $tactic;
        if(in_array($med, [
                'RS',
                'RAiP',
                'RettA',
                'LRA',
                'NotSan',
                'Arzt',
                'NA',
            ])
            || in_array('Dienst-Kfz mit Sondersignal', $license)) {
            $return[] = 'SEG Transport';
        }
        if(in_array($tactic, [
            'GF',
            'ZF',
            'VF',
            'ELRD',
            'ORGL',
        ])) {
            $return[] = 'Führung';
        }
        if(in_array('SEG IuK', $groups)) {
            $return[] = 'SEG IuK';
        }
        if(in_array('FLIGHT', $groups)) {
            $return[] = 'FLIGHT';
        }
        if(in_array('Spitzenabdeckung', $groups)) {
            $return[] = 'Spitzenabdeckung';
        }
        if(in_array('B-Dienst', $groups)) {
            $return[] = 'B-Dienst';
        }
        if(in_array('Motorradstaffel', $groups)) {
            $return[] = 'Motorradstaffel';
        }
        if(in_array('Fachberater Drohne', $groups)) {
            $return[] = 'FB Drohne';
        }
        return $return;
    }

    public function getFunctions($record) {
        $qualis = (new \Illuminate\Support\Collection($record['qualifikationen']))->pluck('name_kurz', 'liste');
        $med = $qualis->get('Medizinische Qualifikation');
        $tactic = $qualis->get('Führungsqualifikation');
        $return = [$med];
        if(!in_array($tactic, ['H', 'HGA'])) {
            $return[] = $tactic;
        };

        $groups = $record['gruppen_namen'];
        if(in_array('SEG IuK', $groups)) {
            $return[] = 'IuK';
        }
        if(in_array('FLIGHT', $groups)) {
            $return[] = 'FLIGHT';
        }
        if(in_array('Motorradstaffel', $groups)) {
            $return[] = 'Motorradstaffel';
        }
        return array_values(array_unique(array_filter($return)));
    }
}
