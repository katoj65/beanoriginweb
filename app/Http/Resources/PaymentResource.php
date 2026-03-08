<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'shopping_cart_session' => $this->shopping_cart_session,
            'transaction_reference' => $this->transaction_reference,
            'number_of_items' => $this->number_of_items,
            'quanitity' => $this->quanitity,
            'total_amount' => $this->total_amount,
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user?->id,
                    'name' => trim(($this->user?->fname ?? '') . ' ' . ($this->user?->lname ?? '')),
                    'email' => $this->user?->email,
                ];
            }),
        ];
    }
}
