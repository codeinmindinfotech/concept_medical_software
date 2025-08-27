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
        if (!user_can('doctor-list')) {
            abort(403, 'Unauthorized access.');
        }
        $doctors = Doctor::latest()->get();
        if ($request->ajax()) {
            return view('doctors.list', compact('doctors'))->render();
        } 

        return view('doctors.index',compact('doctors'));
    }
    
    public function create(): View
    {
        if (!user_can('doctor-create')) {
            abort(403, 'Unauthorized access.');
        }
        extract($this->getCommonDropdowns());
        return view('doctors.create',compact('contactTypes','paymentMethods'));
    }
    
    public function store(DoctorRequest $request): JsonResponse
    {
        if (!user_can('doctor-create')) {
            abort(403, 'Unauthorized access.');
        }
        $validated = $request->validated();
        Doctor::create($validated);
    
        return response()->json([
            'redirect' => route('doctors.index'),
            'message' => 'Doctor created successfully',
        ]);
    }
    
    public function show(Doctor $doctor): View
    {
        if (!user_can('doctor-list')) {
            abort(403, 'Unauthorized access.');
        }
        return view('doctors.show',compact('doctor'));
    }
    
    public function edit(Doctor $doctor): View
    {
        if (!user_can('doctor-edit')) {
            abort(403, 'Unauthorized access.');
        }
        extract($this->getCommonDropdowns());
        return view('doctors.edit',compact('doctor','contactTypes','paymentMethods'));
    }

    public function update(DoctorRequest $request, Doctor $doctor): JsonResponse 
    {
        if (!user_can('doctor-edit')) {
            abort(403, 'Unauthorized access.');
        }
        $validated = $request->validated();
        $doctor->update($validated);
        return response()->json([
            'redirect' => route('doctors.index'),
            'message' => 'Doctor updated successfully',
        ]);
    }
    
    public function destroy(Doctor $doctor): RedirectResponse
    {
        if (!user_can('doctor-delete')) {
            abort(403, 'Unauthorized access.');
        }
        $doctor->delete();
    
        return redirect()->route('doctors.index')
                        ->with('success','doctor deleted successfully');
    }
}