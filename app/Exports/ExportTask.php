<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTask implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $data;
    public function __construct($request){
        $this->data = Task::select('taskName', 'projectName', 'startDate', 'endDate', 'name')
        ->join('users', 'tasks.userId', '=', 'users.id')
        ->where('tasks.userId', Auth::user()->id);
        if($request!=null){
        $this->data->where('tasks.projectName',$request);
        }
        $this->data = $this->data->get();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         // Add sequential numbering for Sr. no column
         $numberedData = $this->data->map(function ($row, $index) {
            return array_merge(['Sr.no' => $index + 1], $row->toArray());
        });

        return $numberedData;
    }
    public function headings(): array
    {
        return
            [
                "Sr.no",
                "Task Name",
                "Project Name",
                "Start Date",
                "End Date",
                "Name"
            ];
    }

    public function styles(Worksheet $sheet):array
    {
      return [
            1    => ['font' => ['bold' => true]],
            // 2    => ['font' => ['bold' => true]],
            // 3    => ['font' => ['bold' => true]],
            // 4    => ['font' => ['bold' => true]],
            // 5    => ['font' => ['bold' => true]],
            // 6    => ['font' => ['bold' => true]],
        ];
    }




}
