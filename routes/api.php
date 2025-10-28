<?php

use App\Http\Controllers\OnlyOfficeController;
use Illuminate\Support\Facades\Route;

Route::post('/onlyoffice/callback/{fileId?}', [OnlyOfficeController::class, 'callback']);
// Route::get('/onlyoffice/callback1/{fileId?}', [OnlyOfficeController::class, 'callback1']);
