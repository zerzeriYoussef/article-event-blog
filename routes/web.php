<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        Route::feeds();

        Route::get('/', [HomeController::class, 'index'])->name('welcome');

        Route::get('/contact', [App\Http\Controllers\ContactMessageController::class, 'index'])->name('contact');
        
        Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
        
        // Event participation routes
        Route::middleware('auth')->group(function () {
            Route::post('/posts/{post:slug}/join', [App\Http\Controllers\EventParticipantController::class, 'store'])->name('events.join');
            Route::delete('/posts/{post:slug}/leave', [App\Http\Controllers\EventParticipantController::class, 'destroy'])->name('events.leave');
            Route::post('/posts/{post:slug}/participants/{participant}/accept', [App\Http\Controllers\EventParticipantController::class, 'accept'])->name('events.participants.accept');
            Route::post('/posts/{post:slug}/participants/{participant}/reject', [App\Http\Controllers\EventParticipantController::class, 'reject'])->name('events.participants.reject');
        });

        // Route::Resource('posts', PostController::class);



        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            
            // User's event participations
            Route::get('/my-events', [App\Http\Controllers\MyEventsController::class, 'index'])->name('my-events');
            
            // Notifications
            Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index']);
            Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
            Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
        });

        require __DIR__ . '/auth.php';
    }
);
