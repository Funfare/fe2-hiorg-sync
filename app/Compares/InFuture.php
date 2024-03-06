<?php

namespace App\Compares;

use Carbon\Carbon;

class InFuture
{
    public function valid($source, $value) {
        try {
            $date = Carbon::parse($source);
        }
        catch (\Exception $e) {
            return false;
        }
        return $date > now();
    }
}
