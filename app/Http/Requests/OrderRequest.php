<?php

namespace App\Http\Requests;

use App\Enums\OrderDelivery;
use App\Enums\OrderPayment;
use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        /** @var User $user */
        $user = $this->user();

        $this->merge([
            'uuid' => Str::uuid()->toString(),
            'user_id' => $user->id,
            'user_address_id' => $user->addresses()->firstWhere('is_main', true)->id,
            'status' => OrderStatus::UNPAID,
        ]);
    }

    public function rules(): array
    {
        return [
            'uuid' => ['required', 'uuid'],
            'user_id' => ['required', Rule::exists('users', 'id')],
            'user_address_id' => ['required', Rule::exists('user_addresses', 'id')],
            'promo_code_id' => ['nullable', Rule::exists('promo_codes', 'id')],
            'delivery_method' => ['required', Rule::in(OrderDelivery::getValues())],
            'payment_method' => ['required', Rule::in(OrderPayment::getValues())],
            'status' => ['required', Rule::in(OrderStatus::getValues())],
            'goods_cost' => ['required', 'numeric'],
            'delivery_cost' => ['nullable', 'numeric'],
            'total_cost' => ['required', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.good_id' => ['required', Rule::exists('goods', 'id')],
            'items.*.quantity' => ['required', 'integer', 'gte:1'],
        ];
    }

    public function authorize(): bool
    {
        return boolval($this->user());
    }
}
