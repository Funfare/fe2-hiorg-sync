<?php

namespace App\Compares;

class InArray
{
    public function valid($source, $value) {
        if(!is_array($source) && !$source instanceof \ArrayAccess) {
            return \Str::contains($source, $value);
        }

        return in_array($value, $source);
    }
}
