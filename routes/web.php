<?php

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Session;

Route::get('/kelaz-nur', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login/profile', [AuthController::class, 'showLogin'])->name('login.profile');
Route::post('/login/profile', [RoomController::class, 'profile'])->name('profile.post');
Route::post('/login/join', [AuthController::class, 'showJoin'])->name('login.join');
Route::post('/login/join/post', [RoomController::class, 'roomLogin'])->name('join.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    // if (!Session::has('user')) {
    //     return redirect('/login')->withErrors(['unauthorized' => 'Silakan login terlebih dahulu!']);
    // } else {
        return view('index');
    // }
});

Route::get('/room', function () {
    return view('index');
})->name('room.index');

Route::get('/puzzle/{player_id}/{room_id}/{gallery_id}', [RoomController::class, 'puzzleMain']);
Route::get('/gallery', [RoomController::class, 'gallery'])->name('gallery.index');

Route::post('/room/create', [RoomController::class, 'createRoom'])->name('room.create');
Route::post('/room/join', [RoomController::class, 'joinRoom'])->name('room.join');
Route::get('/room/{code}', [RoomController::class, 'showRoom'])->name('room.show');
Route::get('/roomLogin', [RoomController::class, 'roomLogin'])->name('room.login');
Route::get('/room/{code}/players', [RoomController::class, 'getPlayers'])->name('room.players');
Route::get('/room/{room}/countdown-time', [RoomController::class, 'getCountdownTime'])->name('room.countdown-time');
Route::get('/room/{code}/data', [RoomController::class, 'getRoomData']);
Route::get('/room/{code}/start-time', [RoomController::class, 'getStartTime'])->name('room.start-time');

Route::post('/saveGameResult', [GameController::class, 'store']);
Route::get('/getWinner/{room_id}/{round}', [GameController::class, 'determineWinner']);
Route::get('/checkAllPlayersFinished/{room_id}/{round}', [GameController::class, 'checkAllPlayersFinished']);
Route::post('/updateLevel/{playerId}', [GameController::class, 'updateUserLevel']);
Route::get('/getUserData/{userId}', [GameController::class, 'getUserData']);
Route::post('/resetWins/{playerId}', [GameController::class, 'resetWins']);

Route::resource('galleries', GalleryController::class);
Route::get('/showGalleries/{id}', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/showGalleries', [GalleryController::class, 'showAll'])->name('galleries.show');
Route::get('/detail/{id}', [GalleryController::class, 'show'])->name('galleries.detail');

Route::get('/tutorial', [RoomController::class, 'tutorial'])->name('tutorial.index');
Route::get('/about', [RoomController::class, 'about'])->name('about.index');
Route::get('/profile', [RoomController::class, 'profile'])->name('profile.index');