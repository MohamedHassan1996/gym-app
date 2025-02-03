<?php

namespace App\Models\Client;

use App\Models\Course\Course;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'tenant';
    protected $fillable = [
        'client_id',
        'course_id',
        'start_date',
        'status',
    ];

    protected $cast = [
        'status' => 'integer'
    ];

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($model) {
            $model->subscriptions()->delete();
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(ClientCourseSubscription::class, 'client_course_id', 'id');
    }
}
