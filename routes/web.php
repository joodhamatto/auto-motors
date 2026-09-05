<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SetupController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::get('/language/{locale}', LocaleController::class)->name('locale');
Route::get('/sitemap.xml', [PortfolioController::class, 'sitemap'])->name('sitemap');
Route::middleware('setup.available')->group(function () {
    Route::get('/setup', [SetupController::class, 'create'])->name('setup');
    Route::post('/setup', [SetupController::class, 'store'])->middleware('throttle:5,1')->name('setup.store');
});
Route::middleware(['admin.exists', 'guest'])->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});
Route::post('/admin/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');
Route::prefix('admin')->name('admin.')->middleware(['admin.exists', 'auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/settings', [ContentController::class, 'settings'])->name('settings');
    Route::put('/settings', [ContentController::class, 'updateSettings'])->name('settings.update');
    Route::delete('/products/{product}/images/{image}', [ContentController::class, 'destroyProductImage'])->name('product-images.destroy');
    Route::post('/{resource}/{id}/toggle', [ContentController::class, 'toggle'])->name('content.toggle');
    Route::get('/{resource}', [ContentController::class, 'index'])->name('content.index');
    Route::get('/{resource}/create', [ContentController::class, 'create'])->name('content.create');
    Route::post('/{resource}', [ContentController::class, 'store'])->name('content.store');
    Route::get('/{resource}/{id}/edit', [ContentController::class, 'edit'])->name('content.edit');
    Route::put('/{resource}/{id}', [ContentController::class, 'update'])->name('content.update');
    Route::delete('/{resource}/{id}', [ContentController::class, 'destroy'])->name('content.destroy');
});
