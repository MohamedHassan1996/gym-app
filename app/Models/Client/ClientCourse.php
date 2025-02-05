<?php

namespace App\Models\Client;

use App\Models\Course\Course;
use App\Traits\CreatedUpdatedBy;
use Carbon\Carbon;
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

    protected $casts = [
        'status' => 'integer'
    ];

    /*public static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            ClientCourseSubscription::where('client_course_id', $model->id)->delete();
        });
    }*/

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

    public function getDaysLeftForNextSubscription()
    {
        $registrationDate = Carbon::parse($this->start_date); // The date the user registered
        $latestSubscription = $this->subscriptions->first(); // Assuming subscriptions are sorted by the latest first

        if (!$latestSubscription) {
            return 'No subscription found.';
        }

        $subscriptionStart = Carbon::parse($latestSubscription->subscription_date);
        $subscriptionEnd = Carbon::parse($latestSubscription->end_at);
        $currentDate = Carbon::now(); // Today's date


        // Determine the actual start date for calculation
        $actualStartDate = $registrationDate->greaterThan($subscriptionStart) || $registrationDate->lessThan($subscriptionStart)
            ? $registrationDate
            : $subscriptionStart;



        // Ensure we're counting from "now" or the actual start date, whichever is later
        $effectiveDate = $currentDate->greaterThan($actualStartDate)
            ? $currentDate
            : $actualStartDate;

        // Calculate the days left from the effective date to the subscription end date
        $leftDays = $effectiveDate->diffInDays($subscriptionEnd, false); // Use `false` for signed difference


        return $leftDays;
    }
}
