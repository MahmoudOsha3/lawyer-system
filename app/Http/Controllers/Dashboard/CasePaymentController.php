<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CasePayment\CasePaymentStoreRequest;
use App\Http\Requests\CasePayment\CasePaymentUpdateRequest;
use App\Models\CasePayment;
use App\Models\Cases;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CasePaymentController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(CasePaymentStoreRequest $request)
    {
        $this->checkAvaliablity($request) ;
        $payment  = CasePayment::create($request->validated());
        return redirect()->back()->with('success' , 'تم إيدع المبلغ بنجاح الي حسابك') ;
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(CasePaymentUpdateRequest $request, CasePayment $casePayment)
    {
        $this->checkAvaliablity($request) ;
        $casePayment->update($request->validated());
        return back()->with('success' , 'تم تعديل بيانات الدفع بنجاح') ;
    }

    public function destroy(CasePayment $casePayment)
    {
        $casePayment->delete() ;
        return back()->with('success' , 'تم الحذف بيانات الدفع بنجاح') ;
    }

    public function checkAvaliablity($request)
    {
        $amount_payment = CasePayment::where('case_id' , $request->case_id)
                            ->sum('amount');
        $case_price = Cases::where('id' , $request->case_id)->value('case_price');

        $amount_after_this_request = $amount_payment + $request->amount ;
        if ($amount_after_this_request > $case_price) {
            throw ValidationException::withMessages([
                'amount' => 'لا يمكن إيداع مبلغ أكثر من المتفق عليه'
            ]);
        }
    }
}
