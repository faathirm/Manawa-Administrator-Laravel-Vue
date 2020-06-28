<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/admin','AdminController@all');
Route::post('/admin','AdminController@new');
Route::post('/admin/update','AdminController@update');
Route::post('/admin/delete','AdminController@delete');

Route::get('/farmer','FarmerController@all');
Route::post('/farmer','FarmerController@new');
Route::post('/farmer/update','FarmerController@update');
Route::post('/farmer/delete','FarmerController@delete');

Route::get('/customer','CustomerController@all');
Route::post('/customer','CustomerController@new');
Route::post('/customer/detail','CustomerController@detail');
Route::post('/customer/update','CustomerController@update');
Route::post('/customer/delete','CustomerController@delete');

Route::get('/animal','AnimalController@all');
Route::post('/animal','AnimalController@new');
Route::post('/animal/update','AnimalController@update');
Route::post('/animal/delete','AnimalController@delete');

Route::get('/variety','VarietyController@all');
Route::get('/variety/animal','VarietyController@animal');
Route::post('/variety','VarietyController@new');
Route::post('/variety/update','VarietyController@update');
Route::post('/variety/delete','VarietyController@delete');

Route::get('/farmvariety','FarmVarietyController@all');
Route::post('/farmvariety','FarmVarietyController@new');
Route::post('/farmvariety/update','FarmVarietyController@update');
Route::post('/farmvariety/delete','FarmVarietyController@delete');
Route::get('/farmvariety/farm','FarmVarietyController@allFarm');
Route::get('/farmvariety/variety','FarmVarietyController@allVariety');

Route::get('/customerlivestock','LivestockController@all');
Route::post('/customerlivestock','LivestockController@new');
Route::post('/customerlivestock/detail','LivestockController@detail');
Route::post('/customerlivestock/report','LivestockController@report');
Route::post('/customerlivestock/reportdelete','LivestockController@reportDelete');
Route::post('/customerlivestock/update','LivestockController@update');
Route::post('/customerlivestock/delete','LivestockController@delete');
Route::post('/customerlivestock/sell','LivestockController@sell');

Route::get('/transaction','JournalController@all');
Route::get('/transaction/confirmation','ConfirmationController@all');
Route::post('/transaction/confirmation','ConfirmationController@update');
Route::post('/transaction/confirmation/delete','ConfirmationController@delete');

Route::get('/withdrawal','WithdrawController@all');
Route::post('/withdrawal/upload','WithdrawController@update');
Route::post('/withdrawal/decline','WithdrawController@decline');
Route::post('/withdrawal/delete','WithdrawController@delete');

//Notification
Route::post('/crm/notification/post','CRMController@post');
Route::get('/testaja','CRMController@test');

//Voucher
Route::get('/voucher','VoucherController@all');
Route::post('/voucher','VoucherController@new');
Route::post('/voucher/update','VoucherController@update');
Route::post('/voucher/delete','VoucherController@delete');

//Ticketing
Route::get('/ticketing','TicketingController@all');
Route::post('/ticketing/update','TicketingController@update');
Route::post('/ticketing/delete','TicketingController@delete');

//MOBILE
Route::get('/help/category','HelpController@categoryList');
Route::get('/help/category/{id}','HelpController@articleList');
Route::get('/help/article/{id}/{iduser}','HelpController@articleDetail');
Route::post('/help/article/like','HelpController@like');
Route::get('/reward/{iduser}','CustomerController@getReward');
Route::post('/reward/addvoucher','CustomerController@addVoucher');
Route::get('/reward/allpoint/{id}','CustomerController@allPoint');
Route::post('/ticketing/new','TicketingController@new');
