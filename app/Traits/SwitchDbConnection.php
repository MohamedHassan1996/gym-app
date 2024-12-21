<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

trait SwitchDbConnection
{
    public function switchDatabase()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        Config::set('database.connections.tenant.database', $tenant->database);

    }
}
