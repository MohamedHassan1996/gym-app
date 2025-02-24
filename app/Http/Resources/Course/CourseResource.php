<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $trainers = $this->trainers->map(function ($trainer) {
            return [
                'trainerId' => $trainer->id,
                'name' => $trainer->user?->name??"",
                'avatar' => $trainer->user?->avatar ? Storage::disk('public')->url($trainer->user->avatar) :""
            ];
        });

        $clients = [];

        if($this->clients) {
            $clients = $this->clients->map(function ($client) {
                return [
                    'clientId' => $client->id,
                    'name' => $client->user?->name??"",
                    'avatar' => $client->user?->avatar ? Storage::disk('public')->url($client->user->avatar) :""
                ];
            });
        }

        return [
            'courseId' => $this->id,
            'name' => $this->name,
            'startAt' => $this->start_at,
            'endAt' => $this->end_at??"",
            'description' => $this->description??'',
            'classes' => $this->classes??[],
            'price' => (string)$this->price,
            'isActive' => $this->is_active,
            'totalSubscribers' => $this->activeSubscriptions??0,
            'trainers' => $trainers,
            // 'sportCategory' => [
            //     'sportCategoryId' => $this->sport_category_id,
            //     'name' => $this->sportCategory?->name??"",
            // ],
            'clients' => $clients,
            'beforeAlertDay' => $this->before_alert_day
        ];
    }
}
