<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Organization;
use App\Models\Tab;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
Organization::forceCreate([
    'key' => 'juhw',
    'name' => 'JUH Wü'
]);

        Tab::forceCreate([
            'organization_id' => 1,
            'name' => 'Stammdaten'
        ]);
        Tab::forceCreate([
            'organization_id' => 1,
            'name' => 'OS Funktionen'
        ]);
        Tab::forceCreate([
            'organization_id' => 1,
            'name' => 'OS Gruppen'
        ]);
        Tab::forceCreate([
            'organization_id' => 1,
            'name' => 'Alarmgruppen'
        ]);
        Tab::forceCreate([
            'organization_id' => 1,
            'name' => 'Provisionierung'
        ]);
        \DB::unprepared(file_get_contents(__DIR__.'/rule_sets.sql'));
        \DB::unprepared(file_get_contents(__DIR__.'/rules.sql'));
    }
}
