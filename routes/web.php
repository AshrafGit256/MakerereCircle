<?php

use App\Http\Controllers\ProfileController;
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



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/', Home::class)->name('Home');
    Route::get('/explore', Explore::class)->name('explore');
    Route::get('/reels', LivewireReels::class)->name('reels');

    Route::get('/post/{post}', Page::class)->name('post');


    Route::get('/chat',Index::class)->name('chat');
    Route::get('/chat/{chat}',Main::class)->name('chat.main');







    Route::get('/profile/{user}',ProfileHome::class)->name('profile.home');
    Route::get('/profile/{user}/reels',Reels::class)->name('profile.reels');
    Route::get('/profile/{user}/saved',Saved::class)->name('profile.saved');
    



});

Route::get('admin', function () {
   return view('admin.auth.login'); 
});

Route::get('admin/dashboard', function () {
    return view('admin.dashboard'); 
 });

 Route::get(' admin/admin/list', function () {
    return view('admin.admin.list'); 
 });


Route::get('/', function () {
    return view('welcome'); 
 });

require __DIR__.'/auth.php';
