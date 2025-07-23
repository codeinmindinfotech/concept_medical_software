<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
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
        $user = auth()->user();
        $patient = $user->hasRole('patient') ? $user->userable : null;

        if ($user->hasRole('superadmin')) {
            return view('dashboard.index', compact('patient'));
        }
    
        if ($user->hasRole('patient')) {
            $patient = $user->userable; // Assuming morph relation to Patient model
            return redirect()->route('patients.patient_dashboard', $patient->id);
        }

        abort(403, 'Unauthorized');
    }
}