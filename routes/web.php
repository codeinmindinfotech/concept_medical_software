<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Backend\AppointmentController;
use App\Http\Controllers\Backend\AudioController;
use App\Http\Controllers\Backend\ChargeCodeController;
use App\Http\Controllers\Backend\ChargeCodePriceController;
use App\Http\Controllers\Backend\ClinicController;
use App\Http\Controllers\Backend\CommunicationController;
use App\Http\Controllers\Backend\CompanyController;
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
use App\Http\Controllers\Backend\PlannerController;
use App\Http\Controllers\Backend\RecallController;
use App\Http\Controllers\Backend\RecallNotificationController;
use App\Http\Controllers\Backend\SmsController;
use App\Http\Controllers\Backend\TaskController;
use App\Http\Controllers\Backend\TaskFollowupController;
use App\Http\Controllers\Auth\SuperadminLoginController;
use App\Http\Controllers\Backend\ConfigurationController;
use App\Http\Controllers\Backend\DoctorMessageController;
use App\Http\Controllers\Backend\Master\DocumentTemplateController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\PasswordChangeController;
use App\Http\Controllers\BroadcastController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

Route::get('/', function () {
    return view('frontend.index');
});

Auth::routes();

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::post('/custom-password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('custom.password.email');

Route::prefix('superadmin')->group(function () {
    Route::get('login', [SuperadminLoginController::class, 'showLoginForm'])->name('superadmin.login');
    Route::post('login', [SuperadminLoginController::class, 'login'])->name('superadmin.login.submit');
    Route::post('logout', [SuperadminLoginController::class, 'logout'])->name('superadmin.logout');
});

Route::post('/broadcasting/auth', [BroadcastController::class, 'authenticate'])->middleware('auth.multi');


Route::group(['middleware' => ['auth']], function () {
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('documents', DocumentTemplateController::class);
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::post('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
        
        Route::get('/send-notification', [NotificationController::class, 'showForm'])->name('notifications.form')->middleware('auth:web');
        Route::post('/send-notification', [NotificationController::class, 'sendToCompany'])->name('notifications.send')->middleware('auth:web');

        Route::resource('configurations', ConfigurationController::class)->except(['show']);

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('companies', CompanyController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::get('/change-password', [PasswordChangeController::class, 'showForm'])->name('password.change');
        Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.user.update');

        
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
        Route::get('/patient/list/dashboard/', [PatientController::class, 'patient_list_dashboard'])->name('patient.patient_list_dashboard');

        Route::prefix('patients/{patient}')->name('tasks.')->group(function () {
            Route::resource('tasks', TaskController::class)->except(['show']);
        });

        Route::get('/planner', [PlannerController::class, 'index'])->name('planner.index');
        Route::post('/appointments/{appointment}/reschedule', [PlannerController::class, 'reschedule'])->name('appointments.reschedule');


        Route::get('tasks/notifications', [TaskController::class, 'notifications'])->name('tasks.notifications');

        Route::prefix('patients/{patient}')->name('recalls.')->group(function () {
            Route::resource('recalls', RecallController::class)->except(['show']);
        });

        Route::prefix('patients/{patient}')->group(function () {
            Route::resource('waiting-lists', WaitingListController::class)
                ->names('waiting-lists')
                ->except(['show']);
        });

        Route::prefix('patients/{patient}')->group(function () {
            Route::resource('fee-notes', FeeNoteController::class)
                ->names('fee-notes')
                ->except(['show']);
        });

        Route::prefix('patients/{patient}')->group(function () {
            Route::resource('sms', SmsController::class)
                ->names('sms')
                ->except(['show', 'create', 'update', 'edit']);
        });

        Route::prefix('patients/{patient}')->group(function () {
            Route::resource('communications', CommunicationController::class)
                ->names('communications');
        });
        Route::post(
            '/communications/{communication}/received',
            [CommunicationController::class, 'markAsReceived']
        )
            ->name('communications.received');



        Route::prefix('patients/{patient}/audio')->group(function () {
            Route::get('/', [PatientAudioController::class, 'index'])->name('patients.audio.index');
            Route::get('/create', [PatientAudioController::class, 'create'])->name('patients.audio.create');
            Route::post('/', [PatientAudioController::class, 'store'])->name('patients.audio.store');
            Route::delete('/{audio}', [PatientAudioController::class, 'destroy'])->name('patients.audio.destroy');
        });

        Route::prefix('appointments')->group(function () {
            Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('appointments.schedule')->defaults('flag', 1);
            Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.byDateGlobal')->defaults('flag', 1);
            Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('appointments.calendarEvents')->defaults('flag', 1);
            Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.storeGlobal')->defaults('flag', 1);
            Route::post('/hospital', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.storeGlobal')->defaults('flag', 1);
        });

        Route::prefix('patients/{patient}/appointments')->group(function () {
            Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('patients.appointments.schedule')->defaults('flag', 0);
            Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('patients.appointments.byDate')->defaults('flag', 0);
            Route::post('/store', [AppointmentController::class, 'store'])->name('patients.appointments.store')->defaults('flag', 0);
            Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('patients.appointments.destroy')->defaults('flag', 0);
            Route::post('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('patients.appointments.updateStatus')->defaults('flag', 0);
            Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('patients.appointments.calendarEvents')->defaults('flag', 0);
            Route::post('/hospital-appointments', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.store')->defaults('flag', 0);
        });

        Route::post('/appointments/update-slot', [AppointmentController::class, 'updateSlot'])->name('appointments.update-slot');


        Route::get('/recalls/notifications', [RecallNotificationController::class, 'index'])->name('recalls.notifications');
        Route::get('/recalls/{id}/email', [RecallNotificationController::class, 'sendEmail'])->name('recalls.email');
        Route::get('/recalls/{id}/sms', [RecallNotificationController::class, 'sendSms'])->name('recalls.sms');

        Route::resource('patients', PatientController::class);
        Route::post('/patients/upload-picture', [PatientController::class, 'uploadPicture'])->name('patients.upload-picture');

        Route::resource('doctors', DoctorController::class);
        Route::resource('insurances', InsuranceController::class);
        Route::resource('consultants', ConsultantController::class);
        Route::resource('clinics', ClinicController::class);
        Route::resource('chargecodes', ChargeCodeController::class);
        Route::resource('chargecodeprices', ChargeCodePriceController::class);
        Route::resource('audios', AudioController::class);
        Route::prefix('patients/{patient}/tasks/{task}/followups')->name('followups.')->group(function () {
            Route::post('/{followup?}', [TaskFollowupController::class, 'storeOrUpdate'])->name('storeOrUpdate');
            Route::delete('/{followup}', [TaskFollowupController::class, 'destroy'])->name('destroy');
        });

        Route::get('/chargecodeprices/{insurance}/adjust-prices', [ChargeCodePriceController::class, 'showAdjustPrices'])->name('chargecodeprices.adjust-prices');
        Route::post('/chargecodeprices/{insurance}/adjust-prices', [ChargeCodePriceController::class, 'processAdjustPrices'])->name('chargecodeprices.process-adjust-prices');
    });
    Route::prefix("manager")->name("manager.")->middleware('role:manager')
        ->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::post('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');

        Route::get('/send-notification', [NotificationController::class, 'showForm'])->name('notifications.form')->middleware('auth:web');
        Route::post('/send-notification', [NotificationController::class, 'sendToCompany'])->name('notifications.send')->middleware('auth:web');

        Route::resource('configurations', ConfigurationController::class)->except(['show']);

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('users', UserController::class);
        Route::get('/change-password', [PasswordChangeController::class, 'showForm'])->name('password.change');
        Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.user.update');


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
        Route::get('/patient/list/dashboard/', [PatientController::class, 'patient_list_dashboard'])->name('patient.patient_list_dashboard');

        Route::prefix('patients/{patient}')->name('tasks.')->group(function () {
            Route::resource('tasks', TaskController::class)->except(['show']);
        });

        Route::get('/planner', [PlannerController::class, 'index'])->name('planner.index');
        Route::post('/appointments/{appointment}/reschedule', [PlannerController::class, 'reschedule'])->name('appointments.reschedule');


        Route::get('tasks/notifications', [TaskController::class, 'notifications'])->name('tasks.notifications');

        Route::prefix('patients/{patient}')->name('recalls.')->group(function () {
            Route::resource('recalls', RecallController::class)->except(['show']);
        });

        Route::prefix('patients/{patient}')->group(function () {
            Route::resource('waiting-lists', WaitingListController::class)
                ->names('waiting-lists')
                ->except(['show']);
        });

        Route::prefix('patients/{patient}')->group(function () {
            Route::resource('fee-notes', FeeNoteController::class)
                ->names('fee-notes')
                ->except(['show']);
        });

        Route::prefix('patients/{patient}')->group(function () {
            Route::resource('sms', SmsController::class)
                ->names('sms')
                ->except(['show', 'create', 'update', 'edit']);
        });

        Route::prefix('patients/{patient}')->group(function () {
            Route::resource('communications', CommunicationController::class)
                ->names('communications');
        });
        Route::post(
            '/communications/{communication}/received',
            [CommunicationController::class, 'markAsReceived']
        )
            ->name('communications.received');



        Route::prefix('patients/{patient}/audio')->group(function () {
            Route::get('/', [PatientAudioController::class, 'index'])->name('patients.audio.index');
            Route::get('/create', [PatientAudioController::class, 'create'])->name('patients.audio.create');
            Route::post('/', [PatientAudioController::class, 'store'])->name('patients.audio.store');
            Route::delete('/{audio}', [PatientAudioController::class, 'destroy'])->name('patients.audio.destroy');
        });

        Route::prefix('appointments')->group(function () {
            Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('appointments.schedule')->defaults('flag', 1);
            Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.byDateGlobal')->defaults('flag', 1);
            Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('appointments.calendarEvents')->defaults('flag', 1);
            Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.storeGlobal')->defaults('flag', 1);
            Route::post('/hospital', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.storeGlobal')->defaults('flag', 1);
        });

        Route::prefix('patients/{patient}/appointments')->group(function () {
            Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('patients.appointments.schedule')->defaults('flag', 0);
            Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('patients.appointments.byDate')->defaults('flag', 0);
            Route::post('/store', [AppointmentController::class, 'store'])->name('patients.appointments.store')->defaults('flag', 0);
            Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('patients.appointments.destroy')->defaults('flag', 0);
            Route::post('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('patients.appointments.updateStatus')->defaults('flag', 0);
            Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('patients.appointments.calendarEvents')->defaults('flag', 0);
            Route::post('/hospital-appointments', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.store')->defaults('flag', 0);
        });

        Route::post('/appointments/update-slot', [AppointmentController::class, 'updateSlot'])->name('appointments.update-slot');


        Route::get('/recalls/notifications', [RecallNotificationController::class, 'index'])->name('recalls.notifications');
        Route::get('/recalls/{id}/email', [RecallNotificationController::class, 'sendEmail'])->name('recalls.email');
        Route::get('/recalls/{id}/sms', [RecallNotificationController::class, 'sendSms'])->name('recalls.sms');

        Route::resource('patients', PatientController::class);
        Route::post('/patients/upload-picture', [PatientController::class, 'uploadPicture'])->name('patients.upload-picture');

        Route::resource('doctors', DoctorController::class);
        Route::resource('insurances', InsuranceController::class);
        Route::resource('consultants', ConsultantController::class);
        Route::resource('clinics', ClinicController::class);
        Route::resource('chargecodes', ChargeCodeController::class);
        Route::resource('chargecodeprices', ChargeCodePriceController::class);
        Route::resource('audios', AudioController::class);
        Route::prefix('patients/{patient}/tasks/{task}/followups')->name('followups.')->group(function () {
            Route::post('/{followup?}', [TaskFollowupController::class, 'storeOrUpdate'])->name('storeOrUpdate');
            Route::delete('/{followup}', [TaskFollowupController::class, 'destroy'])->name('destroy');
        });

        Route::get('/chargecodeprices/{insurance}/adjust-prices', [ChargeCodePriceController::class, 'showAdjustPrices'])->name('chargecodeprices.adjust-prices');
        Route::post('/chargecodeprices/{insurance}/adjust-prices', [ChargeCodePriceController::class, 'processAdjustPrices'])->name('chargecodeprices.process-adjust-prices');
    });
});

$roles = ['clinic', 'doctor', 'patient'];
foreach ($roles as $role) {
    Route::prefix($role)
        ->name("$role.")
        ->middleware(['auth:' . $role, 'check.guard.role']) // Custom middleware
        ->group(function () use ($role) {
            Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
            Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
            Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
            Route::post('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');

            // Add doctor-specific routes only in doctor group
            if ($role === 'doctor') {
                Route::get('/send-notification', [DoctorMessageController::class, 'showForm'])->name('notification.form');
                Route::post('/send-notification', [DoctorMessageController::class, 'send'])->name('notification.send');
            }

            Route::resource('configurations', ConfigurationController::class)->except(['show']);

            Route::get('/change-password', [PasswordChangeController::class, 'showForm'])->name('password.change');
            Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.user.update');

            Route::resource('dashboard', DashboardController::class);
            Route::resource('companies', CompanyController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('users', UserController::class);
            Route::resource('patients', PatientController::class);
            Route::post('/patients/upload-picture', [PatientController::class, 'uploadPicture'])->name('patients.upload-picture');

            Route::resource('doctors', DoctorController::class);
            Route::resource('insurances', InsuranceController::class);
            Route::resource('consultants', ConsultantController::class);
            Route::resource('clinics', ClinicController::class);
            Route::resource('chargecodes', ChargeCodeController::class);
            Route::resource('chargecodeprices', ChargeCodePriceController::class);
            Route::resource('audios', AudioController::class);
            Route::prefix('patients/{patient}/tasks/{task}/followups')->name('followups.')->group(function () {
                Route::post('/{followup?}', [TaskFollowupController::class, 'storeOrUpdate'])->name('storeOrUpdate');
                Route::delete('/{followup}', [TaskFollowupController::class, 'destroy'])->name('destroy');
            });

            Route::get('/chargecodeprices/{insurance}/adjust-prices', [ChargeCodePriceController::class, 'showAdjustPrices'])->name('chargecodeprices.adjust-prices');
            Route::post('/chargecodeprices/{insurance}/adjust-prices', [ChargeCodePriceController::class, 'processAdjustPrices'])->name('chargecodeprices.process-adjust-prices');

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
            Route::get('/patient/list/dashboard/', [PatientController::class, 'patient_list_dashboard'])->name('patient.patient_list_dashboard');

            Route::prefix('patients/{patient}')->name('tasks.')->group(function () {
                Route::resource('tasks', TaskController::class)->except(['show']);
            });

            Route::get('/planner', [PlannerController::class, 'index'])->name('planner.index');
            Route::post('/appointments/{appointment}/reschedule', [PlannerController::class, 'reschedule'])->name('appointments.reschedule');


            Route::get('tasks/notifications', [TaskController::class, 'notifications'])->name('tasks.notifications');

            Route::prefix('patients/{patient}')->name('recalls.')->group(function () {
                Route::resource('recalls', RecallController::class)->except(['show']);
            });

            Route::prefix('patients/{patient}')->group(function () {
                Route::resource('waiting-lists', WaitingListController::class)
                    ->names('waiting-lists')
                    ->except(['show']);
            });

            Route::prefix('patients/{patient}')->group(function () {
                Route::resource('fee-notes', FeeNoteController::class)
                    ->names('fee-notes')
                    ->except(['show']);
            });

            Route::prefix('patients/{patient}')->group(function () {
                Route::resource('sms', SmsController::class)
                    ->names('sms')
                    ->except(['show', 'create', 'update', 'edit']);
            });

            Route::prefix('patients/{patient}')->group(function () {
                Route::resource('communications', CommunicationController::class)
                    ->names('communications');
            });
            Route::post(
                '/communications/{communication}/received',
                [CommunicationController::class, 'markAsReceived']
            )
                ->name('communications.received');



            Route::prefix('patients/{patient}/audio')->group(function () {
                Route::get('/', [PatientAudioController::class, 'index'])->name('patients.audio.index');
                Route::get('/create', [PatientAudioController::class, 'create'])->name('patients.audio.create');
                Route::post('/', [PatientAudioController::class, 'store'])->name('patients.audio.store');
                Route::delete('/{audio}', [PatientAudioController::class, 'destroy'])->name('patients.audio.destroy');
            });

            Route::prefix('appointments')->group(function () {
                Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('appointments.schedule')->defaults('flag', 1);
                Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.byDateGlobal')->defaults('flag', 1);
                Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('appointments.calendarEvents')->defaults('flag', 1);
                Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.storeGlobal')->defaults('flag', 1);
                Route::post('/hospital', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.storeGlobal')->defaults('flag', 1);
            });

            Route::prefix('patients/{patient}/appointments')->group(function () {
                Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('patients.appointments.schedule')->defaults('flag', 0);
                Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('patients.appointments.byDate')->defaults('flag', 0);
                Route::post('/store', [AppointmentController::class, 'store'])->name('patients.appointments.store')->defaults('flag', 0);
                Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('patients.appointments.destroy')->defaults('flag', 0);
                Route::post('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('patients.appointments.updateStatus')->defaults('flag', 0);
                Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('patients.appointments.calendarEvents')->defaults('flag', 0);
                Route::post('/hospital-appointments', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.store')->defaults('flag', 0);
            });

            Route::post('/appointments/update-slot', [AppointmentController::class, 'updateSlot'])->name('appointments.update-slot');


            Route::get('/recalls/notifications', [RecallNotificationController::class, 'index'])->name('recalls.notifications');
            Route::get('/recalls/{id}/email', [RecallNotificationController::class, 'sendEmail'])->name('recalls.email');
            Route::get('/recalls/{id}/sms', [RecallNotificationController::class, 'sendSms'])->name('recalls.sms');
        });
}
