<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\InvestorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/team-members', [TeamMemberController::class, 'index']);

    Route::get('/meetings', [MeetingController::class, 'index']);
    Route::get('/meetings/create', [MeetingController::class, 'create']);
    Route::post('/meetings', [MeetingController::class, 'store']);
    Route::get('/meetings/{id}/edit', [MeetingController::class, 'edit']);
    Route::put('/meetings/{id}', [MeetingController::class, 'update']);
    Route::delete('/meetings/{id}', [MeetingController::class, 'destroy']);

    Route::get('/partnerships', [PartnershipController::class, 'index']);
    Route::post('/partnerships', [PartnershipController::class, 'store']);
    Route::patch('/partnerships/{id}/stage', [PartnershipController::class, 'updateStage']);
    Route::delete('/partnerships/{id}', [PartnershipController::class, 'destroy']);

    Route::get('/investors', [InvestorController::class, 'index']);
    Route::get('/investors/create', [InvestorController::class, 'create']);
    Route::post('/investors', [InvestorController::class, 'store']);
    Route::get('/investors/{id}/edit', [InvestorController::class, 'edit']);
    Route::put('/investors/{id}', [InvestorController::class, 'update']);
    Route::delete('/investors/{id}', [InvestorController::class, 'destroy']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
