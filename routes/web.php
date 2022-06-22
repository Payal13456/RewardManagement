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
        Route::get('/users-detail', [App\Http\Controllers\PanelController::class, 'getUsersAllDetails'])->name('users-detail');
        
        Route::view('/category', 'category')->name('category');
        Route::post('/category/submit',[App\Http\Controllers\PanelController::class, 'createNewCategory'])->name('category-submit');
        Route::get('/category-list/all', [App\Http\Controllers\PanelController::class, 'getAllCategoryList'])->name('category-list-all');
        Route::delete('/category-list/delete', [App\Http\Controllers\PanelController::class, 'deleteSelectedCategory'])->name('category-list-delete');
        Route::get('/category-list/edit', [App\Http\Controllers\PanelController::class, 'editSelectedCategory'])->name('category-list-edit');

        Route::get('/vendor', [App\Http\Controllers\PanelController::class, 'createNewVendor'])->name('vendor');
        Route::view('/vendor-list','vendor-list')->name('vendor-list');
        Route::get('/vendor/list/all',[App\Http\Controllers\PanelController::class, 'getAllVendorList'])->name('vendor-list-all');

        Route::get('/message-to-users',[App\Http\Controllers\PanelController::class, 'messageToUsersList'])->name('message-to-users');
        Route::post('/message-to-users/submit',[App\Http\Controllers\PanelController::class, 'messageToUsersSubmit'])->name('message-to-user-submit');

        Route::view('/subscription-plans', 'subscription-plans')->name('subscription-plans');
        Route::get('/subscription-plans/list', [App\Http\Controllers\PanelController::class, 'getAllSubscriptionPlanList'])->name('subscription-plans-list');
        Route::post('/subscription-plans/submit', [App\Http\Controllers\PanelController::class, 'createNewSubscriptionPlanSubmit'])->name('subscription-plans-submit');
        Route::delete('/subscription-plans/delete', [App\Http\Controllers\PanelController::class, 'deleteSelectedSubscriptionPlan'])->name('subscription-plans-delete');
        Route::get('/subscription-plans/edit', [App\Http\Controllers\PanelController::class, 'editSelectedSubscriptionPlan'])->name('subscription-plans-edit');

        Route::get('/offers', [App\Http\Controllers\PanelController::class, 'createNewOffers'])->name('offers');
        Route::get('/offers/list', [App\Http\Controllers\PanelController::class, 'getAllOffersList'])->name('offers-list');
        Route::post('/offers/submit', [App\Http\Controllers\PanelController::class, 'newOffersDetailSubmit'])->name('offers-submit');

        Route::view('/reedem-request', 'reedem-request')->name('reedem-request');
        Route::get('/reedem-request/list', [App\Http\Controllers\PanelController::class, 'getReedemRequestList'])->name('reedem-request-list');
        Route::put('/reedem-request/action', [App\Http\Controllers\PanelController::class, 'reedemRequestAction'])->name('reedem-request-action');


    });
});
