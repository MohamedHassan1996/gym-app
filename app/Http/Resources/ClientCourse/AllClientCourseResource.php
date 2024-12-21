<?php

namespace App\Http\Resources\ClientCourse;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AllClientCourseResource extends JsonResource
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
            'registrationDate' => $this->start_date,
            //'lastSubscribedDate' => $this->subscriptions->first()->subscription_date,
            'leftDaysForNextSubscription' => $this->getDaysLeftForNextSubscription(),
            'courseName' => $this->course->sportCategory->name,
        ];

    }

    public function getDaysLeftForNextSubscription()
    {
        // Parse the first registration date as the base day
        $firstRegistrationDate = Carbon::parse($this->start_date);

        // Ensure subscriptions are sorted by subscription_date in descending order
        $latestSubscription = $this->subscriptions->first();
        $lastSubscriptionDate = $latestSubscription
            ? Carbon::parse($latestSubscription->subscription_date)
            : $firstRegistrationDate;

        // Calculate the next subscription date
        $nextSubscriptionDate = $lastSubscriptionDate->copy()->addMonth();

                // Align the next subscription date to the original registration day
                $nextSubscriptionDate->day = $firstRegistrationDate->day;


        if($nextSubscriptionDate->isPast()) {
            return - $lastSubscriptionDate->diffInDays(Carbon::now()->startOfDay(), false);
        }

        // Handle cases where the adjusted day is invalid (e.g., February 30)
        if ($nextSubscriptionDate->day !== $firstRegistrationDate->day) {
            $nextSubscriptionDate->day = $nextSubscriptionDate->daysInMonth;
        }

        // Ensure the next subscription date is in the future
        while ($nextSubscriptionDate->isPast()) {
            $nextSubscriptionDate = $nextSubscriptionDate->addMonth();
            $nextSubscriptionDate->day = $firstRegistrationDate->day;

            if ($nextSubscriptionDate->day !== $firstRegistrationDate->day) {
                $nextSubscriptionDate->day = $nextSubscriptionDate->daysInMonth;
            }
        }

        // Calculate the number of days left
        $daysLeft = Carbon::now()->startOfDay()->diffInDays($nextSubscriptionDate->startOfDay(), false);

        // Return the number of days left (negative for overdue, positive for future)
        return $daysLeft;
    }



}
