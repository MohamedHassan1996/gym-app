<?php

namespace App\Models\Parameter;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterValue extends Model
{
    use HasFactory, CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'description',
        'parameter_id'
    ];
}
