<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DoctorRequest;
use App\Services\PasswordResetService;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;  // or Imagick

class DoctorController extends Controller
{ 
    use DropdownTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $this->authorize('viewAny', Doctor::class);
        $query = Doctor::with('salutationOption')->companyOnly();

        if (has_role('doctor')) {
            $user = auth()->user();
            $query = $query->where('id', $user->id);
        }

        $doctors = $query->latest()->get();
        if ($request->ajax()) {
            return view('doctors.list', compact('doctors'))->render();
        } 
        
        return view(guard_view('doctors.index', 'patient_admin.doctor.index'),compact('doctors'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $this->authorize('create', Doctor::class);
        extract($this->getCommonDropdowns());
        return view(guard_view('doctors.create', 'patient_admin.doctor.create'),compact('contactTypes','titles', 'paymentMethods'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorRequest $request): JsonResponse
    {
        $this->authorize('create', Doctor::class);
        $validated = $request->validated();
        $doctor = Doctor::create($validated);
         // Handle signature
        if ($doctor) {
            $signaturePath = $this->handleSignature($request, $doctor);
            $doctor->doctor_signature = $signaturePath;
            $doctor->save();
        }

        return response()->json([
            'redirect' =>guard_route('doctors.index'),
            'message' => 'Doctor created successfully. A password reset link has been sent.',
        ]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor): View
    {
        $this->authorize('view', $doctor);
        return view(guard_view('doctors.show', 'patient_admin.doctor.show'),compact('doctor'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor): View
    {
        $this->authorize('update', $doctor);
        extract($this->getCommonDropdowns());
        return view(guard_view('doctors.edit', 'patient_admin.doctor.edit'),compact('doctor','titles', 'contactTypes','paymentMethods'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(DoctorRequest $request, Doctor $doctor): JsonResponse 
    {
        $this->authorize('update', $doctor);
        $validated = $request->validated();
        $doctor->update($validated);
        // assignRoleToGuardedModel($doctor, 'doctor', 'doctor',  $doctor->company_id);

        // Handle signature
        $signaturePath = $this->handleSignature($request, $doctor);
        if ($signaturePath) {
            $doctor->doctor_signature = $signaturePath;
            $doctor->save();
        }

        return response()->json([
            'redirect' =>guard_route('doctors.index'),
            'message' => 'Doctor updated successfully',
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor): RedirectResponse
    {
        $this->authorize('delete', $doctor);
        $doctor->delete();
    
        return redirect(guard_route('doctors.index'))
                        ->with('success','doctor deleted successfully');
    }

    protected function handleSignature($request, $patient)
    {
        $dir = "doctor_signatures/{$patient->id}";
        $manager = new ImageManager(new Driver());
    
        // 1. Uploaded file
        if ($request->hasFile('signature_file')) {
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            } else {
                Storage::disk('public')->deleteDirectory($dir);
                Storage::disk('public')->makeDirectory($dir);
            }
    
            $img = $manager->read($request->file('signature_file')->getRealPath());
            $img->resize(100, 80);
        // 2. Drawn signature
        } elseif ($request->signature_draw) {
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            } else {
                Storage::disk('public')->deleteDirectory($dir);
                Storage::disk('public')->makeDirectory($dir);
            }
    
            $data = str_replace('data:image/png;base64,', '', $request->signature_draw);
            $data = base64_decode($data);
    
            $img = $manager->read($data);
            // Resize image
            $img->resize(100, 80);
    
        // 3. Existing signature
         }
         elseif ($patient->patient_signature && Storage::disk('public')->exists($patient->patient_signature)) {
            $img = $manager->read(storage_path("app/public/" . $patient->patient_signature));
            \Log::info('Using existing patient signature: ' . $patient->patient_signature);
    
        // 4. Create blank canvas
        } 
        else {
            $text = $request->name;
            $fontPath = public_path('assets/fonts/DancingScript-VariableFont_wght.ttf');
            $fontSize = 16;
        
            // Measure text bounding box
            $box = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth  = abs($box[2] - $box[0]);
            $textHeight = abs($box[7] - $box[1]);
        
            // Define padding (px)
            $padding = 4;
        
            // Create GD canvas with padding
            $canvasWidth  = $textWidth + ($padding * 2);
            $canvasHeight = $textHeight + ($padding * 2);
        
            $gdResource = imagecreatetruecolor($canvasWidth, $canvasHeight);
        
            // Optional: fill with white
            $white = imagecolorallocate($gdResource, 255, 255, 255);
            imagefill($gdResource, 0, 0, $white);
        
            // Load into Intervention
            $img = $manager->read($gdResource);
        
            // Draw text centered inside the padded canvas
            $img->text($text, $canvasWidth / 2, $canvasHeight / 2, function ($font) use ($fontPath, $fontSize) {
                $font->filename($fontPath);
                $font->size($fontSize);
                $font->color('#1b5a90');
                $font->align('center');
                $font->valign('middle');
            });
        
            // $img now has padding and correct proportions
        }
        $filename = "signature.png";
        $path = "$dir/$filename";
    
        // Save image
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
    
        $img->save(storage_path("app/public/" . $path));
    
        return $path;
    }
    public function uploadPicture(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'doctor_picture' => 'nullable|image|max:4096',
            'doctor_picture_webcam' => 'nullable|string',
        ]);
    
        $doctor = Doctor::findOrFail($request->doctor_id);
    
        // Create Image Manager instance (v3)
        $manager = new ImageManager(new Driver());

        // Delete old images
        $dir = "doctor_pictures/{$doctor->id}";
        Storage::disk('public')->deleteDirectory($dir);
        Storage::disk('public')->makeDirectory($dir);
    
        // Load image from file upload
        if ($request->hasFile('doctor_picture')) {
            $img = $manager->read($request->file('doctor_picture')->getRealPath());
        }
        // Load image from webcam base64
        elseif ($request->doctor_picture_webcam) {
            $data = $request->doctor_picture_webcam;
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = base64_decode($data);
    
            $img = $manager->read($data);
        } else {
            return back()->with('error', 'No image uploaded.');
        }
    
        // Save main profile (400x400)
        $img->cover(400, 400)->toJpeg()->save(storage_path("app/public/$dir/profile.jpg"));
    
        // Save small (150x150)
        $small = clone $img;
        $small->cover(150, 150)->toJpeg()->save(storage_path("app/public/$dir/small.jpg"));
    
        // Save medium (300x300)
        $medium = clone $img;
        $medium->cover(300, 300)->toJpeg()->save(storage_path("app/public/$dir/medium.jpg"));
    
        // Save large (600x600)
        $large = clone $img;
        $large->cover(600, 600)->toJpeg()->save(storage_path("app/public/$dir/large.jpg"));
    
        // DB save
        $doctor->doctor_picture = "$dir/profile.jpg";
        $doctor->save();
    
        return back()->with('success', 'Profile picture updated successfully!');
    }
}