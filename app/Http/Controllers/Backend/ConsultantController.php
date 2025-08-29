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
        $pageTitle = "Consultants List";
        $consultants = Consultant::latest()->paginate(5);

        if ($request->ajax()) {
            return view('consultants.list', compact('consultants'))->render();
        }

        return view('consultants.index', compact('consultants', 'pageTitle'));
    }

    public function create(): View
    {
        $pageTitle = "Consultants Create";
        $insurances = Insurance::all();
        return view('consultants.create', compact('pageTitle', 'insurances'));
    }

    public function store(ConsultantRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('consultants', 'public');
        }

        $consultant = Consultant::create($data);
        $consultant->insurances()->sync($request->input('insurance_id', []));

        return response()->json([
            'redirect' => guard_route('consultants.index'),
            'message' => 'Consultant created successfully',
        ]);
    }

    public function show($consultantId): View
    {
        $consultant = Consultant::findOrFail($consultantId);
        $pageTitle = "Show Consultant";
        return view('consultants.show', compact('consultant', 'pageTitle'));
    }

    public function edit($consultantId): View
    {
        $consultant = Consultant::findOrFail($consultantId);
        $pageTitle = "Edit Consultant";
        $insurances = Insurance::all();
        return view('consultants.edit', compact('consultant', 'pageTitle', 'insurances'));
    }

    public function update(ConsultantRequest $request, $consultantId): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($consultant->image) {
                Storage::disk('public')->delete($consultant->image);
            }
            $data['image'] = $request->file('image')->store('consultants', 'public');
        } else {
            unset($data['image']);
        }

        $consultant = Consultant::findOrFail($consultantId);
        $consultant->update($data);
        $consultant->insurances()->sync($request->input('insurance_id', []));

        return response()->json([
            'redirect' => guard_route('consultants.index'),
            'message' => 'Consultant updated successfully',
        ]);
    }

    public function destroy($consultantId): RedirectResponse
    {
        $consultant = Consultant::findOrFail($consultantId);
        $consultant->delete();

        return redirect()->guard_route('consultants.index')->with('success', 'Consultant deleted successfully');
    }
}
