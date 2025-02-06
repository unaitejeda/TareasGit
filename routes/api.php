<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostControllerAdvance;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\LugaresController;
use App\Http\Controllers\Api\TiposController;
use App\Http\Controllers\Api\TareasController;
use App\Http\Controllers\Api\MomentodiaController;
use App\Http\Controllers\Api\EstadosController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::group(['middleware' => 'auth:sanctum'], function() {

    Route::apiResource('users', UserController::class);

    Route::post('users/updateimg', [UserController::class,'updateimg']); //Listar

    Route::apiResource('posts', PostControllerAdvance::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);

    Route::get('category-list', [CategoryController::class, 'getList']);
    Route::get('/user', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);

    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});

Route::get('category-list', [CategoryController::class, 'getList']);

Route::get('get-posts', [PostControllerAdvance::class, 'getPosts']);
Route::get('get-category-posts/{id}', [PostControllerAdvance::class, 'getCategoryByPosts']);
Route::get('get-post/{id}', [PostControllerAdvance::class, 'getPost']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('lugares', LugaresController::class);
    Route::apiResource('tipos', TiposController::class);
    Route::apiResource('tareas', TareasController::class);
    Route::apiResource('momentodia', MomentodiaController::class);
    Route::apiResource('estados', EstadosController::class);
});

// api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('tareas-usuario', [TareasController::class, 'getTareasUsuario']);
    Route::get('tareas-generales', [TareasController::class, 'getTareasGenerales']);
    Route::put('tareas/finalizar/{id}', [TareasController::class, 'finalizarTarea']);
    Route::delete('tareas/{id}', [TareasController::class, 'destroy']);
    Route::put('tareas/{id}', [TareasController::class, 'update']);

    Route::post('tareas', action: [TareasController::class, 'store']);


    Route::get('estados', [EstadosController::class, 'index']);
    Route::get('momentodia', [MomentodiaController::class, 'index']);

    
    Route::get('users', [UserController::class, 'getUsers']);
    Route::post('tareas-asignadas', [TareasController::class, 'asignarTarea']);
});