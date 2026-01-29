<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/rides', function () {
    return view('admin.rides.index');
});

Route::get('/admin/rides/{id}', function ($id) {
    return view('admin.rides.show', compact('id'));
});
