<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\PinjamanController;
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

// Routes untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Routes untuk user yang sudah login
Route::middleware('islogin')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::prefix('member')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('member.index');
        Route::post('/', [MemberController::class, 'store'])->name('member.store');
        Route::put('/{id}', [MemberController::class, 'update'])->name('member.update');
        Route::delete('/{id}', [MemberController::class, 'destroy'])->name('member.destroy');
    });

    Route::prefix('member-card')->group(function () {
        Route::get('/', [MemberCardController::class, 'index'])->name('member-card.index');
        Route::post('/', [MemberCardController::class, 'store'])->name('member-card.store');
        Route::put('/{id}', [MemberCardController::class, 'update'])->name('member-card.update');
        Route::delete('/{id}', [MemberCardController::class, 'destroy'])->name('member-card.destroy');
    });

    Route::prefix('publisher')->group(function () {
        Route::get('/', [PenerbitController::class, 'index'])->name('penerbit.index');
        Route::post('/', [PenerbitController::class, 'store'])->name('penerbit.store');
        Route::put('/{id}', [PenerbitController::class, 'update'])->name('penerbit.update');
        Route::delete('/{id}', [PenerbitController::class, 'destroy'])->name('penerbit.destroy');
    });

    Route::prefix('book')->group(function () {
        Route::get('/', [BukuController::class, 'index'])->name('buku.index');
        Route::post('/', [BukuController::class, 'store'])->name('buku.store');
        Route::put('/{id}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    Route::prefix('loan')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('pinjaman.index');
        Route::post('/', [PinjamanController::class, 'store'])->name('pinjaman.store');
        Route::patch('/{id}/return', [PinjamanController::class, 'returnBook'])->name('pinjaman.return');
        Route::get('/export', [PinjamanController::class, 'export'])->name('pinjaman.export');
        Route::put('/{id}', [PinjamanController::class, 'update'])->name('pinjaman.update');
        Route::delete('/{id}', [PinjamanController::class, 'destroy'])->name('pinjaman.destroy');
    });
});