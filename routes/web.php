<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ClaimReviewController;
use App\Http\Controllers\Admin\ItemReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('items/browse', [ItemController::class, 'index'])->name('items.index');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('items/mine', [ItemController::class, 'myItems'])->name('items.mine');
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('items', [ItemController::class, 'store'])->name('items.store');

    Route::get('claims', [ClaimController::class, 'index'])->name('claims.index');
    Route::get('claims/received', [ClaimController::class, 'received'])->name('claims.received');
    Route::get('claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');
    Route::post('claims/{claim}/approve-ownership', [ClaimController::class, 'approveOwnership'])->name('claims.approve-ownership');
    Route::post('claims/{claim}/reject-ownership', [ClaimController::class, 'rejectOwnership'])->name('claims.reject-ownership');
    Route::post('claims/{claim}/schedule', [ClaimController::class, 'proposeSchedule'])->name('claims.schedule');
    Route::post('claims/{claim}/confirm-schedule', [ClaimController::class, 'confirmSchedule'])->name('claims.confirm-schedule');
    Route::post('claims/{claim}/reschedule', [ClaimController::class, 'requestReschedule'])->name('claims.reschedule');
    Route::post('claims/{claim}/confirm', [ClaimController::class, 'confirmReceipt'])->name('claims.confirm');
    Route::post('items/{item}/claims', [ClaimController::class, 'store'])->name('claims.store');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('items', [ItemReviewController::class, 'index'])->name('items.index');
        Route::patch('items/{item}/status', [ItemReviewController::class, 'updateStatus'])->name('items.status');
        Route::get('claims', [ClaimReviewController::class, 'index'])->name('claims.index');
        Route::get('claims/{claim}', [ClaimReviewController::class, 'show'])->name('claims.show');
        Route::post('claims/{claim}/approve', [ClaimReviewController::class, 'approve'])->name('claims.approve');
        Route::post('claims/{claim}/reject', [ClaimReviewController::class, 'reject'])->name('claims.reject');
        Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });
});

Route::get('items/{item}', [ItemController::class, 'show'])
    ->whereNumber('item')
    ->name('items.show');
