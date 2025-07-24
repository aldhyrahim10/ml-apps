<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/detection-anomali', function () {
    return view('pages.detection-anomali.index');
})->name("anomali");

Route::get('/logistic', function () {
    return view('pages.logistic.index');
})->name("logistic");

Route::get('/log-system', function () {
    return view('pages.log-system.index');
})->name("log-system");

Route::get('/operational', function () {
    return view('pages.operational.index');
})->name("operational");

