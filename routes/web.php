<?php

use App\Http\Controllers\files;
use App\Http\Controllers\flush;
use App\Http\Controllers\users;
use App\Http\Controllers\orders;
use App\Http\Controllers\centers;
use App\Http\Controllers\history;
use App\Http\Controllers\expenses;
use App\Http\Controllers\salaries;
use App\Http\Controllers\companies;
use App\Http\Controllers\discounts;
use App\Http\Controllers\operation;
use App\Http\Controllers\accountant;
use App\Http\Controllers\causedelay;
use App\Http\Controllers\orderstate;
use App\Http\Controllers\subcenters;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\causereturn;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\firebasecrud;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\supllies\agents;
use App\Http\Controllers\customer_service;
use App\Http\Controllers\isagentcontroller;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\iscompanycontroller;
use App\Http\Controllers\financiace_accountants;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\persons_accounts_financial;
use App\Http\Controllers\supllies\companies as SuplliesCompanies;

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
    'home' => false
]);
Route::middleware(['lang', 'auth','RankParent'])->group(function () {
Route::controller(NotificationsController::class)->group(function(){
    Route::get('notifications','notifications')->name('notifications');
    Route::post('notifications/read','read')->name('read');
});
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
    Route::get('/storage/{dd}', [\App\Http\Controllers\files::class, 'exist']);
    Route::get('storage/{id}/drvlicence.pdf', [\App\Http\Controllers\files::class, 'tt'])->middleware('auth');
    Route::get('storage/{id}/drvlicence.pdf')->middleware('auth');
    Route::controller(centers::class)->group(function () {
        Route::get('centers/', 'read')->name('centers');
        Route::get('centers/add', 'add')->name('center_add');
        Route::post('centers/store', 'store')->name('center_store');
        Route::get('centers/edit/{id}', 'edit')->name('center_edit');
        Route::put('centers/update', 'update')->name('center_update');
        Route::delete('centers/delete/{id}', 'delete')->name('center_delete');
    });
    Route::controller(subcenters::class)->group(function () {
        Route::get('centers/sub/add', 'addsubcenter')->name('addsubcenter');
        Route::post('centers/sub/store', 'storesubcenter')->name('storesubcenter');
        Route::get('centers/sub/', 'readsubcenter')->name('readsubcenter');
        Route::get('centers/sub/edit{id}', 'editsubcenter')->name('editsubcenter');
        Route::put('centers/sub/update', 'updatesubcenter')->name('updatesubcenter');
        Route::delete('centers/sub/delete/{id}', 'deletesubcenter')->name('deletesubcenter');
        Route::get('centers/sub/excel', 'excelsubcenter')->name('excelsubcenter');
        Route::post('centers/sub/excel/store', 'excelcenterstore')->name('excelcenterstore');
    });
    Route::controller(users::class)->group(function () {
        Route::get('users', 'read')->name('users');
        Route::get('users/view/{file}', 'view')->name('viewfile');
        Route::get('users/add', 'add')->name('user_add');
        Route::post('users/store', 'store')->name('user_store');
        Route::get('users/edit/{id}', 'edit')->name('user_edit');
        Route::put('users/update/', 'update')->name('user_update');
        Route::delete('users/delete/{id}', 'delete')->name('user_delete');
        Route::get('users/delegates/manage', 'manage_index')->name('manage_index');
        Route::get('users/delegates/manage/add', 'manage_add')->name('manage_add');
        Route::post('users/delegates/manage/store', 'manage_store')->name('manage_store');
        Route::get('users/delegates/manage/edit/{id}', 'manage_edit')->name('manage_edit');
        Route::put('users/delegates/manage/update', 'manage_update')->name('manage_update');
        Route::delete('users/delegates/manage/delete/{id}', 'manage_delete')->name('manage_delete');
        Route::get('users/delegates', 'delegates')->name('delegates');
        Route::get('users/delegates/track','track')->name('trackdelegates');
        Route::get('users/delegates/delete/{id}', 'delegates_delete')->name('delegates_delete');
        Route::post('users/delegates/store', 'delegates_store')->name('delegates_store');
        Route::get('users/delegates/app', 'firebase_read')->name('firebase_read');
        Route::post('users/delegates/app/store', 'firebase_store')->name('firebase_store');
    });
    Route::controller(companies::class)->group(function () {
        Route::get('companies', 'read')->name('companies');
        Route::get('companies/add', 'add')->name('companies_add');
        Route::post('companies/store', 'store')->name('companies_store');
        Route::get('companies/addnum', 'addnum')->name('company_addnum');
        Route::post('companies/num/store', 'num_store')->name('companies_num_store');
        Route::get('companies/nums/{id}', 'viewnums')->name('company_view_nums');
        Route::get('companies/edit/{id}', 'edit')->name('company_edit');
        Route::put('companies/update', 'update')->name('companies_update');
        Route::delete('companies/delete/{id}', 'delete')->name('company_delete');
    });
    Route::controller(operation::class)->group(function () {
        Route::get('operation/', 'index')->name('operation');
    });
    Route::controller(customer_service::class)->group(function () {
        Route::get('customerservice', 'index')->name('customerservice');
    });
    Route::controller(accountant::class)->group(function () {
        Route::get('accountant', 'index')->name('accountant');
        Route::get('accountant/pesronsfin/add', 'add')->name('accountant_add_personfin');
        Route::post('accountant/pesronsfin/store', 'store')->name('accountant_store_personfin');
        Route::get('accountant/pesronsfin', 'index_persons')->name('accountant_pesronsfin');
        //
        Route::get('accountant/finances/add', 'add_finance')->name('accountant_add_finance');
        Route::post('accountant/finances/store', 'store_finance')->name('accountant_store_finance');
        Route::get('accountant/finances/person/{id}', 'person_acontant')->name('accountant_person_acontant');
        //orders
        Route::get('accountant/orders/add', 'addorders')->name('accountant_addorders');
        Route::get('accountant/orders/new', 'new')->name('accountant_neworders');
        Route::get('accountant/orders/edit/{id}', 'edit')->name('accountant_orderedit');
        Route::put('accountant/orders/update','update')->name('accountant_orderupdate');
        //history
        Route::get('accountant/history/orders', 'orders_history')->name('accountant_orders_history');
        Route::get('accountant/history/arcieve', 'history_arcieve')->name('accountant_history_arcieve');
        //supplies
        Route::get('accountant/supplies/agents/new', 'supplies_newd')->name('accountant_supplies_newd');
        Route::get('accountant/supplies/agents/hold', 'hold_supplies')->name('accountant_hold_supplies');
        Route::get('accountant/supplies/agents/supply/h/{agentid}', 'h_supplies')->name('accountant_agent_h_supplies');
        Route::get('accountant/supplies/agents/store/{id}', 'supplies_stored')->name('accountant_supplies_stored');
        Route::post('accountant/supplies/agents/supply', 'supplies_supply')->name('accountant_supplies_supply');
        Route::get('accountant/orders/delegate','orders_delegate')->name('accountant_orders_delegate');
        Route::get('accountant/delegates/search/{id}','orders_delegate_search')->name('accountant.delegates.search');
        Route::get('accountant/company/{param}','indexcompanies')->name('accountantindexcompanies');
        Route::match(['get', 'post'],'accountant/orders/search', 'orderssearch')->name('accountantorderssearch');

    });
    Route::controller(iscompanycontroller::class)->group(function () {
        Route::get('company', 'index')->name('orderscompany');
        Route::get('company/orders/new','add_manual')->name('companyaddmanual');
        Route::post('company/orders/store','store_m')->name('companyaddmanualstore');
        Route::get('company/orders/{id}/edit','edit')->name('companyorderedit')->middleware('orderdisableeditcompany');
        Route::post('company/orders/{id}/update','update')->name('companyorderupdate')->middleware('orderdisableeditcompany');
        Route::get('company/sheet', 'addfromsheet')->name('companyaddsheet');
        Route::post('company/sheet/store', 'storesheet')->name('companystoresheet');
        Route::get('company/history', 'history')->name('companyhistory');
        Route::post('company/checks','checks')->name('companychecks');
    });
    Route::controller(isagentcontroller::class)->group(function () {
        Route::get('agent', 'index')->name('agent');
    });
    Route::controller(orders::class)->middleware('AllowedRanksMiddleWare')->group(function () {
        //orders section
        Route::get('orders-data','orders')->name('ordersdata');
        Route::get('orders/company/{param}','indexcompanies')->name('orderscompantnotdeliver');
        Route::get('orders/add', 'addorders')->name('addorders');
        Route::post('orders/store', 'storeorders')->name('storeorders');
        Route::get('orders/new', 'new')->name('neworders');
        Route::post('orders/new/store', 'store_m')->name('store_m');
        Route::get('orders/all', 'index')->name('orders_all');
        Route::get('orders/all/ajax','ordersajax')->name('ordersajax');
        Route::get('orders/stamp', 'stamp')->name('stamp');
        Route::get('orders/stamp_sub', 'stamp_sub')->name('stamp_sub');
        Route::get('orders/edit/{id}', 'edit')->name('orderedit');
        Route::put('orders/update/', 'update')->name('orderupdate');
        Route::get('orders/delete/{id}', 'delete')->name('order_delete');
        Route::post('orders/check', 'checks')->name('orderchecks');
        //search
        Route::match(['get', 'post'],'orders/search', 'orderssearch')->name('orderssearch');
        //print
        Route::get('orders/print/{id}','print')->name('print');
        Route::get('orders/delegates','orders_delegate')->name('orders.delegates');
        Route::get('orders/delegates/search/{id}','orders_delegate_search')->name('orders.delegates.search');
    });
    Route::controller(orderstate::class)->group(function () {
        // order states
        Route::get('orders/orderstate/add', 'addorderstate')->name('addorderstate');
        Route::post('orders/orderstate/store', 'storeorderstate')->name('storeorderstate');
        Route::get('orders/orderstate', 'listorderstate')->name('listorderstate');
        Route::get('orders/orderstate/edit/{id}', 'editorderstate')->name('editorderstate');
        Route::put('orders/orderstate/update', 'updateorderstate')->name('updateorderstate');
        Route::delete('orders/orderstate/delete/{id}', 'deleteorderstate')->name('deleteorderstate');
    });
    Route::controller(causereturn::class)->group(function () {
        //causes return
        Route::get('orders/causesreturn/add', 'addcausereturn')->name('addcausereturn');
        Route::post('orders/causesreturn/store', 'storecausereturn')->name('storecausereturn');
        Route::get('orders/causesreturn', 'listcausesreturn')->name('listcausesreturn');
        Route::get('orders/causesreturn/edit/{id}', 'editcausereturn')->name('editcausereturn');
        Route::put('orders/causesreturn/update', 'updatecausereturn')->name('updatecausereturn');
        Route::delete('orders/causesreturn/delete/{id}', 'deletecausereturn')->name('deletecausereturn');
    });
    Route::controller(causedelay::class)->group(function (){
        Route::get('orders/causesdelay/add','addcause')->name('addcausedelay');
        Route::post('orders/causesdelay/store','storecause')->name('storecausedelay');
        Route::get('orders/causesdelay/all','listcauses')->name('listcausesdelay');
        Route::get('orders/causesdelay/edit/{id}','editcause')->name('editcausedelay');
        Route::put('orders/causesdelay/update','updatecause')->name('updatecausedelay');
        Route::delete('orders/causesdelay/delete/{id}','deletecause')->name('deletecausedelay');
    });
    //expenses
    Route::controller(salaries::class)->group(function () {
        Route::get('salaries/', 'salaries')->name('salaries');
        Route::get('salaries/edit/{id}', 'edit')->name('editsalary');
        Route::put('salaries/update', 'update')->name('updatesalary');
    });
    Route::controller(discounts::class)->group(function () {
        Route::get('discounts/', 'discounts')->name('discounts');
        Route::get('discounts/add', 'discounts_add')->name('discounts_add');
        Route::post('discounts/store', 'discounts_store')->name('discounts_store');
    });
    Route::controller(agents::class)->group(function () {
        Route::get('supplies/agents/new', 'supplies_newd')->name('supplies_newd');
        Route::get('supplies/agents/store/{id}', 'supplies_stored')->name('supplies_stored');
        Route::post('supplies/agents/supply', 'supplies_supply')->name('supplies_supply');
        Route::get('supplies/agents/supply/h/{agentid}', 'h_supplies')->name('agent_h_supplies');
        Route::get('supplies/agents/hold', 'hold_supplies')->name('hold_supplies');
        Route::post('supplies/agents/confirm', 'confirm_supplies')->name('confirm_supplies');
        Route::get('supplies/agents/{id}','sheet')->name('supply_agent_sheet');
    });
    Route::controller(SuplliesCompanies::class)->group(function () {
        Route::get('supplies/companies/supply', 'companies_supplies')->name('companies_supplies');
        Route::get('supplies/companies/store/{id}', 'companies_new')->name('companies_new');
        Route::post('supplies/companies/supply', 'companies_supply')->name('companies_supply');
        Route::get('supplies/companies/supply/h/{id}', 'h_csupplies')->name('h_csupplies');
        Route::get('supplies/companies/filter','filter')->name('companies.filter');
        Route::post('supplies/companies/filter/store','filter_ajax')->name('companies.filter.ajax');
        Route::get('supplies/companies/{id}','sheet')->name('supply_company_sheet');
    });
    Route::controller(expenses::class)->group(function () {
        Route::get('expenses/', 'expenses')->name('expenses');
        Route::get('expenses/add', 'expenses_add')->name('expenses_add');
        Route::post('expenses/store', 'expenses_store')->name('expenses_store');
        Route::get('supplies/profitsout', 'profits_out')->name('profits_out');
        Route::post('supplies/profitsout/store', 'profits_out_store')->name('profits_out_store');
    });
    Route::controller(persons_accounts_financial::class)->group(function () {
        Route::get('pesronsfin/add', 'add')->name('add_personfin');
        Route::post('pesronsfin/store', 'store')->name('store_personfin');
        Route::get('pesronsfin', 'index')->name('pesronsfin');
        Route::get('pesronsfin/edit/{id}', 'edit')->name('edit_personfin');
        Route::post('pesronsfin/update', 'update')->name('update_personfin');
        Route::delete('pesronsfin/destroy/{id}', 'delete')->name('destroy_personfin');
    });
    Route::controller(financiace_accountants::class)->group(function () {
        Route::get('finances/add', 'add')->name('add_finance');
        Route::post('finances/store', 'store')->name('store_finance');
        Route::get('finances/person/{id}', 'person_acontant')->name('person_acontant');
        Route::get('finances/hold', 'accountants_hold')->name('accountants_hold');
        Route::post('finances/hold/store', 'accountants_hold_store')->name('accountants_hold_store');
    });
    Route::controller(history::class)->group(function () {
        Route::get('history/orders', 'orders_history')->name('orders_history');
        Route::get('history/ajax','history_ajax')->name('history_ajax');
        Route::get('history/orders/add','orders_added')->name('orders_added');
        Route::get('history/orders/delete','orders_deleted')->name('orders_deleted');
        Route::get('history/arcieve', 'history_arcieve')->name('history_arcieve');
        Route::get('history/arcieve/ajax', 'archieve_ajax')->name('archieve_ajax');
        Route::get('history/print','print_history')->name('print_history');
        Route::get('history/print/{id}','print_historyc')->name('print_historyc');
        Route::get('history/restore/{id}','restore')->name('restore');
    });
    Route::controller(firebasecrud::class)->group(function () {
        Route::get('/cretefb/{tt}', 'create');
        Route::get('fb', 'index');
    });
});
Route::get('lang/set/{lang}', [LanguageController::class, 'set'])->name('language.set');
Route::get('/generate-pdf', [PdfController::class,'generatePdf']);
