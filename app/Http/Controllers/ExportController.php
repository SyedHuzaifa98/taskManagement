<?php

namespace App\Http\Controllers;
use App\Exports\ExportTask;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class ExportController extends Controller
{
    public function exportTasks(Request $request){
// dd($request->input('modifiedProjectName'));
return Excel::download(new ExportTask($request->modifiedProjectName), 'tasks.xlsx');
    }
}
