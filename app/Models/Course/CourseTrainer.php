<?php

namespace App\Models\Course;

use App\Models\Trainer\Trainer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTrainer extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = [
        'course_id',
        'trainer_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'id');
    }
}
