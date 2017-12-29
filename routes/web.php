<?php

#Admin Routes
Route::get('login', 'AdminController@redirectToGoogle')->name('login');
Route::get('logout', 'AdminController@logout')->name('logout');
Route::get('sso-callback', 'AdminController@handleGoogleCallback')->name('sso-callback');
Route::get('notice', 'AdminController@notice')->name('notice');


Route::group(['middleware' => 'auth.backend'], function() {
    Route::get('admin', 'AdminController@index');
    #Content Routes
    Route::resource('users', 'UsersController');
    Route::get('users.dataTables', ['uses' => 'UsersController@dataTables', 'as' => 'users.dataTables']);
    Route::get('networks.dataTables', ['uses' => 'NetworksController@dataTables', 'as' => 'networks.dataTables']);
    Route::get('networks.connect/{id}', ['uses' => 'NetworksController@connect', 'as' => 'networks.connect']);
    Route::resource('networks', 'NetworksController');

    Route::get('network_clicks.dataTables', ['uses' => 'NetworkClicksController@dataTables', 'as' => 'network_clicks.dataTables']);
    Route::get('network_clicks/export-to-excel', 'NetworkClicksController@export')->name('network_clicks.export');
    Route::resource('network_clicks', 'NetworkClicksController');


    Route::resource('reports', 'ReportsController');
    Route::get('reports.dataTables', ['uses' => 'ReportsController@dataTables', 'as' => 'reports.dataTables']);
});

#Frontend
Route::get('/', 'FrontendController@index');
Route::get('camp', 'FrontendController@camp');
Route::get('callback', 'FrontendController@callback');
# gia lap he thong vincom.
Route::get('excamp', 'FrontendController@exampleCamp');
# gia lap callback cua he thong mobilefun
Route::get('excallback', 'FrontendController@exampleCallback');
Route::get('api/source/{uid}', 'FrontendController@source');
Route::get('report', 'FrontendController@report');
Route::get('smscallback', 'FrontendController@smsCallback');
Route::get('sms', function(){

    return view('sms');
   // return redirect("sms:/**/&body=/* body text here */");
});

Route::get('test', function(){

    $example = json_decode(file_get_contents('http://v.fatv.vn/partner/subs?start=2017-11-08&end=2017-11-08'), true);

    return response()->json($example);
});

