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
        return response()->json([
            'redirect' => guard_route('clinics.index'),
            'message' => 'Clinic created successfully, and clinic database initialized.',
        ]);
    }

    public function edit($clinicId): View
    {
        $clinic = Clinic::findOrFail($clinicId);
        $pageTitle = "Edit Clinic";
        return view('clinics.edit', compact('clinic', 'pageTitle'));
    }

    public function update(ClinicRequest $request, $clinicId): JsonResponse
    {
        $data = $this->extractDayFields($request);
        $clinic = Clinic::findOrFail($clinicId);
        $clinic->update($data);

        return response()->json([
            'redirect' => guard_route('clinics.index'),
            'message' => 'Clinic updated successfully',
        ]);
    }

    public function show($clinicId): View
    {
        $pageTitle = "Show Clinic";
        return view('clinics.show', compact('clinic', 'pageTitle'));
    }

    public function destroy($clinicId): RedirectResponse
    {
        $clinic = Clinic::findOrFail($clinicId);
        $clinic->delete();

        return redirect()->guard_route('clinics.index')->with('success', 'Clinic deleted successfully.');
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
