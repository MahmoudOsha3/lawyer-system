<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true ;
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|digits:11',
            'phone2' => 'nullable|digits:11',
            'email' => 'nullable|email|unique:clients,email',
            'national_id' => 'nullable|digits:14',
            'passport_number' => 'nullable|digits:14',
            'job' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'address'=> 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'type' => 'nullable|in:person,company',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الموكل مطلوب.',
            'phone.digits' => 'رقم الهاتف يجب أن يكون 11 رقم.',
            'national_id.digits' => 'الرقم القومي يجب أن يكون 14 رقم.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقاً.',
        ];
    }
}
