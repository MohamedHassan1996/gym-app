<?php

namespace App\Models\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'description',
        'date_of_birth',
        'gender',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
