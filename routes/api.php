<?php

use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ImpactController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\WhyUsController;
use App\Http\Controllers\AuthController as ControllersAuthController;
use App\Http\Controllers\Site\projectController as SiteProjectController;
use App\Http\Controllers\User\UserController as UserUserController;
use Illuminate\Support\Facades\Route;





Route::prefix('admin')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
});
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/project/{id}', [SiteProjectController::class, 'index']);

Route::post('/login', [ControllersAuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::prefix('sliders')->group(function() {
        Route::get('/', [SliderController::class, 'index']);
        Route::post('/create', [SliderController::class, 'store']);
        Route::put('/{id}', [SliderController::class, 'update']);
        Route::get('/{id}', [SliderController::class, 'show']);
        Route::delete('/{id}', [SliderController::class, 'destroy']);
        // Route::get('/export/data', [SliderController::class, 'export']);

    });
        Route::prefix('abouts')->group(function() {
        Route::get('/', [AboutController::class, 'index']);
        Route::post('/create', [AboutController::class, 'store']);
        Route::put('/{id}', [AboutController::class, 'update']);
        Route::get('/{id}', [AboutController::class, 'show']);
        Route::delete('/{id}', [AboutController::class, 'destroy']);
        // Route::get('/export/data', [AboutController::class, 'export']);

    });
        Route::prefix('impacts')->group(function() {
        Route::get('/', [ImpactController::class, 'index']);
        Route::post('/create', [ImpactController::class, 'store']);
        Route::put('/{id}', [ImpactController::class, 'update']);
        Route::get('/{id}', [ImpactController::class, 'show']);
        Route::delete('/{id}', [ImpactController::class, 'destroy']);
        // Route::get('/export/data', [ImpactController::class, 'export']);

    });
        Route::prefix('whyUs')->group(function() {
        Route::get('/', [WhyUsController::class, 'index']);
        Route::post('/create', [WhyUsController::class, 'store']);
        Route::put('/{id}', [WhyUsController::class, 'update']);
        Route::get('/{id}', [WhyUsController::class, 'show']);
        Route::delete('/{id}', [WhyUsController::class, 'destroy']);
        // Route::get('/export/data', [WhyUsController::class, 'export']);

    });        
        Route::prefix('projects')->group(function() {
        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/create', [ProjectController::class, 'store']);
        Route::put('/{id}', [ProjectController::class, 'update']);
        Route::get('/{id}', [ProjectController::class, 'show']);
        Route::delete('/{id}', [ProjectController::class, 'destroy']);
        // Route::get('/export/data', [ProjectController::class, 'export']);

    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('/profile', [UserUserController::class, 'profile']);
    Route::put('/profile', [UserUserController::class, 'update']);
});
