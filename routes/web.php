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

use App\Abonne;
//use App\Http\Controllers\Backend\RoleController;

Route::get('/testabonnes/{id}', function($id){

    $abonne= Abonne::where('email', '=', $id)->firstOrFail();


    dd($abonne->commandes);
});


Auth::routes();

Route::get('/','Backend\DashboardController@index');
Route::get('/dashboard', 'Backend\DashboardController@index');

//User
Route::get('/users', 'Backend\UserController@index')->name('users');
Route::get('/user/delete/{id}', 'Backend\UserController@delete')->name('user.delete');
Route::get('/user/restore/{id}', 'Backend\UserController@restore')->name('user.restore');

Route::get('/user/edit/{id}', 'Backend\UserController@edit')->name('user.edit');
Route::post('/user/update', 'Backend\UserController@update')->name('user.update');

Route::get('/profile', 'Backend\UserController@profile')->name('user.profile');
Route::post('/profile', 'Backend\UserController@updateProfile')->name('user.profile');
Route::get('/user/add', 'Backend\UserController@add')->name('user.add');
Route::post('/user/store', 'Backend\UserController@store')->name('user.store');

//Roles 
Route::resource('roles', Backend\RoleController::class);

//Subscribers
Route::get('/subscribers/visualize/file/{id}', 'backend\Subscribers\ManagementController@visualizeFile')->name('subscribers.visualize.file');

Route::get('/subscribers/download/file/{id}', 'backend\Subscribers\ManagementController@downloadFile')->name('subscribers.download.file');

Route::get('/subscribers/delete/file/{id}', 'backend\Subscribers\ManagementController@deleteFile')->name('subscribers.delete.file');

Route::get('/subscribers/send/form/{id}', 'backend\Subscribers\ManagementController@rejetSendForm')->name('subscribers.send.form');

Route::get('/subscribers/send/reminder/{id}', 'backend\Subscribers\ManagementController@sendReminder')->name('subscribers.send.reminder');

Route::get('/subscribers/details', 'backend\Subscribers\ManagementController@details')->name('subscribers.details');

Route::post('/subscribers/update', 'backend\Subscribers\ManagementController@update')->name('subscribers.update');

Route::get('/subscribers/edit/{id}', 'backend\Subscribers\ManagementController@edit')->name('subscribers.edit');

Route::get('/subscribers/home', 'backend\Subscribers\ManagementController@index')->name('subscribers.home');

Route::get('/subscribers/search', 'backend\Subscribers\ManagementController@index')->name('subscribers.search');


//CashOut
Route::get('/cash/out/view/documet/{id}', 'backend\CashOut\ManagementController@viewDocument')->name('cash.out.view.documet');

Route::patch('/cash/out/operation/{id}', 'backend\CashOut\ManagementController@operation')->name('cash.out.operation');

Route::get('/cash/out/request/{status}/{id}', 'backend\CashOut\ManagementController@request')->name('cash.out.request');

Route::patch('/cash/out/detail/{id}', 'backend\CashOut\ManagementController@detail')->name('cash.out.detail');

Route::get('/cash/out/edit/{id}', 'backend\CashOut\ManagementController@edit')->name('cash.out.edit');

Route::get('/cash/out/serach', 'backend\CashOut\ManagementController@search')->name('cash.out.serach');

Route::get('/cash/out/home', 'backend\CashOut\ManagementController@index')->name('cash.out.home');

Route::get('/cash/out/operator', 'backend\CashOut\ManagementController@operator')->name('cash.out.operator');

Route::get('/cash/out/confirme/operation/{id}/{operator}', 'backend\CashOut\ManagementController@operationConfirmation')->name('cash.out.confirme.operation');

//Bill
Route::get('/transaction/bill/{idBill}/download', 'Backend\BaseController@downloadBill')->name('transaction.bill.download')->where('idBill', '[0-9]+');;

//Sponsoring
Route::get('/sponsoring/plan/send', 'Backend\Sponsoring\ManagementController@planSend')->name('sponsoring.plan.send');

Route::any('/sponsoring/email/form/{id}', 'Backend\Sponsoring\ManagementController@emailForm')->name('sponsoring.email.form');
Route::post('/sponsoring/email/form/{id}', 'Backend\Sponsoring\ManagementController@emailSend')->name('sponsoring.email.form');

Route::get('/sponsoring/search', 'Backend\Sponsoring\ManagementController@search')->name('sponsoring.search');

