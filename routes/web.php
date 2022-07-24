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
        Route::put('/users-list/block-unblock', [App\Http\Controllers\PanelController::class, 'userBlockUnblock'])->name('users-action-block-unblock');
        Route::put('/users-list/active-deactive', [App\Http\Controllers\PanelController::class, 'userActiveDeactive'])->name('users-action-active-deactive');
        Route::get('/users-detail', [App\Http\Controllers\PanelController::class, 'getUsersAllDetails'])->name('users-detail');
        
        Route::view('/category', 'category')->name('category');
        Route::post('/category/submit',[App\Http\Controllers\PanelController::class, 'createNewCategory'])->name('category-submit');
        Route::get('/category-list/all', [App\Http\Controllers\PanelController::class, 'getAllCategoryList'])->name('category-list-all');
        Route::delete('/category-list/delete', [App\Http\Controllers\PanelController::class, 'deleteSelectedCategory'])->name('category-list-delete');
        Route::put('/category-list/active-deactive', [App\Http\Controllers\PanelController::class, 'activeDeactiveSelectedCategory'])->name('category-list-active-deactive');
        Route::get('/category-list/edit', [App\Http\Controllers\PanelController::class, 'editSelectedCategory'])->name('category-list-edit');

        Route::get('/vendor', [App\Http\Controllers\PanelController::class, 'createNewVendor'])->name('vendor');
        Route::post('/vendor/submit', [App\Http\Controllers\PanelController::class, 'submitNewVendorDetails'])->name('vendor-create-submit');
        Route::view('/vendor-list','vendor-list')->name('vendor-list');
        Route::get('/vendor-list/all',[App\Http\Controllers\PanelController::class, 'getAllVendorList'])->name('vendor-list-all');
        Route::get('/vendor/update/{id}', [App\Http\Controllers\PanelController::class, 'editSelectedVendorDetails'])->name('vendor-create-update');
        Route::delete('/vendor/cover-img/delete/', [App\Http\Controllers\PanelController::class, 'removeVendorCoverImage'])->name('vendor-cover-delete');
        Route::post('/vendor/update', [App\Http\Controllers\PanelController::class, 'updateSelectedVendorDetails'])->name('vendor-edit-update');
        Route::get('/vendor/action', [App\Http\Controllers\PanelController::class, 'actionRequestVendorDetails'])->name('vendor-action');
        Route::get('/vendor/details', [App\Http\Controllers\PanelController::class, 'getVendorDetails'])->name('vendor-details');

        Route::get('/message-to-users',[App\Http\Controllers\PanelController::class, 'messageToUsersList'])->name('message-to-users');
        Route::post('/message-to-users/submit',[App\Http\Controllers\PanelController::class, 'messageToUsersSubmit'])->name('message-to-user-submit');

        Route::get('/subscription-plans', [App\Http\Controllers\PanelController::class, 'getCateForSubscriptionPlan'])->name('subscription-plans');
        Route::get('/subscription-plans/list', [App\Http\Controllers\PanelController::class, 'getAllSubscriptionPlanList'])->name('subscription-plans-list');
        Route::post('/subscription-plans/submit', [App\Http\Controllers\PanelController::class, 'createNewSubscriptionPlanSubmit'])->name('subscription-plans-submit');
        Route::delete('/subscription-plans/delete', [App\Http\Controllers\PanelController::class, 'deleteSelectedSubscriptionPlan'])->name('subscription-plans-delete');
        Route::get('/subscription-plans/edit', [App\Http\Controllers\PanelController::class, 'editSelectedSubscriptionPlan'])->name('subscription-plans-edit');
        Route::put('/subscription-plans/active-deactive', [App\Http\Controllers\PanelController::class, 'activeDeactiveSubscriptionPlan'])->name('subscription-plans-active-deactive');

        Route::get('/offers', [App\Http\Controllers\PanelController::class, 'createNewOffers'])->name('offers');
        Route::get('/offers/list', [App\Http\Controllers\PanelController::class, 'getAllOffersList'])->name('offers-list');
        Route::post('/offers/submit', [App\Http\Controllers\PanelController::class, 'newOffersDetailSubmit'])->name('offers-submit');
        Route::delete('/offers/delete', [App\Http\Controllers\PanelController::class, 'deleteSelectedOffers'])->name('offers-delete');
        Route::put('/offers/active-deactive', [App\Http\Controllers\PanelController::class, 'activeDeactiveSelectedOffers'])->name('offers-active-deactive');
        Route::get('/offers/edit', [App\Http\Controllers\PanelController::class, 'editSelectedOffers'])->name('offers-edit');
        Route::post('/offers/update', [App\Http\Controllers\PanelController::class, 'selectedOffersDetailUpdate'])->name('offers-update');

        Route::view('/redeem-request', 'redeem-request')->name('redeem-request');
        Route::get('/redeem-request/list', [App\Http\Controllers\PanelController::class, 'getReedemRequestList'])->name('redeem-request-list');
        Route::get('/redeem-approved/list', [App\Http\Controllers\PanelController::class, 'getReedemApprovedList'])->name('redeem-approved-list');
        Route::get('/redeem-rejected/list', [App\Http\Controllers\PanelController::class, 'getReedemRejectedList'])->name('redeem-rejected-list');
        Route::put('/redeem-request/action', [App\Http\Controllers\PanelController::class, 'reedemRequestAction'])->name('redeem-request-action');

        Route::view('/push-notification','push-notification')->name('push-notification');
        Route::get('/push-notification/list',[App\Http\Controllers\PanelController::class, 'getPushNotificationList'])->name('push-notification-list');
        
        Route::view('/reports-and-feedback','reports-and-feedback')->name('reports-and-feedback');
        Route::get('/reports-and-feedback/list',[App\Http\Controllers\PanelController::class, 'getReportAndFeedbackList'])->name('reports-and-feedback-list');

        Route::view('/referral', 'referral')->name('referral');
        Route::post('/referral/submit',[App\Http\Controllers\PanelController::class, 'createNewReferralAmount'])->name('referral-submit');
        Route::get('/referral-list/all', [App\Http\Controllers\PanelController::class, 'getAllReferralAmountList'])->name('referral-list-all');
        Route::delete('/referral-list/delete', [App\Http\Controllers\PanelController::class, 'deleteSelectedReferralAmount'])->name('referral-list-delete');
        Route::patch('/referral-list/active-deactive', [App\Http\Controllers\PanelController::class, 'activeDeactiveSelectedReferralAmount'])->name('referral-list-active-deactive');
        Route::get('/referral-list/edit', [App\Http\Controllers\PanelController::class, 'editSelectedReferralAmount'])->name('referral-list-edit');

    });
});
