<?php

namespace App\Services\Select;

use App\Models\Client\Client;
use App\Models\Trainer\Trainer;
use App\Models\User;
use App\Traits\SwitchDbConnection;

class TrainerSelectService
{
    use SwitchDbConnection;

    public function getAllTrainers()
    {
        $this->switchDatabase();

        $trainers = Trainer::select('id', 'user_id')->get();

        $userIds = $trainers->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userIds)
            ->select('id', 'name')
            ->get()
            ->keyBy('id');

        // Map the data
        $result = $trainers->map(function ($trainer) use ($users) {
            return [
                'value' => $trainer->id,
                'label' => $users[$trainer->user_id]->name ?? null,
            ];
        });

        return $result;

    }
}
