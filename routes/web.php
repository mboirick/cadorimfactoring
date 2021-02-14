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
Route::get('/payement/waiting', 'Backend\Payement\ManagementController@waiting')->name('payement.waiting');
Route::get('/payement/clients', 'Backend\Payement\ManagementController@clients')->name('payement.clients');
Route::get('/payement/customer/edit/{id}', 'Backend\Payement\ManagementController@edit')->name('payement.customer.edit');
Route::get('/payement/customer/story/{id}', 'Backend\Payement\ManagementController@story')->name('payement.customer.story');
Route::get('/payement/detail/{id}', 'Backend\Payement\ManagementController@detail')->name('payement.detail');
Route::get('/payement/add/customer', function(){ return view('backend.payement.management.addcustomer');})->name('payement.add.customer');
Route::post('/payement/add/customer', 'Backend\Payement\ManagementController@store')->name('payement.add.customer');
Route::post('/payement/transaction', 'Backend\Payement\ManagementController@transaction')->name('payement.transaction');
Route::post('payement/customer/edit/{id}', 'Backend\Payement\ManagementController@update')->name('payement.customer.edit');
Route::get('/payement/customer/credit/{id}', 'Backend\Payement\ManagementController@credit')->name('payement.customer.credit');
Route::get('/payement/customer/debite/{id}', 'Backend\Payement\ManagementController@debite')->name('payement.customer.debite');
Route::post('/payement/add/amount', 'Backend\Payement\ManagementController@addamount')->name('payement.add.amount');
Route::post('/payement/customer/withdrawal/amount', 'Backend\Payement\ManagementController@withdrawalamount')->name('payement.customer.withdrawal.amount');

///////////-***************** KYC ***********************/

Route::get('/kyc-management/{id}/{email}', 'KycController@index')->name('kyc-management');
Route::get('/revenu-management/{id}/{email}', 'KycController@revenu')->name('revenu-management');
Route::post('/kyc-management/update', 'KycController@update')->name('kyc-management.update');
Route::post('/kyc-management/updaterevenu', 'KycController@updaterevenu')->name('kyc-management.updaterevenu');



//******************VIREMENT MANEGEMENT  ******************** */
Route::get('/virement-management', 'VirementController@index')->name('virement-management');
Route::resource('virement-management', 'VirementController');

Route::post('/mail/send', 'MailController@send')->name('mail-send');

//Agencies Management
Route::get('/agencies/home', 'Backend\Agency\ManagementController@index')->name('agencies.home');
Route::get('/agencies/search', 'Backend\Agency\ManagementController@index')->name('agencies.search');

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

Route::any('/agency/add', 'Backend\Agency\ManagementController@create')->name('agency.add');
Route::post('/agency/add', 'Backend\Agency\ManagementController@store')->name('agency.add');

Route::any('/agency/edit/{id}', 'Backend\Agency\ManagementController@update')->name('agency.edit');
Route::get('/agency/edit/{id}', 'Backend\Agency\ManagementController@edit')->name('agency.edit');


//ATL
Route::get('atlpay/home', 'Backend\Atlpay\ManagementController@index')->name('atlpay.home');
Route::post('atlpay/export/excel', 'Backend\Atlpay\ManagementController@exportExcel')->name('atlpay.export.excel');
Route::get('atlpay/detail/{date}', 'Backend\Atlpay\ManagementController@details')->name('atlpay.detail');
Route::get('/profile', 'ProfileController@index');
Route::resource('transfert-management', 'TransfertController');

//sondage
Route::get('/survey/home', 'Backend\SurveyController@index')->name('survey.home');
Route::get('/survey/answers/{idPoll}', 'Backend\SurveyController@answers')->name('survey.answers');
Route::get('/survey/add',  function(){ 
    return view('backend.survey.add');
})->name('survey.add');

Route::post('/survey/store', 'Backend\SurveyController@store')->name('survey.store');
Route::post('/survey/update', 'Backend\SurveyController@update')->name('survey.update');
Route::get('/survey/send/email/{id}', 'Backend\SurveyController@emailing')->name('survey.send.email');
Route::post('sondage-management/creation', 'SondageController@creation')->name('sondage-management.creation');
Route::post('sondage-envoiemail', 'SondageController@envoiemail')->name('sondage-envoiemail');
Route::post('/sondage-search', 'SondageController@searchsondage')->name('searchsondage');

// Réduction
Route::resource('/reduce','Backend\ReductionController');
Route::get('reduce/delete/{id}','Backend\ReductionController@destroy');