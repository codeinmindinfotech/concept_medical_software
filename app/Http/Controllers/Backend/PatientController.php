<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Models\Backend\Insurance;
use App\Models\Consultant;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Traits\DropdownTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Password;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;  // or Imagick
use Intervention\Image\Facades\Image;


class PatientController extends Controller
{ 
    use DropdownTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $this->authorize('viewAny',  Patient::class);
        

        if (has_role('patient')) {
            $user = auth()->user();
            $patients = Patient::with('title')->companyOnly()->where('id', $user->id)->paginate(1);
        } else {
            // Admins can search all patients
            $query = Patient::with('title')->companyOnly()->latest();

            if ($request->filled('first_name')) {
                $query->where('first_name', 'like', '%' . $request->first_name . '%');
            }

            if ($request->filled('surname')) {
                $query->where('surname', 'like', '%' . $request->surname . '%');
            }

            // if ($request->filled('title')) {
            //     $query->whereHas('title', function ($q) use ($request) {
            //         $q->where('value', $request->title);
            //     });
            // }
            if ($request->filled('phone')) {
                $query->where('phone', 'like', '%' . $request->phone . '%');
            }
    
            // if ($request->filled('pin')) {
            //     $query->where('pin', 'like', '%' . $request->pin . '%');
            // }
    
            if ($request->filled('dob')) {
                $query->whereDate('dob', $request->dob);
            }

            if ($request->get('tab') === 'trashed') {
                $query->onlyTrashed();
            }

            $patients = $query->paginate(10)->withQueryString();
        }

