<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::group([
    'middleware' => 'jwt.verify'
], function ($router) {
    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('profile');

    Route::prefix('/user')->group(function () {
        Route::get('/list', [UserController::class, 'list'])->name('list');
        Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('delete');
    });

    Route::prefix('/book')->group(function () {
        Route::get('/list', [BookController::class, 'list'])->name('list');
        Route::post('/store', [BookController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BookController::class, 'edit'])->name('edit');
        Route::post('/update/{book}', [BookController::class, 'update'])->name('update');
        Route::delete('/delete/{book}', [BookController::class, 'delete'])->name('delete');
    });

    Route::post('/book_renting/{book}/{user}', [BookController::class, 'bookRenting'])->name('book.renting');
    Route::post('/book_return', [BookController::class, 'bookReturn'])->name('book.return');

    Route::get('/userList', [UserController::class, 'UserBookRanted'])->name('user.list');
});
