<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CasePayment;
use App\Models\CaseExpense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $tab  = $request->get('tab', 'payments'); // payments | expenses | office
        $from = $request->get('from');
        $to   = $request->get('to');

        $stats = [
            'total_payments'  => CasePayment::sum('amount'),
            'pending_fees'    => \App\Models\Cases::get()
                ->sum(fn ($c) => max(0, $c->case_price - $c->payments()->sum('amount'))),

            'expenses_paid'   => CaseExpense::where('status', 'paid')->sum('amount'),
            'expenses_pending'=> CaseExpense::where('status', 'pending')->sum('amount'),
        ];

        $payments = CasePayment::with(['case.client:id,name'])
            ->when($from, fn ($q) => $q->where('payment_date', '>=', $from))
            ->when($to,   fn ($q) => $q->where('payment_date', '<=', $to))
            ->latest('payment_date')
            ->paginate(15, ['*'], 'payments_page')
            ->withQueryString();

        $caseExpenses = CaseExpense::with(['case.client:id,name'])
            ->when($from, fn ($q) => $q->where('expense_date', '>=', $from))
            ->when($to,   fn ($q) => $q->where('expense_date', '<=', $to))
            ->latest('expense_date')
            ->paginate(15, ['*'], 'expenses_page')
            ->withQueryString();

        return Inertia::render('Finances/Index', [
            'stats'        => $stats,
            'payments'     => $payments,
            'caseExpenses' => $caseExpenses,
            'filters'      => $request->only(['tab', 'from', 'to']),
        ]);
    }
}