        if ($request->ajax()) {
            return view('patients.list', compact('patients'))->render();
        }

        
        return view(guard_view('patients.index', 'patient_admin.profile.index'), compact('patients'));
    }

    public function ajax(Request $request)
    {
        $query = Patient::with(['doctor'])
            ->companyOnly()
            ->latest();

        if ($request->get('tab') === 'trashed') {
            $query->onlyTrashed();
        }

        $patients = $query->get();

        $data = $patients->map(function ($patient, $index) {
            return [
                'id'        => $patient->id,
                'index'     => $index + 1,
                'doctor'    => $patient->doctor?->name ?? '-',
                'patient_name' => $patient->full_name,
                'address'   => $patient->address ?? '-',
                'phone'     => $patient->phone ?? '-',
                'dob'       => format_date($patient->dob),
                'status'    => $patient->trashed() ? 'Trashed' : 'Active',
                'patient_picture' => $patient->patient_picture 
                    ? asset('storage/' . $patient->patient_picture)
                    : asset('assets_admin/img/patients/default.jpg'),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $this->authorize('create',  Patient::class);
        $pageTitle = "Patients Create";
        extract($this->getCommonDropdowns());
        $doctors = Doctor::companyOnly()->orderBy('name')->get(); 
        $consultants = Consultant::companyOnly()->orderBy('name')->get(); 
        $insurances = Insurance::companyOnly()->orderBy('code')->get(); 
        return view(guard_view('patients.create', 'patient_admin.profile.create'), compact('pageTitle','titles','insurances','relations','consultants', 'preferredContact','doctors'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientRequest $request , PasswordResetService $resetService): JsonResponse
    {
        $this->authorize('create', Patient::class);
        $validated = $request->validated();

        $validated['rip'] = $request->has('rip');
        $validated['sms_consent'] = $request->has('sms_consent');
        $validated['email_consent'] = $request->has('email_consent');

        $patient = Patient::create($validated);
        assignRoleToGuardedModel($patient, 'patient', 'patient');

         // Handle signature
        if ($patient) {
            $signaturePath = $this->handleSignature($request, $patient);
            $patient->patient_signature = $signaturePath;
            $patient->save();
        }

        if ($patient) {
            try {
                $resetService->sendResetLink($patient, 'patient', 'patients');     
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'redirect' =>guard_route('patients.edit', $patient->id),
            'message' => 'Patient created successfully. A password reset link has been sent.',
        ]);

        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient): View
    {
        $this->authorize('viewAny', $patient);
        $pageTitle = "Show Patient";
        return view(guard_view('patients.show', 'patient_admin.profile.view'),compact('patient','pageTitle'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient): View
    {
        $this->authorize('update', $patient);
        $pageTitle = "Edit Patient";
        extract($this->getCommonDropdowns());
        $doctors = Doctor::companyOnly()->orderBy('name')->get(); 
        $insurances = Insurance::companyOnly()->orderBy('code')->get();
        $consultants = Consultant::companyOnly()->orderBy('name')->get();
        return view(guard_view('patients.edit', 'patient_admin.profile.edit'), compact('patient', 'pageTitle', 'titles', 'relations', 'consultants', 'insurances', 'preferredContact','doctors'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(PatientRequest $request, Patient $patient): JsonResponse
    {
        $this->authorize('update', $patient);

        $validated = $request->validated();
    
        // Handle checkbox fields (they won't be present if unchecked)
        $validated['rip'] = $request->has('rip');
        $validated['sms_consent'] = $request->has('sms_consent');
        $validated['email_consent'] = $request->has('email_consent');

        // Update the patient
        $patient->update($validated);
        assignRoleToGuardedModel($patient, 'patient', 'patient');

        // Handle signature
        $signaturePath = $this->handleSignature($request, $patient);
        if ($signaturePath) {
            $patient->patient_signature = $signaturePath;
            $patient->save();
        }

        
        return response()->json([
            'redirect' =>guard_route('patients.edit', $patient->id),
            'message' => 'Patient updated successfully',
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient): RedirectResponse
    {
        $this->authorize('delete', $patient);

        $patient->delete();
    
        return redirect(guard_route('patients.index'))
                        ->with('success','Patient deleted successfully');
    }

    public function UploadPictureForm(Patient $patient): View
    {
        $this->authorize('update', $patient);
    
        return view('patients.upload',compact('patient'));
    }

    public function uploadPicture(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'patient_picture' => 'nullable|image|max:4096',
            'patient_picture_webcam' => 'nullable|string',
        ]);
    
        $patient = Patient::findOrFail($request->patient_id);
    
        // Create Image Manager instance (v3)
        $manager = new ImageManager(new Driver());

        // Delete old images
        $dir = "patient_pictures/{$patient->id}";
        Storage::disk('public')->deleteDirectory($dir);
        Storage::disk('public')->makeDirectory($dir);
    
        // Load image from file upload
        if ($request->hasFile('patient_picture')) {
            $img = $manager->read($request->file('patient_picture')->getRealPath());
        }
        // Load image from webcam base64
        elseif ($request->patient_picture_webcam) {
            $data = $request->patient_picture_webcam;
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
        $patient->patient_picture = "$dir/profile.jpg";
        $patient->save();
    
        return back()->with('success', 'Profile picture updated successfully!');
    }
    

    public function restore($id)
    {
        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->restore();

        return redirect(guard_route('patients.index'))
            ->with('success','Patient restored successfully');
    }

    protected function handleSignature($request, $patient)
    {
        $dir = "patient_signatures/{$patient->id}";
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
    
        // 3. Existing signature
        } elseif ($patient->patient_signature && Storage::disk('public')->exists($patient->patient_signature)) {
            $img = $manager->read(storage_path("app/public/" . $patient->patient_signature));
            \Log::info('Using existing patient signature: ' . $patient->patient_signature);
    
        // 4. Create blank canvas
        } else {
            $gdResource = imagecreatetruecolor(150, 100);
            $white = imagecolorallocate($gdResource, 255, 255, 255);
            imagefill($gdResource, 0, 0, $white);
    
            $img = $manager->read($gdResource);
    
            $text = $request->first_name . ' ' . $request->surname;
            $img->text($text, 75, 50, function ($font) {
                $font->filename(public_path('assets/fonts/OpenSans-Regular.ttf'));
                $font->size(18);
                $font->color('#000000');
                $font->align('center');
                $font->valign('middle');
            });
        }
    
        // Resize image
        $img->resize(150, 100);
    
        $filename = "signature.png";
        $path = "$dir/$filename";
    
        // Save image
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
    
        $img->save(storage_path("app/public/" . $path));
    
        return $path;
    }
    
    
}