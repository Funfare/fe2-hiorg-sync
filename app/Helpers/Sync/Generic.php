<?php

namespace App\Helpers\Sync;

class Generic
{

    public function isValid($record) {
        return true;
    }
    public function getPhoneNumber($record) {
        if(!empty($record['handy'])) {
            $phone =  $record['handy'];
        } elseif(!empty($record['telpriv'])) {
            $phone =  $record['telpriv'];
        } else {
            $phone =  $record['teldienst'];
        }
        if(empty($phone)) {
            return '';
        }
        if(str_starts_with($phone, '+')) {
            $phone = '00'.substr($phone, 1);
        }
        if(!str_starts_with($phone, '00')) {
            $phone = '0049'.substr($phone, 1);
        }
        $phone = str_replace([' ', '/', '-', '+'], '', $phone);
        return $phone;
    }

    function getGroups($record) {

        return $record['gruppen_namen'];
    }

    function getFunctions($record) {
        $qualis = (new \Illuminate\Support\Collection($record['qualifikationen']))->pluck('name_kurz', 'liste');
        $med = $qualis->get('Medizinische Qualifikation');
        $tactic = $qualis->get('Führungsqualifikation');
        $return = [$med];
        if(!in_array($tactic, ['H', 'G1'])) {
            $return[] = $tactic;
        };
        return array_unique($return);
    }
}
