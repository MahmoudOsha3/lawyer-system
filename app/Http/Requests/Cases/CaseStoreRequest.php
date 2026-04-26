<?php

namespace App\Http\Requests\Cases;

use Illuminate\Foundation\Http\FormRequest;

class CaseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true ;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'case_number' => 'required|string|max:50',
            'case_type' => 'required|string',
            'court' => 'required|string|max:255',
            'opponent' => 'required|string|max:255',
            'start_date' => 'required|date',
            'next_session' => 'required|date',
            'case_price' => 'required|decimal:0,10|min:0',
            'description' => 'required|string|max:500' ,
            'status' => 'required|string|in:pending,open,closed'
        ];
    }
}
