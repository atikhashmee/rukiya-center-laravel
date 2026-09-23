<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\Customer\BlogController as CustomerBlogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ServiceController as CustomerController;
use App\Http\Controllers\CustomerController as AdminCustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\BookingWizardController;
use App\Models\Theme;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => view(Theme::resolveViewName('index')))->name('home');
Route::get('/about', fn () => view(Theme::resolveViewName('about')))->name('about');
Route::get('/contact', fn () => view(Theme::resolveViewName('contact')))->name('contact');
Route::get('/free-counselling', fn () => view(Theme::resolveViewName('free-counselling')))->name('free.counselling');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/blog', [CustomerBlogController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [CustomerBlogController::class, 'show'])->name('posts.show');
Route::post('/blog/{post}/comment', [CustomerBlogController::class, 'storeComment'])->name('posts.comment.store');
Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/checkout', [CartController::class, 'placeOrder'])->name('cart.placeOrder');
Route::get('service/{name}', [CustomerController::class, 'index'])->name('service');

// Booking Wizard (no auth required)
Route::prefix('book')->name('wizard.')->group(function () {
    Route::get('/', [BookingWizardController::class, 'index'])->name('index');
    Route::get('/service/{category}', [BookingWizardController::class, 'selectService'])->name('service');
    Route::get('/instructor/{serviceId}', [BookingWizardController::class, 'selectInstructor'])->name('instructor');
    Route::get('/schedule/{serviceId}/{instructorId}', [BookingWizardController::class, 'selectSchedule'])->name('schedule');
    Route::get('/confirm', [BookingWizardController::class, 'confirm'])->name('confirm');
    Route::post('/store', [BookingWizardController::class, 'store'])->name('store');
    Route::get('/completed', [BookingWizardController::class, 'confirmation'])->name('confirmation');
    Route::get('/pending', [BookingWizardController::class, 'pending'])->name('pending');

    // Public payment routes (no auth required)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [PaymentController::class, 'checkout'])->name('checkout');
        Route::post('/process', [PaymentController::class, 'processPayment'])->name('process');
        Route::get('/success', [PaymentController::class, 'paymentSuccess'])->name('success');
        Route::get('/failed', [PaymentController::class, 'paymentFailed'])->name('failed');
        Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook'])->name('stripe.webhook');
    });
});

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
    Route::get('dashboard', DashboardController::class)->middleware('can:dashboard.view')->name('dashboard');

    /*
     * Each section is guarded by "<section>.view" (read) and "<section>.manage" (write).
     * The manage group is registered first so routes like /products/create are matched
     * before the /products/{product} show route would swallow them.
     * Sections whose controller has no show() only register index.
     */

    // Blog
    Route::middleware('can:blog.manage')->group(function () {
        Route::resource('blog', BlogController::class)->except(['index', 'show']);
        Route::post('blog-comments/{comment}/approve', [BlogController::class, 'approveComment'])->name('blog.comments.approve');
        Route::delete('blog-comments/{comment}', [BlogController::class, 'destroyComment'])->name('blog.comments.destroy');
    });
    Route::resource('blog', BlogController::class)->only(['index', 'show'])->middleware('can:blog.view');

    // Products & categories
    Route::middleware('can:products.manage')->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show'])->names('products');
        Route::resource('product-categories', ProductCategoryController::class)->except(['index', 'show'])->names('productCategories');
    });
    Route::middleware('can:products.view')->group(function () {
        Route::resource('products', ProductController::class)->only(['index', 'show'])->names('products');
        Route::resource('product-categories', ProductCategoryController::class)->only(['index', 'show'])->names('productCategories');
    });

    // Services & categories
    Route::middleware('can:services.manage')->group(function () {
        Route::resource('services', ServiceController::class)->except(['index', 'show'])->names('services');
        Route::resource('service-categories', ServiceCategoryController::class)->except(['index', 'show'])->names('serviceCategories');
        Route::post('services/{service}/schedules', [ServiceController::class, 'storeSchedule'])->name('services.schedules.store');
        Route::delete('services/{service}/schedules/{schedule}', [ServiceController::class, 'destroySchedule'])->name('services.schedules.destroy');
    });
    Route::middleware('can:services.view')->group(function () {
        Route::resource('services', ServiceController::class)->only(['index', 'show'])->names('services');
        Route::resource('service-categories', ServiceCategoryController::class)->only(['index', 'show'])->names('serviceCategories');
    });

    // Customers (controller has no show)
    Route::middleware('can:customers.manage')->group(function () {
        Route::resource('customers', AdminCustomerController::class)->except(['index', 'show'])->names('customers');
        Route::post('verify-customer-email/{id}', [AdminCustomerController::class, 'verifyEmail'])->name('customers.verifyEmail');
    });
    Route::resource('customers', AdminCustomerController::class)->only(['index'])->middleware('can:customers.view')->names('customers');

    // Bookings
    Route::middleware('can:bookings.manage')->group(function () {
        Route::resource('bookings', BookingController::class)->except(['index', 'show'])->names('bookings');
        Route::post('bookings/{booking}/send-email', [BookingController::class, 'sendOrderEmail'])->name('bookings.sendOrderEmail');
        Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    });
    Route::resource('bookings', BookingController::class)->only(['index', 'show'])->middleware('can:bookings.view')->names('bookings');

    // Orders
    Route::put('orders/{order}', [\App\Http\Controllers\OrderController::class, 'update'])->middleware('can:orders.manage')->name('orders.update');
    Route::middleware('can:orders.view')->group(function () {
        Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    });

    // Instructors (controller has no show)
    Route::resource('instructors', InstructorController::class)->except(['index', 'show'])->middleware('can:instructors.manage')->names('instructors');
    Route::resource('instructors', InstructorController::class)->only(['index'])->middleware('can:instructors.view')->names('instructors');

    // Admin users (controller has no show)
    Route::resource('users', UserController::class)->except(['index', 'show'])->middleware('can:users.manage')->names('users');
    Route::resource('users', UserController::class)->only(['index'])->middleware('can:users.view')->names('users');

    // Roles & permissions
    Route::resource('roles', RoleController::class)->except(['index', 'show'])->middleware('can:roles.manage')->names('roles');
    Route::resource('roles', RoleController::class)->only(['index'])->middleware('can:roles.view')->names('roles');

    // Theme management
    Route::middleware('can:themes.manage')->group(function () {
        Route::resource('themes', ThemeController::class)->except(['index', 'show'])->names('themes');
        Route::post('themes/{theme}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
        Route::post('themes/{theme}/deactivate', [ThemeController::class, 'deactivate'])->name('themes.deactivate');
        Route::put('themes/{theme}/file/{key}', [ThemeController::class, 'updateFile'])->name('themes.updateFile');
    });
    Route::middleware('can:themes.view')->group(function () {
        Route::resource('themes', ThemeController::class)->only(['index', 'show'])->names('themes');
        Route::get('themes/{theme}/file/{key}', [ThemeController::class, 'getFile'])->name('themes.file');
    });
});

require __DIR__.'/settings.php';
