<?php

namespace App\Models\Course;

use App\Enums\Course\CourseStatus;
use App\Models\Client\Client;
use App\Models\Client\ClientCourse;
use App\Models\Sport\SportCategory;
use App\Models\Trainer\Trainer;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'start_at',
        'end_at',
        'description',
        'classes',
        'price',
        'is_active',
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

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'course_trainers', 'course_id', 'trainer_id');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_courses');
    }

    public function getActiveSubscriptionsAttribute()
    {
        return $this->clients()->count();
    }
}
