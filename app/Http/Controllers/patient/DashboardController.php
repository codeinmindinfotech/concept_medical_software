<?php

namespace App\Http\Controllers\patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user();
        return view('patient_admin.patient-dashboard', compact('patient'));
        
    }
}
