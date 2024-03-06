<?php

namespace App\Compares;

class GreaterEquals
{
    public function valid($source, $value) {
        return $source >= $value;
    }
}
