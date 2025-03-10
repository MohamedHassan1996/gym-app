<?php

namespace App\Models\Client;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientCourseSubscription extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'tenant';

    protected $fillable = [
        'client_course_id',
        'subscription_date',
        'end_at',
        'number_of_months',
        'price'
    ];

    protected $casts = [
        'price' => 'float'
    ];

    public function course()
    {
        return $this->belongsTo(ClientCourse::class, 'client_course_id', 'id');
    }


}
