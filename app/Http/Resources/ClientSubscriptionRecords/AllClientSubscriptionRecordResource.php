<?php

namespace App\Http\Resources\ClientSubscriptionRecords;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AllClientSubscriptionRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'clientCourseId' => $this->id,
            'subscriptionDate' => Carbon::parse($this->subscription_date)->format('d/m/Y'),
            'courseName' => $this->course->name,
            'status' => (int)$this->status,
            'numberOfMonths' => $this->subscriptions[0]->number_of_months,
            'price' => $this->subscriptions[0]->price,
        ];

    }

    // public function getDaysLeftForNextSubscription()
    // {
    //     $registrationDate = Carbon::parse($this->start_date); // The date the user registered
    //     $latestSubscription = $this->subscriptions->first(); // Assuming subscriptions are sorted by the latest first

    //     if (!$latestSubscription) {
    //         return 'No subscription found.';
    //     }

    //     $subscriptionStart = Carbon::parse($latestSubscription->subscription_date);
    //     $subscriptionEnd = Carbon::parse($latestSubscription->end_at);
    //     $currentDate = Carbon::now(); // Today's date

    //     // Determine the actual start date for calculation
    //     $actualStartDate = $registrationDate->greaterThan($subscriptionStart) || $registrationDate->lessThan($subscriptionStart)
    //         ? $registrationDate
    //         : $subscriptionStart;

    //     // Ensure we're counting from "now" or the actual start date, whichever is later
    //     $effectiveDate = $currentDate->greaterThan($actualStartDate)
    //         ? $currentDate
    //         : $actualStartDate;

    //     // Calculate the days left from the effective date to the subscription end date
    //     $leftDays = $effectiveDate->diffInDays($subscriptionEnd, false); // Use `false` for signed difference

    //     if ($leftDays < 0) {
    //         return $leftDays;
    //     }

    //     return $leftDays;
    // }



}
