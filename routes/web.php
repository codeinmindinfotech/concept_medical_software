<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PatientController;
use App\Http\Controllers\Backend\DashboardController;

Route::get('/', function () {
    return view('frontend.index');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('patients', PatientController::class);
});