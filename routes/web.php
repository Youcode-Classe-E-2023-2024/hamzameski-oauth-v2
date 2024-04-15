<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});


Route::get('/users-table', function () {
    return view('dashboard.users-table');
});
