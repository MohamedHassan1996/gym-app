<?php

namespace App\Http\Resources\SportCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SportCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sportCategoryId' => $this->id,
            'name' => $this->name,
            'description' => $this->description??'',
        ];
    }
}
