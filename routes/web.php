<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ServiceController as CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => view('Themes.index'))->name('home');
Route::get('/about', fn () => view('Themes.about'))->name('about');
Route::get('/contact', fn () => view('Themes.contact'))->name('contact');
Route::get('/services', fn () => view('Themes.service'))->name('services');
Route::get('/free-counselling', fn () => view('Themes.free-counselling'))->name('free.counselling');
Route::get('service/{name}', [CustomerController::class, 'index'])->name('service');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login-auth', [AuthController::class, 'login'])->name('login.auth');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register-store', [AuthController::class, 'registerStore'])->name('store');
    Route::middleware(['verified.customer', 'auth.customer:customer'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('my-booking', [CustomerController::class, 'myBooking'])->name('mybooking');
        Route::get('/booking-preview/{service}', [BookController::class, 'index'])->name('book.preview');
        Route::post('/booking-store', [BookController::class, 'store'])->name('book.store');
        Route::get('/booking-confirm', [BookController::class, 'bookConfirm'])->name('book.confirm');
        Route::get('/booking-pending', [BookController::class, 'bookPending'])->name('book.pending');
        Route::get('/booking-failed', [BookController::class, 'bookFailed'])->name('book.failed');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth:customer'])->group(function () {
        Route::get('/email/verify', [AuthController::class, 'emailVerify'])->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'emailVerified'])->middleware(['signed'])->name('verification.verify');
        Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->middleware(['throttle:6,1'])->name('verification.send');
    });
});

Route::prefix('admin')->middleware(['auth:web', 'verified:web'])->group(function () {
    Route::redirect('/', 'admin/dashboard', 301);
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::resource('blog', BlogController::class);
    Route::resource('products', ProductController::class)->names('products');
    Route::resource('services', ServiceController::class)->names('services');
});

require __DIR__.'/settings.php';
