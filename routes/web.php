<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\WelcomeController;
use Database\Seeders\KategoriSeeder;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']); // /user
    Route::post('/list', [UserController::class, 'list']); // /user/list
    Route::get('/tambah', [UserController::class, 'tambah']); // /user/tambah
    Route::get('/create', [UserController::class, 'create']); // /user/create
    Route::get('/{id}/edit', [UserController::class, 'edit']); // /user/edit
    Route::put('/{id}', [UserController::class, 'update']); // /menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']); // Menghapus data user by id
    Route::get('/{id}', [UserController::class, 'show']); // /user/detail
    Route::post('/', [UserController::class, 'tambah_simpan']); // /user (POST)
    Route::get('/ubah/{id}', [UserController::class, 'ubah']); // /user/ubah/1
    Route::put('/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']); // /user/ubah_simpan/1
    Route::get('/hapus/{id}', [UserController::class, 'hapus']); // /user/hapus/1
    Route::get('/test-relasi', [UserController::class, 'testRelasi']);
    Route::get('/jumlah-pengguna', [UserController::class, 'jumlahPengguna']);
});

Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);