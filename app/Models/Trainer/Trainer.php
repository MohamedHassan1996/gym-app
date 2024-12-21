<?php

namespace App\Models\Trainer;

use App\Models\Sport\SportCategory;
use App\Models\User;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trainer extends Model
{
    use HasFactory;
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
    public function sportCategories():BelongsToMany
    {
        return $this->belongsToMany(SportCategory::class, 'trainer_sport_categories', 'trainer_id', 'sport_category_id');
    }
}
