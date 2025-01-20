<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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

Route::get('/', function () {
    return view('welcome');
});


 // 他のログイン必須のルートも追加
Route::middleware(['auth'])->group(function () {
    Route::resource('reports', ReportController::class);
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');
});

//->except(['create', 'edit']);で特定のアクションをはじける



require __DIR__.'/auth.php';


