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
use App\Http\Controllers\Backend\CalendarController;
use App\Http\Controllers\Backend\ClinicMessageController;
use App\Http\Controllers\Backend\ConfigurationController;
use App\Http\Controllers\Backend\DoctorMessageController;
use App\Http\Controllers\Backend\ManagerNotificationController;
use App\Http\Controllers\Backend\Master\DocumentTemplateController;
use App\Http\Controllers\Backend\Master\WhatsAppController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\PasswordChangeController;
use App\Http\Controllers\Backend\PatientAptController;
use App\Http\Controllers\Backend\PatientDocumentController;
use App\Http\Controllers\Backend\PatientMessageController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\OnlyOfficeController;

Route::get('/', function () {
    return view('frontend.index');
});

Route::get('/onlyoffice/editor/{fileId}', [OnlyOfficeController::class, 'editor'])->name('onlyoffice.editor');;


// Route::post('/onlyoffice/callback/{document}', [OnlyOfficeController::class, 'callback'])->name('onlyoffice.callback');

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

 // Function for patient sub-resources
 $patientSubRoutes = function ($prefix = 'patients/{patient}') {

    Route::post('/whatsapp/send-runtime', [WhatsAppController::class, 'sendRuntime'])->name('whatsapp.send.runtime');

    Route::prefix("$prefix/notes")->group(function () {
        Route::get('/', [PatientNoteController::class, 'index'])->name('patients.notes.index');
        Route::get('/create', [PatientNoteController::class, 'create'])->name('patients.notes.create');
        Route::post('/', [PatientNoteController::class, 'store'])->name('patients.notes.store');
        Route::get('/{note}/edit', [PatientNoteController::class, 'edit'])->name('patients.notes.edit');
        Route::put('/{note}', [PatientNoteController::class, 'update'])->name('patients.notes.update');
        Route::post('/{note}/toggle-completed', [PatientNoteController::class, 'toggleCompleted'])->name('patients.notes.toggleCompleted');
        Route::delete('/{note}', [PatientNoteController::class, 'destroy'])->name('patients.notes.destroy');
    });

    Route::prefix("$prefix/physical")->group(function () {
        Route::get('/', [PatientPhysicalController::class, 'index'])->name('patients.physical.index');
        Route::get('/create', [PatientPhysicalController::class, 'create'])->name('patients.physical.create');
        Route::post('/', [PatientPhysicalController::class, 'store'])->name('patients.physical.store');
        Route::get('/{physical}/edit', [PatientPhysicalController::class, 'edit'])->name('patients.physical.edit');
        Route::put('/{physical}', [PatientPhysicalController::class, 'update'])->name('patients.physical.update');
        Route::delete('/{physical}', [PatientPhysicalController::class, 'destroy'])->name('patients.physical.destroy');
    });

    Route::prefix("$prefix/history")->group(function () {
        Route::get('/', [PatientHistoryController::class, 'index'])->name('patients.history.index');
        Route::get('/create', [PatientHistoryController::class, 'create'])->name('patients.history.create');
        Route::post('/', [PatientHistoryController::class, 'store'])->name('patients.history.store');
        Route::get('/{history}/edit', [PatientHistoryController::class, 'edit'])->name('patients.history.edit');
        Route::put('/{history}', [PatientHistoryController::class, 'update'])->name('patients.history.update');
        Route::delete('/{history}', [PatientHistoryController::class, 'destroy'])->name('patients.history.destroy');
    });

    Route::prefix("$prefix/audio")->group(function () {
        Route::get('/', [PatientAudioController::class, 'index'])->name('patients.audio.index');
        Route::get('/create', [PatientAudioController::class, 'create'])->name('patients.audio.create');
        Route::post('/', [PatientAudioController::class, 'store'])->name('patients.audio.store');
        Route::delete('/{audio}', [PatientAudioController::class, 'destroy'])->name('patients.audio.destroy');
    });

    // Tasks, Recalls, Fee Notes, Waiting Lists, Communications, SMS
    Route::prefix("$prefix")->group(function () {
        Route::resource('apts', PatientAptController::class)->except(['show','edit','update','destroy','create'])->names('apts');
        Route::resource('tasks', TaskController::class)->except(['show'])->names('tasks');
        Route::resource('recalls', RecallController::class)->except(['show'])->names('recalls');
        Route::resource('fee-notes', FeeNoteController::class)->except(['show'])->names('fee-notes');
        Route::resource('waiting-lists', WaitingListController::class)->except(['show'])->names('waiting-lists');
        Route::resource('communications', CommunicationController::class)->names('communications');
        Route::resource('sms', SmsController::class)->except(['show', 'create', 'update', 'edit'])->names('sms');
    });
    Route::post('/communications/{communication}/received', [CommunicationController::class, 'markAsReceived'])->name('communications.received');

    Route::prefix("$prefix")->group(function () {
        Route::post('/documents/preview-template-create', [PatientDocumentController::class, 'previewTemplateCreate'])->name('patient-documents.previewTemplateCreate');
        Route::get('/documents/{document}/download-pdf', [PatientDocumentController::class, 'downloadConvertedPdf'])->name('patient-documents.download-pdf');
        Route::get('/documents/{document}/email', [PatientDocumentController::class, 'emailForm'])->name('patient-documents.email.form');
        Route::post('/documents/{document}/email/send', [PatientDocumentController::class, 'sendEmail'])->name('patient-documents.email.send');
        Route::post('/documents/{document}/change-template',[PatientDocumentController::class, 'changeTemplate'])->name('patient-documents.changeTemplate');
        Route::resource('documents', PatientDocumentController::class)->except(['show'])->names('patient-documents');
        // Route::post('/documents/temp-preview', [PatientDocumentController::class, 'previewTemplateCreate'])->name('patient-documents.tempPreview');
        // Route::get('/documents/load-exiting-file/{id}', [PatientDocumentController::class, 'loadExitingFile'])->name('patient-documents.loadExitingFile');  
    });

    // Follow-ups
    Route::prefix("$prefix/tasks/{task}/followups")->name('followups.')->group(function () {
        Route::post('/{followup?}', [TaskFollowupController::class, 'storeOrUpdate'])->name('storeOrUpdate');
        Route::delete('/{followup}', [TaskFollowupController::class, 'destroy'])->name('destroy');
    });

    // Appointments
    Route::prefix("$prefix/appointments")->group(function () {
        Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('patients.appointments.schedule')->defaults('flag', 0);
        Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('patients.appointments.byDate')->defaults('flag', 0);
        Route::post('/store', [AppointmentController::class, 'store'])->name('patients.appointments.store')->defaults('flag', 0);
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('patients.appointments.destroy')->defaults('flag', 0);
        Route::post('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('patients.appointments.updateStatus')->defaults('flag', 0);
        Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('patients.appointments.calendarEvents')->defaults('flag', 0);
        Route::post('/hospital-appointments', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.store')->defaults('flag', 0);
    });

    Route::prefix('appointments')->group(function () {
        Route::post('/{appointment}/reschedule', [PlannerController::class, 'reschedule'])->name('appointments.reschedule');
        Route::post('/available-slots', [AppointmentController::class, 'availableSlots'])->name('appointments.availableSlots')->defaults('flag', 0);
        Route::post('/move', [AppointmentController::class, 'move'])->name('appointments.move')->defaults('flag', 0);
        Route::post('/forDate', [AppointmentController::class, 'getAppointmentsForDate'])->name('appointments.forDate');
        Route::post('/update-slot', [AppointmentController::class, 'updateSlot'])->name('appointments.update-slot');
        Route::post('/clinic-overview-counts', [AppointmentController::class, 'clinicOverviewCounts'])->name('appointments.clinicOverviewCounts')->defaults('flag', 1);
        Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('appointments.schedule')->defaults('flag', 1);
        Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('appointments.byDateGlobal')->defaults('flag', 1);
        Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('appointments.calendarEvents')->defaults('flag', 1);
        Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.storeGlobal')->defaults('flag', 1);
        Route::post('/hospital', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.storeGlobal')->defaults('flag', 1);
    });

    Route::prefix('reports')->group(function () {
        Route::get('/entire-day', [ReportController::class, 'entireDayReport'])->name('reports.entire-day');
        Route::post('/entire-day/email', [ReportController::class, 'emailEntireDayReport'])->name('reports.entire-day.email');
    });
    
    Route::prefix('calendar')->group(function () {
        Route::post('/store', [CalendarController::class, 'store'])->name('calendar.store');
        Route::post('/fetch-days', [CalendarController::class, 'fetchDays'])->name('calendar.fetchDays');
    });

    Route::prefix('recalls')->group(function () {
        Route::get('notifications', [RecallNotificationController::class, 'index'])->name('recalls.notifications');
        Route::get('{id}/email', [RecallNotificationController::class, 'sendEmail'])->name('recalls.email');
        Route::get('{id}/sms', [RecallNotificationController::class, 'sendSms'])->name('recalls.sms');
    });

    Route::prefix('chargecodeprices')->group(function () {
        Route::get('{insurance}/adjust-prices', [ChargeCodePriceController::class, 'showAdjustPrices'])->name('chargecodeprices.adjust-prices');
        Route::post('{insurance}/adjust-prices', [ChargeCodePriceController::class, 'processAdjustPrices'])->name('chargecodeprices.process-adjust-prices');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::post('/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    });



    Route::get("$prefix/upload-picture", [PatientController::class, 'UploadPictureForm'])->name('patients.upload-picture-form');
};


Route::group(['middleware' => ['auth']], function() use ($patientSubRoutes) {
    Route::middleware('role:superadmin')->group(function() use ($patientSubRoutes) {

        // Basic resources
        Route::resource('documents', DocumentTemplateController::class);
        Route::post('documents/library/download', [DocumentTemplateController::class, 'downloadSelectedDocuments'])->name('documents.library.download');
        Route::post('documents/temp-upload', [DocumentTemplateController::class, 'tempUpload'])->name('documents.tempUpload');
        Route::get('documents/load-file/{id}', [DocumentTemplateController::class, 'loadFile'])->name('documents.loadFile');
        Route::get('/doc', [DocumentTemplateController::class, 'doc']);

        Route::resource('configurations', ConfigurationController::class)->except(['show']);
        Route::resource('companies', CompanyController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('patients', PatientController::class);
        Route::post('/patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');

        Route::resource('doctors', DoctorController::class);
        Route::resource('insurances', InsuranceController::class);
        Route::resource('consultants', ConsultantController::class);
        Route::resource('clinics', ClinicController::class);
        Route::resource('chargecodes', ChargeCodeController::class);
        Route::resource('chargecodeprices', ChargeCodePriceController::class);
        Route::resource('audios', AudioController::class);

        Route::get('/companies/{company}/managers', [CompanyController::class, 'getManagers'])->name('company.manager');
        Route::post('/patients/upload-picture', [PatientController::class, 'uploadPicture'])->name('patients.upload-picture');

        // Dashboard & Password
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/change-password', [PasswordChangeController::class, 'showForm'])->name('password.change');
        Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.user.update');

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/send', [NotificationController::class, 'showForm'])->name('notifications.form');
            Route::post('/send', [NotificationController::class, 'sendToCompany'])->name('notifications.send');
        });
    
    
        // Dropdowns
        Route::resource('dropdowns', DropDownController::class);
        Route::prefix('dropdownvalues')->group(function () {
            Route::get('/list/{dropDownId}', [DropDownValueController::class, 'index'])->name('dropdownvalues.index');
            Route::get('/create/{dropDownId}', [DropDownValueController::class, 'create'])->name('dropdownvalues.create');
            Route::post('/store/{dropDownId}', [DropDownValueController::class, 'store'])->name('dropdownvalues.store');
            Route::get('/{id}/edit/{dropDownId}', [DropDownValueController::class, 'edit'])->name('dropdownvalues.edit');
            Route::put('/{id}/update', [DropDownValueController::class, 'update'])->name('dropdownvalues.update');
        });
        // Apply patient sub-routes
        $patientSubRoutes();
    
        // Planner & global appointments
        Route::get('/planner', [PlannerController::class, 'index'])->name('planner.index');      
        Route::get('tasks/notifications', [TaskController::class, 'notifications'])->name('tasks.notifications');
    });
    
    Route::prefix("manager")->name("manager.")->middleware('role:manager')
        ->group(function () use ($patientSubRoutes)  {

            Route::prefix('send-manager-notification')->group(function () { 
                Route::get('/', [ManagerNotificationController::class, 'showManagerForm'])->name('notifications.managerform');
                Route::post('/', [ManagerNotificationController::class, 'sendFromManager'])->name('notifications.managersend');
            });

            // Basic resources
        Route::resource('documents', DocumentTemplateController::class);
        Route::post('documents/library/download', [DocumentTemplateController::class, 'downloadSelectedDocuments'])->name('documents.library.download');


        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/change-password', [PasswordChangeController::class, 'showForm'])->name('password.change');
        Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.user.update');
        Route::get('/patient/list/dashboard/', [PatientController::class, 'patient_list_dashboard'])->name('patient.patient_list_dashboard');
        Route::get('/planner', [PlannerController::class, 'index'])->name('planner.index');
        Route::get('tasks/notifications', [TaskController::class, 'notifications'])->name('tasks.notifications');    
        Route::post('/clinic-overview-counts', [AppointmentController::class, 'clinicOverviewCounts'])->name('appointments.clinicOverviewCounts')->defaults('flag', 0);
         
        Route::resource('configurations', ConfigurationController::class)->except(['show']);
        Route::resource('users', UserController::class);
        Route::resource('patients', PatientController::class);
        Route::post('/patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');

        Route::resource('doctors', DoctorController::class);
        Route::resource('insurances', InsuranceController::class);
        Route::resource('consultants', ConsultantController::class);
        Route::resource('clinics', ClinicController::class);
        Route::resource('chargecodes', ChargeCodeController::class);
        Route::resource('chargecodeprices', ChargeCodePriceController::class);
        Route::resource('audios', AudioController::class);

        Route::post('/patients/upload-picture', [PatientController::class, 'uploadPicture'])->name('patients.upload-picture');
        
        $patientSubRoutes();
    });
});

$roles = ['clinic', 'doctor', 'patient'];
foreach ($roles as $role) {
    Route::prefix($role)
        ->name("$role.")
        ->middleware(['auth:' . $role, 'check.guard.role']) // Custom middleware
        ->group(function () use ($role, $patientSubRoutes) {
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
            Route::resource('configurations', ConfigurationController::class)->except(['show']);


            // Add doctor-specific routes only in doctor group
            if ($role === 'doctor') {
                Route::get('/send-notification', [DoctorMessageController::class, 'showForm'])->name('notification.form');
                Route::post('/send-notification', [DoctorMessageController::class, 'send'])->name('notification.send');
            }

            if ($role === 'clinic') {
                Route::get('/send-clinic-notification', [ClinicMessageController::class, 'showForm'])->name('clinic.notification.form');
                Route::post('/send-clinic-notification', [ClinicMessageController::class, 'send'])->name('clinic.notification.send');
            }

            if ($role === 'patient') {
                Route::get('/send-patient-notification', [PatientMessageController::class, 'showForm'])->name('patient.notification.form');
                Route::post('/send-patient-notification', [PatientMessageController::class, 'send'])->name('patient.notification.send');
            }

            Route::get('/change-password', [PasswordChangeController::class, 'showForm'])->name('password.change');
            Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.user.update');
            Route::post('/patients/upload-picture', [PatientController::class, 'uploadPicture'])->name('patients.upload-picture');

            $patientSubRoutes();
            Route::get('/patient/list/dashboard/', [PatientController::class, 'patient_list_dashboard'])->name('patient.patient_list_dashboard');
            Route::get('/planner', [PlannerController::class, 'index'])->name('planner.index');
            Route::get('tasks/notifications', [TaskController::class, 'notifications'])->name('tasks.notifications');
            Route::post('/clinic-overview-counts', [AppointmentController::class, 'clinicOverviewCounts'])->name('appointments.clinicOverviewCounts')->defaults('flag', 0);         
        });
}

Route::Group(['prefix' => 'admin'], function () { 
        Route::get('/index_admin', function () {
        return view('admin.index_admin');
        })->name('pagee');
        Route::get('/appointment-list', function () {
        return view('admin.appointment-list');
        })->name('appointment-list');
        Route::get('/specialities', function () {
        return view('admin.specialities');
        })->name('specialities');
        Route::get('/doctor-list', function () {
        return view('admin.doctor-list');
        })->name('doctor-list');
        Route::get('/patient-list', function () {
        return view('admin.patient-list');
        })->name('patient-list');
        Route::get('/reviews', function () {
        return view('admin.reviews');
        })->name('reviews');
        Route::get('/transactions-list', function () {
        return view('admin.transactions-list');
        })->name('transactions-list');
        Route::get('/settings', function () {
        return view('admin.settings');
        })->name('settings');
        Route::get('/invoice-report', function () {
        return view('admin.invoice-report');
        })->name('invoice-report');
        Route::get('/profile', function () {
        return view('admin.profile');
        })->name('profile');
        Route::get('/login', function () {
        return view('admin.login');
        })->name('login');
        Route::get('/register', function () {
        return view('admin.register');
        })->name('register');
        Route::get('/forgot-password', function () {
        return view('admin.forgot-password');
        })->name('forgot-password');
        Route::get('/lock-screen', function () {
        return view('admin.lock-screen');
        })->name('lock-screen');
        Route::get('/error-404', function () {
        return view('admin.error-404');
        })->name('error-404');
        Route::get('/error-500', function () {
        return view('admin.error-500');
        })->name('error-500');
        Route::get('/blank-page', function () {
        return view('admin.blank-page');
        })->name('blank-page');
        Route::get('/components', function () {
        return view('admin.components');
        })->name('components');
        Route::get('/form-basic-inputs', function () {
        return view('admin.form-basic-inputs');
        })->name('form-basic');
        Route::get('/form-input-groups', function () {
        return view('admin.form-input-groups');
        })->name('form-inputs');
        Route::get('/form-horizontal', function () {
        return view('admin.form-horizontal');
        })->name('form-horizontal');
        Route::get('/form-vertical', function () {
        return view('admin.form-vertical');
        })->name('form-vertical');
        Route::get('/form-mask', function () {
        return view('admin.form-mask');
        })->name('form-mask');
        Route::get('/form-validation', function () {
        return view('admin.form-validation');
        })->name('form-validation');
        Route::get('/tables-basic', function () {
        return view('admin.tables-basic');
        })->name('tables-basic');
        Route::get('/data-tables', function () {
        return view('admin.data-tables');
        })->name('data-tables');
        Route::get('/invoice', function () {
        return view('admin.invoice');
        })->name('invoice');
        Route::get('/calendar', function () {
        return view('admin.calendar');
        })->name('calendar');
        Route::get('/blog', function () {
        return view('admin.blog');
        })->name('blog');
        Route::get('/blog-details', function () {
        return view('admin.blog-details');
        })->name('blog-details');
        Route::get('/add-blog', function () {
        return view('admin.add-blog');
        })->name('add-blog');
        Route::get('/edit-blog', function () {
        return view('admin.edit-blog');
        })->name('edit-blog');
        Route::get('/product-list', function () {
        return view('admin.product-list');
        })->name('product-list');
        Route::get('/pharmacy-list', function () {
        return view('admin.pharmacy-list');
        })->name('pharmacy-list');
        Route::get('/pending-blog', function () {
        return view('admin.pending-blog');
        })->name('pending-blog');
});
