<?php

namespace App\Models\Client;

use App\Models\User;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

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
