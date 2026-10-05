<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

/*
| Public site — navigation per the deck's developer handoff:
| Home | What We Do | MarTech | AI for Marketing | About | Insights | Contact
*/
Route::get('/', [PageController::class, 'home']);
Route::get('/what-we-do', [PageController::class, 'whatWeDo']);
Route::get('/martech', [PageController::class, 'martech']);
Route::get('/ai-for-marketing', [PageController::class, 'ai']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/contact', [PageController::class, 'contact']);
Route::get('/insights', [InsightController::class, 'index']);
Route::get('/insights/{post}', [InsightController::class, 'show']);
Route::get('/sitemap.xml', SitemapController::class);
Route::get('/robots.txt', fn () => response(
    "User-agent: *\nDisallow: /admin\n\nSitemap: ".url('/sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain']
));

Route::post('/contact', [LeadController::class, 'store'])->middleware('throttle:forms');
Route::post('/newsletter', [SubscriberController::class, 'store'])->middleware('throttle:forms');

/*
| Admin panel
*/
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('/login', [Admin\AuthController::class, 'store'])->middleware('throttle:login');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'destroy']);
        Route::get('/', Admin\DashboardController::class);

        Route::get('/content', [Admin\ContentController::class, 'index']);
        Route::get('/content/{section}', [Admin\ContentController::class, 'edit']);
        Route::put('/content/{section}', [Admin\ContentController::class, 'update']);
        Route::delete('/content/{section}', [Admin\ContentController::class, 'destroy']);
        Route::post('/uploads', Admin\UploadController::class);

        Route::get('/leads', [Admin\LeadController::class, 'index']);
        Route::get('/leads/export', [Admin\LeadController::class, 'export']);
        Route::patch('/leads/{lead}', [Admin\LeadController::class, 'update']);
        Route::delete('/leads/{lead}', [Admin\LeadController::class, 'destroy']);

        Route::get('/subscribers', [Admin\SubscriberController::class, 'index']);
        Route::get('/subscribers/export', [Admin\SubscriberController::class, 'export']);
        Route::delete('/subscribers/{subscriber}', [Admin\SubscriberController::class, 'destroy']);

        Route::get('/posts', [Admin\PostController::class, 'index']);
        Route::get('/posts/create', [Admin\PostController::class, 'create']);
        Route::post('/posts', [Admin\PostController::class, 'store']);
        Route::get('/posts/{post:id}/edit', [Admin\PostController::class, 'edit']);
        Route::put('/posts/{post:id}', [Admin\PostController::class, 'update']);
        Route::delete('/posts/{post:id}', [Admin\PostController::class, 'destroy']);
    });
});
