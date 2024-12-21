<?php

namespace App\Models\Parameter;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory, CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'order'
    ];
}
