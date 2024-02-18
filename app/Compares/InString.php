<?php

namespace App\Compares;

class InString
{
    public function valid($source, $value) {
        return \Str::contains($source, $value);
    }
}
