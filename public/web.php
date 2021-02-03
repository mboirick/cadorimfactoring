<?php

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



//************TABLEAU DE BORD */


Auth::routes();
Route::get('/','DashboardController@index');
Route::get('/dashboard', 'DashboardController@index');

//***************CASH MENAGEMENT************* */
Route::get('/cache-management', 'CacheController@index')->name('cache-management');
Route::get('/cache-management/clients', 'CacheController@client');
Route::get('/cache-management/addclient', function(){ return view('cache-mgmt/addclient');})->name('cache-management.addclient');
Route::post('/cache-management/creatclient', 'CacheController@creatclient')->name('cache-management.creatclient');
Route::get('/cache-management/{id}/editClient', 'CacheController@editClient')->name('cache-management.editClient');
Route::get('/cache-management/{id}/details', 'CacheController@details')->name('cache-management.details');
Route::post('/cache-management/{id}/updateClient', 'CacheController@updateClient')->name('cache-management.updateClient');


//************* */ CASHOUT MANAGEMENT *********************//////
Route::get('/cashout-management', 'CashoutController@index')->name('cashout-management');
Route::post('cashout-management/searchCashOut', 'CashoutController@searchCashOut')->name('cashout-management.searchCashOut'); 
Route::get('cashout-management/{id}/editcashout', 'CashoutController@editcashout')->name('cashout-management.editcashout');
Route::patch('cashout-management/{id}/update', 'CashoutController@update')->name('cashout-management.update'); 


///////////-***************** KYC ***********************/

Route::get('/kyc-management', 'KycController@index')->name('kyc-management');


//******************VIREMENT MANEGEMENT  ******************** */
Route::get('/virement-management', 'VirementController@index')->name('virement-management');



//******************Abonnes MANEGEMENT  ******************** */
Route::get('/abonnes-management', 'AbonnesController@index')->name('abonnes-management');
Route::post('abonnes-management/searchabonnes', 'AbonnesController@searchabonnes')->name('abonnes-management.searchabonnes');
Route::get('abonnes-management/{id}/editabonnes', 'AbonnesController@editabonnes')->name('abonnes-management.editabonnes');
Route::post('abonnes-management/updateAbonnes', 'AbonnesController@updateAbonnes')->name('abonnes-management.updateAbonnes');
Route::get('/abonnes-management/abonnes', 'AbonnesController@abonnes')->name('abonnes-management.abonnes');


//******************Stats MANEGEMENT  ******************** */
Route::get('/stats-management', 'StatsController@index')->name('stats');


Route::get('/cache-management/retrait', 'CacheController@retrait')->name('cache-management.retrait'); 
Route::get('/cache-management/cashin', 'CacheController@cashin')->name('cache-management.cashin');

Route::get('/cache-management/cashout', 'CacheController@cashout')->name('cache-management.cashout');

//Route::resource('cache-management/retrait', 'CacheController');
Route::resource('cache-management', 'CacheController');
Route::post('cache-management/search', 'CacheController@search')->name('cache-management.search');    
Route::post('cache-management/searchCashOut', 'CacheController@searchCashOut')->name('cache-management.searchCashOut');  

Route::post('cache-management/editcashout', 'CacheController@editcashout')->name('cache-management.editcashout');

Route::resource('transfert-management', 'TransfertController');
Route::post('transfert-management/search', 'TransfertController@search')->name('transfert-management.search');
Route::get('cache-management/{id}/editSolde', 'CacheController@editSolde')->name('cache-management.editSolde');

Route::post('cache-management/updatesolde', 'CacheController@updatesolde')->name('cache-management.updatesolde');
Route::post('cache-management/excel', 'CacheController@exportExcel')->name('excel_cash');
Route::post('cache-management/excel_cashout', 'CacheController@exportExcel_cashout')->name('excel_cashout');

//Route::get('/agence-management', 'AgenceController@index');updatesolde
//Route::get('agence-management/destroy', 'AgenceController@destroy')->name('agence-management.destroy');
//Route::get('agence-management/{id}/edit', 'AgenceController@edit');
//Route::get('agence-management/create', 'AgenceController@create')->name('agence-management.create');
Route::post('atlpay-management/search', 'AtlpayController@search')->name('atlpay-management.search');

Route::resource('atlpay-management', 'AtlpayController');
Route::post('atlpay-management/excel', 'AtlpayController@exportExcel')->name('excel');
Route::post('atlpay-management/pdf', 'AtlpayController@exportPDF')->name('pdf');




Route::get('/dashboard', 'CacheController@index');
// Route::get('/system-management/{option}', 'SystemMgmtController@index');
Route::get('/profile', 'ProfileController@index');

Route::post('user-management/search', 'UserManagementController@search')->name('user-management.search');
Route::resource('user-management', 'UserManagementController');
Route::resource('transfert-management', 'TransfertController');

Route::resource('employee-management', 'EmployeeManagementController');
Route::post('employee-management/search', 'EmployeeManagementController@search')->name('employee-management.search');

//Route::resource('system-management/department', 'DepartmentController');
Route::post('system-management/department/search', 'DepartmentController@search')->name('department.search');

Route::resource('system-management/division', 'DivisionController');
Route::post('system-management/division/search', 'DivisionController@search')->name('division.search');

Route::resource('system-management/country', 'CountryController');
Route::post('system-management/country/search', 'CountryController@search')->name('country.search');

Route::resource('system-management/state', 'StateController');
Route::post('system-management/state/search', 'StateController@search')->name('state.search');

Route::resource('system-management/city', 'CityController');
Route::post('system-management/city/search', 'CityController@search')->name('city.search');

Route::get('system-management/report', 'ReportController@index');
Route::post('system-management/report/search', 'ReportController@search')->name('report.search');
Route::post('system-management/report/excel', 'ReportController@exportExcel')->name('report.excel');
Route::post('system-management/report/pdf', 'ReportController@exportPDF')->name('report.pdf');

Route::get('avatars/{name}', 'EmployeeManagementController@load');
//Route::get('avatars/{name}', 'AgenceController@load');