<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\TagController as FrontendTagController;
use App\Http\Controllers\Frontend\WorkScheduleController as FrontendWorkScheduleController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CKEditorController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\WebsiteLinkController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\WorkScheduleController;


/*
|--------------------------------------------------------------------------
| Frontend routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap');

Route::get('/search', [SearchController::class, 'index'])
    ->name('frontend.search');

Route::get('/lich-cong-tac', [FrontendWorkScheduleController::class, 'index'])
    ->name('frontend.work_schedules.index');

Route::get('/posts/{slug}', [FrontendPostController::class, 'show'])
    ->name('frontend.posts.show');

Route::get('/category/{slug}', [FrontendCategoryController::class, 'show'])
    ->name('frontend.categories.show');
    


/*
|--------------------------------------------------------------------------
| Breeze dashboard / profile
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect('/admin');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,editor,reporter'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

        Route::middleware('permission:user.manage')
            ->resource('users', UserController::class);

        Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])
            ->name('ckeditor.upload');

        Route::get('posts-pending', [PostController::class, 'pending'])
    ->middleware('permission:post.publish')
    ->name('posts.pending');

Route::patch('posts/{post}/reject', [PostController::class, 'reject'])
    ->middleware('permission:post.publish')
    ->name('posts.reject');
Route::get('posts-trash', [PostController::class, 'trash'])
    ->middleware('permission:post.delete')
    ->name('posts.trash');

Route::patch('posts/{id}/restore', [PostController::class, 'restore'])
    ->middleware('permission:post.delete')
    ->name('posts.restore');

Route::delete('posts/{id}/force-delete', [PostController::class, 'forceDelete'])
    ->middleware('permission:post.delete')
    ->name('posts.force_delete');
        Route::middleware('permission:category.manage')
            ->resource('categories', CategoryController::class);

            Route::delete('post-attachments/{attachment}', [PostController::class, 'deleteAttachment'])
    ->middleware('permission:post.edit')
    ->name('post_attachments.destroy');
        Route::middleware('permission:post.create')
            ->resource('posts', PostController::class);

        Route::patch('posts/{post}/publish', [PostController::class, 'publish'])
            ->middleware('permission:post.publish')
            ->name('posts.publish');

        Route::patch('posts/{post}/draft', [PostController::class, 'draft'])
            ->middleware('permission:post.publish')
            ->name('posts.draft');

        Route::middleware('permission:page.manage')
    ->resource('work-schedules', WorkScheduleController::class);

        Route::middleware('permission:banner.manage')
            ->resource('banners', BannerController::class);

        Route::middleware('permission:menu.manage')
            ->resource('menus', MenuController::class);

        Route::middleware('permission:page.manage')
            ->resource('pages', PageController::class);

        Route::middleware('permission:media.manage')
            ->resource('media', MediaController::class)
            ->only(['index', 'store', 'destroy']);

        Route::get('settings', [SettingController::class, 'edit'])
            ->middleware('permission:setting.manage')
            ->name('settings.edit');

        Route::post('settings', [SettingController::class, 'update'])
            ->middleware('permission:setting.manage')
            ->name('settings.update');

        Route::middleware('permission:category.manage')
            ->resource('tags', TagController::class);
        Route::middleware('permission:setting.manage')
    ->resource('website-links', WebsiteLinkController::class);
            Route::get('notifications/unread/data', [NotificationController::class, 'unreadData'])
            ->name('notifications.unread_data');
            Route::get('notifications', [NotificationController::class, 'index'])
    ->name('notifications.index');

Route::get('notifications/{notification}/read', [NotificationController::class, 'read'])
    ->name('notifications.read');

Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.mark_all_read');
    Route::middleware('permission:setting.manage')
    ->resource('videos', VideoController::class);

    });


/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/
Route::get('/tag/{slug}', [FrontendTagController::class, 'show'])
    ->name('frontend.tags.show');
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Static page route - phải để cuối cùng
|--------------------------------------------------------------------------
*/


Route::get('/{slug}', [FrontendPageController::class, 'show'])
    ->name('frontend.pages.show');