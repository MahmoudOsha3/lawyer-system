<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CaseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CaseFileController extends Controller
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
        $request->validate([
            'case_id' => 'required|exists:cases,id',
            'file' => 'required|file|max:3072',
            'title' => 'required|string|min:3|max:40'
        ]) ;

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->storeAs($request->case_id , $fileName, 'files');
            CaseFile::create([
                'case_id' => $request->case_id ,
                'file' => $request->case_id .'/'. $fileName ,
                'title' => $request->title
            ]) ;
            return back()->with('success' , 'تم رفع الملف بنجاح') ;

        }
        return back()->with('success' , 'حدث خطأ في عملية رفع الملف') ;


    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(CaseFile $caseFile)
    {
        if (Storage::disk('files')->exists($caseFile->file)) {
            Storage::disk('files')->delete($caseFile->file);
        }
        $caseFile->delete();

        return back()->with('success', 'تم حذف المستند بنجاح');
    }

    public function download(CaseFile $caseFile)
    {
        if (!Storage::disk('files')->exists($caseFile->file))
        {
            return back()->with('error', 'الملف غير موجود');
        }
        $fileName = $caseFile->title . '.' . pathinfo($caseFile->file, PATHINFO_EXTENSION);
        return Storage::disk('files')->download($caseFile->file, $fileName);
    }
}
