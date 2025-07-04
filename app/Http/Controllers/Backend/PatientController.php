<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Models\Backend\Insurance;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{ 
    use DropdownTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:patient-list|patient-create|patient-edit|patient-delete', ['only' => ['index','show']]);
         $this->middleware('permission:patient-create', ['only' => ['create','store']]);
         $this->middleware('permission:patient-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:patient-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $pageTitle = "Patients List";

        $patients = Patient::latest()->paginate(5);
        if ($request->ajax()) {
            return view('patients.list', compact('patients'))->render();
        }

        return view('patients.index',compact('patients','pageTitle'));
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
        return view('patients.create', compact('pageTitle','titles','insurances', 'contactMethods','doctors'));
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

        Patient::create($validated);
    
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
        $pageTitle = "Edit Patient";
        extract($this->getCommonDropdowns());
        $doctors = Doctor::orderBy('name')->get(); 
        $insurances = Insurance::orderBy('code')->get();
        return view('patients.edit',compact('patient','pageTitle','titles','insurances', 'contactMethods','doctors'));
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
        $validated = $request->validated();
    
        // Handle checkbox fields (they won't be present if unchecked)
        $validated['rip'] = $request->has('rip');
        $validated['sms_consent'] = $request->has('sms_consent');
        $validated['email_consent'] = $request->has('email_consent');

        // Update the patient
        $patient->update($validated);
    
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
        $patient->delete();
    
        return redirect()->route('patients.index')
                        ->with('success','Patient deleted successfully');
    }
}