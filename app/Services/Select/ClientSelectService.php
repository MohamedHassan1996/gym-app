<?php

namespace App\Services\Select;

use App\Models\Client\Client;
use App\Models\User;
use App\Traits\SwitchDbConnection;

class ClientSelectService
{
    use SwitchDbConnection;

    public function getAllClients()
    {
        $this->switchDatabase();

        $clients = Client::select('id', 'user_id')->get();

        $userIds = $clients->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userIds)
            ->select('id', 'name')
            ->get()
            ->keyBy('id');

        // Map the data
        $result = $clients->map(function ($client) use ($users) {
            return [
                'value' => $client->id,
                'label' => $users[$client->user_id]->name ?? null,
            ];
        });

        return $result;

    }
}
