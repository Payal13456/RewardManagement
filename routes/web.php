<?php

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


Auth::routes();

Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::group(['Prefix' => '/'], function () {
    Route::group(['middleware' => 'auth'], function () {

        Route::get('/', [App\Http\Controllers\PanelController::class, 'getDashboard'])->name('/');
        
        Route::view('/users-list', 'user-list')->name('users-list');
        Route::get('/users-list/all', [App\Http\Controllers\PanelController::class, 'getAllUsersList'])->name('users-list-all');
        Route::put('/users-list/{action}', [App\Http\Controllers\PanelController::class, 'userBlockUnblock'])->name('users-action');
        
        Route::view('/category', 'category')->name('category');
        Route::post('/category/submit',[App\Http\Controllers\PanelController::class, 'createNewCategory'])->name('category-submit');
        Route::get('/category-list/all', [App\Http\Controllers\PanelController::class, 'getAllCategoryList'])->name('category-list-all');
        Route::delete('/category-list/delete', [App\Http\Controllers\PanelController::class, 'deleteSelectedCategory'])->name('category-list-delete');
        Route::get('/category-list/edit', [App\Http\Controllers\PanelController::class, 'editSelectedCategory'])->name('category-list-edit');

        Route::get('/vendor', [App\Http\Controllers\PanelController::class, 'createNewVendor'])->name('vendor-create');

    });
});
