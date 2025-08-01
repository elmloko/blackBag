<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleHasPermissionController;
use App\Http\Controllers\DespachoController;
use App\Http\Controllers\SacaController;
use App\Http\Controllers\EtiquetasController;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\EventController;
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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/{id}/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::put('utest/{id}/restoring', [UserController::class, 'restoring'])->name('users.restoring');
    Route::get('users/excel', [UserController::class, 'excel'])->name('users.excel');
    Route::get('users/pdf', [UserController::class, 'pdf'])->name('users.pdf');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

    //Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('roles.create');
    // Route::get('/role/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::post('/role', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/role/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/role/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/role/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    //Permisos
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permission/create', [PermissionController::class, 'create'])->name('permissions.create');
    // Route::get('/permission/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::post('/permission', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permission/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permission/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permission/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    //Accesos
    Route::get('/role-has-permissions', [RoleHasPermissionController::class, 'index'])->name('role-has-permissions.index');
    Route::get('/role-has-permission/create', [RoleHasPermissionController::class, 'create'])->name('role-has-permissions.create');
    // Route::get('/role-has-permission/{roleHasPermission}', [RoleHasPermissionController::class, 'show'])->name('role-has-permissions.show');
    Route::post('/role-has-permission', [RoleHasPermissionController::class, 'store'])->name('role-has-permissions.store');
    Route::get('/role-has-permission/{roleHasPermission}/edit', [RoleHasPermissionController::class, 'edit'])->name('role-has-permissions.edit');
    Route::put('/role-has-permission/{roleHasPermission', [RoleHasPermissionController::class, 'update'])->name('role-has-permissions.update');
    Route::delete('/role-has-permission/{roleHasPermission}', [RoleHasPermissionController::class, 'destroy'])->name('role-has-permissions.destroy');

    //despachos
    Route::get('/alllc', [DespachoController::class, 'getAlllc']);
    Route::get('/allmx', [DespachoController::class, 'getAllmx']);
    Route::get('/allems', [DespachoController::class, 'getAllems']);
    Route::get('/iniciar', [DespachoController::class, 'getIniciar']);
    Route::get('/iniciarems', [DespachoController::class, 'getIniciarems']);
    Route::get('/iniciarmx', [DespachoController::class, 'getIniciarmx']);
    Route::get('/expedicion', [DespachoController::class, 'getExpedicion']);
    Route::get('/expedicionems', [DespachoController::class, 'getExpedicionems']);
    Route::get('/expedicionmx', [DespachoController::class, 'getExpedicionmx']);
    Route::get('/admitir', [DespachoController::class, 'getAdmitir']);
    Route::get('/admitirems', [DespachoController::class, 'getAdmitirems']);
    Route::get('/admitirmx', [DespachoController::class, 'getAdmitirmx']);

    Route::get('/cn35', [EtiquetasController::class, 'getCn35']);
    Route::get('/cn38', [EtiquetasController::class, 'getCn38']);

    //sacas
    Route::get('/sacas/crear/{id}', [SacaController::class, 'crear'])->name('saca.crear');
    Route::post('/sacas', [SacaController::class, 'store'])->name('saca.store');
    Route::delete('/saca/{id}', [SacaController::class, 'destroy'])->name('saca.delete');
    Route::put('/saca/{id}', [SacaController::class, 'update'])->name('saca.update');
    Route::post('/despacho/{id}/cerrar', [SacaController::class, 'cerrar'])->name('despacho.cerrar');

    //Eventos
    Route::get('/event', [EventController::class, 'getEvent']);

    //contenido
    Route::put('/contenido/{id}', [ContenidoController::class, 'update'])->name('contenido.update');
    Route::post('/contenido/store', [ContenidoController::class, 'store'])->name('contenido.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
