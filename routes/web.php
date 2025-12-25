<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Backend\AppointmentController;
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
use App\Http\Controllers\patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\EmailTestController;
use App\Http\Controllers\InternalChatController;
use App\Http\Controllers\CompanyEmailTestController;
use App\Http\Controllers\CompanyMailController;

Route::get('/email-test', [EmailTestController::class, 'showForm']);
Route::post('/email-test', [EmailTestController::class, 'sendEmail'])->name('email.send');

Route::get('/', function () {
    return view('frontend.index');
});

Route::get('/onlyoffice/editor/{fileId}', [OnlyOfficeController::class, 'editor'])->name('onlyoffice.editor');;

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

        // Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('patients.appointments.schedule')->defaults('flag', 0);
        Route::post('/by-date', [AppointmentController::class, 'getAppointmentsByDate'])->name('patients.appointments.byDate')->defaults('flag', 0);
        Route::post('/store', [AppointmentController::class, 'store'])->name('patients.appointments.store')->defaults('flag', 0);
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('patients.appointments.destroy')->defaults('flag', 0);
        Route::post('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('patients.appointments.updateStatus')->defaults('flag', 0);
        Route::post('/calendar-events', [AppointmentController::class, 'calendarEvents'])->name('patients.appointments.calendarEvents')->defaults('flag', 0);
        Route::post('/hospital-appointments', [AppointmentController::class, 'storeHospitalAppointment'])->name('hospital_appointments.store')->defaults('flag', 0);
    });

    Route::prefix('appointments')->group(function () {
        Route::get('/schedule', [AppointmentController::class, 'schedulePage'])->name('appointments.schedule')->defaults('flag', 1);
        Route::get('/{id}/edit', [AppointmentController::class, 'fetchAppointmentData'])->name('appointments.edit');

        Route::post('/{appointment}/reschedule', [PlannerController::class, 'reschedule'])->name('appointments.reschedule');
        Route::post('/available-slots', [AppointmentController::class, 'availableSlots'])->name('appointments.availableSlots')->defaults('flag', 0);
        Route::post('/move', [AppointmentController::class, 'move'])->name('appointments.move')->defaults('flag', 0);
        Route::post('/forDate', [AppointmentController::class, 'getAppointmentsForDate'])->name('appointments.forDate');
        Route::post('/update-slot', [AppointmentController::class, 'updateSlot'])->name('appointments.update-slot');
        Route::post('/clinic-overview-counts', [AppointmentController::class, 'clinicOverviewCounts'])->name('appointments.clinicOverviewCounts')->defaults('flag', 1);
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

    // new dashboard
    Route::get('/{clinic}/schedule', [ClinicController::class, 'schedule'])->name('clinic.schedule');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar-days', [CalendarController::class, 'getDays'])->name('calendar.days');
    Route::get('/appointmentindex', [CalendarController::class, 'appointmentindex'])->name('patients.appointments.index');
    Route::get('/appointment/check-slot', [AppointmentController::class, 'checkSlot'])->name('patients.appointments.checkSlot');
    Route::put('/appointment/update-time/{appointment}', [AppointmentController::class, 'updateTime'])->name('patients.appointments.updateTime');

    Route::prefix("patients/{patient}/appointments")->group(function () {
        Route::get('/', [PatientAppointmentController::class, 'index'])->name('patients.appointments.main.index');
    });
    Route::post('/doctor/upload-picture', [DoctorController::class, 'uploadPicture'])->name('doctor.upload-picture');


    Route::get("$prefix/upload-picture", [PatientController::class, 'UploadPictureForm'])->name('patients.upload-picture-form');
    // Internal chat system
    Route::get('/internal-chat', [InternalChatController::class, 'index'])->name('chat.index');
    Route::post('/internal-chat/get-or-create', [InternalChatController::class, 'getOrCreateConversation'])->name('chat.getconversation');
    Route::post('/internal-chat/send', [InternalChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/unread-count', [InternalChatController::class, 'unreadCount'])->name('chat.unread-count');

    Route::get('tasks/notifications', [TaskController::class, 'notifications'])->name('tasks.notifications');

    /// companys etting email 
    Route::post('/company/email/test', [CompanyEmailTestController::class, 'send'])->name('company.email.test');

    Route::prefix('company')->group(function () {
        Route::get('{company?}/mail', [CompanyMailController::class, 'folders'])->name('company.mail.folders');
        Route::get('{company?}/mail/folder/{folder}', [CompanyMailController::class, 'folder'])->name('company.mail.folder');
        Route::get('{company?}/mail/message/{id}', [CompanyMailController::class, 'message'])->name('company.mail.message');
        Route::post('{company?}/mail/send', [CompanyMailController::class, 'send'])->name('company.mail.send');
    });
    
    

};

$resources = [
    'documents' => DocumentTemplateController::class,
    'roles' => RoleController::class,
    'users' => UserController::class,
    'patients' => PatientController::class,
    'doctors' => DoctorController::class,
    'insurances' => InsuranceController::class,
    'consultants' => ConsultantController::class,
    'clinics' => ClinicController::class,
    'chargecodes' => ChargeCodeController::class,
    'chargecodeprices' => ChargeCodePriceController::class,
    'configurations' => ConfigurationController::class,
    'companies' =>  CompanyController::class
];

// Web guard roles: superadmin, manager, consultant
Route::group(['middleware' => ['auth']], function() use ($resources, $patientSubRoutes) {

    // All web guard roles share the same "no prefix" routes
    Route::group(['middleware' => ['role:superadmin|manager|consultant']], function() use ($resources, $patientSubRoutes) {

        // Resource routes
        foreach ($resources as $uri => $controller) {
            if ($uri === 'configurations') {
                Route::resource($uri, $controller)->except(['show']);
            } else {
                Route::resource($uri, $controller);
            }
        }

        // Superadmin-specific extra routes
        Route::middleware('role:superadmin')->group(function() {
            Route::post('documents/library/download', [DocumentTemplateController::class, 'downloadSelectedDocuments'])->name('documents.library.download');
            Route::post('documents/temp-upload', [DocumentTemplateController::class, 'tempUpload'])->name('documents.tempUpload');
            Route::get('documents/load-file/{id}', [DocumentTemplateController::class, 'loadFile'])->name('documents.loadFile');
            Route::get('/doc', [DocumentTemplateController::class, 'doc']);

            
            Route::get('/companies/{company}/managers', [CompanyController::class, 'getManagers'])->name('company.manager');
            Route::post('/patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');

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
        });

        Route::middleware('role:manager|consultant')->group(function () {
            Route::prefix('send-manager-notification')->group(function () {
                Route::get('/', [ManagerNotificationController::class, 'showManagerForm'])
                    ->name('notifications.managerform');
        
                Route::post('/', [ManagerNotificationController::class, 'sendFromManager'])
                    ->name('notifications.managersend');
            });
        });
        

        // Common routes for all web roles
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/change-password', [PasswordChangeController::class, 'showForm'])->name('password.change');
        Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.user.update');
        Route::get('/planner', [PlannerController::class, 'index'])->name('planner.index');
        Route::post('/patients/upload-picture', [PatientController::class, 'uploadPicture'])->name('patients.upload-picture');

        // Apply patient sub-routes
        $patientSubRoutes();

        // change userwise permision
        Route::get('{user}/edit-permissions', [UserController::class, 'editPermissions'])->name('users.edit_permissions');
        Route::put('{user}/update-permissions', [UserController::class, 'updatePermissions'])->name('users.update_permissions');

          
    });
});

// Patient guard routes
Route::prefix('patient')->name('patient.')->middleware(['auth:patient', 'check.guard.role'])->group(function() use ($resources, $patientSubRoutes) {

    Route::resource('dashboard', PatientDashboardController::class);

    foreach ($resources as $uri => $controller) {
        if ($uri === 'configurations') {
            Route::resource($uri, $controller)->except(['show']);
        } else {
            Route::resource($uri, $controller);
        }
    } 

    Route::get('/send-patient-notification', [PatientMessageController::class, 'showForm'])->name('patient.notification.form');
    Route::post('/send-patient-notification', [PatientMessageController::class, 'send'])->name('patient.notification.send');

    Route::get('/change-password', [PasswordChangeController::class, 'showForm'])->name('password.change');
    Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.user.update');

    Route::post('/patients/upload-picture', [PatientController::class, 'uploadPicture'])->name('patients.upload-picture');

    $patientSubRoutes();
    Route::get('/patient/list/dashboard/', [PatientController::class, 'patient_list_dashboard'])->name('patient.patient_list_dashboard');
    Route::post('/clinic-overview-counts', [AppointmentController::class, 'clinicOverviewCounts'])->name('appointments.clinicOverviewCounts')->defaults('flag', 0);
});