<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ValueDataController;

Route::prefix('object')->group(function () {
  Route::post('/', [ValueDataController::class, 'store']);
  Route::get('/get_all_records', [ValueDataController::class, 'getAllRecords']);
  Route::get('/{key}', [ValueDataController::class, 'getByKey']);
});

