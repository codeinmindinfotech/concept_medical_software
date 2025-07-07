<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $user = auth()->user();
        $patient = $user->hasRole('patient') ? $user->userable : null;

        return view('dashboard.index', compact('patient'));
    }
}