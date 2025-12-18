<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsuranceRequest;
use App\Models\Backend\Insurance;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Traits\DropdownTrait;
use Illuminate\Http\JsonResponse;

class InsuranceController extends Controller
{ 
    use DropdownTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $pageTitle = "Insurances List";

        $insurances = Insurance::companyOnly()->latest()->paginate(5);
        if ($request->ajax()) {
            return view('insurances.list', compact('insurances'))->render();
        }

        return view(guard_view('insurances.index', 'patient_admin.insurance.index'),compact('insurances','pageTitle'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $pageTitle = "Insurances Create";
        return view(guard_view('insurances.create', 'patient_admin.insurance.create'), compact('pageTitle'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InsuranceRequest $request): JsonResponse
    {
        $validated = $request->validated();
    
        Insurance::create($validated);
        return response()->json([
            'redirect' =>guard_route('insurances.index'),
            'message' => 'insurance created successfully',
        ]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function show(Insurance $insurance): View
    {
        $pageTitle = "Show Insurance";
        return view(guard_view('insurances.show', 'patient_admin.insurance.show'),compact('insurance','pageTitle'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function edit(Insurance $insurance): View
    {
        $pageTitle = "Edit Insurance";
        return view(guard_view('insurances.edit', 'patient_admin.insurance.edit'),compact('insurance','pageTitle'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function update(InsuranceRequest $request, Insurance $insurance): JsonResponse
    {
        $validated = $request->validated();
    
        $insurance->update($validated);
    
        return response()->json([
            'redirect' =>guard_route('insurances.index'),
            'message' => 'Insurance updated successfully',
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Insurance $insurance): RedirectResponse
    {
        $insurance->delete();
    
        return redirect(guard_route('insurances.index'))
                        ->with('success','Insurance deleted successfully');
    }
}