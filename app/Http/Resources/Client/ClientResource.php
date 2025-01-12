<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\User\AllUserDataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = (new AllUserDataResource($this->user))->toArray($request);

        return array_merge([
            'clientId' => $this->id,
            'name' => $this->name,
            'description' => $this->description ?? '',
            'dateOfBirth' => $this->date_of_birth ?? '',
            'gender' => $this->gender,
        ], $user);
    }
}
