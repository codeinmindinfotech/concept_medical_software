<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Models\Backend\Insurance;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Traits\DropdownTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{ 
    use DropdownTrait;
    protected $userService;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;            
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $user = auth()->user();

        if ($user->hasRole('patient')) {
            // Restrict to logged-in patient only
            $patients = Patient::with('title')->where('id', $user->userable_id)->paginate(1);
        } else {
            // Admins can search all patients
            $query = Patient::with('title')->latest();

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

            $patients = $query->paginate(10)->withQueryString();
        }

        if ($request->ajax()) {
            return view('patients.list', compact('patients'))->render();
        }

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $pageTitle = "Patients Create";
        extract($this->getCommonDropdowns());
        $doctors = Doctor::orderBy('name')->get(); 
        $insurances = Insurance::orderBy('code')->get(); 
        return view('patients.create', compact('pageTitle','titles','insurances', 'preferredContact','doctors'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientRequest $request): JsonResponse
    {
        $validated = $request->validated();
    
        $validated['rip'] = $request->has('rip');
        $validated['sms_consent'] = $request->has('sms_consent');
        $validated['email_consent'] = $request->has('email_consent');

        $patient = Patient::create($validated);

        // Create linked user for patient
        $this->userService->createUserForModel(
            $patient->first_name . ' ' . $patient->surname,
            $patient->email,
            'patient',
            $patient->id,
            Patient::class
        );

        return response()->json([
            'redirect' => route('patients.index'),
            'message' => 'Patient created successfully',
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
        $this->authorize('view', $patient);
        $pageTitle = "Show Patient";
        return view('patients.show',compact('patient','pageTitle'));
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
        $doctors = Doctor::orderBy('name')->get(); 
        $insurances = Insurance::orderBy('code')->get();
        return view('patients.edit',compact('patient','pageTitle','titles','insurances', 'preferredContact','doctors'));
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
        
        // Sync user details after patient update
        $user = $this->userService->updateUserForModel(
            $patient->id,
            Patient::class,
            [
                'name' => $patient->first_name . ' ' . $patient->surname,
                'email' => $patient->email,
                'role' => 'patient',
            ]
        );
        
        // If no linked user found, create one
        if (!$user) {
            $this->userService->createUserForModel(
                $patient->first_name . ' ' . $patient->surname,
                $patient->email,
                'patient',
                $patient->id,
                Patient::class
            );
        }

        return response()->json([
            'redirect' => route('patients.index'),
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
    
        return redirect()->route('patients.index')
                        ->with('success','Patient deleted successfully');
    }

    public function uploadPicture(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'patient_picture' => 'required|image|max:2048',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        if ($request->hasFile('patient_picture')) {
            $file = $request->file('patient_picture');

            // Delete old picture if exists
            if ($patient->patient_picture && Storage::disk('public')->exists($patient->patient_picture)) {
                Storage::disk('public')->delete($patient->patient_picture);
            }

            // Create new filename like picture_123.jpg
            $filename = 'picture_' . $patient->id . '.' . $file->getClientOriginalExtension();

            // Save the file in public/patient_pictures
            $path = $file->storeAs('patient_pictures', $filename, 'public');

            $patient->patient_picture = $path;
            $patient->save();
        }

        return response()->json([
            'message' => 'Profile picture updated successfully.',
            'image_url' => asset('storage/' . $patient->patient_picture),
        ]);
    }

}