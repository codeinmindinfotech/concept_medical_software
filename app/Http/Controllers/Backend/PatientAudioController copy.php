<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientAudioFile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientAudioController extends Controller
{
    public function index(Request $request, Patient $patient): View|string
    {
        $audios = $patient->audio()->latest()->get();
        if ($request->ajax()) {
            return view('patients.audio.list', compact('patient', 'audios'))->render();
        }
        return view('patients.audio.index', compact('patient', 'audios'));
    }

    public function create(Patient $patient): View
    {
        return view('patients.audio.create', [
            'patient' => $patient
        ]);
    }

    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'file_path' => 'required|file|mimes:mp3,wav,m4a,aac,ogg,webm|max:10240'
        ]);

        $patient->audio()->create([
            ...$data
        ]);

        return redirect()
            ->route('patients.audio.index', $patient->id)
            ->with('success', 'Audio uploaded successfully!');

    }

    public function destroy($patientId, $audioId): RedirectResponse
    {
        $audio = PatientAudioFile::findOrFail($audioId);

      

        // Delete the physical file
        if (Storage::disk('public')->exists($audio->file_path)) {
            Storage::disk('public')->delete($audio->file_path);
        }

        // Delete the database record
        $audio->delete();

    
        return redirect()->route('patients.audio.index', $patientId)
                        ->with('success','Audio Recording deleted successfully');
    }
}
