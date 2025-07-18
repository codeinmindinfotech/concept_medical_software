<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DoctorRequest;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;

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
        $doctors = Doctor::latest()->get();
        if ($request->ajax()) {
            return view('doctors.list', compact('doctors'))->render();
        } 

        return view('doctors.index',compact('doctors'));
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
        return view('doctors.create',compact('contactTypes','paymentMethods'));
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
        Doctor::create($validated);
    
        return response()->json([
            'redirect' => route('doctors.index'),
            'message' => 'Doctor created successfully',
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
        return view('doctors.show',compact('doctor'));
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
        return view('doctors.edit',compact('doctor','contactTypes','paymentMethods'));
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
        return response()->json([
            'redirect' => route('doctors.index'),
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
    
        return redirect()->route('doctors.index')
                        ->with('success','doctor deleted successfully');
    }
}