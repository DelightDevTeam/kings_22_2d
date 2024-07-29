<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'from_user_id' => $this->fromUser->name,
            'to_user_id' => $this->toUser->name,
            'type' => $this->type,
            'amount' => $this->amount,
            'datetime' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
