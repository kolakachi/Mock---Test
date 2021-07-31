<?php

use App\Http\Controllers\BoardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [BoardController::class,'index'])->name('user.board');
Route::post('/store', [BoardController::class,'store'])->name('user.board.store');
Route::get('/dump-sql', [BoardController::class,'dumpSQL'])->name('user.board.dump-sql');