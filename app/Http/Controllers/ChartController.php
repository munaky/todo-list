<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ChartController extends Controller
{
    public function get(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => ['required', 'in:status,priority,assignee'],
            ]);

            if ($validated['type'] == 'status') $summary = $this->getStatusSummary();
            if ($validated['type'] == 'priority') $summary = $this->getPrioritySummary();
            if ($validated['type'] == 'assignee') $summary = $this->getAssigneeSummary();

            // return response()->json([
            //     'code' => 200,
            //     'status' => 'success',
            //     'message' => 'chart data fetched successfully',
            //     'data' => $summary
            // ]);

            return response()->json($summary);
        } catch (Throwable $e) {
            info($e);
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ]);
        }
    }

    private function getStatusSummary()
    {
        $summary = [];
        $result = Todo::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        foreach ($result as $item) {
            $summary[$item->status] = $item->total;
        }

        return [
            'status_summary' => $summary
        ];
    }

    private function getPrioritySummary()
    {
        $summary = [];
        $result = Todo::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get();

        foreach ($result as $item) {
            $summary[$item->priority] = $item->total;
        }

        return [
            'priority_summary' => $summary
        ];
    }

    private function getAssigneeSummary()
    {
        $summary = [];
        $result = Todo::select(
            'assignee',
            DB::raw('count(*) as total'),
            DB::raw("COUNT(CASE WHEN status = 'pending' THEN 1 END) as total_pending_todos"),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN time_tracked ELSE 0 END) as total_timetracked_completed_todos")
            )
            ->groupBy('assignee')
            ->get();

        foreach ($result as $item) {
            $summary[$item->assignee]['total_todos'] = $item->total;
            $summary[$item->assignee]['total_pending_todos'] = $item->total_pending_todos;
            $summary[$item->assignee]['total_timetracked_complete_todos'] = $item->total_timetracked_completed_todos;
        }

        return [
            'assignee_summary' => $summary
        ];
    }
}
