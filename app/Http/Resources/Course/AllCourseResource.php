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
            'name' => $this->name,
            'classes' => $this->classes,
            'isActive' => $this->is_active,
            'totalSubscribers' => $this->activeSubscriptions??0,
        ];
    }
}
