<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cases\CaseStoreRequest;
use App\Models\Cases;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseController extends Controller
{

    public function index(Request $request)
    {
        $cases = Cases::query()
            ->when($request->name, function ($q) use ($request) {
                $q->whereHas('client', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->name}%");
                });
            })
            ->when($request->case_number, function ($q) use ($request) {
                $q->where('case_number', 'like', "%{$request->case_number}%");
            })
            ->with('client:id,name')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Cases/Index', [
            'cases'   => $cases,
            'filters' => $request->only(['name', 'case_number']),
            'clients' => \App\Models\Client::select('id', 'name')->orderBy('name')->get(),
        ]);
    }


    public function create()
    {
        //
    }

    public function store(CaseStoreRequest $request)
    {
        Cases::create($request->validated()) ;
        return redirect()->back() ;
    }

    public function show($caseId)
    {
        $case = Cases::with([
            'client',
            'payments' => function($query){
                $query->latest() ;
            } ,
            'expenses' => function($query) {
                $query->latest();
            },
            'sessions' => function($query) {
                $query->latest();
            },
            'files'
        ])->findOrfail($caseId) ;

        return Inertia::render('Cases/Show', [
            'legalCase' => $case,
            'stats' => [
                'total_paid' => $case->payments->sum('amount'),
                'total_expenses' => $case->expenses->sum('amount'),
                'remaining' => $case->case_price - $case->payments->sum('amount'),
            ]
        ]);
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Cases $case)
    {
        // $case->update($request->validated()) ;
        // return redirect()->back() ;
    }

    public function destroy(Cases $case)
    {
        $case->delete() ;
        return redirect()->back() ;
    }
}
