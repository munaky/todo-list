<?php

namespace App\Http\Controllers;

use App\Exports\TodosExport;
use App\Models\Todo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class TodoController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => ['required'],
                'assignee' => ['nullable'],
                'due_date' => ['required', 'date', 'after_or_equal:today'],
                'time_tracked' => ['nullable'],
                'status' => ['nullable', 'in:pending,open,in_progress,completed'],
                'priority' => ['required', 'in:low,medium,high'],
            ]);

            $todo = Todo::create($validated);


            return response()->json(Todo::find($todo->id), 201);
        } catch (Throwable $e) {
            info($e);
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => ['nullable'],
                'assignee' => ['nullable'],
                'start' => ['nullable', 'date'],
                'end'   => ['nullable', 'date', 'after_or_equal:start'],
                'min'   => ['nullable', 'numeric', 'lte:max'],
                'max'   => ['nullable', 'numeric', 'gte:min'],
                'status' => ['nullable'],
                'priority' => ['nullable'],
            ]);

            return Excel::download(new TodosExport($validated), 'todos.xlsx');
        } catch (Throwable $e) {
            info($e);
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
