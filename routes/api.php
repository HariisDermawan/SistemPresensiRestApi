<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Dosen\KelasController as DosenKelasController;
use App\Http\Controllers\Dosen\PresensiController;
use App\Http\Controllers\Siswa\PresensiController as SiswaPresensiController;
use App\Http\Controllers\Siswa\ProfilController;
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
    Route::get('/me/role', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'message' => 'Role user login',
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name')
        ]);
    });
    Route::prefix('admin')->group(function () {
        Route::apiResource('siswa', SiswaController::class);
        Route::apiResource('dosen', AdminDosenController::class);
        Route::apiResource('kelas', AdminKelasController::class);
        Route::apiResource('presensi', PresensiController::class);
        Route::apiResource('presensi-siswa', SiswaPresensiController::class);
        Route::prefix('dosen')->group(function () {
            Route::get('{dosen_id}/kelas', [DosenKelasController::class, 'index']);
            Route::post('{dosen_id}/kelas', [DosenKelasController::class, 'attach']);
            Route::delete('{dosen_id}/kelas', [DosenKelasController::class, 'detach']);
        });
    });
    Route::prefix('dosen')->group(function () {
        Route::get('kelas', [DosenKelasController::class, 'index']);
        Route::get('kelas/{id}', [DosenKelasController::class, 'show']);
        Route::post('kelas', [DosenKelasController::class, 'store']);
        Route::put('kelas/{id}', [DosenKelasController::class, 'update']);
        Route::delete('kelas/{id}', [DosenKelasController::class, 'destroy']);
        Route::get('presensi', [PresensiController::class, 'index']);
        Route::get('presensi/{id}', [PresensiController::class, 'show']);
        Route::post('presensi', [PresensiController::class, 'store']);
        Route::put('presensi/{id}', [PresensiController::class, 'update']);
        Route::delete('presensi/{id}', [PresensiController::class, 'destroy']);
    });

    Route::middleware('auth:sanctum')->prefix('siswa')->group(function () {
        Route::get('presensi', [SiswaPresensiController::class, 'index']);
        Route::get('presensi/{id}', [SiswaPresensiController::class, 'show']);
        Route::post('presensi', [SiswaPresensiController::class, 'store']);
        Route::put('presensi/{id}', [SiswaPresensiController::class, 'update']);
        Route::delete('presensi/{id}', [SiswaPresensiController::class, 'destroy']);
        Route::get('profile/{id}', [ProfilController::class, 'show']);
        Route::put('profile', [ProfilController::class, 'update']);
        Route::delete('profile', [ProfilController::class, 'destroy']);
    });
});
