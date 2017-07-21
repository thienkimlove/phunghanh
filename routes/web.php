<?php

#Admin Routes
Route::get('admin/login', 'AdminController@redirectToGoogle');
Route::get('admin/logout', 'AdminController@logout');
Route::get('admin/callback', 'AdminController@handleGoogleCallback');
Route::get('admin/notice', 'AdminController@notice');
Route::get('admin', 'AdminController@index');
#Content Routes
Route::resource('admin/users', 'UsersController');
Route::resource('admin/categories', 'CategoriesController');
Route::resource('admin/networks', 'NetworksController');
Route::resource('admin/network_clicks', 'NetworkClicksController');

#Frontend
Route::get('/', 'FrontendController@index');
Route::get('camp', 'FrontendController@camp');
Route::get('callback', 'FrontendController@callback');
# gia lap he thong vincom.
Route::get('excamp', 'FrontendController@exampleCamp');
# gia lap callback cua he thong mobilefun
Route::get('excallback', 'FrontendController@exampleCallback');

