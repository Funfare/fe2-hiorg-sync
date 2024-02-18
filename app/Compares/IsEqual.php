<?php

namespace App\Compares;

class IsEqual
{
    public function valid($source, $value) {
        return $source == $value;
    }
}
