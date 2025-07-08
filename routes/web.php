<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\ConsultantController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PatientController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DoctorController;
use App\Http\Controllers\Backend\InsuranceController;
use App\Http\Controllers\Backend\Master\DropDownController;
use App\Http\Controllers\Backend\Master\DropDownValueController;
use App\Http\Controllers\Backend\PatientNoteController;
use App\Http\Controllers\Backend\PatientPhysicalController;

Route::get('/', function () {
    return view('frontend.index');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::resource('dashboard', DashboardController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('insurances', InsuranceController::class);
    Route::resource('consultants', ConsultantController::class);

    // dropdown  parent
    Route::resource('dropdowns', DropDownController::class);
    
    // dropdown  parent child
    Route::get('/dropdownvalues/list/{dropDownId}', [DropDownValueController::class, 'index'])->name('dropdownvalues.index');
    Route::get('/dropdownvalues/create/{dropDownId}', [DropDownValueController::class, 'create'])->name('dropdownvalues.create');
    Route::post('/dropdownvalues/store/{dropDownId}', [DropDownValueController::class, 'store'])->name('dropdownvalues.store');
    Route::get('/dropdownvalues/{id}/edit/{dropDownId}', [DropDownValueController::class, 'edit'])->name('dropdownvalues.edit');
    Route::put('/dropdownvalues/{id}/update', [DropDownValueController::class, 'update'])->name('dropdownvalues.update');

    Route::prefix('patients/{patient}/notes')->group(function () {
        Route::get('/', [PatientNoteController::class, 'index'])->name('patients.notes.index');
        Route::get('/create', [PatientNoteController::class, 'create'])->name('patients.notes.create');
        Route::post('/', [PatientNoteController::class, 'store'])->name('patients.notes.store');
        Route::get('/{note}/edit', [PatientNoteController::class, 'edit'])->name('patients.notes.edit');
        Route::put('/{note}', [PatientNoteController::class, 'update'])->name('patients.notes.update');
        Route::post('/{note}/toggle-completed', [PatientNoteController::class, 'toggleCompleted'])->name('patients.notes.toggleCompleted');
        Route::delete('/{note}', [PatientNoteController::class, 'destroy'])->name('patients.notes.destroy');
    });

    Route::prefix('patients/{patient}/physical')->group(function () {
        Route::get('/', [PatientPhysicalController::class, 'index'])->name('patients.physical.index');
        Route::get('/create', [PatientPhysicalController::class, 'create'])->name('patients.physical.create');
        Route::post('/', [PatientPhysicalController::class, 'store'])->name('patients.physical.store');
        Route::get('/{physical}/edit', [PatientPhysicalController::class, 'edit'])->name('patients.physical.edit');
        Route::put('/{physical}', [PatientPhysicalController::class, 'update'])->name('patients.physical.update');
        Route::delete('/{physical}', [PatientPhysicalController::class, 'destroy'])->name('patients.physical.destroy');
    });
    
    
});