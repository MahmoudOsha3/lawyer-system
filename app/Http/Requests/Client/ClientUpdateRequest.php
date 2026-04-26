<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true ;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|digits:11',
            'phone2' => 'nullable|digits:11',
            'email' => ['nullable' , 'email' , Rule::unique('clients' , 'email')->ignore($this->input('id'))] ,
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
            'name.sometimes' => 'الاسم مطلوب إدخاله',
            'name.string' => 'يجب أن يكون الاسم نص صحيح',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرف',
            'phone.digits' => 'رقم الهاتف يجب أن يكون 11 رقم',
            'phone2.digits' => 'رقم الهاتف الثاني يجب أن يكون 11 رقم',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'national_id.digits' => 'الرقم القومي يجب أن يكون 14 رقم',
            'passport_number.digits' => 'رقم جواز السفر يجب أن يكون 14 رقم',
            'job.string' => 'الوظيفة يجب أن تكون نص',
            'job.max' => 'الوظيفة يجب ألا تتجاوز 255 حرف',
            'company.string' => 'جهة العمل يجب أن تكون نص',
            'company.max' => 'جهة العمل يجب ألا تتجاوز 255 حرف',
            'address.string' => 'العنوان يجب أن يكون نص',
            'address.max' => 'العنوان يجب ألا يتجاوز 255 حرف',
            'notes.string' => 'الملاحظات يجب أن تكون نص',
            'notes.max' => 'الملاحظات يجب ألا تتجاوز 500 حرف',
            'type.in' => 'نوع الموكل غير صحيح',
        ];
    }
}
