<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Gym Owner',
            'email' => 'admin@admin.com',
            'password' => 'M@Ns123456', // Hash the password
            'role' => 1,
            'tenant_id' => null, // Assume tenant with ID 1 exists
        ]);

        $role = Role::findByName(name: 'superAdmin');

        $user->assignRole($role);

        $user2 = User::create([
            'name' => 'Client',
            'email' => 'client@client.com',
            'password' => 'M@Ns123456', // Hash the password
            'role' => 0,
            'tenant_id' => null, // Assume tenant with ID 1 exists
        ]);


    }
}
