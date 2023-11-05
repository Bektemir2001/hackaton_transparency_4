<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'organizations'], function (){
    Route::get('/', [OrganizationController::class, 'get']);
    Route::get('/get', [OrganizationController::class, 'getOrganization']);
    Route::get('/search', [OrganizationController::class, 'search']);
    Route::get('/success/graphic/{organization}', [OrganizationController::class, 'graphicSuccessForMonth']);
    Route::get('/diagram/{organization}', [OrganizationController::class, 'countForDiagram']);
});


Route::group(['prefix' => 'purchases'], function (){
    Route::get('/', [PurchaseController::class, 'get']);
    Route::get('/{purchase}', [PurchaseController::class, 'getOne']);
});

Route::group(['prefix' => 'categories'], function (){
    Route::get('/', [CategoryController::class, 'get']);
});

Route::group(['prefix' => 'region'], function (){
    Route::get('/{region}', [RegionController::class, 'graphicSuccessForMonth']);
});