Route::get('/sponsoring/home', 'Backend\Sponsoring\ManagementController@index')->name('sponsoring.home');

//CashFlow
Route::get('/cash/flow/invoices/{path}', 'Backend\CashFlow\TransactionController@sowPdf')->name('cash.flow.invoices');

Route::any('/cash/flow/transaction/add/files/{idCash}', 'Backend\CashFlow\TransactionController@addFiles')->name('cash.flow.add.files');
Route::post('/cash/flow/transaction/add/files/{idCash}', 'Backend\CashFlow\TransactionController@uploadProofDocument')->name('cash.flow.add.files');

Route::get('/cash/flow/transaction/confirmation/{state}/{idBill}', 'Backend\CashFlow\TransactionController@confirmation')->name('cash.flow.transaction.confirmation');

Route::get('/cash/flow/daily/report', 'Backend\CashFlow\ManagementController@dailyReport')->name('cash.flow.daily.report');

Route::any('/cash/flow/transaction/withdrawal', 'Backend\CashFlow\TransactionController@withdrawal')->name('cash.flow.transaction.withdrawal');
Route::post('/cash/flow/transaction/withdrawal', 'Backend\CashFlow\TransactionController@amputation')->name('cash.flow.transaction.withdrawal');

Route::any('/cash/flow/transaction/deposit', 'Backend\CashFlow\TransactionController@deposit')->name('cash.flow.transaction.deposit');
Route::post('/cash/flow/transaction/deposit', 'Backend\CashFlow\TransactionController@put')->name('cash.flow.transaction.deposit');

Route::get('/cash/flow/home', 'Backend\CashFlow\ManagementController@index')->name('cash.flow.home');

//***************CASH MENAGEMENT************* */
//Route::get('/cache-management', 'CacheController@indcash/flow/transaction/confirmationex')->name('cache-management');
//Route::get('/cache-management/{id}/details', 'CacheController@details')->name('cache-management.details');

//Route::get('/paiement-management/depotview', 'PayementController@depotview')->name('depotview');
//Route::get('/paiement-management/retraitview', 'PayementController@retraitview')->name('retraitview');

//Route::post('/paiement-management/depotretrait', 'PayementController@depotretrait')->name('depotretrait');
//Route::get('/paiement-management/story', 'PayementController@story');
//Route::get('/paiement-management/attente', 'PayementController@attente');
//Route::get('/paiement-management/clients', 'PayementController@client');
//Route::get('/paiement-management/search', 'PayementController@search')->name('paiement-management.search'); 

//Route::get('/paiement-management/{id}/editClient', 'PayementController@editClient')->name('paiement-management.editClient');

//Route::get('/paiement-management/{id}/clientstory', 'PayementController@clientstory')->name('paiement-management.clientstory');
//Route::get('/paiement-management/{id}/clientdemande', 'PayementController@clientdemande')->name('paiement-management.clientdemande');
//Route::get('/paiement-management/{id}/detail', 'PayementController@detail')->name('paiement-management.detail');
//Route::get('/paiement-management/addclient', function(){ return view('paiement-mgmt/addclient');})->name('paiement-management.addclient');
//Route::post('/paiement-management/creatclient', 'PayementController@creatclient')->name('paiement-management.creatclient');
//Route::post('/paiement-management/gestion', 'PayementController@gestion')->name('paiement-management.gestion');
//Route::post('/paiement-management/decision', 'PayementController@decision')->name('paiement-management.decision');
//Route::post('/paiement-management/{id}/updateClient', 'PayementController@updateClient')->name('paiement-management.updateClient');

//Route::get('/paiement-management/{id}/crediter', 'PayementController@crediter')->name('paiement-management.crediter');
//Route::get('/paiement-management/{id}/debiter', 'PayementController@debiter')->name('paiement-management.debiter');

