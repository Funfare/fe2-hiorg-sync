<?php

namespace App\Compares;

class IsEmpty
{
    public function valid($source, $value) {
        return empty(trim($source));
    }
}
