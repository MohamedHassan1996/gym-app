<?php

namespace App\Http\Resources\Client;
use App\Http\Resources\User\UserResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'clientId' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'status' => $this->user->status,
            'avatar' => $this->user->avatar?Storage::disk('public')->url($this->user->avatar):"",
            'gender' => $this->gender,
        ];
    }
}
