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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:consultant-list|consultant-create|consultant-edit|consultant-delete', ['only' => ['index','show']]);
         $this->middleware('permission:consultant-create', ['only' => ['create','store']]);
         $this->middleware('permission:consultant-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:consultant-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $pageTitle = "Consultants List";

        $consultants = Consultant::latest()->paginate(5);
        if ($request->ajax()) {
            return view('consultants.list', compact('consultants'))->render();
        }

        return view('consultants.index',compact('consultants','pageTitle'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $pageTitle = "Consultants Create";
        $insurances = Insurance::all();
        return view('consultants.create', compact('pageTitle','insurances'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConsultantRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('consultants', 'public');
        }

        $consultant = Consultant::create($data);

        $consultant->insurances()->sync($request->input('insurance_id', []));

        return response()->json([
            'redirect' => route('consultants.index'),
            'message' => 'Consultant created successfully',
        ]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\consultant  $consultant
     * @return \Illuminate\Http\Response
     */
    public function show(Consultant $consultant): View
    {
        $pageTitle = "Show Consultant";
        return view('consultants.show',compact('consultant','pageTitle'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\consultant  $consultant
     * @return \Illuminate\Http\Response
     */
    public function edit(Consultant $consultant): View
    {
        $pageTitle = "Edit Consultant";
        $insurances = Insurance::all();

        return view('consultants.edit',compact('consultant','pageTitle','insurances'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\consultant  $consultant
     * @return \Illuminate\Http\Response
     */
    public function update(ConsultantRequest $request, Consultant $consultant): JsonResponse
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

        $consultant->update($data);

        // Sync selected insurance IDs
        $consultant->insurances()->sync($request->input('insurance_id', []));

        return response()->json([
            'redirect' => route('consultants.index'),
            'message' => 'Consultant updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Consultant  $consultant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consultant $consultant): RedirectResponse
    {
        $consultant->delete();
    
        return redirect()->route('consultants.index')
                        ->with('success','Consultant deleted successfully');
    }
}