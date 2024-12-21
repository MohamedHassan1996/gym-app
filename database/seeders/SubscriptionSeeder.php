<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        $subscription = Subscription::create([
            'plan_id' => 1,
            'user_id' => 1,
            'tenant_database' => 'gym_1',
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
        ]);
    }
}
