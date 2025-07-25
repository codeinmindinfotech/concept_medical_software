<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DropDownValue;
use App\Models\Patient;
use App\Models\Task;
use App\Models\User;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use DropdownTrait;

    public function index(Patient $patient)
    {
        $tasks = Task::with(['creator', 'owner', 'category', 'status'])->where('patient_id', $patient->id)->paginate(10);
        // Pass other variables as needed
        $users = User::role('superadmin')->get();

        $statuses = $this->getDropdownOptions('STATUS');
        $taskcategories = $this->getDropdownOptions('CATEGORY');

        return view('patients.dashboard.tasks.index', compact('patient', 'tasks', 'users', 'taskcategories', 'statuses'));
    }

    public function create(Patient $patient)
    {
        $patient = Patient::findOrFail($patient->id); // <-- Add this line

        $users = User::role('superadmin')->get();
        $statuses = $this->getDropdownOptions('STATUS');
        $taskcategories = $this->getDropdownOptions('CATEGORY');

        return view('patients.dashboard.tasks.create', compact('patient', 'users', 'taskcategories', 'statuses'));
    }


    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'subject' => 'required|string|max:255',
            'task' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'task_creator_id' => 'required|exists:users,id',
            'task_owner_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:drop_down_values,id',
            'status_id' => 'required|exists:drop_down_values,id',
        ]);


        Task::create($request->all());
        return response()->json([
            'redirect' => route('tasks.tasks.index', ['patient' => $request->patient_id]),
            'message' => 'Task created successfully',
        ]);
    }

    public function edit(Patient $patient, $taskId)
    {
        $task = Task::with(['creator', 'owner', 'category', 'status'])->findOrFail($taskId);
        $users = User::role('superadmin')->get();
        $statuses = $this->getDropdownOptions('STATUS');
        $taskCategories = $this->getDropdownOptions('CATEGORY');


        return view('patients.dashboard.tasks.edit', compact('patient','task', 'users', 'statuses', 'taskCategories'));
    }

    public function update(Request $request, Patient $patient, $taskId): JsonResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'task' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'task_creator_id' => 'required|exists:users,id',
            'task_owner_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:drop_down_values,id',
        ]);

        $task = Task::findOrFail($taskId);
        $task->update($request->all());

        return response()->json([
            'redirect' => route('tasks.tasks.index', ['patient' => $patient->id]),
            'message' => 'Task updated successfully',
        ]);
    }
    public function destroy(Patient $patient,Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()
            ->route('tasks.tasks.index', ['patient' => $patient->id])
            ->with('success', 'Task deleted successfully.');
    }
}
