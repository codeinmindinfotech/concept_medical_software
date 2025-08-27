<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View|RedirectResponse
    {   
        $patient = null;
        return view('dashboard.index', compact('patient'));
        
    }
    
}