//Route::post('/paiement-management/addmontant', 'PayementController@addmontant')->name('paiement-management.addmontant');
//Route::post('/paiement-management/retirermontant', 'PayementController@retirermontant')->name('paiement-management.retirermontant');
//Route::post('/paiement-management/searchcompte', 'PayementController@searchcompte')->name('searchcompte');
//************* */ CASHOUT MANAGEMENT *********************//////
/*Route::get('/cashout-management', 'CashoutController@index')->name('cashout-management');
Route::get('cashout-management/searchCashOut', 'CashoutController@searchCashOut')->name('cashout-management.searchCashOut'); 
Route::get('cashout-management/{id}/editcashout', 'CashoutController@editcashout')->name('cashout-management.editcashout');
Route::patch('cashout-management/{id}/update', 'CashoutController@update')->name('cashout-management.update'); 
Route::patch('cashout-management/{id}/infos', 'CashoutController@infos')->name('cashout-management.infos'); 
Route::get('cashout-management/{id}/gazaconfirmation', 'CashoutController@gazaconfirmation')->name('cashout-management.gazaconfirmation');
Route::get('/cashout-gaza', 'CashoutController@gaza')->name('gaza-transfert');
Route::get('/cashout-correction', 'CashoutController@correction');*/
///////////-***************** KYC ***********************/

Route::get('/kyc-management/{id}/{email}', 'KycController@index')->name('kyc-management');
Route::get('/revenu-management/{id}/{email}', 'KycController@revenu')->name('revenu-management');
Route::post('/kyc-management/update', 'KycController@update')->name('kyc-management.update');
Route::post('/kyc-management/updaterevenu', 'KycController@updaterevenu')->name('kyc-management.updaterevenu');



//******************VIREMENT MANEGEMENT  ******************** */
Route::get('/virement-management', 'VirementController@index')->name('virement-management');
Route::resource('virement-management', 'VirementController');


Route::post('/mail/send', 'MailController@send')->name('mail-send');
//Parrainage
//Route::get('/parrainage-management/formulaire-planifier-envoie-courriel', 'ParrainageController@formulaireplanifierenvoiecourriel')->name('parrainage-management.formulaire-planifier-envoie-courriel');
//Route::get('/parrainage-management/search', 'ParrainageController@search')->name('parrainage-management.search');
//Route::get('/parrainage-management', 'ParrainageController@index')->name('parrainage-management');
//Route::get('/parrainage-management/formulaire-courriel/{id}', 'ParrainageController@formulairecourriel')->name('parrainage-management.formulaire-courriel')->where('id', '[0-9]+');

//******************Abonnes MANEGEMENT  ******************** */
//Route::get('/abonnes-management', 'AbonnesController@index')->name('abonnes-management');
//Route::get('/abonnes-details', 'AbonnesController@details')->name('abonnes-details');
//Route::get('/abonnes-kyc/{email}', 'AbonnesController@kyc')->name('abonnes');
//Route::get('abonnes-management/searchabonnes', 'AbonnesController@searchabonnes')->name('abonnes-management.searchabonnes');
//Route::get('abonnes-management/{id}/editabonnes', 'AbonnesController@editabonnes')->name('abonnes-management.editabonnes');
//Route::post('abonnes-management/updateAbonnes', 'AbonnesController@updateAbonnes')->name('abonnes-management.updateAbonnes');
//Route::post('abonnes-management/mailrejet', 'AbonnesController@mailrejet')->name('abonnes-management.mailrejet');
//Route::get('/abonnes-management/abonnes', 'AbonnesController@abonnes')->name('abonnes-management.abonnes');
//Route::get('/abonnes-parrainage', 'AbonnesController@parrainage');


//Route::get('/abonnes-management/{id}/telechargerdocument', 'AbonnesController@telechargerdocument')->name('telechargerdocument');
//Route::get('/abonnes-management/{id}/visualiser', 'AbonnesController@visualiser')->name('visualiser');
//Route::get('/abonnes-management/{id}/supprimerdocument', 'AbonnesController@supprimerdocument')->name('supprimerdocument');

//******************Stats MANEGEMENT  ******************** */
//Route::get('/stats-management', 'StatsController@index')->name('stats');


//Route::get('/cache-management/retrait', 'CacheController@retrait')->name('cache-management.retrait'); 
//Route::get('/cache-management/cashin', 'CacheController@cashin')->name('cache-management.cashin');

//Route::get('/cache-management/cashout', 'CacheController@cashout')->name('cache-management.cashout');

//Route::resource('cache-management/retrait', 'CacheController');
//Route::resource('cache-management', 'CacheController');
Route::get('cache-management/search', 'CacheController@search')->name('cache-management.search');
//Route::get('cache-management/addcash', 'CacheController@addcash')->name('cache-management.addcash');
//Route::post('cache-management/addcashstore', 'CacheController@addcashstore')->name('cache-management.addcashstore');
Route::get('cache-management/retraitcash', 'CacheController@retraitcash')->name('cache-management.retraitcash'); 

