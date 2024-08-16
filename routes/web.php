<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\SortingItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

// Routes accessible without authentication
Route::get('/login', [AccountController::class, 'indexLogin'])->name('account.indexLogin');
Route::post('/login', [AccountController::class, 'login'])->name('account.login');
Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
Route::resource('account', AccountController::class);
// Group routes requiring authentication
Route::middleware(['isLogin'])->group(function () {

    Route::resource('inventory', InventoryController::class);
    Route::get('/', [InventoryController::class, 'dashboard'])->name('inventory.dashboard');
    Route::get('listinventories', [InventoryController::class, 'showInventories'])->name('inventory.showInventories');

    Route::post('/inventory/{inventory}/storeOutgoing', [DistributionController::class, 'storeOutgoing'])->name('inventory.storeOutgoing');
    Route::post('/submit-outgoing', [DistributionController::class, 'submitOutgoingByScan'])->name('inventory.outgoingByScan');
    Route::post('/scan-barcode', [DistributionController::class, 'scan'])->name('barcode.scan');
    Route::put('inventory/update-all', [DistributionController::class, 'updateAll'])->name('inventory.updateAll');
    Route::get('outgoing', [DistributionController::class, 'showOutgoingForm'])->name('inventory.showOutgoingForm');
    Route::get('ingoing', [DistributionController::class, 'showIngoingForm'])->name('inventory.showIngoingForm');
    Route::get('storeIngoing', [DistributionController::class, 'listIngoingItem'])->name('inventory.listIngoingItem');
    Route::post('/inventory/{inventory}/storeIngoing', [DistributionController::class, 'storeIngoing'])->name('inventory.storeIngoing');

    // Route to handle category View
    Route::get('category', [SortingItemController::class, 'showCategory'])->name('inventory.listCategory');
    // Route to handle category store
    Route::post('/categories', [SortingItemController::class, 'storeCategory'])->name('categories.store');
    // Route to handle category updates
    Route::put('/categories/{category}', [SortingItemController::class, 'updateCategory'])->name('categories.update');
    // Route to handle category deletion with reassignment
    Route::delete('/categories/{category}', [SortingItemController::class, 'destroyCategory'])->name('categories.destroy');

    // Route to handle Location View
    Route::get('location', [SortingItemController::class, 'showLocation'])->name('inventory.listLocation');
    // Route to handle Location store
    Route::post('/locations', [SortingItemController::class, 'storeLocation'])->name('locations.store');
    // Route to handle Location updates
    Route::put('/locations/{location}', [SortingItemController::class, 'updateLocation'])->name('locations.update');
    // Route to handle Location deletion with reassignment
    Route::delete('/locations/{location}', [SortingItemController::class, 'destroyLocation'])->name('locations.destroy');

    // Route to handle Brand View
    Route::get('brand', [SortingItemController::class, 'showBrands'])->name('inventory.listBrands');
    // Route to handle Brand store
    Route::post('/brands', [SortingItemController::class, 'storeBrand'])->name('brands.store');
    // Route to handle brand updates
    Route::put('/brands/{brands}', [SortingItemController::class, 'updateBrand'])->name('brands.update');
    // Route to handle Brand deletion with reassignment
    Route::delete('/brand/{brand}', [SortingItemController::class, 'destroyBrand'])->name('brands.destroy');


});


