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
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ClaimController as AdminClaimController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
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

    Route::post('companies/bulk', [AdminCompanyController::class, 'bulk'])->name('companies.bulk');
    Route::resource('companies', AdminCompanyController::class)->except(['show']);
    Route::post('companies/{company}/plans', [AdminPlanController::class, 'store'])->name('companies.plans.store');
    Route::put('companies/{company}/plans/{plan}', [AdminPlanController::class, 'update'])->name('companies.plans.update');
    Route::delete('companies/{company}/plans/{plan}', [AdminPlanController::class, 'destroy'])->name('companies.plans.destroy');

    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::post('reviews/{review}/reply', [AdminReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::put('coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');

    Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('pages', [AdminPageController::class, 'index'])->name('pages.index');
    Route::post('pages', [AdminPageController::class, 'store'])->name('pages.store');
    Route::put('pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');
    Route::delete('pages/{page}', [AdminPageController::class, 'destroy'])->name('pages.destroy');

    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');

    Route::get('claims', [AdminClaimController::class, 'index'])->name('claims.index');
    Route::post('claims/{claim}/approve', [AdminClaimController::class, 'approve'])->name('claims.approve');
    Route::post('claims/{claim}/reject', [AdminClaimController::class, 'reject'])->name('claims.reject');

    Route::get('settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('activity', [AdminActivityLogController::class, 'index'])->name('activity.index');
});

Route::middleware(['auth', 'role:partner,admin,super_admin'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/', [PartnerDashboardController::class, 'index'])->name('dashboard');
    Route::put('/profile', [PartnerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/plans', [PartnerDashboardController::class, 'storePlan'])->name('plans.store');
    Route::delete('/plans/{plan}', [PartnerDashboardController::class, 'destroyPlan'])->name('plans.destroy');
    Route::post('/reviews/{review}/reply', [PartnerDashboardController::class, 'replyReview'])->name('reviews.reply');
});

require __DIR__.'/auth.php';

// Category catch-all after named app routes
Route::get('/{category:slug}', [CategoryController::class, 'show'])
    ->where('category', '^(?!login|register|dashboard|admin|partner|profile|password|forgot-password|reset-password|verify-email|confirm-password|search|coupons|claim|about|methodology|faq|pages|go|locale|currency|tools|web-hosting-advisor|hosting-company|sitemap\\.xml|up).+')
    ->name('categories.show');
