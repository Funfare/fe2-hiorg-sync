<?php

namespace App\Compares;

class ValueListInArray
{
    public function valid($source, $value) {
        if(!is_array($source) && !$source instanceof \ArrayAccess) {
            $source = [$source];
        }
        $valueList = explode(';', $value);

        foreach($valueList as $item) {
            if(in_array($item, $source)) {
                return true;
            }
        }
        return false;
    }
}
