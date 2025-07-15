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
    public function index(Request $request, Patient $patient): View|string
    {
        $notes = $patient->notes()->latest()->get();
        if ($request->ajax()) {
            return view('patients.notes.list', compact('patient', 'notes'))->render();
        }
        return view('patients.notes.index', compact('patient', 'notes'));
    }

    public function create(Patient $patient): View
    {
        return view('patients.notes.create', [
            'patient' => $patient,
            'note' => null,
        ]);
    }

    public function store(Request $request, Patient $patient): JsonResponse
    {
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
            'redirect' => route('patients.notes.index', $patient->id),
            'message' => 'Note added successfully',
        ]);
    }

    public function edit(Patient $patient, PatientNote $note): View
    {
        return view('patients.notes.edit', compact('patient', 'note'));
    }

    public function update(Request $request, Patient $patient, PatientNote $note): JsonResponse
    {
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
            'redirect' => route('patients.notes.index', $patient->id),
            'message' => 'Note updated successfully',
        ]);
    }

    public function destroy($patientId, $noteId): RedirectResponse
    {
        PatientNote::destroy($noteId);
    
        return redirect()->route('patients.notes.index', $patientId)
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
