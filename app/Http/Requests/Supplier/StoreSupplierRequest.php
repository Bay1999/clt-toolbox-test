<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('suppliers')],
            'email' => ['required', 'email', 'max:100', Rule::unique('suppliers')],
            'location' => ['required', 'string', 'max:255'],
            'material_certification' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required.',
            'name.unique' => 'Supplier name is already exists.',
            'email.required' => 'Supplier email is required.',
            'email.email' => 'Supplier email is not valid.',
            'location.required' => 'Supplier location is required.',
            'material_certification.required' => 'Material certification is required.',
        ];
    }
}
