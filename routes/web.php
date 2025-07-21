<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend\AudioController;
use App\Http\Controllers\Backend\ChargeCodeController;
use App\Http\Controllers\Backend\ChargeCodePriceController;
use App\Http\Controllers\Backend\ClinicController;
use App\Http\Controllers\Backend\WaitingListController;
use App\Http\Controllers\Backend\ConsultantController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PatientController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DoctorController;
use App\Http\Controllers\Backend\FeeNoteController;
use App\Http\Controllers\Backend\InsuranceController;
use App\Http\Controllers\Backend\Master\DropDownController;
use App\Http\Controllers\Backend\Master\DropDownValueController;
use App\Http\Controllers\Backend\PatientAudioController;
use App\Http\Controllers\Backend\PatientHistoryController;
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
    Route::resource('clinics', ClinicController::class);
    Route::resource('chargecodes', ChargeCodeController::class);
    Route::resource('chargecodeprices', ChargeCodePriceController::class);
    Route::resource('audios', AudioController::class);

    Route::get('/chargecodeprices/{insurance}/adjust-prices', [ChargeCodePriceController::class, 'showAdjustPrices'])->name('chargecodeprices.adjust-prices');
    Route::post('/chargecodeprices/{insurance}/adjust-prices', [ChargeCodePriceController::class, 'processAdjustPrices'])->name('chargecodeprices.process-adjust-prices');

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

    Route::prefix('patients/{patient}/history')->group(function () {
        Route::get('/', [PatientHistoryController::class, 'index'])->name('patients.history.index');
        Route::get('/create', [PatientHistoryController::class, 'create'])->name('patients.history.create');
        Route::post('/', [PatientHistoryController::class, 'store'])->name('patients.history.store');
        Route::get('/{history}/edit', [PatientHistoryController::class, 'edit'])->name('patients.history.edit');
        Route::put('/{history}', [PatientHistoryController::class, 'update'])->name('patients.history.update');
        Route::delete('/{history}', [PatientHistoryController::class, 'destroy'])->name('patients.history.destroy');
    });
    
    Route::get('/patients/dashboard/{patient}', [PatientController::class, 'patient_dashboard'])->name('patients.patient_dashboard');

    Route::prefix('patients/{patient}')->name('waiting-lists.')->group(function () {
        Route::post('waiting-lists', [WaitingListController::class, 'store'])->name('store');
        Route::get('waiting-lists', [WaitingListController::class, 'index'])->name('index');
        Route::get('waiting-lists/{waitingList}', [WaitingListController::class, 'show'])->name('show');
        Route::put('waiting-lists/{waitingList}', [WaitingListController::class, 'update'])->name('update');
        Route::delete('waiting-lists/{waitingList}', [WaitingListController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('patients/{patient}')->name('feenotes.')->group(function () {
        Route::post('feenotes', [FeeNoteController::class, 'store'])->name('store');
        Route::get('feenotes', [FeeNoteController::class, 'index'])->name('index');
        Route::get('feenotes/{feenote}', [FeeNoteController::class, 'show'])->name('show');
        Route::put('feenotes/{feenote}', [FeeNoteController::class, 'update'])->name('update');
        Route::delete('feenotes/{feenote}', [FeeNoteController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('patients/{patient}/audio')->group(function () {
        Route::get('/', [PatientAudioController::class, 'index'])->name('patients.audio.index');
        Route::get('/create', [PatientAudioController::class, 'create'])->name('patients.audio.create');
        Route::post('/', [PatientAudioController::class, 'store'])->name('patients.audio.store');
        Route::delete('/{audio}', [PatientAudioController::class, 'destroy'])->name('patients.audio.destroy');
    });


   
});