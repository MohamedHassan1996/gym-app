<?php

namespace App\Models\Trainer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSportCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'sport_category_id',
    ];
}
