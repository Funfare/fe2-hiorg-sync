<?php

namespace App\Compares;

use Carbon\Carbon;

class IsOlder
{
    public function valid($source, $value) {
        try {
            $date = Carbon::parse($source);
        }
        catch (\Exception $e) {
            return false;
        }
        return $date->diff(today())->y > $value;
    }
}
