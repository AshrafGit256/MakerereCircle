<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Chat2Controller;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PostController;
use App\Livewire\Chat\Index;
use App\Livewire\Chat\Main;
use App\Livewire\Explore;
use App\Livewire\Home;
use App\Livewire\ClaimForm;
use App\Livewire\Post\View\Page;
use App\Livewire\Profile\Home as ProfileHome;
use App\Livewire\Profile\Reels;
use App\Livewire\Profile\Saved;
use App\Livewire\Groups\Show as GroupShow;

use App\Livewire\Reels as LivewireReels;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public route for landing page
Route::get('/landing', function () {
    return view('landingpage.index');
})->name('dashboard');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', Home::class)->name('Home');
    // Route::post('auth_register', [AuthController::class, 'auth_register']);

    Route::get('/explore', Explore::class)->name('explore');
    Route::get('/reels', LivewireReels::class)->name('reels');

    Route::get('/post/{post}', Page::class)->name('post');

    Route::get('/chat', Index::class)->name('chat');
    Route::get('/chat/{chat}', Main::class)->name('chat.main');

    Route::get('/profile/{user}', ProfileHome::class)->name('profile.home');
    Route::get('/profile/{user}/reels', Reels::class)->name('profile.reels');
    Route::get('/profile/{user}/saved', Saved::class)->name('profile.saved');
    Route::get('/settings', \App\Livewire\Settings\Page::class)->name('settings');

    // Placeholder routes for new sections
    Route::get('/network', \App\Livewire\Networks::class)->name('network');
    Route::get('/events', function(){ return response('<h1>Events</h1><p>Campus events listing coming soon.</p>', 200); })->name('events');
    Route::get('/market', function(){ return response('<h1>Market</h1><p>Marketplace listings coming soon.</p>', 200); })->name('market');


    // Open groups (colleges & places)
    Route::get('/groups/{group:slug}', GroupShow::class)->name('groups.show');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login'); // Redirects to login page
    })->name('logout');

});


// Public routes
Route::get('admin', [AuthController::class, 'login_admin']);
Route::post('admin', [AuthController::class, 'Auth_login_admin']);
Route::get('admin/logout', [AuthController::class, 'logout_admin']);



Route::group(['middleware' => 'common'], function () {
    Route::get('chat2', [Chat2Controller::class, 'chat2']);
    Route::post('submit_message', [Chat2Controller::class, 'submit_message']);
});



Route::group(['middleware' => 'admin'], function () {

    Route::get('admin/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('admin/admin/list', [AdminController::class, 'list']);
    Route::get('admin/admin/add', [AdminController::class, 'add']);
    Route::post('admin/admin/add', [AdminController::class, 'insert']);
    Route::get('admin/admin/edit/{id}', [AdminController::class, 'edit']);
    Route::post('admin/admin/edit/{id}', [AdminController::class, 'update']);
    Route::get('admin/admin/delete/{id}', [AdminController::class, 'delete']);
    Route::delete('admin/admin/delete/{id}', [AdminController::class, 'delete']);
    Route::get('admin/user/list', [AdminController::class, 'user_list']);

    Route::get('admin/order/list', [OrderController::class, 'list']);
    Route::get('admin/order/detail/{id}', [OrderController::class, 'order_detail']);
    Route::get('admin/order_status', [OrderController::class, 'order_status']);

    Route::get('admin/post/list', [PostController::class, 'list']);
    Route::get('admin/post/add', [PostController::class, 'add']);
    Route::post('admin/post/add', [PostController::class, 'insert']);
    Route::get('admin/post/edit/{id}', [PostController::class, 'edit']);
    Route::post('admin/post/edit/{id}', [PostController::class, 'update']);
    Route::get('admin/post/delete/{id}', [PostController::class, 'delete']);
});

// Route::get('search', [P])




require __DIR__ . '/auth.php';
