<?php

namespace App\Http\Requests\CasePayment;

use Illuminate\Foundation\Http\FormRequest;

class CasePaymentStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true ;
    }

    public function rules(): array
    {
        return [
            'case_id' => 'required|exists:cases,id' ,
            'amount' => 'required|integer|min:1',
            'payment_date' => 'required|date' ,
            'notes' => 'nullable|string|max:500'
        ];
    }

    public function messages()
    {
        return [
            'case_id.required' => 'القضية غير صالحة ',
            'amount.required' => 'المبلغ مطلوب إدخالة لإكمال العملية بنجاح',
            'amount.min' => 'يجب أن يكون المبلغ صحيح',
            'payment_date.required' => 'يجب إدخال تاريخ الدفع',
            'payment_date.date' => 'يجب أن يكون التاريخ صحيح',
            'notes.max' => 'الملاحظات يجب أن تكون اقل من 500 حرق',
        ] ;
    }
}
