<?php

namespace App\Exports;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class TodosExport implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    protected $title;
    protected $assignees;
    protected $start;
    protected $end;
    protected $min;
    protected $max;
    protected $statuses;
    protected $priorities;

    protected $count = 0;
    protected $time_trackeds = 0;

    public function __construct($filters)
    {
        $this->title = isset($filters['title']) ? $filters['title'] : null;
        $this->assignees = isset($filters['assignee']) ? explode(',', $filters['assignee']) : null;
        $this->start = isset($filters['start']) ? $filters['start'] : null;
        $this->end = isset($filters['end']) ? $filters['end'] : null;
        $this->min = isset($filters['min']) ? $filters['min'] : null;
        $this->max = isset($filters['max']) ? $filters['max'] : null;
        $this->statuses = isset($filters['status']) ? explode(',', $filters['status']) : null;
        $this->priorities = isset($filters['priority']) ? explode(',', $filters['priority']) : null;
    }

    public function collection()
    {
        $todos = Todo::select('title', 'assignee', 'due_date', 'time_tracked', 'status', 'priority');

        if($this->title) $todos->where('title', 'like', '%' . $this->title . '%');
        if($this->assignees) $todos->whereIn('assignee', $this->assignees);
        // if($this->assignees) {
        //     foreach($this->assignees as $assignee) {
        //         $todos->orWhere('assignee', 'like', '%' . $assignee . '%');
        //     }
        // }
        if($this->start) $todos->where('due_date', '>=', $this->start);
        if($this->end) $todos->where('due_date', '<=', $this->end);
        if($this->min) $todos->where('time_tracked', '>=', $this->min);
        if($this->max) $todos->where('time_tracked', '<=', $this->max);
        if($this->statuses) $todos->whereIn('status', $this->statuses);
        if($this->priorities) $todos->whereIn('priority', $this->priorities);
        
        $todos = $todos->get();

        $this->count = $todos->count();
        $this->time_trackeds = $todos->sum('time_tracked');

        info($todos);

        return $todos;
    }

    public function map($todo): array
    {
        return [
            $todo->title,
            $todo->assignee,
            $todo->due_date,
            (string)($todo->time_tracked ?? '0'),
            $todo->status,
            $todo->priority,
        ];
    }

    public function headings(): array
    {
        return [
            'title',
            'assignee',
            'due_date',
            'time_tracked',
            'status',
            'priority',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $summary_row = $this->count + 3;
                $total_todo_row = $this->count + 4;
                $total_time_tracked_row = $this->count + 5;

                $sheet->mergeCells("A$summary_row:B$summary_row");
                $sheet->setCellValue("A$summary_row", 'Summary');

                $sheet->setCellValue("A$total_todo_row", 'total todo');
                $sheet->setCellValue("B$total_todo_row", $this->count);

                $sheet->setCellValue("A$total_time_tracked_row", 'total time tracked');
                $sheet->setCellValue("B$total_time_tracked_row", $this->time_trackeds);
            },
        ];
    }
}
