<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Authentifikasi
Route::prefix('auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('mahasiswa')->group(function() {
    Route::get('/', [MahasiswaController::class, 'fetch']);
    Route::post('/', [MahasiswaController::class, 'store']);
    Route::put('/{id}', [MahasiswaController::class, 'update']);
    Route::delete('/{id}', [MahasiswaController::class, 'remove']);
});

// Route::prefix('mahasiswa', function() {
//     Route::group(['middleware' => ['jwt']], function() {
//         Route::get('/', [MahasiswaController::class, 'fetch']);
//         Route::post('/', [MahasiswaController::class, 'store']);
//         Route::put('/{id}', [MahasiswaController::class, 'update']);
//         Route::delete('/{id}', [MahasiswaController::class, 'remove']);
//     });
// });

Route::get('/testResponse', function() {
    return Response()->json([
        'status' => 200,
        'message' => 'Hello World' 
    ]);
});