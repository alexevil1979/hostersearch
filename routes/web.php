<?php

use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\GoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Partner\DashboardController as PartnerDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', SearchController::class)->name('search');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/locale/{locale}', [LocaleController::class, 'locale'])->name('locale.switch');
Route::get('/currency/{currency}', [LocaleController::class, 'currency'])->name('currency.switch');

Route::get('/go/{company:slug}', GoController::class)->name('go');

Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');

Route::get('/tools/web-hosting-comparison', [ComparisonController::class, 'index'])->name('comparisons.index');
Route::post('/tools/web-hosting-comparison', [ComparisonController::class, 'build'])->name('comparisons.build');
Route::get('/tools/web-hosting-comparison/{slugs}', [ComparisonController::class, 'show'])
    ->where('slugs', '[a-z0-9\-]+')
    ->name('comparisons.show');

Route::get('/web-hosting-advisor', [AdvisorController::class, 'index'])->name('advisor.index');
Route::post('/web-hosting-advisor', [AdvisorController::class, 'store'])->name('advisor.store');
Route::get('/web-hosting-advisor/result/{result}', [AdvisorController::class, 'result'])->name('advisor.result');

Route::get('/hosting-company/{company:slug}-reviews', [CompanyController::class, 'show'])->name('companies.show');
Route::post('/hosting-company/{company:slug}-reviews/reviews', [CompanyController::class, 'storeReview'])
    ->middleware('auth')
    ->name('companies.reviews.store');

Route::get('/claim', [ClaimController::class, 'create'])->name('claim.create');
Route::post('/claim', [ClaimController::class, 'store'])->middleware('auth')->name('claim.store');

Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/about', fn () => redirect()->route('pages.show', 'about'))->name('about');
Route::get('/methodology', fn () => redirect()->route('pages.show', 'methodology'))->name('methodology');
Route::get('/faq', fn () => redirect()->route('pages.show', 'faq'))->name('faq');
Route::get('/who-is-hosting', fn () => redirect()->route('pages.show', 'who-is-hosting'))->name('who-is-hosting');

// Category "best of" pages last so they don't swallow other routes
Route::get('/{category:slug}', [CategoryController::class, 'show'])
    ->where('category', '^(?!login|register|dashboard|admin|partner|profile|password|forgot-password|reset-password|verify-email|confirm-password|search|coupons|claim|about|methodology|faq|pages|go|locale|currency|tools|web-hosting-advisor|hosting-company|up).+')
    ->name('categories.show');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user?->isPartner()) {
        return redirect()->route('partner.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:partner,admin,super_admin'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/', [PartnerDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
