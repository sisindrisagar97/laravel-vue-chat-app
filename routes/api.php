<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MessageController;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->post('/broadcasting/auth', function (Request $request) {
    \Log::info('Broadcasting auth request', ['user' => auth()->user(),'headers' => $request->headers->all(), 'body' => $request->all()]);
    return Broadcast::auth($request); 
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', function (Request $request) {
        $users = User::where('id', '!=', $request->user()->id)->get();
        return response()->json($users, 200);
    });
    Route::get('/messages/{user}', [MessageController::class, 'getMessages']);
    Route::post('/messages', [MessageController::class, 'store']);
});
Route::post('/simple-post', function() {
    return response()->json(['message' => 'POST request works']);
});


