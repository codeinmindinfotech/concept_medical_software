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
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class PatientAudioController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file_path' => 'required|file|mimes:mp3,wav,m4a,aac,ogg,webm|max:10240',

        ]);

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
        // Validate incoming file (audio types & max size 10MB)
        $data = $request->validate([
            'file_path' => 'required|file|mimes:mp3,wav,m4a,aac,ogg,webm|max:10240',
        ]);
    
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
    
            // Create unique filename based on patient ID + timestamp + extension
            $filename = $patient->id . '_audio_' . time() . '.' . $file->getClientOriginalExtension();
    
            // Store original uploaded file in 'public/audio'
            $storedPath = $file->storeAs('audio', $filename, 'public');
            $fullInputPath = storage_path('app/public/' . $storedPath);
    
            // Prepare WAV filename & path for converted audio
            $wavFilename = pathinfo($filename, PATHINFO_FILENAME) . '.wav';
            $fullWavPath = storage_path('app/public/audio/' . $wavFilename);
    
            // Paths to Python and ffmpeg executables — set in .env
            $pythonPath = env('PYTHON_PATH');
            $ffmpegPath = env('FFMPEG_PATH');
    
            try {
                // Step 1: Convert uploaded audio to WAV with ffmpeg
                $ffmpeg = new Process([
                    $ffmpegPath,
                    '-y', // overwrite output if exists
                    '-i', $fullInputPath,
                    $fullWavPath,
                ]);
                $ffmpeg->run();
    
                if (!$ffmpeg->isSuccessful()) {
                    throw new ProcessFailedException($ffmpeg);
                }
    
                // Step 2: Transcribe WAV using Whisper via exec()
                $outputDir = storage_path('app/public/audio');
                $command = "\"$pythonPath\" -m whisper \"$fullWavPath\" --model tiny --language English --output_format txt --output_dir \"$outputDir\"";
    
                exec($command, $output, $return_var);
    
                if ($return_var !== 0) {
                    \Log::error('Whisper transcription failed', [
                        'command' => $command,
                        'output' => $output,
                        'return_code' => $return_var,
                    ]);
                    return back()->withErrors(['transcription_error' => 'Whisper transcription process failed.']);
                }
    
                // Step 3: Read the generated transcription (.txt) file
                $txtPath = str_replace('.wav', '.txt', $fullWavPath);
                $transcription = file_exists($txtPath)
                    ? mb_convert_encoding(file_get_contents($txtPath), 'UTF-8', 'auto')
                    : null;
    
                if (empty($transcription)) {
                    return back()->withErrors(['transcription_error' => 'No transcription was generated.']);
                }
    
                // Step 4: Save audio file path and transcription text in DB (adjust model relation as needed)
                $patient->audio()->create([
                    'file_path' => $storedPath,
                    'transcription' => $transcription,
                ]);
    
                // Optional cleanup (uncomment if needed)
                if (file_exists($fullWavPath)) unlink($fullWavPath);
                if (file_exists($txtPath)) unlink($txtPath);
    
                return redirect()
                    ->route('patients.audio.index', $patient->id)
                    ->with('success', 'Audio uploaded and transcribed successfully!');
            } catch (\Throwable $e) {
                return back()->withErrors(['transcription_error' => 'Transcription failed: ' . $e->getMessage()]);
            }
        }
    
        // No file uploaded
        return back()->withErrors(['file_path' => 'No file uploaded.']);
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
