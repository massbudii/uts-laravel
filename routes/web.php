<?php

use App\Http\Controllers\ProdukConntroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produk-data', [ProdukConntroller::class , 'index'])->name('produk.index');
Route::get('/produk-data/create', [ProdukConntroller::class , 'create'])->name('produk.create');
Route::post('/produk-data/store', [ProdukConntroller::class , 'store'])->name('produk.store');
Route::get('/produk-data/show/{id}', [ProdukConntroller::class , 'show'])->name('produk.show');
Route::get('/produk-data/edit/{id}', [ProdukConntroller::class , 'edit'])->name('produk.edit');
Route::put('/produk-data/update/{id}', [ProdukConntroller::class , 'update'])->name('produk.update');
Route::delete('/produk-data/delete/{id}', [ProdukConntroller::class , 'destroy'])->name('produk.destroy');


// Route::resource('/produk', ProdukConntroller::class);
