<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Task;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $projectName = Task::select('projectName')
            ->distinct()
            ->where('userId', Auth::user()->id)
            ->get()
            ->pluck('projectName')
            ->toArray();

        if ($request->ajax()) {
            $data = Task::select('*')
                ->where('userId', Auth::user()->id);
            if ($request->get('projectName') && !empty($request->get('projectName'))) {
                $data->where('tasks.projectName', $request->get('projectName'));
            }
            $data = $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($task) {
                    $updateUrl = route('task.edit', $task->id);
                    $actionBtn = '
                <a href="' . $updateUrl . '" class="btn btn-success"><i class="fa-solid fa-pen-to-square"></i></a>
            <a href="" data-toggle="tooltip" data-id="' . $task->id . '" data-original-title="Delete" class="btn btn-danger deleteTask"><i class="fa-solid fa-delete-left"></i></a>
                ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('task.index', compact('projectName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('task.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'taskName' => ['required'],
            'projectName' => ['required'],
            'startDate' => ['nullable', 'date'],
            'endDate' => ['nullable', 'date'],
        ]);

        $validatedData['userId'] = Auth::user()->id;

        // Create a new Task instance and fill it with the validated data
        $task = Task::create($validatedData);

        return back()->with('success', 'Task added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Task::find($id);
        return view('task.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'taskName' => ['required'],
            'projectName' => ['required'],
            'startDate' => ['nullable', 'date'],
            'endDate' => ['nullable', 'date'],
        ]);

        $validatedData['userId'] = Auth::user()->id;
        Task::where('id', $id)->update($validatedData);
        return redirect('task')->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Task::find($id);
        $record->delete();
        return redirect('task')->with('success', 'Deleted Succesfully');
    }
}
