<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $emailUnique = 'unique:clients,email';
        if ($this->route('client')) {
            $emailUnique .= ',' . $this->route('client')->id;
        }

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', $emailUnique],
            'address' => ['nullable', 'string', 'max:1000'],
            'medical_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
