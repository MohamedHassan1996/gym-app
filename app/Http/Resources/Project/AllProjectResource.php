<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'projectId' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'progress' => 70,
            'duration' => "10:00"
        ];
    }
}
