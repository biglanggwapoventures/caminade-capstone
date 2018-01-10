<?php

Route::get('/', 'HomeController')->name('home');
Route::post('/', 'CustomAuthController@doLogin')->name('do.login');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('/', 'CustomAuthController@showLoginPage')->name('show.login');
    Route::post('/', 'CustomAuthController@doLogin')->name('do.login');

    Route::resource('pet-category', 'PetCategoryController');
    Route::resource('pet-breed', 'PetBreedController');
    Route::resource('pet-reproductive-alteration', 'PetReproductiveAlterationController');
    Route::resource('pet', 'PetController');
    Route::resource('user', 'UserController');
    Route::resource('appointment', 'AppointmentController');
});

Route::resource('service', 'ServiceController');
Route::resource('product-category', 'ProductCategoryController');
Route::resource('product', 'ProductController');
Route::resource('supplier', 'SupplierController');

Route::group(['prefix' => 'auth', 'as' => 'auth:'], function () {
    Route::get('facebook', 'FBAuthController@redirectToProvider')->name('facebook');
    Route::get('facebook/callback', 'FBAuthController@handleProviderCallback');

    Route::get('google', 'GoogleAuthController@redirectToProvider')->name('google');;
    Route::get('google/callback', 'GoogleAuthController@handleProviderCallback');
});

Route::group(['prefix' => 'user', 'namespace' => 'User', 'as' => 'user.'], function () {
    Route::resource('pet', 'PetController');
    Route::resource('appointment', 'AppointmentController');
});

Route::get('our-products', 'ProductShowcaseController')->name('product-showcase');
Route::get('our-services', 'ServiceShowcaseController')->name('service-showcase');

Route::post('logout', 'LogoutController')->name('logout');

Route::group(['prefix' => 'api', 'as' => 'api:'], function () {
    Route::get('customer/{customerId}/pets', 'APIController@getPetsFromCustomer')->name('get-customer-pets');
});
