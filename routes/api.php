<?php

use App\Http\Controllers\Backend\Master\WhatsAppController;
use App\Http\Controllers\Backend\Master\WhatsAppWebhookController;
use App\Http\Controllers\OnlyOfficeController;
use Illuminate\Support\Facades\Route;

Route::post('/onlyoffice/patient_callback/{documentId}', [OnlyOfficeController::class, 'patientCallback']);
Route::post('/onlyoffice/document_callback/{documentId}', [OnlyOfficeController::class, 'DocumentCallback']);

Route::post('/companies/{company}/whatsapp/send-text', [WhatsAppController::class, 'sendText']);
Route::post('/companies/{company}/whatsapp/send-document', [WhatsAppController::class, 'sendDocument']);

Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify']);
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle']);
