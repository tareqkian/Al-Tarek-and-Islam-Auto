<?php

use App\Http\Controllers\AutobanBrandController;
use App\Http\Controllers\AutobanModelController;
use App\Http\Controllers\AutobanTypeController;
use App\Http\Controllers\AutobanYearController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TranslatorController;
use App\Http\Controllers\UsersController;
use \Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function(){
  Route::get('/user', function (Request $request) {
    $user = $request->user()->load('role.permissions');
    $user['avatar'] = URL::to($user['avatar']);
    return $user;
  });
  Route::apiResource('/menu',MenuController::class);
  Route::apiResource('/items',MenuItemController::class);
  Route::put('/orderItems/{id}',[MenuItemController::class,'updateOrder']);
  Route::apiResource('/roles',RolesController::class);
  Route::put('/updateRole/{id}',[RolesController::class,'updateRole']);
  Route::apiResource('/permissions',PermissionsController::class);

  Route::get('/currentPermissions',[PermissionsController::class,'current']);
  Route::apiResource('/users',UsersController::class);
  Route::put('/changePassword/{id}',[UsersController::class,"changePassword"]);
  Route::apiResource('/log',LogController::class);

  Route::apiResource('/translation',TranslatorController::class);
  Route::apiResource('/languages', LanguagesController::class);

  Route::apiResource('/autobanBrands', AutobanBrandController::class);
  Route::apiResource('/autobanModels', AutobanModelController::class);
  Route::apiResource('/autobanTypes', AutobanTypeController::class);
  Route::apiResource('/autobanYears', AutobanYearController::class);

  Route::post('/logout',[AuthController::class,'logout']);
});
Route::apiResource('/settings',SettingController::class);

Route::get('/routes',[MenuController::class,'initROUTES']);
Route::get('/routes/{id}',[MenuController::class,'initROUTE']);

Route::post('/checkEmail',[AuthController::class,'checkEmail']);
Route::put('/register/{id}',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

