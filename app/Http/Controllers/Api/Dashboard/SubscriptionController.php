<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        /*$validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'gym_name' => 'required|string',
        ]);

        $user = auth()->user(); // Admin user
        $plan = Plan::findOrFail($validated['plan_id']);

        // Create tenant database name
        $databaseName = 'gym_' . $user->id;

        // Create tenant database
        DB::statement("CREATE DATABASE $databaseName");

        // Create tenant record
        $tenant = Tenant::create([
            'id' => $user->id,
            'name' => $validated['gym_name'],
            'database' => $databaseName,
        ]);

        // Create subscription record
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'tenant_database' => $databaseName,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);*/

        $user = auth()->user(); // Admin user
        $databaseName = 'gym_' . $user->id;

        Config::set('database.connections.tenant.database', $databaseName);

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations',
            '--force' => true,
        ]);

        return response()->json(['message' => 'Subscription successful.']);
    }
}
