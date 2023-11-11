<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call(RoleSeeder::Class);
    }
}
