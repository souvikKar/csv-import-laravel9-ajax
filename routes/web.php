<?php

use App\Http\Controllers\CsvController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('index', [CsvController::class, 'index']);
Route::post('upload', [CsvController::class, 'upload'])->name('uploadFile');
Route::post('import', [CsvController::class, 'import']);

//Route::get('/uploadFile', [CsvController::class, 'index'])->name('uploadFile');

//Route::post('/fetchData', [CsvController::class, 'fetchData'])->name('fetchData');
