<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicRequest;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ClinicController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:clinic-list|clinic-create|clinic-edit|clinic-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:clinic-create', ['only' => ['create','store']]);
    //     $this->middleware('permission:clinic-edit', ['only' => ['edit','update']]);
    //     $this->middleware('permission:clinic-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request): View|string
    {
        $pageTitle = "Clinics List";
        $clinics = Clinic::latest()->get();

        if ($request->ajax()) {
            return view('clinics.list', compact('clinics'))->render();
        }

        return view('clinics.index', compact('clinics', 'pageTitle'));
    }

    public function create(): View
    {
        $pageTitle = "Create Clinic";
        return view('clinics.create', compact('pageTitle'));
    }

    public function store(ClinicRequest $request): JsonResponse
    {
        $data = $this->extractDayFields($request);
        $clinic = Clinic::create($data);
        assignRoleToGuardedModel($clinic, 'clinic', 'clinic');
        return response()->json([
            'redirect' => route('clinics.index'),
            'message' => 'Clinic created successfully',
        ]);
    }

    public function edit(Clinic $clinic): View
    {
        $pageTitle = "Edit Clinic";
        return view('clinics.edit', compact('clinic', 'pageTitle'));
    }

    public function update(ClinicRequest $request, Clinic $clinic): JsonResponse
    {
        $data = $this->extractDayFields($request);
        $clinic->update($data);

        return response()->json([
            'redirect' => route('clinics.index'),
            'message' => 'Clinic updated successfully',
        ]);
    }

    public function show(Clinic $clinic): View
    {
        $pageTitle = "Show Clinic";
        return view('clinics.show', compact('clinic', 'pageTitle'));
    }

    public function destroy(Clinic $clinic): RedirectResponse
    {
        $clinic->delete();

        return redirect()->route('clinics.index')->with('success', 'Clinic deleted successfully.');
    }

    protected function extractDayFields(Request $request): array
    {
        $data = $request->validated();
        $days = ['mon','tue','wed','thu','fri','sat','sun'];

        foreach ($days as $day) {
            $data[$day] = $request->boolean($day);
        }

        return $data;
    }
}
