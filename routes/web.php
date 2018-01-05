<?php

Route::get('/', 'HomeController');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('/', 'CustomAuthController@showLoginPage')->name('show.login');
    Route::post('/', 'CustomAuthController@doLogin')->name('do.login');

    Route::resource('pet-category', 'PetCategoryController');
    Route::resource('pet-breed', 'PetBreedController');
    Route::resource('pet-reproductive-alteration', 'PetReproductiveAlterationController');
    Route::resource('pet', 'PetController');
    Route::resource('user', 'UserController');
});

Route::resource('service', 'ServiceController');
Route::resource('product-category', 'ProductCategoryController');
Route::resource('product', 'ProductController');
Route::resource('supplier', 'SupplierController');

Route::group(['prefix' => 'auth'], function () {
    Route::get('facebook', 'FBAuthController@redirectToProvider')->name('auth:facebook');
    Route::get('facebook/callback', 'FBAuthController@handleProviderCallback');

    Route::get('google', 'GoogleAuthController@redirectToProvider')->name('auth:google');;
    Route::get('google/callback', 'GoogleAuthController@handleProviderCallback');
});
