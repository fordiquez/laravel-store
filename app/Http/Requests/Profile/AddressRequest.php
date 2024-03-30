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
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')],
            'state_id' => ['nullable', 'integer', Rule::exists('states', 'id')],
            'city_id' => ['nullable', 'integer', Rule::exists('cities', 'id')],
            'street' => ['required', 'string', 'max:255'],
            'house' => ['required', 'string', 'max:10'],
            'flat' => ['nullable', 'string', 'max:10'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'is_main' => ['required', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return boolval($this->user());
    }
}
