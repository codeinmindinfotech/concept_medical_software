<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Patient;
use App\Models\PatientAudioFile;
use Illuminate\Support\Facades\Storage;


class AudioController extends Controller
{ 
    public function index(Request $request): View|string
    {
        $audios = PatientAudioFile::with('patient','doctor')->latest()->get();
        if ($request->ajax()) {
            return view('audio.list', compact('audios'))->render();
        } 

        return view('audio.index',compact('audios'));
    }
    
    public function create()
    {
        $doctors = Doctor::orderBy('name')->get(); 
        $patients = Patient::orderBy('first_name')->get(); 
        return view('audio.create',compact('patients','doctors'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'file_path' => 'required|file|mimes:mp3,wav,m4a,aac,ogg,webm|max:10240'
        ]);
        $patient = Patient::findOrFail($data['patient_id']);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = $patient->id . '_audio_' . time() . '.' . strtolower($file->getClientOriginalExtension());
            $folder = storage_path('app/public/audio');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);  // Create directory with 0755 permissions recursively
            }
            
            $data['file_path'] = $file->storeAs('audio', $filename, 'public');
        }

        $patient->audio()->create([
            'doctor_id' => $data['doctor_id'],
            'file_path' => $data['file_path'],
        ]);

        return redirect()
            ->route('audios.index')
            ->with('success', 'Audio uploaded successfully!');

    }
   
    public function show(PatientAudioFile $audio): View
    {
        return view('audio.show',compact('audio'));
    }
    
    public function edit(PatientAudioFile $audio): View
    {
        $doctors = Doctor::orderBy('name')->get(); 
        $patients = Patient::orderBy('first_name')->get();
        return view('audio.edit',compact('doctors','patients','audio'));
    }

    public function update(Request $request, PatientAudioFile $audio)
    {
        $rules = [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
        ];

        // Only require file_path if there's no existing file_path in the database
        if (!$audio->file_path) {
            $rules['file_path'] = 'required|file|mimes:mp3,wav,m4a,aac,ogg,webm|max:10240';
        } else {
            // file_path optional if already exists
            $rules['file_path'] = 'nullable|file|mimes:mp3,wav,m4a,aac,ogg,webm|max:10240';
        }

        $data = $request->validate($rules);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = $audio->patient_id . '_audio_' . time() . '.' . $file->getClientOriginalExtension();
            $data['file_path'] = $file->storeAs('audio', $filename, 'public');
        } else {
            // Keep existing file_path if no new file uploaded
            unset($data['file_path']);
        }

        $audio->update($data);

        return redirect()->route('audios.index')->with('success', 'Audio updated successfully!');
    }

    public function destroy(PatientAudioFile $audio): RedirectResponse
    {
        $audio = PatientAudioFile::findOrFail($audio->id);

        if (Storage::disk('public')->exists($audio->file_path)) {
            Storage::disk('public')->delete($audio->file_path);
        }
        $audio->delete();
    
        return redirect()->route('audios.index')
                        ->with('success','Audio Recording deleted successfully');
    }
    
}