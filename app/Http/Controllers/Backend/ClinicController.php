<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicRequest;
use App\Models\Clinic;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;


class ClinicController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:clinic-list|clinic-create|clinic-edit|clinic-delete', ['only' => ['index','show']]);
        $this->middleware('permission:clinic-create', ['only' => ['create','store']]);
        $this->middleware('permission:clinic-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:clinic-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View|string
    {
        $pageTitle = "Clinics List";
        $clinics = Clinic::companyOnly()->latest()->get();

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

    public function store(ClinicRequest $request, PasswordResetService $resetService): JsonResponse
    {
        $data = $this->extractDayFields($request);
        $clinic = Clinic::create($data);
        assignRoleToGuardedModel($clinic, 'clinic', 'clinic');

        if ($clinic) {
            try {
                $resetService->sendResetLink($clinic, 'clinic', 'clinics');     
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'redirect' => guard_route('clinics.index'),
            'message' => 'Clinic created successfully. A password reset link has been sent.',
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
            'redirect' =>guard_route('clinics.index'),
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

        return redirect(guard_route('clinics.index'))->with('success', 'Clinic deleted successfully.');
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

    public function schedule(Clinic $clinic)
    {
        $days = [
            'mon' => 1, 'tue' => 2, 'wed' => 3,
            'thu' => 4, 'fri' => 5, 'sat' => 6, 'sun' => 0
        ];

        $result = [];

        foreach ($days as $day => $dow) {

            // If clinic not active this day → block
            if ($clinic->$day == 0) {
                $result[$day] = [
                    'active'      => false,
                    'interval'    => null,
                    'slots'       => [],
                    'business'    => [],
                ];
                continue;
            }

            $interval = intval($clinic->{$day . '_interval'} ?? 15);

            $ranges = [];

            // AM
            if ($clinic->{$day.'_start_am'} && $clinic->{$day.'_finish_am'}) {
                $ranges[] = [
                    'start' => $clinic->{$day.'_start_am'},
                    'end'   => $clinic->{$day.'_finish_am'},
                ];
            }

            // PM
            if ($clinic->{$day.'_start_pm'} && $clinic->{$day.'_finish_pm'}) {
                $ranges[] = [
                    'start' => $clinic->{$day.'_start_pm'},
                    'end'   => $clinic->{$day.'_finish_pm'},
                ];
            }

            $result[$day] = [
                'active'      => true,
                'interval'    => $interval,
                'business'    => $ranges,
                'dow'         => $dow,
            ];
        }

        return response()->json($result);
    }
}
