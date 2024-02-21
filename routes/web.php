<?php

use App\Http\Controllers\OutletController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [OutletController::class, 'index'])->name('outlets.home');
Route::get('/outlet/new', [OutletController::class, 'create'])->name('outlets.create');
Route::post('/outlet/new', [OutletController::class, 'store'])->name('outlets.store');
Route::post('/outlet/{id}', [OutletController::class, 'updateCode'])->name('outlets.updateCode');
Route::get('/outlet/{id}', [OutletController::class, 'show'])->name('outlets.show');

Route::get('/outlet/{id}/create/menu', [OutletController::class, 'createMenu'])->name('outlets.createMenu');
Route::post('/outlet/{id}/create/menu', [OutletController::class, 'storeMenu'])->name('outlets.storeMenu');
Route::post('/outlet/delete/menu/{id}', [OutletController::class, 'deleteMenu'])->name('outlets.deleteMenu');

Route::post('/outlet/transaction/update/{id}', [OutletController::class, 'updateTransaction'])->name('outlets.updateTransaction');