<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Chat\Index;
use App\Livewire\Chat\Main;
use App\Livewire\Explore;
use App\Livewire\Home;
use App\Livewire\Post\View\Page;
use App\Livewire\Profile\Home as ProfileHome;
use App\Livewire\Profile\Reels;
use App\Livewire\Profile\Saved;
use App\Livewire\Reels as LivewireReels;
use Illuminate\Support\Facades\Route;

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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/', Home::class)->name('Home');
    Route::get('/explore', Explore::class)->name('explore');
    Route::get('/reels', LivewireReels::class)->name('reels');

    Route::get('/post/{post}', Page::class)->name('post');


    Route::get('/chat', Index::class)->name('chat');
    Route::get('/chat/{chat}', Main::class)->name('chat.main');







    Route::get('/profile/{user}', ProfileHome::class)->name('profile.home');
    Route::get('/profile/{user}/reels', Reels::class)->name('profile.reels');
    Route::get('/profile/{user}/saved', Saved::class)->name('profile.saved');
});


// Public routes
Route::get('admin', [AuthController::class, 'login_admin']);
Route::post('admin', [AuthController::class, 'Auth_login_admin']);
Route::get('admin/logout', [AuthController::class, 'logout_admin']);


Route::group(['middleware' => 'admin'], function () {

    Route::get('admin/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('admin/admin/list', [AdminController::class, 'list']);
    Route::get('admin/admin/add', [AdminController::class, 'add']);
    Route::post('admin/admin/add', [AdminController::class, 'insert']);
    Route::get('admin/admin/edit/{id}', [AdminController::class, 'edit']);
    Route::post('admin/admin/edit/{id}', [AdminController::class, 'update']);
    Route::get('admin/admin/delete/{id}', [AdminController::class, 'delete']);
    Route::delete('admin/admin/delete/{id}', [AdminController::class, 'delete']);
    Route::get('admin/customer/list', [AdminController::class, 'customer_list']);
});





require __DIR__ . '/auth.php';
