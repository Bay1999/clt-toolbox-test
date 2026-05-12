<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('suppliers')->ignore($this->route('supplier')->id ?? $this->route('supplier')),
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('suppliers')->ignore($this->route('supplier')->id ?? $this->route('supplier')),
            ],
            'location' => ['required', 'string', 'max:255'],
            'material_certification' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required.',
            'name.unique' => 'Supplier name already exists.',
            'email.required' => 'Supplier email is required.',
            'email.email' => 'Supplier email is not valid.',
            'email.unique' => 'Supplier email already exists.',
            'location.required' => 'Supplier location is required.',
            'material_certification.required' => 'Material certification is required.',
        ];
    }
}
