<?php

namespace App\Http\Resources\Client;
use App\Http\Resources\User\UserResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AllClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'trainerId' => $this->id,
            'name' => $this->user->name,
            'phone' => $this->user,
            'gender' => $this->gender,
            'sportCategoryNames' => $this->sportCategories->pluck('name')->toArray(),
        ];
    }
}
