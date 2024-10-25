<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GutingAuthController;
use App\Http\Controllers\GutingAdminUserController;
use App\Http\Controllers\GutingCategoryController;
use App\Http\Controllers\GutingProductsController;
use App\Http\Controllers\GutingChartController;
use App\Http\Controllers\GutingAdminController;
use App\Http\Controllers\GutingPostController;
use App\Http\Controllers\GutingBlogController;
use App\Http\Controllers\GutingCommentController;
use App\Http\Controllers\GutingActivityLogController;
use App\Http\Controllers\GutingAddpostController;
use App\Http\Controllers\GutingUserController;
use App\Http\Controllers\GutingProfileController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login'); 
});

// Authentication Routes
Route::get('/register', [GutingAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [GutingAuthController::class, 'register']);
Route::get('/login', [GutingAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [GutingAuthController::class, 'login']);

// OTP Routes
Route::get('/otp', [GutingAuthController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp', [GutingAuthController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/otp/resend', [GutingAuthController::class, 'resendOtp'])->name('otp.resend');

// Logout Route
Route::post('/logout', [GutingAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard Routes
Route::get('/dashboard', [GutingAdminController::class, 'adminDashboard'])->middleware('auth')->name('dashboard'); 
Route::get('/user/dashboard', [GutingAuthController::class, 'userDashboard'])->middleware('auth')->name('user.dashboard');

Route::get('/dashboard', [GutingAuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::get('/admin/rolandodashboard', [GutingAuthController::class, 'adminDashboard'])->middleware('auth');
Route::get('/user/rolandodashboard', [GutingAuthController::class, 'userDashboard'])->middleware('auth');





// CRUD User Routes
Route::resource('users', GutingAdminController::class)->except(['create', 'show']);
Route::get('/users/delete/{id}', [GutingAdminController::class, 'delete'])->name('users.delete');

// CRUD Category Routes
Route::resource('categories', GutingCategoryController::class)->except(['create', 'show']);
Route::get('/categories/delete/{id}', [GutingCategoryController::class, 'delete'])->name('categories.delete');

// CRUD Products Routes
Route::resource('products', GutingProductsController::class)->except(['create', 'show']);
Route::get('/products/delete/{id}', [GutingProductsController::class, 'delete'])->name('products.delete');

// Chart Routes
Route::get('/charts', [GutingChartController::class, 'showCharts'])->name('charts');
Route::get('/products/create', [GutingChartController::class, 'showProductForm'])->name('products.create');

// Post Routes
Route::resource('posts', GutingPostController::class);
Route::get('/posts/delete/{id}', [GutingPostController::class, 'delete'])->name('posts.delete');
Route::post('posts/{post}/toggle-publish', [GutingPostController::class, 'togglePublish'])->name('posts.togglePublish');

// Blog Routes
Route::prefix('blog')->group(function() {
    Route::get('/', [GutingBlogController::class, 'index'])->name('blog.index');
    Route::post('/', [GutingBlogController::class, 'store'])->name('blog.store');
    Route::get('/edit/{id}', [GutingBlogController::class, 'edit'])->name('blog.edit');
    Route::put('/update/{id}', [GutingBlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [GutingBlogController::class, 'destroy'])->name('blog.destroy');

});

// Comment Routes
Route::prefix('comments')->group(function() {
    Route::post('/', [GutingCommentController::class, 'store'])->name('comments.store');
    Route::delete('/{comment}', [GutingCommentController::class, 'destroy'])->name('comments.destroy');
    Route::put('/update/{comment}', [GutingCommentController::class, 'update'])->name('comments.update');
});

// Activity Logs Route
Route::get('/activity-log', [GutingActivityLogController::class, 'index'])->name('activity.log');

// Add Post Routes
Route::prefix('addposts')->group(function() {
    Route::get('/', [GutingAddpostController::class, 'index'])->name('addpost.index');
    Route::post('/', [GutingAddpostController::class, 'store'])->name('addposts.store');
    Route::get('/edit/{post}', [GutingAddpostController::class, 'edit'])->name('addpost.edit');
    Route::put('/edit/{post}', [GutingAddpostController::class, 'update'])->name('addposts.update');
    Route::delete('/addposts/{id}', [GutingAddpostController::class, 'destroy'])->name('addpost.destroy');

});

Route::get('/user/dashboard', [GutingUserController::class, 'dashboard'])->middleware('auth')->name('user.rolandodashboard');
Route::get('/user/dashboard', [GutingUserController::class, 'dashboard'])->name('user.rolandodashboard');
Route::get('/user/dashboard', [GutingUserController::class, 'dashboard'])->name('user.dashboard');
Route::get('/admin/dashboard', [GutingAdminController::class, 'adminDashboard'])->middleware('auth')->name('admin.dashboard'); 
Route::post('/update-profile', [GutingUserController::class, 'updateProfile'])->name('update.profile')->middleware('auth');


Route::post('/user/deactivate', [GutingUserController::class, 'deactivate'])->middleware('auth')->name('user.deactivate');


Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [GutingProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [GutingProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/otp', [GutingProfileController::class, 'showOtpForm'])->name('profile.otp.form');
    Route::post('/profile/verify-otp', [GutingProfileController::class, 'verifyOtp'])->name('profile.otp.verify');
    Route::put('/profile/update-password', [GutingProfileController::class, 'updatePassword'])->name('profile.update.password');
});
Route::get('/users', [GutingAdminController::class, 'index'])->name('users.index');
Route::get('/users', [GutingAdminController::class, 'index'])->name('users.index');

