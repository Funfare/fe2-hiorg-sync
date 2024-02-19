<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \DB::unprepared(file_get_contents(__DIR__.'/rule_sets.sql'));
        \DB::unprepared(file_get_contents(__DIR__.'/rules.sql'));
    }
}
