<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\FolderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('token', 'token');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'username' => $request->user()->name,
        'email' => $request->user()->email]);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/files/{folder?}/size', [FileController::class, 'size'])->where('folder', '[A-Za-z0-9]+');
    Route::get('/files/size', [FileController::class, 'size'])->where('folder', '[A-Za-z0-9]+');
    Route::get('/files/disksize', [FileController::class, 'disksize'])->where('folder', '[A-Za-z0-9]+');
    Route::get('/files/download', [FileController::class, 'download']);
    Route::get('/files/publiclink', [FileController::class, 'publiclink']);
    Route::get('/files/{folder?}', [FileController::class, 'index'])->where('folder', '[A-Za-z0-9]+');
    Route::put('/files', [FileController::class, 'rename']);
    Route::delete('/files', [FileController::class, 'delete']);
    Route::get('/folders', [FolderController::class, 'index']);
    Route::post('/folders', [FolderController::class, 'create']);
    Route::post('/upload', [FileController::class, 'upload']);
});

Route::get('/files/download/{publiclink}', [FileController::class, 'downloadPublic'])->name('download.link');




