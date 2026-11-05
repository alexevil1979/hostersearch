<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            FeatureAndTypeSeeder::class,
            CompanySeeder::class,
            ComparisonSeeder::class,
            AdvisorSeeder::class,
            PageSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
