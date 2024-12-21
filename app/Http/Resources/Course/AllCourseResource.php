<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AllCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'courseId' => $this->id,
            'startAt' => $this->start_at,
            'endAt' => $this->end_at,
            'price' => $this->price,
            'isActive' => $this->is_active,
            'trainerName' => $this->trainer?->user?->name,
            'name' => $this->name
        ];
    }
}
