<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TaskFollowup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskFollowupController extends Controller
{
    public function storeOrUpdate(Request $request, $patientId, $taskId, $followupId = null) : JsonResponse
    {
        $request->validate([
            'followup_date' => 'required|date',
            'note' => 'required|string',
        ]);

        if ($followupId) {
            $followup = TaskFollowup::where('task_id', $taskId)->findOrFail($followupId);
            $followup->update([
                'followup_date' => $request->followup_date,
                'note' => $request->note,
            ]);
            return response()->json([
                'redirect' => route('tasks.tasks.index', $patientId),
                'message' => 'Follow-up Updated successfully',
            ]);
        } else {
            TaskFollowup::create([
                'task_id' => $taskId,
                'followup_date' => $request->followup_date,
                'note' => $request->note,
            ]);

            return response()->json([
                'redirect' => route('tasks.tasks.index', $patientId),
                'message' => 'Follow-up created successfully',
            ]);
        }
    }

    public function destroy($patientId, $taskId, TaskFollowup $followup) : RedirectResponse
    {
        $followup->delete();
        return redirect()
            ->route('tasks.tasks.index', ['patient' => $patientId])
            ->with('success', 'Follow Up deleted successfully.');
    }

}
