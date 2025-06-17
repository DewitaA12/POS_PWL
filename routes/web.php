<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StokController;
use Illuminate\Support\Facades\Route;
use Monolog\Level;

Route::pattern('id', '[0-9]+');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'store'])->name('store');

Route::middleware(['auth'])->group(function(){
    Route::get('/welcome', [WelcomeController::class, 'index']);

    Route::prefix('user')->middleware(['authorize:ADM'])->group(function(){
        Route::get('/', [UserController::class, 'index']); // /user
        Route::post('/list', [UserController::class, 'list']); // /user/list
        Route::get('/tambah', [UserController::class, 'tambah']); // /user/tambah
        Route::get('/create', [UserController::class, 'create']); // /user/create
        Route::post('/',[UserController::class, 'store']);
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); 
        Route::post('/ajax',[UserController::class, 'store_ajax']);
        Route::get('/{id}/edit', [UserController::class, 'edit']); // /user/edit
        Route::put('/{id}', [UserController::class, 'update']); // /menyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // /user/edit
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax'])->name('user.update_ajax');
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax'])->name('user.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax'])->name('user.delete_ajax');
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
        Route::delete('/{id}', [UserController::class, 'destroy']); // Menghapus data user by id
        Route::get('/{id}', [UserController::class, 'show']); // /user/detail
        Route::post('/', [UserController::class, 'tambah_simpan']); // /user (POST)
        Route::get('/ubah/{id}', [UserController::class, 'ubah']); // /user/ubah/1
        Route::put('/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']); // /user/ubah_simpan/1
        Route::get('/hapus/{id}', [UserController::class, 'hapus']); // /user/hapus/1
        Route::get('/test-relasi', [UserController::class, 'testRelasi']);
        Route::get('/jumlah-pengguna', [UserController::class, 'jumlahPengguna']);
    });

    Route::prefix('level')->middleware(['authorize:ADM,MNG'])->group(function(){
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list',[LevelController::class, 'list']);
        Route::get('/create', [LevelController::class, 'create']); 
        Route::post('/', [LevelController::class, 'store']);
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']); 
        Route::post('/ajax',[LevelController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // /level/edit
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax'])->name('level.update_ajax');
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax'])->name('level.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax'])->name('level.delete_ajax');
        Route::get('/{id}', [LevelController::class, 'show']); 
        Route::get('/{id}/edit', [LevelController::class, 'edit']);
        Route::put('/{id}', [LevelController::class, 'update']);
        Route::delete('/{id}', [LevelController::class, 'destroy']);
        Route::get('/import', [LevelController::class, 'import']);
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/export_excel', [LevelController::class, 'export_excel']); 
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']); 
    });

    Route::prefix('kategori')->middleware(['authorize:ADM,STF'])->group(function(){
        Route::get('/', [KategoriController::class, 'index']);
        Route::post('/list',[KategoriController::class, 'list']);
        Route::get('/create', [KategoriController::class, 'create']); 
        Route::post('/', [KategoriController::class, 'store']);
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); 
        Route::post('/ajax',[KategoriController::class, 'store_ajax']);
        Route::get('/{id}', [KategoriController::class, 'show']); 
        Route::get('/{id}/edit', [KategoriController::class, 'edit']);
        Route::put('/{id}', [KategoriController::class, 'update']);
        Route::delete('/{id}', [KategoriController::class, 'destroy']);
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); 
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax'])->name('kategori.update_ajax');
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax'])->name('kategori.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax'])->name('kategori.delete_ajax');
        Route::get('/import', [KategoriController::class, 'import']);
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);
        Route::get('/export_excel', [KategoriController::class, 'export_excel']); 
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']); 
    });

        Route::prefix('barang')->middleware(['authorize:ADM,MNG,STF'])->group(function(){
        Route::get('/', [BarangController::class, 'index']);
        Route::post('/list',[BarangController::class, 'list']);
        Route::get('/create', [BarangController::class, 'create']); 
        Route::post('/', [BarangController::class, 'store']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']); 
        Route::post('/ajax',[BarangController::class, 'store_ajax']);
        Route::get('/{id}', [BarangController::class, 'show']); 
        Route::get('/{id}/edit', [BarangController::class, 'edit']);
        Route::put('/{id}', [BarangController::class, 'update']);
        Route::delete('/{id}', [BarangController::class, 'destroy']);
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); 
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax'])->name('barang.update_ajax');
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax'])->name('barang.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax'])->name('barang.delete_ajax');
        Route::get('/import',[BarangController::class,'import']);    
        Route::post('/import_ajax',[BarangController::class,'import_ajax']);
        Route::get('/export_excel', [BarangController::class, 'export_excel']); 
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']); 
        });

        Route::prefix('supplier')->middleware(['authorize:ADM,MNG'])->group(function(){
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/list',[SupplierController::class, 'list']);
        Route::get('/create', [SupplierController::class, 'create']); 
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax'])->name('supplier.create_ajax'); 
        Route::post('/ajax',[SupplierController::class, 'store_ajax'])->name('supplier.store_ajax');
        Route::get('/{id}', [SupplierController::class, 'show']); 
        Route::get('/{id}/edit', [SupplierController::class, 'edit']);
        Route::put('/{id}', [SupplierController::class, 'update']);
        Route::delete('/{id}', [SupplierController::class, 'destroy']);
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); 
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax'])->name('supplier.update_ajax');
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax'])->name('supplier.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax'])->name('supplier.delete_ajax');
        Route::get('/import', [SupplierController::class, 'import']);
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
        Route::get('/export_excel', [SupplierController::class, 'export_excel']);
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']);  
        });

        Route::prefix('stok')->middleware(['authorize:ADM,MNG'])->group(function(){
        Route::get('/', [StokController::class, 'index'])->name('stok.index');  
        Route::post('/list', [StokController::class, 'list'])->name('stok.list');  
        Route::get('/create', [StokController::class, 'create'])->name('stok.create'); 
        Route::post('/', [StokController::class, 'store'])->name('stok.store');  
        Route::get('/create_ajax', [StokController::class, 'createAjax'])->name('stok.create_ajax'); 
        Route::post('/store_ajax', [StokController::class, 'storeAjax'])->name('stok.store_ajax'); 
        Route::get('/{id}', [StokController::class, 'show'])->name('stok.show');       
        Route::get('/{id}/show_ajax', [StokController::class, 'showAjax'])->name('stok.show_ajax');
        Route::get('/{id}/edit_ajax', [StokController::class, 'editAjax'])->name('stok.edit_ajax'); 
        Route::put('/{id}/update_ajax', [StokController::class, 'updateAjax'])->name('stok.update_ajax');   
        Route::get('/{id}/delete_ajax', [StokController::class, 'confirmAjax'])->name('stok.delete_ajax'); 
        Route::delete('/{id}/delete_ajax', [StokController::class, 'deleteAjax']);
        Route::delete('/{id}', [StokController::class, 'destroy'])->name('stok.destroy');  
        Route::get('/{id}/edit', [StokController::class, 'edit'])->name('stok.edit'); 
        Route::put('/{id}', [StokController::class, 'update'])->name('stok.update');   
        Route::get('/import', [StokController::class, 'import'])->name('stok.import'); 
        Route::post('/import_ajax', [StokController::class, 'importAjax'])->name('stok.import_ajax');  
        Route::get('/export_excel', [StokController::class, 'export_excel']); 
        Route::get('/export_pdf', [StokController::class, 'export_pdf']); 
    });

});