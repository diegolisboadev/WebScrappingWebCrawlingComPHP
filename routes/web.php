<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebScrappingController;

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

Route::get('/web',[WebScrappingController::class, 'web'])->name('web');
Route::get('/webAjax', [WebScrappingController::class, 'webAjax'])->name('webAjax');

Route::get('/webAjax2', [WebScrappingController::class, 'webAjax2'])->name('webAjax2');
Route::get('/webAjax3', [WebScrappingController::class, 'webAjax3'])->name('webAjax3');
