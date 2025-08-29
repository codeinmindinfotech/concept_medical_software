<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Models\DropDown;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DropDownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:dropdown-list|dropdown-create|dropdown-edit|dropdown-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:dropdown-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:dropdown-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:dropdown-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|string
    {
        $pageTitle = "DropDown List";
        $dropdowns = DropDown::latest()->get();
        if ($request->ajax()) {
            return view('dropdowns.list', compact('dropdowns'))->render();
        } 
        return view('dropdowns.index',compact('dropdowns','pageTitle'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $pageTitle = "Create DropDown";
        return view('dropdowns.create',compact('pageTitle'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => [
                'required',
                'unique:drop_downs,code',
                'regex:/^[A-Z0-9_]+$/',
                'max:255'
            ],
            'name' => 'required|string']);
        Dropdown::create($request->all());
        return redirect()->guard_route('dropdowns.index')
                        ->with('success','DropDown created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function show(DropDown $dropdown): View
    {
        $pageTitle = "Show DropDown";
        return view('dropdowns.show',compact('dropdown','pageTitle'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function edit(DropDown $dropdown): View
    {
        $pageTitle = "Edit DropDown";
        return view('dropdowns.edit',compact('dropdown','pageTitle'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DropDown $dropdown): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $dropdown->update($request->only('name'));
    
        return redirect()->guard_route('dropdowns.index')
                        ->with('success','dropdown updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\dropdown  $dropdown
     * @return \Illuminate\Http\Response
     */
    public function destroy(DropDown $dropdown): RedirectResponse
    {
        $dropdown->delete();
    
        return redirect()->guard_route('dropdowns.index')
                        ->with('success','dropdown deleted successfully');
    }
}