Route::get('cache-rapportquotidien/{date}', 'CacheController@rapportquotidien')->name('cache-management.rapportquotidien');
Route::post('cache-management/searchCashOut', 'CacheController@searchCashOut')->name('cache-management.searchCashOut');  

Route::post('cache-management/editcashout', 'CacheController@editcashout')->name('cache-management.editcashout');

Route::resource('transfert-management', 'TransfertController');
Route::post('transfert-management/search', 'TransfertController@search')->name('transfert-management.search');
//Route::get('cache-management/{id}/editSolde', 'CacheController@editSolde')->name('cache-management.editSolde');

//Route::post('cache-management/updatesolde', 'CacheController@updatesolde')->name('cache-management.updatesolde');
Route::post('cache-management/excel', 'CacheController@exportExcel')->name('excel_cash');
Route::post('cache-management/excel_cashout', 'CacheController@exportExcel_cashout')->name('excel_cashout');

//Route::get('/agence-management', 'AgenceController@index');
Route::get('/agencies/home', 'Backend\Agency\ManagementController@index')->name('agencies.home');
Route::get('/agencies/search', 'Backend\Agency\ManagementController@index')->name('agencies.search');

//Route::post('/agence-management/depotretraitagence', 'AgenceController@depotretraitagence')->name('depotretraitagence');
//Route::get('/agence-management/potadegenceview', 'AgenceController@depotagenceview')->name('depotagenceview');
//Route::get('/agence-management/retraitagenceview', 'AgenceController@retraitagenceview')->name('retraitagenceview');
//Route::get('agence-management/destroy', 'AgenceController@destroy')->name('agence-management.destroy');
//Route::get('/agence-management/{id}/crediter', 'AgenceController@crediter')->name('agence-management.crediter');
//Route::get('/agence-management/{id}/debiter', 'AgenceController@debiter')->name('agence-management.debiter');
//Route::get('/agence-management/{id}/editagence', 'AgenceController@editagence')->name('agence-management.editagence');
//Route::get('/agence-management/{id}/agencestory', 'AgenceController@agencestory')->name('agence-management.agencestory');
//Route::get('/agence-management/{id}/operationstory', 'Statsagence@index')->name('agence-management.operationstory');

//Route::get('/agence-management/{id}/{jour}/details', 'AgenceController@operationstory')->name('agence-management.details');
//Route::get('agence-management/{id}/edit', 'AgenceController@edit');
//Route::get('agence-management/create', 'AgenceController@create')->name('agence-management.create');
//Route::get('agence-management/search', 'AgenceController@search')->name('agence-management.search');

//Agencies Export
Route::get('/agencies/export/agency', 'Backend\Agency\ExportController@agency')->name('agencies.export.agency');

//Agencies Transaction
Route::get('/agencies/transaction/confirmation/{state}/{idBill}', 'Backend\Agency\TransactionController@confirmation')->name('agencies.transaction.confirmation')->where('idBill', '[0-9]+');

Route::get('/agencies/transaction/credit/confirmation/{idClientDebtor}/{idClientBenefit}', 'Backend\Agency\TransactionController@confirmationCredit')->name('agencies.transaction.credit.confirmation');

Route::get('/agencies/transaction/operation/detail/{idClient}/{day}', 'Backend\Agency\TransactionController@detail')->name('agencies.transaction.operation.detail');

Route::get('/agencies/transaction/operation/story/{id}', 'Backend\Agency\TransactionController@operationStory')->name('agencies.transaction.operation.story');

Route::get('/agencies/transaction/story/{id}', 'Backend\Agency\TransactionController@story')->name('agencies.transaction.story');

Route::get('/agencies/transaction/debit/{id}', 'Backend\Agency\TransactionController@debit')->name('agencies.transaction.debit');
Route::post('/agencies/transaction/pull', 'Backend\Agency\TransactionController@withdrawAmount')->name('agencies.transaction.pull');

Route::any('/agencies/transaction/credit/{id}', 'Backend\Agency\TransactionController@credit')->name('agencies.transaction.credit');
Route::post('/agencies/transaction/credit/{id}', 'Backend\Agency\TransactionController@addAmount')->name('agencies.transaction.credit');

Route::any('/agencies/transaction/deposit', 'Backend\Agency\TransactionController@deposit')->name('agencies.transaction.deposit');
Route::post('/agencies/transaction/deposit', 'Backend\Agency\TransactionController@put')->name('agencies.transaction.deposit');

