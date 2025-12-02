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
use Illuminate\Support\Facades\Password;

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
        $query = Doctor::companyOnly();

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
        return view('doctors.create',compact('contactTypes','paymentMethods'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorRequest $request, PasswordResetService $resetService): JsonResponse
    {
        $this->authorize('create', Doctor::class);
        $validated = $request->validated();
        $doctor = Doctor::create($validated);
        assignRoleToGuardedModel($doctor, 'doctor', 'doctor');

        if ($doctor) {
            try {
                $resetService->sendResetLink($doctor, 'doctor', 'doctors');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
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
        return view(guard_view('doctors.edit', 'patient_admin.doctor.edit'),compact('doctor','contactTypes','paymentMethods'));
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
}