<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddressRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_main' => !$this->user()->addresses()->count(),
        ]);
    }

    public function rules(): array
    {
        return [
            'country_id' => ['required', Rule::exists('countries', 'id')],
            'state_id' => ['required', Rule::exists('states', 'id')],
            'city_id' => ['nullable', Rule::exists('cities', 'id')],
            'street' => ['required', 'max:255'],
            'house' => ['required', 'max:10'],
            'flat' => ['nullable', 'max:10'],
            'postal_code' => ['nullable', 'max:10'],
            'is_main' => ['required', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return boolval($this->user());
    }
}
