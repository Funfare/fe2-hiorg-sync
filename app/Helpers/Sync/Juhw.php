<?php

namespace App\Helpers\Sync;

class Juhw extends Generic
{

    public function getGroups(\Illuminate\Support\Collection $record) {
        $return = ['SEG Behandlung'];
        $groups = $record['gruppen_namen'];
        $license = $record['fahrerlaubnis']['klassen'];
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
            || in_array('Z2', $license)) {
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
        return $return;
    }

    public function getFunctions($record) {
        $qualis = (new \Illuminate\Support\Collection($record['qualifikationen']))->pluck('name_kurz', 'liste');
        $med = $qualis->get('Medizinische Qualifikation');
        $tactic = $qualis->get('Führungsqualifikation');
        $return = [$med];
        if(!in_array($tactic, ['H', 'G1'])) {
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
        return array_unique($return);
    }
}
