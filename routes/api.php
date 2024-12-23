<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Dashboard\Client\ClientController;
use App\Http\Controllers\Api\Dashboard\Course\CourseController;
use App\Http\Controllers\Api\Dashboard\SportCategory\SportCategoryController;
use App\Http\Controllers\Api\Dashboard\User\UserController;
use App\Http\Controllers\Api\Dashboard\SubscriptionController;
use App\Http\Controllers\Api\Dashboard\Trainer\TrainerController;
use App\Http\Controllers\Api\Dashboard\Select\SelectController;
use App\Http\Controllers\ClientCourse\ClientCourseController;
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

/*Route::middleware('tenant')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});*/



Route::prefix('v1/{locale}/auth')->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::prefix('v1/{locale}/dashboard/users')->where(['lang' => 'it|en'])->group(function(){
    Route::get('', [UserController::class, 'index']);
    Route::post('create', [UserController::class, 'create']);
    Route::get('edit', [UserController::class, 'edit']);
    Route::put('update', [UserController::class, 'update']);
    Route::delete('delete', [UserController::class, 'delete']);
    Route::post('change-status', [UserController::class, 'changeStatus']);
});

Route::prefix('v1/{locale}/dashboard/sport-categories')->where(['lang' => 'it|en'])->group(function(){
    Route::get('', [SportCategoryController::class, 'index']);
    Route::post('create', [SportCategoryController::class, 'create']);
    Route::get('edit', [SportCategoryController::class, 'edit']);
    Route::put('update', [SportCategoryController::class, 'update']);
    Route::delete('delete', [SportCategoryController::class, 'delete']);
});

Route::prefix('v1/{locale}/dashboard/trainers')->where(['lang' => 'it|en'])->group(function(){
    Route::get('', [TrainerController::class, 'index']);
    Route::post('create', [TrainerController::class, 'create']);
    Route::get('edit', [TrainerController::class, 'edit']);
    Route::put('update', [TrainerController::class, 'update']);
    Route::delete('delete', [TrainerController::class, 'delete']);
});

Route::prefix('v1/{locale}/dashboard/courses')->where(['lang' => 'it|en'])->group(function(){
    Route::get('', [CourseController::class, 'index']);
    Route::post('create', [CourseController::class, 'create']);
    Route::get('edit', [CourseController::class, 'edit']);
    Route::put('update', [CourseController::class, 'update']);
    Route::delete('delete', [CourseController::class, 'delete']);
});

Route::prefix('v1/{locale}/dashboard/clients')->where(['lang' => 'it|en'])->group(function(){
    Route::get('', [ClientController::class, 'index']);
    Route::post('create', [ClientController::class, 'create']);
    Route::get('edit', [ClientController::class, 'edit']);
    Route::put('update', [ClientController::class, 'update']);
    Route::delete('delete', [ClientController::class, 'delete']);
});

Route::prefix('v1/{locale}/dashboard/client-courses')->where(['lang' => 'it|en'])->group(function(){
    Route::get('', [ClientCourseController::class, 'index']);
    Route::post('create', [ClientCourseController::class, 'create']);
});


Route::prefix('v1/{lang}/dashboard/selects')->where(['lang' => 'it|en'])->group(function(){
    Route::get('', [SelectController::class, 'getSelects']);
});






Route::post('v1/subscriptions', [SubscriptionController::class, 'subscribe']);


/*Route::post('/login', [AuthController::class, 'login']);
Route::post('/subscriptions', [SubscriptionController::class, 'subscribe']);


Route::get('/clients', function (Request $request) {
    return response()->json([
        'user' =>auth()->user(),
        'messagse' => 'this is clients'
    ]);
})->middleware(['feature:clients', 'switchTenant']);


Route::get('/users', function (Request $request) {
    return response()->json([
        'messagse' => 'this is users'
    ]);
})->middleware('feature:users');
*/
