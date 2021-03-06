<?php
use Jenssegers\Agent\Agent;

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

    Route::resource('traffics', 'TrafficsController');
    Route::get('traffics.dataTables', ['uses' => 'TrafficsController@dataTables', 'as' => 'traffics.dataTables']);

    Route::resource('connections', 'ConnectionsController');
    Route::get('connections.dataTables', ['uses' => 'ConnectionsController@dataTables', 'as' => 'connections.dataTables']);
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
Route::get('sms', function(\Illuminate\Http\Request $request){

    //return view('sms');
    #return redirect()->away("//sms:/7892/&body=DK");

    $number = $request->has('num') ? $request->get('num') : 7892;
    $text = $request->has('text') ? urldecode($request->get('text')) : 'DK';

    $agent = new Agent();

    if ($agent->isAndroidOS()) {
        header('Location: sms:'.$number.'?body='.$text);
    } elseif ($agent->isiOS()) {
        header('Location: sms:/'.$number.'/&body='.$text);
    }

});

Route::get('come', 'FrontendController@landing');

Route::get('cookie', function(\Illuminate\Http\Request $request){

    if (isset($_COOKIE['duplicate_'.$request->get('network_id')])) {
        echo 'Cookie for current user with network='.$request->get('network_id').' existed!';
    } else {
        setcookie('duplicate_'.$request->get('network_id'), "1", time()+3600);
        echo 'First time come to site with network_id='.$request->get('network_id');
    }

});

Route::get('show_header',  function(\Illuminate\Http\Request $request){

   echo "Header";

   echo "<pre>";

   print_r($request->headers->all());

});
