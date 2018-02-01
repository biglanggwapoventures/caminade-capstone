<?php

Route::get('/', 'HomeController')->name('home');

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::post('register', 'AccountController@register')->name('register');
        Route::post('login', 'AccountController@login')->name('login');

    });

    Route::group(['middleware' => 'auth'], function () {
        Route::post('logout', 'AccountController@logout')->name('logout');
        Route::patch('update', 'AccountController@update')->name('update');
    });

});

Route::group(['prefix' => 'management', 'namespace' => 'Admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {

    Route::group(['middleware' => 'role:admin'], function () {
        Route::resource('user', 'UserController');
        Route::group(['prefix' => 'user/{userId}'], function () {
            Route::post('block', 'BlockUserController')->name('user.block');
            Route::post('unblock', 'UnblockUserController')->name('user.unblock');
        });
    });

    Route::resource('service', 'ServiceController');
    Route::resource('product', 'ProductController');
    Route::resource('pet', 'PetController');

    Route::group(['middleware' => 'role:admin,staff'], function () {
        Route::resource('pet-category', 'PetCategoryController');
        Route::resource('pet-breed', 'PetBreedController');
        Route::resource('pet-reproductive-alteration', 'PetReproductiveAlterationController');
        Route::resource('product-category', 'ProductCategoryController');
        Route::resource('supplier', 'SupplierController');
        Route::resource('appointment', 'AppointmentController');
        Route::resource('order', 'OrderController');

        Route::get('product/{product}/logs', 'ProductLogController@index')->name('product.logs');
        Route::post('product/{product}/logs', 'ProductLogController@adjust')->name('product.logs.adjust');

        Route::post('appointment/send-sms', 'AppointmentSMSController')->name('appointment.send-sms');
    });

});

Route::group(['prefix' => 'auth', 'as' => 'auth:'], function () {
    Route::get('facebook', 'FBAuthController@redirectToProvider')->name('facebook');
    Route::get('facebook/callback', 'FBAuthController@handleProviderCallback');

    Route::get('google', 'GoogleAuthController@redirectToProvider')->name('google');;
    Route::get('google/callback', 'GoogleAuthController@handleProviderCallback');
});

Route::group(['prefix' => 'user', 'namespace' => 'User', 'as' => 'user.', 'middleware' => ['auth', 'role:customer']], function () {
    Route::resource('pet', 'PetController');
    Route::resource('appointment', 'AppointmentController');
    Route::post('appointment/{appointmentId}/cancel', 'CancelAppointmentController')->name('appointment.cancel');
    Route::get('order-history', 'ViewOrderHistoryController')->name('order-history.show');
});

Route::group(['prefix' => 'doctor', 'namespace' => 'Doctor', 'as' => 'doctor.', 'middleware' => 'auth'], function () {
    Route::resource('appointment', 'AppointmentController');
});

Route::get('our-products', 'ProductShowcaseController')->name('product-showcase');
Route::get('our-services', 'ServiceShowcaseController')->name('service-showcase');

Route::group(['prefix' => 'api', 'as' => 'api:', 'middleware' => 'auth'], function () {
    Route::get('customer/{customerId}/pets', 'APIController@getPetsFromCustomer')->name('get-customer-pets');
    Route::get('doctor/appointments', 'APIController@getDoctorAppointments')->name('get-doctor-appointments');
});
