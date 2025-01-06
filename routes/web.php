<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BroadcastController;

// Catch-all route to load the Vue app
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');