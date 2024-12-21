<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::create([
            'name' => 'Mo gym',
            'database' => 'gym_1',
        ]);
    }
}
