<?php

use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CourseController;
use App\Http\Controllers\Frontend\DonationController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\InitiativeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\TrainingController;
use App\Http\Controllers\Frontend\WorkshopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/workshops', [WorkshopController::class, 'index'])->name('workshops.index');
Route::get('/workshops/{workshop}', [WorkshopController::class, 'show'])->name('workshops.show');

Route::get('/university-training', [TrainingController::class, 'index'])->name('trainings.index');
Route::get('/university-training/{training}', [TrainingController::class, 'show'])->name('trainings.show');

Route::get('/initiatives', [InitiativeController::class, 'index'])->name('initiatives.index');
Route::get('/initiatives/{initiative}', [InitiativeController::class, 'show'])->name('initiatives.show');

Route::get('/donation', [DonationController::class, 'index'])->name('donation.index');
Route::get('/donation/{campaign}', [DonationController::class, 'show'])->name('donation.show');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:10,1')->name('newsletter.store');
