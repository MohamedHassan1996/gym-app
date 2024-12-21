<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run()
    {
        // Seed plans with features
        $plans = [
            [
                'name' => 'Basic Plan',
                'price' => 10.00,
                'features' => [
                    'dashboard' => [
                        ['featureName' => 'home', 'access' => true],
                        ['featureName' => 'clients', 'access' => true],
                        ['featureName' => 'users', 'access' => true],
                        ['featureName' => 'sport_categories', 'access' => true],
                        ['featureName' => 'trainers', 'access' => true],
                    ],
                    'client' => [
                        ['featureName' => 'home', 'access' => true],
                    ]
                ],
            ],
            [
                'name' => 'Standard Plan',
                'price' => 20.00,
                'features' => [
                    ['featureName' => 'clients'],
                    ['featureName' => 'priority support'],
                    ['featureName' => 'custom reports'],
                ],
            ],
            [
                'name' => 'Premium Plan',
                'price' => 50.00,
                'features' => [
                    ['featureName' => 'clients'],
                    ['featureName' => 'priority support'],
                    ['featureName' => 'custom reports'],
                    ['featureName' => 'unlimited access'],
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
