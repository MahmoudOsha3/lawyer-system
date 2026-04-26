<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CaseExpense;
use Illuminate\Http\Request;

class CaseExpenseController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id' ,
            'title' => 'required|string|max:255' ,
            'amount' => 'required|integer|min:1',
            'expense_date' => 'required|date' ,
            'notes' => 'nullable|string|max:500'
        ]) ;
        $expense  = CaseExpense::create($validated) ;
        return redirect()->back()->with('success' , 'تم إدخال المصاريف بنجاح') ;
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, CaseExpense $caseExpense)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|integer|min:1',
            'expense_date' => 'required|date',
            'notes'        => 'nullable|string|max:500',
            'status'       => 'nullable|in:pending,paid',
        ]);
        if($caseExpense->status == 'paid'){
            return back()->with('error' , 'لايمكن تعديل المصروف بعد تأكيد دفعه') ;
        }
        $caseExpense->update($validated);
        return back()->with('success', 'تم تعديل المصروف بنجاح');
    }

    public function destroy(CaseExpense $caseExpense)
    {
        if($caseExpense->status == 'paid'){
            return back()->with('error' , 'لايمكن تعديل المصروف بعد تأكيد دفعه') ;
        }

        $caseExpense->delete() ;
        return back()->with('success' , 'تم حذف المصاريف بنجاح ') ;
    }

    public function payExpense(Request $request , CaseExpense $caseExpense)
    {
        $request->validate(['status' => 'required|in:pending,paid']) ;
        $expense = CaseExpense::findorfail($request->expense_id);
        $expense->update([
            'status' => $request->status
        ]) ;
        return back()->with('success', 'تم تحديث حالة دفع المصروف بنجاح');    }
}
