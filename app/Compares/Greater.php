<?php

namespace App\Compares;

class Greater
{
    public function valid($source, $value) {
        return $source > $value;
    }
}
