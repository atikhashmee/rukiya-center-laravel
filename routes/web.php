<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ServiceController as CustomerController;
use App\Http\Controllers\CustomerController as AdminCustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use App\Models\Theme;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;

$themeViewPrepended = false;

function resolveThemeView(string $key): string
{
    global $themeViewPrepended;
    $theme = Theme::active();

    if ($theme) {
        $path = $theme->resolveViewPath($key);

        if ($path && !$themeViewPrepended) {
            $storageDir = dirname($path);
            if (is_dir($storageDir)) {
                View::prependLocation($storageDir);
                $themeViewPrepended = true;
            }
        }

        if ($path) {
            return $key;
        }
    }

    return "Themes.{$key}";
}

Route::get('/', fn () => view(resolveThemeView('index')))->name('home');
Route::get('/about', fn () => view(resolveThemeView('about')))->name('about');
Route::get('/contact', fn () => view(resolveThemeView('contact')))->name('contact');
Route::get('/services', fn () => view(resolveThemeView('service')))->name('services');
Route::get('/free-counselling', fn () => view(resolveThemeView('free-counselling')))->name('free.counselling');
Route::get('service/{name}', [CustomerController::class, 'index'])->name('service');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login-auth', [AuthController::class, 'login'])->name('login.auth');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register-store', [AuthController::class, 'registerStore'])->name('store');
    Route::middleware(['verified.customer', 'auth.customer:customer'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::get('my-booking', [CustomerController::class, 'myBooking'])->name('mybooking');
        Route::get('my-transactions', [CustomerController::class, 'myTransactions'])->name('mytransactions');
        Route::get('/booking-preview/{service}', [BookController::class, 'index'])->name('book.preview');
        Route::post('/booking-store', [BookController::class, 'store'])->name('book.store');
        Route::get('/booking-confirm', [BookController::class, 'bookConfirm'])->name('book.confirm');
        Route::get('/booking-pending', [BookController::class, 'bookPending'])->name('book.pending');
        Route::get('/booking-failed', [BookController::class, 'bookFailed'])->name('book.failed');

        // start payment section
        Route::get('/payment', [PaymentController::class, 'checkout'])->name('checkout');
        Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('payment.process');
        Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook'])->name('stripe.webhook');

        Route::get('/payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/payment-failed', [PaymentController::class, 'paymentFailed'])->name('payment.failed');
        // end of payment section
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth:customer', 'auth.customer'])->group(function () {
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
    Route::post('verify-customer-email/{id}', [AdminCustomerController::class, 'verifyEmail'])->name('customers.verifyEmail');
    Route::resource('customers', AdminCustomerController::class)->names('customers');

    Route::post('bookings-sendOrderEmail', [BookingController::class, 'sendOrderEmail'])->name('bookings.sendOrderEmail');
    Route::post('bookings-updateStatus', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::resource('bookings', BookingController::class)->names('bookings');
    Route::resource('users', UserController::class)->names('users');

    // Theme management
    Route::resource('themes', ThemeController::class)->names('themes');
    Route::post('themes/{theme}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
    Route::post('themes/{theme}/deactivate', [ThemeController::class, 'deactivate'])->name('themes.deactivate');
    Route::get('themes/{theme}/file/{key}', [ThemeController::class, 'getFile'])->name('themes.file');
    Route::put('themes/{theme}/file/{key}', [ThemeController::class, 'updateFile'])->name('themes.updateFile');
});

require __DIR__.'/settings.php';
