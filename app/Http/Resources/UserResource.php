<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'main_balance' => $this->main_balance,
            'limit' => $this->limit,
            'limit3' => $this->limit3,
            'cor' => $this->cor,
            'cor3' => $this->cor3,
            'status' => $this->status,
        ];

        return [
            'user' => $user,
            'token' => $this->createToken($this->user_name)->plainTextToken,
        ];
    }
}
