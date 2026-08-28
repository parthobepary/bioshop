<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\ShopProfileController;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// SEO routes
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Landing pages
Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('pricing');
Route::get('/features', [LandingController::class, 'features'])->name('features');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::post('/contact', [LandingController::class, 'submitContact'])->name('contact.submit');
Route::get('/terms', [LandingController::class, 'terms'])->name('terms');
Route::get('/privacy', [LandingController::class, 'privacy'])->name('privacy');

// Profile Setup (after registration, before accessing dashboard)
Route::middleware(['auth'])->group(function () {
    Route::get('/setup', [ProfileSetupController::class, 'index'])->name('profile.setup.index');
    Route::post('/setup', [ProfileSetupController::class, 'store'])->name('profile.setup.store');
    Route::post('/setup/check-username', [ProfileSetupController::class, 'checkUsername'])->name('profile.setup.check-username');
});

// Dashboard routes (require profile to be complete)
Route::middleware(['auth', 'verified', 'profile.complete'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Analytics
    Route::get('/dashboard/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Shop Profile Settings
    Route::get('/settings/profile', [ShopProfileController::class, 'edit'])->name('shop.profile.edit');
    Route::post('/settings/profile', [ShopProfileController::class, 'update'])->name('shop.profile.update');
    Route::delete('/settings/profile/photo', [ShopProfileController::class, 'deletePhoto'])->name('shop.profile.delete-photo');

    // Links Management
    Route::get('/dashboard/links', [LinkController::class, 'index'])->name('links.index');
    Route::post('/dashboard/links', [LinkController::class, 'store'])->name('links.store');
    Route::put('/dashboard/links/{link}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/dashboard/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::post('/dashboard/links/{link}/toggle', [LinkController::class, 'toggle'])->name('links.toggle');
    Route::post('/dashboard/links/reorder', [LinkController::class, 'reorder'])->name('links.reorder');

    // Categories Management
    Route::get('/dashboard/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/dashboard/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/dashboard/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/dashboard/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/dashboard/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

    // Products Management
    Route::get('/dashboard/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/dashboard/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/dashboard/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/dashboard/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/dashboard/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/dashboard/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/dashboard/products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
    Route::post('/dashboard/products/reorder', [ProductController::class, 'reorder'])->name('products.reorder');

    // Payment Methods Management
    Route::get('/dashboard/payment', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('/dashboard/payment', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::put('/dashboard/payment/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/dashboard/payment/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('/dashboard/payment/{paymentMethod}/toggle', [PaymentMethodController::class, 'toggle'])->name('payment-methods.toggle');
    Route::post('/dashboard/payment/reorder', [PaymentMethodController::class, 'reorder'])->name('payment-methods.reorder');

    // Billing & Subscriptions
    Route::get('/dashboard/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/dashboard/billing/upgrade', [BillingController::class, 'upgrade'])->name('billing.upgrade');
    Route::post('/dashboard/billing/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::post('/dashboard/billing/cancel', [BillingController::class, 'cancelSubscription'])->name('billing.cancel');

    // WhatsApp Management
    Route::get('/dashboard/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp.index');
    Route::get('/dashboard/whatsapp/settings', [WhatsAppController::class, 'settings'])->name('whatsapp.settings');
    Route::post('/dashboard/whatsapp/settings', [WhatsAppController::class, 'updateSettings'])->name('whatsapp.settings.update');
    Route::get('/dashboard/whatsapp/{conversation}', [WhatsAppController::class, 'show'])->name('whatsapp.show');
    Route::post('/dashboard/whatsapp/{conversation}/reply', [WhatsAppController::class, 'reply'])->name('whatsapp.reply');
    Route::post('/dashboard/whatsapp/{conversation}/status', [WhatsAppController::class, 'updateStatus'])->name('whatsapp.status');

    // WhatsApp API endpoints
    Route::get('/api/whatsapp/conversations', [WhatsAppController::class, 'apiConversations'])->name('whatsapp.api.conversations');
    Route::get('/api/whatsapp/{conversation}/messages', [WhatsAppController::class, 'apiMessages'])->name('whatsapp.api.messages');
});

// User Account Settings (Breeze default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{user}/toggle-ban', [UserManagementController::class, 'toggleBan'])->name('admin.users.toggle-ban');
    Route::post('/users/{user}/make-admin', [UserManagementController::class, 'makeAdmin'])->name('admin.users.make-admin');
    Route::post('/users/{user}/remove-admin', [UserManagementController::class, 'removeAdmin'])->name('admin.users.remove-admin');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

    // Subscriptions
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('admin.subscriptions.index');
    Route::post('/subscriptions/{subscription}/activate', [AdminSubscriptionController::class, 'activate'])->name('admin.subscriptions.activate');
    Route::post('/subscriptions/{subscription}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('admin.subscriptions.cancel');
    Route::post('/subscriptions/{subscription}/extend', [AdminSubscriptionController::class, 'extend'])->name('admin.subscriptions.extend');

    // Payments
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');
    Route::post('/payments/{payment}/refund', [AdminPaymentController::class, 'refund'])->name('admin.payments.refund');
});

// Tracking routes (public, no auth required)
Route::post('/track/link/{linkId}', [PublicPageController::class, 'trackLinkClick']);
Route::post('/track/product/{productId}', [PublicPageController::class, 'trackProductView']);
Route::post('/track/whatsapp', [PublicPageController::class, 'trackWhatsappClick']);

// WhatsApp Webhook (public, no auth - verified by token)
Route::get('/webhook/whatsapp', [WhatsAppController::class, 'verifyWebhook']);
Route::post('/webhook/whatsapp', [WhatsAppController::class, 'handleWebhook']);

// Public shop page (must be last to avoid conflicts with other routes)
Route::get('/{username}', [PublicPageController::class, 'show'])
    ->where('username', '^(?!login|register|dashboard|profile|setup|settings|api|storage|track|admin|pricing|features|about|contact|terms|privacy).*$')
    ->name('public.shop');
