<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userId' => $this->id,
            'name' => $this->name?$this->name:"",
            'email' => $this->email?$this->email:"",
            'status' => $this->status,
            'role' => $this->role,
            'avatar' => $this->avatar?Storage::disk('public')->url($this->avatar):"",
        ];
    }
}
