<?php

namespace App\Helpers\Sync;

use App\Models\Organization;

class Factory
{
    public static function make(Organization $org): Contract
    {
        return new (class_exists('App\\Helpers\\Sync\\' . ucfirst($org->key)) ? 'App\\Helpers\\Sync\\' . ucfirst($org->key) : Generic::class);

    }
}
