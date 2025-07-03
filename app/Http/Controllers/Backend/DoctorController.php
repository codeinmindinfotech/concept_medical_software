<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DoctorRequest;

    
class DoctorController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:doctor-list|doctor-create|doctor-edit|doctor-delete', ['only' => ['index','show']]);
         $this->middleware('permission:doctor-create', ['only' => ['create','store']]);
         $this->middleware('permission:doctor-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:doctor-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $pageTitle = "Doctor List";
        $doctors = Doctor::latest()->paginate(5);
        if ($request->ajax()) {
            return view('doctors.list', compact('doctors'))->render();
        } 

        return view('doctors.index',compact('doctors','pageTitle'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $pageTitle = "Create Doctor";
        return view('doctors.create',compact('pageTitle'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        Doctor::create($validated);
    
        return redirect()->route('doctors.index')
                        ->with('success','Doctor created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor): View
    {
        $pageTitle = "Show Doctor";
        return view('doctors.show',compact('doctor','pageTitle'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor): View
    {
        $pageTitle = "Edit Doctor";
        return view('doctors.edit',compact('doctor','pageTitle'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(DoctorRequest $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validated();
        $doctor->update($validated);
    
        return redirect()->route('doctors.index')
                        ->with('success','doctor updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->delete();
    
        return redirect()->route('doctors.index')
                        ->with('success','doctor deleted successfully');
    }
}