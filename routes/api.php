<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Dosen\KelasController as DosenKelasController;
use App\Http\Controllers\Auth\AuthController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username ?? $user->nim ?? $user->nidn
        ]);
    });
    Route::prefix('admin')->group(function () {
        Route::apiResource('siswa', SiswaController::class);
        Route::apiResource('dosen', AdminDosenController::class);
        Route::apiResource('kelas', AdminKelasController::class);
        Route::prefix('dosen')->group(function () {
            Route::get('{dosen_id}/kelas', [DosenKelasController::class, 'index']);
            Route::post('{dosen_id}/kelas', [DosenKelasController::class, 'attach']);
            Route::delete('{dosen_id}/kelas', [DosenKelasController::class, 'detach']);
        });
    });
    Route::prefix('dosen')->group(function () {
        Route::get('kelas', [DosenKelasController::class, 'index']);       // list semua kelas
        Route::get('kelas/{id}', [DosenKelasController::class, 'show']);   // detail kelas
        Route::post('kelas', [DosenKelasController::class, 'store']);      // attach kelas baru
        Route::put('kelas/{id}', [DosenKelasController::class, 'update']); // update kelas
        Route::delete('kelas/{id}', [DosenKelasController::class, 'destroy']); // detach kelas
    });
});
