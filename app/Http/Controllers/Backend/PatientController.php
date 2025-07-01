<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class PatientController extends Controller
{ 
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
    public function index(): View
    {
        $patients = Patient::latest()->paginate(5);

        return view('patients.index',compact('patients'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('patients.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'dob'               => 'required|date',
            'gender'            => 'required|in:Male,Female,Other',
            'phone'             => 'required|string|max:20',
            'email'             => 'nullable|email|max:255',
            'address'           => 'required|string',
            'emergency_contact' => 'nullable|string|max:255',
            'medical_history'   => 'nullable|string',
            'insurance'         => 'nullable|string|max:255',
        ]);

    
        Patient::create($request->all());
    
        return redirect()->route('patients.index')
                        ->with('success','patient created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient): View
    {
        return view('patients.show',compact('patient'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient): View
    {
        return view('patients.edit',compact('patient'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'dob'               => 'required|date',
            'gender'            => 'required|in:Male,Female,Other',
            'phone'             => 'required|string|max:20',
            'email'             => 'nullable|email|max:255',
            'address'           => 'required|string',
            'emergency_contact' => 'nullable|string|max:255',
            'medical_history'   => 'nullable|string',
            'insurance'         => 'nullable|string|max:255',
        ]);
    
        $patient->update($request->all());
    
        return redirect()->route('patients.index')
                        ->with('success','patient updated successfully');
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