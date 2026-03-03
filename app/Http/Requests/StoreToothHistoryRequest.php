<?php

namespace App\Http\Requests;

use App\Models\ToothHistory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreToothHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'procedure_type' => ['required', 'string', Rule::in(array_keys(ToothHistory::PROCEDURE_TYPES))],
            'status' => ['required', 'string', Rule::in(array_keys(ToothHistory::STATUSES))],
            'surface' => ['nullable', 'string', Rule::in(array_keys(ToothHistory::SURFACES))],
            'detailed_notes' => ['nullable', 'string', 'max:5000'],
            'dentist_id' => ['nullable', 'exists:dentists,id'],
            'date_of_procedure' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
