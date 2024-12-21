<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CourseResource extends JsonResource
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
            'description' => $this->description??'',
            'classes' => $this->classes??[],
            'price' => $this->price,
            'isActive' => $this->is_active,
            'trainerId' => $this->trainer_id,
            'sportCategoryId' => $this->sport_category_id
        ];
    }
}
