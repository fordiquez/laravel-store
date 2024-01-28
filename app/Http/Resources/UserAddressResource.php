<?php

namespace App\Http\Resources;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin UserAddress */
class UserAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_main' => $this->is_main,
            'street' => $this->street,
            'house' => $this->house,
            'flat' => $this->flat,
            'postal_code' => $this->postal_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'country' => [
                'id' => $this->country->id,
                'name' => $this->country->name,
                'iso2' => $this->country->iso2,
            ],
            'state' => [
                'id' => $this->state->id,
                'uuid' => $this->state->uuid,
                'name' => $this->state->name,
            ],
            'city' => [
                'id' => $this->city->id,
                'uuid' => $this->city->uuid,
                'name' => $this->city->name,
            ],
        ];
    }
}