Route::any('/agencies/transaction/withdrawal', 'Backend\Agency\TransactionController@withdrawal')->name('agencies.transaction.withdrawal');
Route::post('/agencies/transaction/withdrawal', 'Backend\Agency\TransactionController@amputation')->name('agencies.transaction.withdrawal');

//Agencies Management
Route::any('/agency/add', 'Backend\Agency\ManagementController@create')->name('agency.add');
Route::post('/agency/add', 'Backend\Agency\ManagementController@store')->name('agency.add');

Route::any('/agency/edit/{id}', 'Backend\Agency\ManagementController@update')->name('agency.edit');
Route::get('/agency/edit/{id}', 'Backend\Agency\ManagementController@edit')->name('agency.edit');

//Route::get('/agence-management/addagence', 'AgenceController@addagence')->name('agence-management.addagence');
//Route::resource('agence-management', 'AgenceController');
//Route::post('/agence-management/addmontant', 'AgenceController@addmontant')->name('agence-management.addmontant');
//Route::post('/agence-management/retirermontant', 'AgenceController@retirermontant')->name('agence-management.retirermontant');


//Route::post('atlpay-management/search', 'AtlpayController@search')->name('atlpay-management.search');

//Route::get('atlpay-management', 'AtlpayController@index');
//Route::post('atlpay-management/excel', 'AtlpayController@exportExcel')->name('excel');
//Route::get('atlpay-management/statatlpay', 'AtlpayController@statatlpay')->name('statatlpay');
//Route::get('atlpay-management/{id}/details', 'AtlpayController@details')->name('atlpay-management.details');
//Route::post('atlpay-management/pdf', 'AtlpayController@exportPDF')->name('pdf');


//ATL
Route::get('atlpay/home', 'Backend\Atlpay\ManagementController@index')->name('atlpay.home');
Route::post('atlpay/export/excel', 'Backend\Atlpay\ManagementController@exportExcel')->name('atlpay.export.excel');
Route::get('atlpay/detail/{date}', 'Backend\Atlpay\ManagementController@details')->name('atlpay.detail');

//Route::get('/dashboard', 'CacheController@index');
// Route::get('/system-management/{option}', 'SystemMgmtController@index');
Route::get('/profile', 'ProfileController@index');

//Route::post('user-management/search', 'UserManagementController@search')->name('user-management.search');
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



//sondage
Route::get('/survey/home', 'Backend\SurveyController@index')->name('survey.home');

Route::get('/survey/answers/{idPoll}', 'Backend\SurveyController@answers')->name('survey.answers');

Route::get('/survey/add',  function(){ 
    return view('backend.survey.add');
})->name('survey.add');


Route::post('/survey/store', 'Backend\SurveyController@store')->name('survey.store');

Route::post('/survey/update', 'Backend\SurveyController@update')->name('survey.update');

Route::get('/survey/send/email/{id}', 'Backend\SurveyController@emailing')->name('survey.send.email');

//Route::get('sondage-management/creationsondage',  function(){ return view('sondage-mgmt/addsondage');})->name('sondage-management.addsondage');

//Route::get('/sondage-management/survey', 'SondageController@survey')->name('sondage-management.survey');
Route::post('sondage-management/creation', 'SondageController@creation')->name('sondage-management.creation');

//Route::get('/sondage-management', 'SondageController@index');
Route::get('/sondage-management/{email}/{id}', 'SondageController@index')->name('sondage-management');
Route::get('/sondage-management/{id_sondages}', 'SondageController@voir')->name('sondage-management.voir');
//Route::get('/sondage-reponses/{id_sondages}', 'SondageController@reponses')->name('sondage-reponses');
//Route::get('/sondage-emailing/{id_sondage}/emailing', 'SondageController@emailing')->name('sondage-emailing');
//Route::post('sondage-management/update', 'SondageController@update')->name('sondage-management.update');

Route::post('sondage-envoiemail', 'SondageController@envoiemail')->name('sondage-envoiemail');
Route::post('/sondage-search', 'SondageController@searchsondage')->name('searchsondage');
//Route::get('avatars/{name}', 'AgenceController@load');

// Réduction
Route::resource('/reduce','Backend\ReductionController');

//Route::resource('/coupon','CouponController');
Route::get('reduce/delete/{id}','Backend\ReductionController@destroy');