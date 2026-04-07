<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/cart-test.html');
});

Route::get('/test', function () {
    return redirect('/cart-test.html');
});
