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
    public function upload(Request $request)
    {
        $request->validate([
            'file_path' => 'required|file|mimes:mp3,wav,m4a,aac,ogg,webm|max:10240',

        ]);

        // if ($request->hasFile('file_path')) {
        //     $file = $request->file('file_path');
            
        //     $filename = $request->patient_id . '_audio_' . time() . '.' . strtolower($file->getClientOriginalExtension());
        //     $folder = storage_path('app/public/audio');

        //     if (!file_exists($folder)) {
        //         mkdir($folder, 0755, true);  // Create directory with 0755 permissions recursively
        //     }
            
        //     $data['file_path'] = $file->storeAs('audio', $filename, 'public');
        // }

        if ($request->hasFile('file_path')) {
            $path  = $request->file('file_path')->store('audio', 'public');
        }
        // Save in DB
        $audio = PatientAudioFile::create([
            'patient_id' => $request->patient_id,
            'file_path' => $path,
        ]);


        return back()->with('success', 'Audio uploaded!')->with('audio_path', Storage::url($path));
    }
    
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

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            // Create new filename like picture_123.jpg
            $filename = $patient->id . '_audio_' . time() . '.' .strtolower($file->getClientOriginalExtension());

            // Save the file in public/patient_pictures
            $data['file_path'] = $file->storeAs('audio', $filename, 'public');
            $patient->audio()->create([
                ...$data
            ]);
            
        }

        // if ($request->hasFile('file_path')) {
        //     $file = $request->file('file_path');
        //     $filename = $patient->id . '_audio_' . time() . '.' .strtolower($file->getClientOriginalExtension());
        //     $folder = storage_path('app/public/audio');

        //     if (!file_exists($folder)) {
        //         mkdir($folder, 0755, true);  // Create directory with 0755 permissions recursively
        //     }
            
        //     $data['file_path'] = $file->storeAs('audio', $filename, 'public');
        // }

        // $patient->audio()->create([
        //     ...$data
        // ]);

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
