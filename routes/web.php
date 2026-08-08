<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\InvestorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\LegalController;


Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
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

Route::get('/company', [CompanyController::class, 'index']);
Route::put('/company', [CompanyController::class, 'update']);
Route::post('/company/milestones', [CompanyController::class, 'storeMilestone']);
Route::delete('/company/milestones/{id}', [CompanyController::class, 'destroyMilestone']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::post('/products/{id}/tasks', [ProductController::class, 'storeTask']);
Route::post('/products/{id}/features', [ProductController::class, 'storeFeature']);
Route::post('/products/{id}/bugs', [ProductController::class, 'storeBug']);

Route::get('/finance', [FinanceController::class, 'index']);
Route::put('/finance/settings', [FinanceController::class, 'updateSettings']);
Route::post('/finance/transactions', [FinanceController::class, 'storeTransaction']);
Route::delete('/finance/transactions/{id}', [FinanceController::class, 'destroyTransaction']);
Route::post('/finance/funding-requests', [FinanceController::class, 'storeFundingRequest']);
Route::delete('/finance/funding-requests/{id}', [FinanceController::class, 'destroyFundingRequest']);

Route::get('/calendar', [CalendarController::class, 'index']);

Route::get('/roadmap', [RoadmapController::class, 'index']);
Route::post('/roadmap/objectives', [RoadmapController::class, 'storeObjective']);
Route::delete('/roadmap/objectives/{id}', [RoadmapController::class, 'destroyObjective']);

Route::get('/legal', [LegalController::class, 'index']);
Route::post('/legal/documents', [LegalController::class, 'storeDocument']);
Route::delete('/legal/documents/{id}', [LegalController::class, 'destroyDocument']);
Route::post('/legal/compliance', [LegalController::class, 'storeCompliance']);
Route::patch('/legal/compliance/{id}/toggle', [LegalController::class, 'toggleCompliance']);
Route::delete('/legal/compliance/{id}', [LegalController::class, 'destroyCompliance']);
});

require __DIR__.'/auth.php';
