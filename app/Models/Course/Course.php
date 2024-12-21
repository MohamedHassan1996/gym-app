<?php

namespace App\Models\Course;

use App\Enums\Course\CourseStatus;
use App\Models\Sport\SportCategory;
use App\Models\Trainer\Trainer;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'start_at',
        'end_at',
        'description',
        'classes',
        'price',
        'is_active',
        'trainer_id',
        'sport_category_id',
    ];

    protected $casts = [
        'classes' => 'array',
        'is_active' => CourseStatus::class
    ];

    public function sportCategory()
    {
        return $this->belongsTo(SportCategory::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
