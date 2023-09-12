<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;



// Public routes for any user
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');


// Protected super_admin routes only
Route::group(['middleware' => ['auth:api', 'role:Super_Admin']], function () {
    // Route::apiResource('users', UserController::class, ['names' => 'users']);

    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    // Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy')->middleware(['deletableUser']);
});


// Protected routes for admin only
Route::group(['middleware' => ['auth:api', 'role:Admin']], function () {
    // Routes goes here
});


// Protected routes for employee only
Route::group(['middleware' => ['auth:api', 'role:Employee']], function () {
    // Routes goes here
});


// Protected routes for user only
Route::group(['middleware' => ['auth:api', 'role:User']], function () {
    // Routes goes here
});


// Protected routes for all the users except user
Route::group(['middleware' => ['auth:api', 'role:Super_Admin|Admin|Employee']], function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('export/users', [UserController::class, 'export'])->name('users.export');
    Route::get('users/get/data', [UserController::class, 'getData'])->name('users.index');
    Route::get('users/current/info', [UserController::class, 'info'])->name('users.info');
});


// Protected routes for any user has any role
Route::group(['middleware' => ['auth:api', 'role:Super_Admin|Admin|Employee|User']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh')->middleware(['scopes:refresh']);
});


// Route::get('export/users', [UserController::class, 'export'])->name('users.export');
