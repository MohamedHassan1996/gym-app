<?php

namespace App\Http\Resources\Trainer;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class TrainerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = (new UserResource($this->user))->toArray($request);

        return array_merge([
            'trainerId' => $this->id,
            'name' => $this->name,
            'description' => $this->description ?? '',
            'dateOfBirth' => $this->date_of_birth ?? '',
            'gender' => $this->gender,
            'sportCategoryIds' => $this->sportCategory->pluck('pivot.sport_category_id')->toArray(),
        ], $user);
    }
}
