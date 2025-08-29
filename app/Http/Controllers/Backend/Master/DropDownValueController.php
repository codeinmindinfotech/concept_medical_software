<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Models\DropDown;
use App\Models\DropDownValue;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DropDownValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:dropdownvalue-list|dropdownvalue-create|dropdownvalue-edit|dropdownvalue-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:dropdownvalue-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:dropdownvalue-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:dropdownvalue-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $dropDownId): View|string
    {
        $pageTitle = "DropDownValue List";
       
        $values = DropDownValue::with('dropdown')
            ->where('drop_down_id', $dropDownId)
            ->latest()
            ->get();

        if ($request->ajax()) {
            return view('dropdownvalues.list', compact('values','dropDownId'))->render();
        }        

        return view('dropdownvalues.index', compact('values', 'pageTitle', 'dropDownId'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($dropdownId): View
    {
        $pageTitle = "Create DropDownValue";
        $dropdown = DropDown::findOrFail($dropdownId);
        return view('dropdownvalues.create',compact('pageTitle','dropdown','dropdownId'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $dropdownId): RedirectResponse
    {
        $request->validate(['value' => 'required|string|max:255']);

        DropDownValue::create([
            'drop_down_id' => $dropdownId,
            'value' => $request->value,
        ]);
    
        return redirect()->guard_route('dropdownvalues.index',$dropdownId)
                        ->with('success','DropDownValue created successfully.');
    }
    
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\dropdownvalue  $dropdownvalue
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $dropDownId): View
    {
        $pageTitle = "Edit DropDownValue";
        $value = DropDownValue::findOrFail($id);
        $dropdown = DropDown::findOrFail($dropDownId);
        return view('dropdownvalues.edit',compact('value', 'dropdown','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate(['value' => 'required|string|max:255']);

        $value = DropDownValue::findOrFail($id);
        $value->update(['value' => $request->value]);

        return redirect()->guard_route('dropdownvalues.index', $value->drop_down_id)
                        ->with('success','dropdown updated successfully');
    }
    
}
