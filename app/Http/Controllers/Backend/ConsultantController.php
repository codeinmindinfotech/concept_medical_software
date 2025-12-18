<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultantRequest;
use App\Models\Backend\Insurance;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ConsultantController extends Controller
{
    public function index(Request $request): View|string
    {
        $this->authorize('viewAny', Consultant::class);

        $pageTitle = "Consultants List";
        $consultants = Consultant::companyOnly()->latest()->paginate(5);

        if ($request->ajax()) {
            return view('consultants.list', compact('consultants'))->render();
        }

        return view(guard_view('consultants.index', 'patient_admin.consultant.index'), compact('consultants', 'pageTitle'));
    }

    public function create(): View
    {
        $this->authorize('create', Consultant::class);

        $pageTitle = "Consultants Create";
        $insurances = Insurance::companyOnly()->get();
        return view(guard_view('consultants.create', 'patient_admin.consultant.create'), compact('pageTitle', 'insurances'));
    }

    public function store(ConsultantRequest $request): JsonResponse
    {
        $this->authorize('create', Consultant::class);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('consultants', 'public');
        }

        $consultant = Consultant::create($data);
        $consultant->insurances()->sync($request->input('insurance_id', []));

        return response()->json([
            'redirect' =>guard_route('consultants.index'),
            'message' => 'Consultant created successfully',
        ]);
    }

    public function show(Consultant $consultant): View
    {
        $this->authorize('view', $consultant);

        $pageTitle = "Show Consultant";
        return view(guard_view('consultants.show', 'patient_admin.consultant.show'), compact('consultant', 'pageTitle'));
    }

    public function edit(Consultant $consultant): View
    {
        $this->authorize('update', $consultant);

        $pageTitle = "Edit Consultant";
        $insurances = Insurance::companyOnly()->get();
        return view(guard_view('consultants.edit', 'patient_admin.consultant.edit'), compact('consultant', 'pageTitle', 'insurances'));
    }

    public function update(ConsultantRequest $request, Consultant $consultant): JsonResponse
    {
        //$this->authorize('update', $consultant);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($consultant->image) {
                Storage::disk('public')->delete($consultant->image);
            }
            $data['image'] = $request->file('image')->store('consultants', 'public');
        } else {
            unset($data['image']);
        }

        $consultant->update($data);
        $consultant->insurances()->sync($request->input('insurance_id', []));

        return response()->json([
            'redirect' =>guard_route('consultants.index'),
            'message' => 'Consultant updated successfully',
        ]);
    }

    public function destroy(Consultant $consultant): RedirectResponse
    {
        $this->authorize('delete', $consultant);

        $consultant->delete();

        return redirect(guard_route('consultants.index'))->with('success', 'Consultant deleted successfully');
    }
}
