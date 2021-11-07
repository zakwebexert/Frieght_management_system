<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/clear',function(){
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
});

Auth::routes();

Route::group([
    'middleware'    => ['auth'],
    'prefix'        => 'client',
    'namespace'     => 'Client'
], function ()
{
    Route::get('/dashboard', 'ClientController@index')->name('client.dashboard');
	Route::get('/profile', 'ClientController@edit')->name('client-profile');
	Route::post('/admin-update', 'ClientController@update')->name('client-update');


});

Route::group([
    'middleware'    => ['auth','is_admin'],
    'prefix'        => 'admin',
    'namespace'     => 'Admin'
], function ()
{
    Route::get('/dashboard', 'AdminController@index')->name('admin.dashboard');
    Route::get('/profile', 'AdminController@edit')->name('admin-profile');
    Route::post('/admin-update', 'AdminController@update')->name('admin-update');
    //Setting Routes
    Route::resource('setting','SettingController');


	//Customer Routes
	Route::resource('customers','CustomerController');
	Route::post('store_customer_contacts','CustomerController@store_customer_contacts')->name('store_customer_contacts');
	Route::post('store_customer_address','CustomerController@store_customer_address')->name('store_customer_address');
	Route::post('store_account_detail','CustomerController@store_account_detail')->name('store_account_detail');
	Route::post('store_notes','CustomerController@store_notes')->name('store_notes');
	Route::post('store_customer_file','CustomerController@store_customer_file')->name('store_customer_file');
	Route::post('store_customer_site','CustomerController@store_customer_site')->name('store_customer_site');


	Route::post('get-customers', 'CustomerController@getCustomers')->name('admin.getCustomers');
	Route::post('get-customer', 'CustomerController@customerDetail')->name('admin.getCustomer');
	Route::get('customer/delete/{id}', 'CustomerController@destroy');
	Route::post('delete-selected-customers', 'CustomerController@deleteSelectedCustomers')->name('admin.delete-selected-customers');

});

