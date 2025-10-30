<?php

use App\Http\Controllers\Backend\Master\WhatsAppController;
use App\Http\Controllers\Backend\Master\WhatsAppWebhookController;
use App\Http\Controllers\OnlyOfficeController;
use Illuminate\Support\Facades\Route;

Route::post('/onlyoffice/callback/{fileId?}', [OnlyOfficeController::class, 'callback']);

Route::post('/companies/{company}/whatsapp/send-text', [WhatsAppController::class, 'sendText']);
Route::post('/companies/{company}/whatsapp/send-document', [WhatsAppController::class, 'sendDocument']);

Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify']);
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle']);
