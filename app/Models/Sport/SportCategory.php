<?php

namespace App\Models\Sport;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportCategory extends Model
{
    use HasFactory, CreatedUpdatedBy;

    protected $connection = 'tenant';
    protected $fillable = [
        'name',
        'description',
    ];

}
