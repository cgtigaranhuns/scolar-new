<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConselhoController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/conselhos/relatorio', [ConselhoController::class, 'relatorio'])->name('conselhos.pdf');
