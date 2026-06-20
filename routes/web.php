<?php
// routes/web.php - VERSION COMPLÈTE & FONCTIONNELLE

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\EmotionController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\DhikrController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HobbyController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CleanTaskController;
use App\Models\Dhikr;
// 📍 ROUTES PUBLIQUES
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Google Auth
Route::prefix('auth')->name('google.')->group(function () {
    Route::get('google', [GoogleAuthController::class, 'redirectToGoogle'])->name('login');
    Route::get('google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('callback');
});

// 🔐 ROUTES PROTÉGÉES (auth middleware)
Route::middleware(['auth'])->group(function () {
    
    // 🔥 HOME DASHBOARD ✅ CALENDRIER + STAGES
    Route::get('/home', [CalendarEventController::class, 'index'])->name('home');
    
    // 📅 CALENDAR API (CRUD)
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::post('events', [CalendarEventController::class, 'store'])->name('store');
        Route::put('events/{id}', [CalendarEventController::class, 'update'])->name('update');
        Route::delete('events/{id}', [CalendarEventController::class, 'destroy'])->name('destroy');
    });
    
    // ⚙️ Welcome & Settings
    Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');
    Route::post('/set-gender', [WelcomeController::class, 'setGender'])->name('set.gender');
    Route::patch('/settings/profile', [WelcomeController::class, 'updateSettings'])->name('settings.profile.update');
    
    // 💼 INTERNSHIP TRACKER ✅ DELETE FONCTIONNE
    Route::prefix('internship')->name('internship.')->group(function () {
        Route::get('/', [InternshipController::class, 'index'])->name('index');
        Route::post('/', [InternshipController::class, 'store'])->name('store');
        Route::get('{internship}/edit', [InternshipController::class, 'edit'])->name('edit');
        Route::put('{internship}', [InternshipController::class, 'update'])->name('update');
        Route::delete('{internship}', [InternshipController::class, 'destroy'])->name('destroy'); // ✅ ID = {internship}
        Route::get('/company-search', [InternshipController::class, 'searchCompany'])->name('company.search');
    });
    
    // 🎯 HOBBIES
    Route::prefix('hobbies')->name('hobbies.')->group(function () {
        Route::get('/', [HobbyController::class, 'index'])->name('index');
        Route::post('/', [HobbyController::class, 'store'])->name('store');
        Route::patch('{id}/toggle', [HobbyController::class, 'toggle'])->name('toggle');
        Route::delete('{id}', [HobbyController::class, 'destroy'])->name('destroy');
    });
    
    // 🌤️ Weather
    Route::get('/weather-view', [WeatherController::class, 'showWithCalendar'])->name('weather.view');
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather.api');
    
    // 🕌 Prayer API
    Route::get('/prayer-times', [App\Http\Controllers\PrayerController::class, 'getPrayerTimes'])->name('prayer.times');
    
    // 😊 Emotions
    Route::post('/emotion/analyze', [EmotionController::class, 'analyze'])->name('emotion.analyze');
    
    // 💰 Finance Tracker
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::post('income', [FinanceController::class, 'storeIncome'])->name('income.store');
        Route::delete('income/{id}', [FinanceController::class, 'deleteIncome'])->name('income.delete');
        Route::post('expense', [FinanceController::class, 'storeExpense'])->name('expense.store');
        Route::delete('expense/{id}', [FinanceController::class, 'deleteExpense'])->name('expense.delete');
        Route::post('debt', [FinanceController::class, 'storeDebt'])->name('debt.store');
        Route::put('debt/{id}', [FinanceController::class, 'updateDebt'])->name('debt.update');
        Route::delete('debt/{id}', [FinanceController::class, 'deleteDebt'])->name('debt.delete');
        Route::post('wishlist', [FinanceController::class, 'storeWishlist'])->name('wishlist.store');
        Route::put('wishlist/{id}/purchased', [FinanceController::class, 'markPurchased'])->name('wishlist.purchased');
        Route::delete('wishlist/{id}', [FinanceController::class, 'deleteWishlist'])->name('wishlist.delete');
    });
    
    // 📋 Tasks
    Route::resource('tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    
    // 🌙 Muslim Hub
    Route::get('/muslim', fn() => view('muslim.index'))->name('muslim.index');
    
    // 📖 Quran
    Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
    Route::get('/quran/{id}', [QuranController::class, 'show'])->name('quran.show');
    
    // 🧎 Dhikr
    Route::get('/dhikr', [DhikrController::class, 'index'])->name('dhikr.index');
    Route::get('/dhikr/{category}', [DhikrController::class, 'show'])->name('dhikr.show');
    
    // 📚 Study
    Route::prefix('study')->name('study.')->group(function () {
        Route::get('management', [StudyController::class, 'index'])->name('index');
        Route::post('lesson', [StudyController::class, 'storeLesson'])->name('lesson.store');
        Route::delete('lesson/{id}', [StudyController::class, 'deleteLesson'])->name('lesson.delete');
        Route::post('homework', [StudyController::class, 'storeHomework'])->name('homework.store');
        Route::patch('homework/{id}', [StudyController::class, 'toggleHomework'])->name('homework.toggle');
    });
    
    // 🧼 Cleaning Routine
    Route::get('/cleantask', [CleanTaskController::class, 'index'])->name('cleantask.index');
    Route::post('/cleantask/{id}/toggle', [CleanTaskController::class, 'toggleStatus'])->name('cleantask.toggle');

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});