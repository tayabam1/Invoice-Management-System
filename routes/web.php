<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // --- AI Routes ---
    Route::post('/api/ai/generate-description', [AiController::class, 'generateDescription'])->name('ai.generate.description');
    Route::post('/ai/generate-description-stream', [AiController::class, 'generateDescriptionStream'])->name('ai.generate.description.stream');
    Route::post('/api/ai/feedback', [AiController::class, 'storeFeedback'])->name('ai.feedback.store');

    Route::resource('clients', App\Http\Controllers\ClientController::class);
    Route::resource('invoices', App\Http\Controllers\InvoiceController::class);
    Route::delete('invoices/{invoice}/logo', [App\Http\Controllers\InvoiceController::class, 'deleteLogo'])->name('invoices.deleteLogo');
    Route::get('invoices/{invoice}/pdf', [App\Http\Controllers\InvoiceController::class, 'downloadPDF'])->name('invoices.downloadPDF');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
