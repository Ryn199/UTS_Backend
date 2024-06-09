<?php

use App\Http\Controllers\Api\Admin\AdminKategoriController;
use App\Http\Controllers\Api\Admin\AdminProdukController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\tambah_keranjangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//auth sanctum
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
//AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum', '@Admin'], 'prefix' => 'admin'], function () {

    //kategori
    Route::get('/kategori', [AdminKategoriController::class, 'index']);
    Route::post('/kategori/create', [AdminKategoriController::class, 'store']);
    Route::get('/kategori/show/{id}', [AdminKategoriController::class, 'show']);
    Route::post('/kategori/update/{id}', [AdminKategoriController::class, 'update']);
    Route::delete('/kategori/delete/{id}', [AdminKategoriController::class, 'destroy']);
    
    //produk
    Route::get('/produk', [AdminProdukController::class, 'index']);
    Route::post('/produk/create', [AdminProdukController::class, 'store']);
    Route::get('/produk/show/{id}', [AdminProdukController::class, 'show']);
    Route::post('/produk/update/{id}', [AdminProdukController::class, 'update']);
    Route::delete('/produk/delete/{id}', [AdminProdukController::class, 'destroy']);
});


//user juga bisa lihat produk untuk di frontend
Route::group(['middleware' => ['auth:sanctum', '@pelanggan']], function () {
    

    Route::get('/produk', [ProdukController::class, 'index']);
    Route::get('/produk/show/{id}', [ProdukController::class, 'show']);

    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/kategori/show/{id}', [KategoriController::class, 'show']);


});

//user untuk nambnah produk ke keranjang

Route::group(['middleware' => 'auth:sanctum', '@pelanggan'], function () {
    //add to cart
    Route::get('/keranjang', [tambah_keranjangController::class, 'index']);
    Route::post('/keranjang/create', [tambah_keranjangController::class, 'store']);
    Route::post('/keranjang/update/{id}', [tambah_keranjangController::class, 'update']);
    Route::delete('/keranjang/delete/{id}', [tambah_keranjangController::class, 'destroy']);


    //checkout
    // Route::post('/checkout', [CheckoutController::class, 'store']);
});

