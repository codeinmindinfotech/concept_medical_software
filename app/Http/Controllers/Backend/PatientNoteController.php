<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PatientNoteController extends Controller
{
    public function index(Request $request, $patientId): View|string
    {
        $patient = Patient::findOrFail($patientId);
        $notes = $patient->notes()->latest()->get();
        if ($request->ajax()) {
            return view('patients.notes.list', compact('patient', 'notes'))->render();
        }
        return view('patients.notes.index', compact('patient', 'notes'));
    }

    public function create($patientId): View
    {
        $patient = Patient::findOrFail($patientId);
        return view('patients.notes.create', [
            'patient' => $patient,
            'note' => null,
        ]);
    }

    public function store(Request $request, $patientId): JsonResponse
    {
        $patient = Patient::findOrFail($patientId);
        $data = $request->validate([
            'method' => 'required|in:phone message,note',
            'notes' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        $patient->notes()->create([
            ...$data,
            'completed' => $request->boolean('completed'),
        ]);

        return response()->json([
            'redirect' => guard_route('patients.notes.index', $patient->id),
            'message' => 'Note added successfully',
        ]);
    }

    public function edit($patientId, $noteId): View
    {
        
        $patient = Patient::findOrFail($patientId);
        $note = PatientNote::findOrFail($noteId);
        return view('patients.notes.edit', compact('patient', 'note'));
    }

    public function update(Request $request, $patientId, $noteId): JsonResponse
    {
        $patient = Patient::findOrFail($patientId);
        $note = PatientNote::findOrFail($noteId);

        $data = $request->validate([
            'method' => 'required|in:phone message,note',
            'notes' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        $note->update([
            ...$data,
            'completed' => $request->boolean('completed'),
        ]);

        return response()->json([
            'redirect' => guard_route('patients.notes.index', $patient->id),
            'message' => 'Note updated successfully',
        ]);
    }

    public function destroy($patientId, $noteId): RedirectResponse
    {
        PatientNote::destroy($noteId);
    
        return redirect()->guard_route('patients.notes.index', $patientId)
                        ->with('success','Note deleted successfully');
    }

    public function toggleCompleted($patientId, $noteId)
    {
        $note = PatientNote::findOrFail($noteId);
        $note->completed = !$note->completed;
        $note->save();

        return response()->json([
            'success' => true,
            'completed' => $note->completed,
            'badge_class' => $note->completed ? 'success' : 'warning',
            'text' => $note->completed ? 'Yes' : 'No',
        ]);
    }

}
