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

use Illuminate\Support\Facades\Auth;

Auth::routes();
Route::middleware('auth')->group(function () {

    Route::get('/', 'HomeController@index');
    Route::get('dashboard', 'HomeController@index');
    Route::get('chart', 'HomeController@chart')->name('chart.chart');
    Route::post('Notifikasi', 'HomeController@Notify');
    Route::post('data_card', 'HomeController@data_card');
    Route::post('detail_info', 'NotificationController@DetailInfo');
    Route::post('ajax_detailinfo', 'NotificationController@ajax_detailinfo');
    Route::post('countpublic', 'NotificationController@countpublic');
    Route::post('notif_settlement', 'HomeController@getNotif');

    Route::prefix('ajax')->group(function () {
        Route::resource('ui_buttons', 'Menu\ButtonController');
    });

    // setting route, put all setting under this comment

    Route::prefix('setting')->group(function () {
        Route::post('get_menu', 'Menu\MenuController@ajax_data');
        Route::resource('menu', 'Menu\MenuController');
        Route::post('get_users', 'Role\UserController@ajax_data');
        Route::resource('users', 'Role\UserController');
        Route::resource('terminal', 'Setting\TerminalController');

        //config
        Route::post("get_config", 'Setting\ConfigController@ajax_data');
        Route::post('/', 'Setting\Configcontroller@store');
        Route::get('config/{id}/delete', 'Setting\ConfigController@destroy');

        Route::resource('config', 'Setting\ConfigController');
    });

    Route::prefix('img')->group(function () {

        Route::get('image', 'UploadImage@getImg');
        Route::post('image', 'UploadImage@PostImg');
        Route::resource('image', 'UploadImage');
    });

    Route::prefix('hrm')->group(function () {
        Route::post("get_employee", 'HR\EmployeeController@ajax_data');
        Route::post('/', 'HR\EmployeeController@store');
        Route::get('employee/{id}/show', 'HR\EmployeeController@show');
        Route::get('employee/{id}/delete', 'HR\EmployeeController@destroy');
        Route::post('employee/add_asset', 'HR\EmployeeController@office_assets')->name('office_asset.add');
        Route::post('employee/del_asset', 'HR\EmployeeController@del_assets');
        Route::post('employee/add_dokumen', 'HR\EmployeeController@add_dokumen');
        Route::resource('employee', 'HR\EmployeeController');

        // Absensi
        Route::post('get_location', 'HR\UploadAbsenController@get_location');
        Route::post("get_absensi", 'HR\UploadAbsenController@ajax_data');
        Route::post('saveAbsensi', 'HR\UploadAbsenController@store');
        Route::get('absensi/{id}/delete', 'HR\UploadAbsenController@destroy');
        Route::post('/', 'HR\UploadAbsenController@import')->name('UploadAbsen.import');
        Route::resource('absensi', 'HR\UploadAbsenController');

        // Rekap absensi

        Route::post("rekapabsen/get_rekapabsensi", 'HR\RekapabsenController@ajax_data');
        Route::post("rekapabsen/get_absensi/{id}", 'HR\RekapabsenController@ajax_absen');
        Route::post("rekapabsen/get_timeabsensi", 'HR\RekapabsenController@time_absen');
        Route::get('rekapabsen/get_download', 'HR\RekapabsenController@downloadrekap');
        Route::post('rekapabsen/generate_rekap', 'HR\RekapabsenController@generate_rekap');
        Route::resource('rekapabsen', 'HR\RekapabsenController');

        // Travel
        Route::post("request/get_travel", 'HR\Req_TravelController@ajax_data');
        Route::get("request/travel/{travel}/show", 'HR\Req_TravelController@show');
        Route::get('request/travel/{travel}/delete', 'HR\Req_TravelController@destroy');
        Route::post('hrm/request/get_division', 'HR\Req_TravelController@getDivision');
        Route::get('request/travel/approve/{all}', 'HR\Req_TravelController@approve_travel')->where('all', '.*');
        Route::get('request/travel/reject/{all}', 'HR\Req_TravelController@reject')->where('all', '.*');
        Route::resource('request/travel', 'HR\Req_TravelController');

        //Overtime
        Route::post("request/get_overtime", 'HR\Req_OvertimeController@ajax_data');
        Route::get("request/overtime/{overtime}/show", 'HR\Req_OvertimeController@show');
        Route::get('request/overtime/{overtime}/delete', 'HR\Req_OvertimeController@destroy');
        Route::get('request/overtime/reject/{all}', 'HR\Req_LeaveController@reject')->where('all', '.*');
        Route::get('request/overtime/approve/{all}', 'HR\Req_OvertimeController@approve')->where('all', '.*');
        Route::resource('request/overtime', 'HR\Req_OvertimeController');

        //Leave
        Route::get("request/leave/{leave}/delete", 'HR\Req_LeaveController@destroy');
        Route::get("request/leave/{leave}/show", 'HR\Req_LeaveController@show');
        Route::get('request/leave/approve/{all}', 'HR\Req_LeaveController@approve')->where('all', '.*');
        Route::get('request/leave/reject/{all}', 'HR\Req_LeaveController@reject')->where('all', '.*');
        Route::post("request/get_special",'HR\Req_LeaveController@SpecialForm');
        Route::post("request/get_permission",'HR\Req_LeaveController@PermissionForm');
        Route::post("request/get_late",'HR\Req_LeaveController@LateForm');
        Route::post("request/get_annual",'HR\Req_LeaveController@AnnualForm');
        Route::post('request/value_chance','HR\Req_LeaveController@value_chance');
        Route::post("request/get_leave", 'HR\Req_LeaveController@ajax_data');
        Route::post('/', 'HR\Req_LeaveController@store');
        Route::resource('request/leave', 'HR\Req_LeaveController');

        //Payroll
        Route::post("payroll/get_salary", 'HR\SalaryController@ajax_data');
        Route::post("payroll/my_salary", 'HR\SalaryController@my_data');
        Route::post("payroll/download", 'HR\SalaryController@download');
        Route::post("salary/checkdetail", 'HR\SalaryController@checkdetail');
        Route::get("salary/masterkeyconfirm", 'HR\SalaryController@masterkeyconfirm');
        Route::post("salary/personaloken", 'HR\SalaryController@personaloken');
        Route::post("salary/storepersonaloken", 'HR\SalaryController@storepersonaloken');
        Route::post("salary/checktoken", 'HR\SalaryController@check_token');
        Route::post("salary/storekey", 'HR\SalaryController@storekey');
        Route::resource('payroll', 'HR\SalaryController');


        Route::post("payroll/checkmonth", 'HR\SalaryDetailController@checkmonth');
        Route::post("payroll/get_monthly", 'HR\SalaryDetailController@ajax_data');
        Route::post('payroll/filterData/{all}', 'HR\SalaryDetailController@filter_data')->where('all', '.*');

        Route::get('payrolldetail/{id}/deduction', 'HR\SalaryDetailController@deduction');
        Route::resource('payrolldetail', 'HR\SalaryDetailController');

        Route::post("reportpayroll/viewreport", 'HR\DownloadPayrollController@countpayroll');
        Route::post("reportpayroll/downloadreport", 'HR\DownloadPayrollController@generatereport');
        Route::resource('reportpayroll', 'HR\DownloadPayrollController');
        
        //public holidays
        Route::post("get_Listmass",'HR\MassLeaveController@ajax_data');
        Route::post('/', 'HR\MassLeaveController@store')->name('saveMass.store');
        Route::post('mass_leave/get_more', 'HR\MassLeaveController@getMoreRow');
        Route::put('mass_leave/update', 'HR\MassLeaveController@update')->name('saveMass.update');
        Route::get("mass_leave/{id}/show", 'HR\MassLeaveController@show');
        Route::resource('mass_leave', 'HR\MassLeaveController');
    });


    Route::prefix('role')->group(function () {
        Route::post('get_accessmenu', 'Role\RoleMenuController@ajax_data');
        Route::resource('accessmenu', 'Role\RoleMenuController');

        Route::post("get_cabang", 'Role\CabangController@ajax_data');
        Route::post('/', 'Role\CabangController@store');
        Route::get('cabang/{id}/delete', 'Role\CabangController@destroy');

        Route::post("get_division", 'Role\DivisionController@ajax_data');
        Route::post('/', 'Role\DivisionController@store');
        Route::get('division/{id}/delete', 'Role\DivisionController@destroy');

        Route::resource('cabang', 'Role\CabangController');
        Route::resource('division', 'Role\DivisionController');
    });

    // end setting route

    // route erp system put all new route under this comment


    Route::prefix('sales')->group(function () {
        Route::get('find_so', 'Sales\QuotationController@find_so');
        Route::post('getproduct_so', 'Sales\QuotationController@getproduct_so');
        Route::post('getdetailproduct_so', 'Sales\QuotationController@getdetailproduct_so');
        Route::post('get_customer', 'Sales\CustomerController@ajax_data');
        Route::post('new_customer', 'Sales\CustomerController@modal_call');
        Route::get('find_customer', 'Sales\CustomerController@find_customer');
        Route::post('new_cabang', 'Sales\CustomerController@modal_cabang');
        Route::post('new_vendor', 'Sales\CustomerController@modal_vendor');
        Route::get('find_customer', 'Sales\CustomerController@find_customer');
        Route::get('find_cabang', 'Sales\CustomerController@find_cabang');
        Route::post('customer/storepic', 'Sales\CustomerController@storepic');
        Route::post('customer/deletepic', 'Sales\CustomerController@deletepic');
        Route::resource('customer', 'Sales\CustomerController');


        Route::get('quotation/create/{id}', 'Sales\QuotationController@create');
        Route::post('quotation/product_clone', 'Sales\QuotationController@product_clone');
        Route::post('quotation/product_clone_change', 'Sales\QuotationController@product_clone_change');
        Route::post('quotation/save_addproduct', 'Sales\QuotationController@save_addproduct');
        Route::post('quotation/save_changeproduct', 'Sales\QuotationController@save_changeproduct');
        Route::post('quotation/delete_changeproduct', 'Sales\QuotationController@delete_changeproduct');
        Route::post('quotation/product_edit', 'Sales\QuotationController@edit_product');
        Route::post('quotation/product_save', 'Sales\QuotationController@save_product');
        Route::post('quotation/status_edit', 'Sales\QuotationController@edit_status');
        Route::post('quotation/status_save', 'Sales\QuotationController@save_status');
        Route::post('quotation/product_detail', 'Sales\QuotationController@detail_product');
        Route::post('quotation/activity', 'Sales\QuotationController@activity');
        Route::post('quotation/activity_new', 'Sales\QuotationController@activity_new');
        Route::post('quotation/activity_save', 'Sales\QuotationController@activity_save');
        Route::post('quotation/document', 'Sales\QuotationController@document');
        Route::post('quotation/document_save', 'Sales\QuotationController@document_save');
        Route::post('quotation/document_upload', 'Sales\QuotationController@document_upload');
        Route::post('quotation/saveFile', 'Sales\QuotationController@saveFile');
        Route::post('quotation/classic_upload', 'Sales\QuotationController@classic_upload');
        Route::post('quotation/saveFileClassic', 'Sales\QuotationController@saveFileClassic');        
        Route::post('quotation/approval', 'Sales\QuotationController@approve');
        Route::post('quotation/approval_save', 'Sales\QuotationController@approve_save');
        Route::post('quotation/ajukandraftbeli', 'Sales\QuotationController@draft_beli');
        Route::post('quotation/ajukanbeli', 'Sales\QuotationController@ajukan_beli');
        Route::post('quotation/kirimpo', 'Sales\QuotationController@kirim_po');
        Route::post('quotation/exec_kirim_po', 'Sales\QuotationController@exec_kirim_po');
        Route::post('quotation/show_dobalikan', 'Sales\QuotationController@show_dobalikan');

        Route::post('quotation/siapkirim', 'Sales\QuotationController@SiapKirim');
        Route::post('quotation/pakaistock', 'Sales\QuotationController@pakaistock');

        Route::post('quotation/split_po', 'Sales\QuotationController@split_po');
        Route::post('quotation/exec_split_po', 'Sales\QuotationController@exec_split_po');
        Route::post('quotation/delete_split_po', 'Sales\QuotationController@delete_split_po');

        Route::post('quotation/filter_home', 'HomeController@filter_data');
        Route::post('get_quotation', 'Sales\QuotationController@ajax_data');
        Route::get('quotation/ex_quo/{all}', 'Sales\QuotationController@ex_quo')->where('all', '.*');
        Route::post('quotation/filterData/{all}', 'Sales\QuotationController@filter_data')->where('all', '.*');
        Route::post('address_add', 'Sales\QuotationController@address_add')->name('address_add.add');
        Route::post('address_pic', 'Sales\QuotationController@address_pic')->name('address_pic.add');
        Route::post('address_add/save', 'Sales\QuotationController@address_add_Save');
        Route::put('address_add/update', 'Sales\QuotationController@address_add_Update')->name('address_add.update');
        Route::get('remove_addwo/{id}/{idquo}/{type}', 'Sales\QuotationController@remove_addwo')->name('remove_wo.remove');
        Route::post('quotation/filterData/{all}', 'Sales\QuotationController@filter_data')->where('all', '.*');
        Route::post('quotation/edit_invoice', 'Sales\QuotationController@edit_invoice');
        Route::post('editinvoices', 'Sales\QuotationController@editinvoices');
        Route::post('confirm_payments', 'Sales\QuotationController@confirm_payments');
        Route::resource('quotation', 'Sales\QuotationController');

        Route::post('get_vendor', 'Sales\VendorController@ajax_data');
        Route::post('new_vendor', 'Sales\VendorController@modal_call');
        Route::post('vendor/deletepic', 'Sales\VendorController@deletepic');
        Route::post('vendor_detail', 'Sales\VendorController@modal_detail');
        Route::get('find_vendor', 'Sales\VendorController@find_vendor');
        Route::post('vendor_store', 'Sales\VendorController@store');
        Route::post('vendor/storepic', 'Sales\VendorController@storepic');
        Route::post('vendor/getListPo', 'Sales\VendorController@listPo');
        Route::post('vendor/ajax_listPO', 'Sales\VendorController@ajax_listPo');
        Route::get('vendor/export/{all}', 'Sales\VendorController@export_normal')->where('all', '.*');
        Route::resource('vendor', 'Sales\VendorController');

        Route::get('download/invoice_costum/{id}/{div}', 'Sales\InvoiceController@invoice_costum');
        Route::get('download/proforma_invoice/{id}', 'Sales\InvoiceController@proforma_invoice');
        Route::post('download/invoice_tab', 'Sales\InvoiceController@invoice_tab');
        Route::post('download/invoice_edit', 'Sales\InvoiceController@invoice_edit');
        Route::post('download/invoice_update', 'Sales\InvoiceController@invoice_update');
        Route::post('download/invoice_cetak', 'Sales\InvoiceController@invoice_cetak');
        Route::post('download/invoice_delete', 'Sales\InvoiceController@invoice_delete');
        Route::post('download/invoice_delete_exec', 'Sales\InvoiceController@invoice_delete_exec');
        Route::post('download/generate_invoice', 'Sales\InvoiceController@generate_invoice');
        Route::post('download/proforma', 'Sales\InvoiceController@proforma');
        Route::get('download/document/{id}/{div}', 'Sales\ExportController@generate');
        Route::post('download/document/additional_note', 'Sales\ExportController@additional_note');
        Route::post('download/document/save_note', 'Sales\ExportController@save_note');
        Route::post('download/so', 'Sales\ExportController@generate_salesorder');
        Route::get('downloadmigrate/document/{id}/{div}', 'Sales\ExportController@generateMigrate');
        Route::post('downloadmigrate/so', 'Sales\ExportController@generate_salesorder');
        Route::resource('download', 'Sales\ExportController');

        Route::post('special/kirimsq', 'Sales\SalesController@kirim_sq');
        Route::post('special/defaulttext', 'Sales\SalesController@defaulttext');
        Route::post('special/exec_kirim_sq', 'Sales\SalesController@exec_kirim_sq');
        Route::resource('special', 'Sales\SalesController');


        Route::post('getvisitplan', 'Sales\VisitPlanController@list_visit');
        Route::post('form', 'Sales\VisitPlanController@vp_create');
        Route::post('edit_form', 'Sales\VisitPlanController@vp_edit');
        Route::post('visitplan/show_form', 'Sales\VisitPlanController@vp_show');
        Route::post('type_form', 'Sales\VisitPlanController@type_form');
        Route::get('delete_form/{id}', 'Sales\VisitPlanController@delete');
        Route::post("saveUpdate", 'Sales\VisitPlanController@saveUpdate')->name('visitplan.saveUpdate');
        Route::post("save_advice", 'Sales\VisitPlanController@save_advice');
        Route::get('find_lokasi', 'Sales\VisitPlanController@find_lokasi');
        Route::get('visitplan/download', 'Sales\VisitPlanController@page_download');
        Route::post("visit_list", 'Sales\VisitPlanController@ListVisit');
        Route::post('filter_visit/{all}', 'Sales\VisitPlanController@filter_visit')->where('all', '.*');
        Route::get('ex_visit/{all}', 'Sales\VisitPlanController@ex_visit')->where('all', '.*');
        Route::resource('visitplan', 'Sales\VisitPlanController');

    });

    Route::prefix('purchasing')->group(function () {
        Route::post('quotation/kirimpo', 'Purchasing\PurchasingController@kirim_po');
        Route::post('quotation/defaulttext', 'Purchasing\PurchasingController@defaulttext');
        Route::post('quotation/exec_kirim_po', 'Purchasing\PurchasingController@exec_kirim_po');
        Route::post('order/ganti_alamat', 'Purchasing\PurchasingController@ganti_alamat');
        Route::post('order/pay_vendor', 'Purchasing\PurchasingController@pay_vendor');
        Route::post('order/savePay_vendor', 'Purchasing\PurchasingController@savePay_vendor');
        Route::post('order/Editpay_vendor', 'Purchasing\PurchasingController@Editpay_vendor');
        Route::post('order/saveEditPay_vendor', 'Purchasing\PurchasingController@saveEditPay_vendor');
        Route::post('order/Hapuspay_vendor', 'Purchasing\PurchasingController@Hapuspay_vendor');
        Route::post('order/save_alamat', 'Purchasing\PurchasingController@save_alamat');
        Route::post('order/delete_alamat', 'Purchasing\PurchasingController@delete_alamat');
        Route::put('order/update_alamat', 'Purchasing\PurchasingController@update_alamat');
        Route::post('order/save_date', 'Purchasing\PurchasingController@save_date');

        Route::post('po/gantivendor', 'Purchasing\PurchasingController@gantivendor');
        Route::post('po/save_gantivendor', 'Purchasing\PurchasingController@save_gantivendor');
        Route::post('po/cancel', 'Purchasing\PurchasingController@cancel');
        Route::post('po/product_clone', 'Purchasing\PurchasingController@product_clone');
        Route::post('po/product_newclone', 'Purchasing\PurchasingController@product_newclone');
        Route::post('po/attachment_mail', 'Purchasing\PurchasingController@attachment');

        Route::post('order/filterData/{all}', 'Purchasing\PurchasingController@filter')->where('all', '.*');
        Route::get('order/export/{all}', 'Purchasing\PurchasingController@ex_quo')->where('all', '.*');
        Route::post('order/approval', 'Purchasing\PurchasingController@approve');
        Route::post('order/approval_save', 'Purchasing\PurchasingController@approve_save');
        Route::post('order/create_payment', 'Purchasing\PurchasingController@create_payment');
        Route::post('order/show_payment', 'Purchasing\PurchasingController@show_payment');
        Route::post('order/changeisppn', 'Purchasing\PurchasingController@changeisppn');
        Route::post('order/addnote', 'Purchasing\ExportController@additional_note');
        Route::post('order/save_note', 'Purchasing\ExportController@save_note');
        Route::post('get_purchase', 'Purchasing\PurchasingController@ajax_data');
        Route::post('order/history', 'Purchasing\PurchasingController@all_history');
        Route::resource('order', 'Purchasing\PurchasingController');


        Route::post('download/draftpo', 'Purchasing\ExportController@generate_draftpo');
        Route::post('download/finalpo', 'Purchasing\ExportController@generate_finalpo');
    });


    Route::prefix('product')->group(function () {
        Route::post("content/get_brand", 'Product\BrandController@ajax_data');
        Route::post('/', 'Product\BrandController@store');
        Route::get('content/brand/{id}/delete', 'Product\BrandController@destroy');

        Route::post("content/get_category", 'Product\CategoryController@ajax_data');
        Route::post('/', 'Product\CategoryController@store');
        Route::get('content/category/{id}/delete', 'Product\CategoryController@destroy');

        Route::resource('content/brand', 'Product\BrandController');
        Route::resource('content/category', 'Product\CategoryController');


        Route::get('get_product', 'Product\ProductController@find_product');
        Route::post('get_detail', 'Product\ProductController@find_detail');
        Route::post('request_other', 'Product\ContentController@modal_call');
        Route::post('request', 'Product\ContentController@prorequest');

        //Product_Live
        Route::post("get_live", 'Product\LiveController@ajax_data');
        Route::get('live/get_export/{type}/{export}', 'Product\LiveController@export')->name('export_excel.excel');
        Route::get('live/ex_pro/{all}', 'Product\LiveController@ex_pro')->where('all', '.*');
        Route::post('live/filterData/{all}', 'Product\LiveController@filter')->where('all', '.*');
        Route::get("live/{live}/show", 'Product\LiveController@show');
        Route::post('live', 'Product\LiveController@index');
        Route::resource('live', 'Product\LiveController');

        //List Content
        Route::post("content/get_listcontent", 'Product\ListContentController@ajax_data');
        Route::post('/', 'Product\ListContentController@store');
        Route::post('import_zip', 'Product\ListContentController@import_zip')->name('importzip.import');
        Route::get('content/listcontent/get_docformat', 'Product\ListContentController@export_formats');
        Route::get('content/listcontent/{listcontent}/delete', 'Product\ListContentController@destroy');
        Route::post('content/listcontent/imports', 'Product\ListContentController@import')->name('listcontent.import');
        Route::post('content/listcontent/saveImport', 'Product\ListContentController@saveImport')->name('saveImport.save');
        Route::get("content/listcontent/{listcontent}/show", 'Product\ListContentController@show');
        Route::get('content/listcontent/{listcontent}/apply', 'Product\ListContentController@apply')->name('listcontent.apply');
        Route::get('content/listcontent/{listcontent}/live', 'Product\ListContentController@live')->name('listcontent.live');
        Route::get('new_content/{listcontent}', 'Product\ListContentController@new_content');

        Route::resource('content/listcontent', 'Product\ListContentController');

        //Waiting
        Route::post("get_waiting", 'Product\PendingApprovalController@ajax_data');
        Route::get('approval/{approval}/approve', 'Product\PendingApprovalController@approve')->name('approval.approve');
        Route::get('approval/{approval}/reject', 'Product\PendingApprovalController@reject')->name('approval.reject');
        Route::get("approval/{approval}/show", 'Product\PendingApprovalController@show');
        Route::get("approval/button", "Product\PendingApprovalController@Button");
        Route::get('approval/{approval}/delete', 'Product\PendingApprovalController@destroy');
        Route::resource('approval', 'Product\PendingApprovalController');
    });


    Route::prefix('warehouse')->group(function () {
        Route::post("get_warehouse", 'Warehouse\WarehouseInController@ajax_data');
        Route::resource('inbound', 'Warehouse\WarehouseInController');

        Route::post('kirim/ganti_alamat', 'Warehouse\WarehouseOutController@ganti_alamat');
        Route::post('kirim/save_alamat', 'Warehouse\WarehouseOutController@save_alamat');
        Route::post('kirim/delete_alamat', 'Warehouse\WarehouseOutController@delete_alamat');
        Route::put('kirim/update_alamat', 'Warehouse\WarehouseOutController@update_alamat');
        Route::post('kirim/cetak', 'Warehouse\WarehouseOutController@CetakDO');
        Route::post('kirim/saveresi', 'Warehouse\WarehouseOutController@save_resi');
        Route::post('kirim/finish', 'Warehouse\WarehouseOutController@finish');
        Route::post('kirim/savefinish', 'Warehouse\WarehouseOutController@save_finish');
        Route::post("get_outbound", 'Warehouse\WarehouseOutController@ajax_data');
        Route::post("outbound/showDO_details", 'Warehouse\WarehouseOutController@showDO_detail');
        Route::post("outbound/DO_cetak", 'Warehouse\WarehouseOutController@DO_cetak');
        Route::post("outbound/update_pengiriman", 'Warehouse\WarehouseOutController@update_pengiriman');
        Route::post("outbound/CetakDO_Update", 'Warehouse\WarehouseOutController@CetakDO_Update');
        Route::post("outbound/kirim_cetakDO", 'Warehouse\WarehouseOutController@kirim_cetakDO');
        Route::post('outbound/store', 'Warehouse\WarehouseOutController@store');
        Route::post('outbound/view_do', 'Warehouse\WarehouseOutController@view_do');
        Route::resource('outbound', 'Warehouse\WarehouseOutController');

        Route::post('get_inventory', 'Inventory\InventoryController@ajax_data');
        Route::post('inventory/cetak_pinjam', 'Inventory\InventoryController@cetak_pinjam');
        Route::post('inventory/editpinjam_stock', 'Inventory\InventoryController@editpinjam_stock');
        Route::post('inventory/updatepinjam_stock', 'Inventory\InventoryController@update_pinjam')->name('pinjam.update');
        Route::post('inventory/addpinjam_stock', 'Inventory\InventoryController@pinjam_stock');
        Route::post('inventory/storepinjam_stock', 'Inventory\InventoryController@store_pinjam')->name('pinjam.store');
        Route::resource('inventory', 'Inventory\InventoryController');


        Route::post('show_inbound', 'Warehouse\update\WarehouseInboundController@show');
        Route::post('get_warehouse', 'Warehouse\update\WarehouseInboundController@ajax_data');
        Route::post('warehouse_inbound/savesn', 'Warehouse\Scanner\WarehousescanController@storesn_in');
        Route::post('warehouse_inbound/history_inbound', 'Warehouse\update\WarehouseInboundController@history');
        Route::post('warehouse_inbound/all_history', 'Warehouse\update\WarehouseInboundController@all_history');
        Route::post('warehouse_inbound/add_note', 'Warehouse\update\WarehouseInboundController@add_note');
        Route::post('warehouse_inbound/save_add_note', 'Warehouse\update\WarehouseInboundController@save_addnote');
        Route::get('warehouse_inbound/ex_quo/{all}', 'Warehouse\update\WarehouseInboundController@ex_quo')->where('all', '.*');
        Route::post('warehouse_inbound/filterData/{all}', 'Warehouse\update\WarehouseInboundController@filter_data')->where('all', '.*');
        Route::post('/', 'Warehouse\update\WarehouseInboundController@index');
        Route::resource('warehouse_inbound', 'Warehouse\update\WarehouseInboundController');


        Route::get('listsn', 'Warehouse\Scanner\WarehousescanController@listsn');
        Route::post('listsn/get_data', 'Warehouse\Scanner\WarehousescanController@ajax_data');
        Route::post('warehouse_outbound/scan', 'Warehouse\Scanner\WarehousescanController@show');
        Route::post('warehouse_outbound/downloadsn', 'Warehouse\Scanner\WarehousescanController@downloadsn');
        Route::post('warehouse_outbound/savesn', 'Warehouse\Scanner\WarehousescanController@store');
        Route::post('warehouse_outbound/deletesn', 'Warehouse\Scanner\WarehousescanController@destroy');
        Route::post('warehouse_outbound/upload_sn', 'Warehouse\Scanner\WarehousescanController@upload_sn')->name('wh.upload_sn');
        Route::post('warehouse_outbound/saveUpload', 'Warehouse\Scanner\WarehousescanController@saveUpload')->name('wh.saveUpload');
        Route::get('warehouse_outbound/download_excel/{all}', 'Warehouse\Scanner\WarehousescanController@download_excel')->where('all', '.*');
        Route::post('warehouse_outbound/searchSN', 'Warehouse\Scanner\WarehousescanController@searchSN');
        Route::post('warehouse_outbound/scan_inbound', 'Warehouse\Scanner\WarehousescanController@show_inbound');
        Route::get('warehouse_outbound/download_excel_inbound/{all}', 'Warehouse\Scanner\WarehousescanController@downloadExcel_inbound')->where('all', '.*');
        Route::post('warehouse_outbound/upload_sn_inbound', 'Warehouse\Scanner\WarehousescanController@uploadSN_inbound');
        Route::post('warehouse_outbound/saveUpload_Inbound', 'Warehouse\Scanner\WarehousescanController@saveUpload_Inbound')->name('wh_in.saveUpload');


        Route::post('document/upload', 'Warehouse\update\WarehouseOutboundController@upload_modal');
        Route::post('document/doupload', 'Warehouse\update\WarehouseOutboundController@upload_process');

        Route::post('get_warehouse_out', 'Warehouse\update\WarehouseOutboundController@ajax_data');
        Route::post('/', 'Warehouse\update\WarehouseOutboundController@index');
        Route::post('show_outbound', 'Warehouse\update\WarehouseOutboundController@show');
        Route::post('warehouse_outbound/store', 'Warehouse\update\WarehouseOutboundController@store');
        Route::post('warehouse_outbound/store_rekap', 'Warehouse\update\WarehouseOutboundController@store_rekap');
        Route::post('warehouse_outbound/view_do', 'Warehouse\update\WarehouseOutboundController@view_do');
        Route::post('kirim_outbound/ganti_alamat', 'Warehouse\update\WarehouseOutboundController@ganti_alamat');
        Route::post('kirim_outbound/save_alamat', 'Warehouse\update\WarehouseOutboundController@save_alamat');
        Route::post('kirim_outbound/delete_alamat', 'Warehouse\update\WarehouseOutboundController@delete_alamat');
        Route::put('kirim_outbound/update_alamat', 'Warehouse\update\WarehouseOutboundController@update_alamat');
        Route::post("warehouse_outbound/cetakDO", 'Warehouse\update\WarehouseOutboundController@cetakDO');
        Route::post("warehouse_outbound/showDO_details", 'Warehouse\update\WarehouseOutboundController@showDO_detail');
        Route::post("warehouse_outbound/DO_cetak", 'Warehouse\update\WarehouseOutboundController@DO_cetak');
        Route::post("warehouse_outbound/update_pengiriman", 'Warehouse\update\WarehouseOutboundController@update_pengiriman');
        Route::post('warehouse_outbound/kirim/finish', 'Warehouse\update\WarehouseOutboundController@finish');
        Route::post('warehouse_outbound/kirim/savefinish', 'Warehouse\update\WarehouseOutboundController@save_finish');
        Route::post('warehouse_outbound/kirim/upload_resi', 'Warehouse\update\WarehouseOutboundController@upload_resi');
        Route::post('warehouse_outbound/kirim/saveUploadResi', 'Warehouse\update\WarehouseOutboundController@saveUploadResi');
        Route::post("warehouse_outbound/update_pengiriman", 'Warehouse\update\WarehouseOutboundController@update_pengiriman');
        Route::get('warehouse_outbound/ex_quo/{all}', 'Warehouse\update\WarehouseOutboundController@ex_quo')->where('all', '.*');
        Route::post('warehouse_outbound/filterData/{all}', 'Warehouse\update\WarehouseOutboundController@filter_data')->where('all', '.*');
        Route::post('warehouse_outbound/kirim/saveresi', 'Warehouse\update\WarehouseOutboundController@save_resi');
        Route::post("warehouse_outbound/DO_delete", 'Warehouse\update\WarehouseOutboundController@DO_delete');

        Route::post('warehouse_outbound/add_row', 'Warehouse\update\WarehouseOutboundController@add_row');
        Route::post('warehouse_outbound/remove_row', 'Warehouse\update\WarehouseOutboundController@remove_row');
        Route::post('warehouse_outbound/add_detail_barang', 'Warehouse\update\WarehouseOutboundController@add_detail_barang');
        Route::post('warehouse_outbound/editbarang_pengiriman', 'Warehouse\update\WarehouseOutboundController@editbarang_pengiriman');
        Route::post('warehouse_outbound/history_outbound', 'Warehouse\update\WarehouseOutboundController@history_outbound');
        Route::post('warehouse_outbound/add_notes', 'Warehouse\update\WarehouseOutboundController@add_notes');
        Route::post('warehouse_outbound/save_addnote', 'Warehouse\update\WarehouseOutboundController@save_addnote');
        Route::resource('warehouse_outbound', 'Warehouse\update\WarehouseOutboundController');
    });

    Route::prefix('distribution')->group(function () {
        Route::get('find_shipping', 'Distribution\ShippingController@find_shipping');
        Route::post('new_shipping', 'Distribution\ShippingController@modal_call');
        Route::post("get_shipping", 'Distribution\ShippingController@ajax_data');
        Route::post('shipping_store', 'Distribution\ShippingController@store');
        Route::post('/', 'Distribution\ShippingController@store');
        Route::get('shipping/{shipping}/delete', 'Distribution\ShippingController@destroy');
        Route::post('shipping/storepic', 'Distribution\ShippingController@storepic');
        Route::resource('shipping', 'Distribution\ShippingController');
    });

    Route::prefix('finance')->group(function () {
        Route::post("get_invoicing", 'Finance\InvoicingController@ajax_data');
        Route::post("invoice/next_payment", 'Finance\InvoicingController@nextpayment');
        Route::get("invoice/edit_invoice/{id}", 'Finance\InvoicingController@update_invoice');
        Route::post('invoice/removes','Finance\InvoicingController@removes');
        Route::put("invoice/update", 'Finance\InvoicingController@saveUpdate')->name('invoice_up.update');
        Route::get("invoice/{id}/show",'Finance\InvoicingController@show');
        Route::post('invoice/filter_data/{all}','Finance\InvoicingController@filter_data')->where('all', '.*');
        
        Route::post('invoice/get_editnpwp','Finance\InvoicingController@edit_invoice_up');
        Route::put('invoice/saveeditnpwp','Finance\InvoicingController@SaveEditUp')->name('edit_ups.update');
        Route::post('invoice/get_editpayment','Finance\InvoicingController@edit_invoice_mid');
        Route::post('invoice/add_rows','Finance\InvoicingController@add_rows');
        Route::post('invoice/remove_rows_payment','Finance\InvoicingController@remove_rows');
        Route::put('invoice/saveeditpayment','Finance\InvoicingController@SaveEditMid')->name('edit_mids.update');
        Route::post('invoice/get_editpotongan','Finance\InvoicingController@edit_invoice_last');
        Route::post('invoice/plus_editpotongan','Finance\InvoicingController@editplus_invoice_last');
        Route::get('invoice/finish_payment/{id}','Finance\InvoicingController@finish_payment');
        Route::post('invoice/removes_potongan','Finance\InvoicingController@remove_potongan');
        Route::post('invoice/add_forms','Finance\InvoicingController@add_forms');
        Route::put('invoice/saveeditpotongan','Finance\InvoicingController@SaveEditLast')->name('edit_last.update');
        Route::post('invoice/cetak_invoicing', 'Finance\InvoicingController@cetak_invoicing');
        
        Route::post('invoice/get_tambahpayment','Finance\InvoicingController@create_invoice_mid');
        Route::post('invoice/savePayment','Finance\InvoicingController@SaveCreateMid')->name('create_mids.save');
        Route::post('invoice/get_detailpayment','Finance\InvoicingController@detail_invoice_mid');
        Route::post('invoice/get_hapuspayment','Finance\InvoicingController@hapus_invoice_mid');
        Route::resource('invoice', 'Finance\InvoicingController');


        Route::post('cash_advance/calculate', 'Finance\CashAdvanceController@calculate');
        Route::get('cash_advance/{id}/settlement', 'Finance\CashAdvanceController@set_settlement');
        Route::get('cash_advance/{id}/show_settlement', 'Finance\CashAdvanceController@show_settlement');
        Route::get('cash_advance/{id}/print_sets', 'Finance\CashAdvanceController@print_settlement');
        Route::post('cash_advance/details_settlement', 'Finance\CashAdvanceController@showDetailSettlement');
        Route::post('cash_advance/get_value', 'Finance\CashAdvanceController@auto_value');
        Route::post('cash_advance/remove', 'Finance\CashAdvanceController@remove');
        Route::post('cash_advance/addAct', 'Finance\CashAdvanceController@AddAct')->name('add_act.add');
        Route::post("get_cash_adv", 'Finance\CashAdvanceController@ajax_data');
        Route::post('/', 'Finance\CashAdvanceController@store')->name('cash.store');
        Route::get('download/pdf_CashAdv/{cash}', 'Finance\CashAdvanceController@PDF_CashAdv')->name('cash.download');
        Route::get('cash_advance/{cash}/{type}/approve_hrd', 'Finance\CashAdvanceController@approve_hrd');
        Route::get('cash_advance/{cash}/ajukan', 'Finance\CashAdvanceController@ajukan_cash')->name('cash.ajukan');
        Route::get('cash_advance/{cash}/{user}/manage_approve', 'Finance\CashAdvanceController@manage_approve');
        Route::get('cash_advance/{cash}/{user}/manage_reject', 'Finance\CashAdvanceController@manage_reject');
        Route::put('cash_advance/{cash}/finance_app', 'Finance\CashAdvanceController@finance_approve')->name('finance_app.update');
        Route::get('cash_advance/{cash}/finance_btl', 'Finance\CashAdvanceController@finance_reject')->name('finance_btl.update');
        Route::get('cash_advance/{cash}/approve', 'Finance\CashAdvanceController@approve')->name('cash.approve');
        Route::get('cash_advance/{cash}/reject', 'Finance\CashAdvanceController@reject')->name('cash.reject');
        Route::post('cash_advance/complete_cash', 'Finance\CashAdvanceController@completed')->name('cash.complete');
        Route::post('cash_advance/saveComplete', 'Finance\CashAdvanceController@saveComplete')->name('cash.savecomplete');
        Route::get("cash_advance/{cash}/show", 'Finance\CashAdvanceController@show');
        Route::get('cash_advance/{id}/delete', 'Finance\CashAdvanceController@destroy');
        Route::put('cash_advance/update', 'Finance\CashAdvanceController@update')->name('finance.update');
        Route::put("settlement/update", 'Finance\CashAdvanceController@saveSettlement')->name('settlement.updates');
        Route::post('cash_advance/edit_detail', 'Finance\CashAdvanceController@detail_settlement');
        Route::post("cash_advance/savedetail", 'Finance\CashAdvanceController@savedetail_settlement')->name('detail_settlement.save');
        Route::post('cash_advance/add_detailset', 'Finance\CashAdvanceController@add_form');
        Route::post('cash_advance/show_detail', 'Finance\CashAdvanceController@showdetail_settlement');
        Route::post('cash_advance/delete_detailsets', 'Finance\CashAdvanceController@delete_detailsettlement');
        Route::get('cash_advance/{id}/{type}/settlements_approval', 'Finance\CashAdvanceController@settlement_approval');
        Route::post('cash_advance/add_kegiatan', 'Finance\CashAdvanceController@add_kegiatan');
        Route::post('cash_advance/addBlank_kegiatan', 'Finance\CashAdvanceController@addBlank_kegiatan');
        Route::get('cash_advance/{id}/edit_finance','Finance\CashAdvanceController@edit_finance');
        Route::get("cash_advance/{id}/reject_finance", 'Finance\CashAdvanceController@reject_finance');
        Route::resource('cash_advance', 'Finance\CashAdvanceController');



        Route::get('payment_voucher/approve_payment/{all}', 'Finance\Pay_VoucherController@approve')->where('all', '.*');
        Route::get('payment_voucher/reject_payment/{all}', 'Finance\Pay_VoucherController@reject')->where('all', '.*');
        Route::get('payment_voucher/export_data/{all}', 'Finance\Pay_VoucherController@export_data')->where('all', '.*');
        Route::post('payment_voucher/filterData/{all}', 'Finance\Pay_VoucherController@filter_data')->where('all', '.*');
        Route::post('payment_voucher/add_note', 'Finance\Pay_VoucherController@add_note');
        Route::post('payment_voucher/save_add_note', 'Finance\Pay_VoucherController@save_add_note')->name('note.addnote');
        Route::post('payment_voucher/add_files', 'Finance\Pay_VoucherController@add_files');
        Route::post('payment_voucher/save_add_files', 'Finance\Pay_VoucherController@save_add_files')->name('file.addfile');
        Route::post('payment_voucher/edit_Payment' , 'Finance\Pay_VoucherController@edit_Payment');
        Route::post('payment_voucher/send_todirector' , 'Finance\Pay_VoucherController@send_todirector');
        Route::post('payment_voucher/hapus_Payment' , 'Finance\Pay_VoucherController@hapus_Payment');
        Route::post('payment_voucher/edit_savePayment' , 'Finance\Pay_VoucherController@edit_savePayment');
        Route::post('payment_voucher/hapus_savePayment' , 'Finance\Pay_VoucherController@hapus_savePayment');
        Route::post('payment_voucher/new_vendor', 'Finance\Pay_VoucherController@modal_vendor');
        Route::get('download/pdf_payment/{payment}', 'Finance\Pay_VoucherController@download_payment')->name('payment.download');
        Route::get('payment_voucher/find_customer', 'Finance\Pay_VoucherController@find_customer');
        Route::put('payment/update', 'Finance\Pay_VoucherController@update')->name('payment.update');
        Route::put('payment/update_finance', 'Finance\Pay_VoucherController@update_finance')->name('payment_finance.update');
        Route::post('payment_voucher/done_payment','Finance\Pay_VoucherController@done_payment');
        Route::post('payment_voucher/save_donepayment', 'Finance\Pay_VoucherController@save_donePayment')->name('pay.donepayment');
        Route::post('payment_voucher/get_value', 'Finance\Pay_VoucherController@auto_value');
        Route::post('payment_voucher/get_valCust', 'Finance\Pay_VoucherController@auto_valueCust');
        Route::post('payment_voucher/change_form', 'Finance\Pay_VoucherController@changeForm');
        Route::post('payment_voucher/saveVendor', 'Finance\Pay_VoucherController@saveVendor');
        Route::get('payment_voucher/{id}/show', 'Finance\Pay_VoucherController@show');
        Route::get('payment_voucher/{id}/edit', 'Finance\Pay_VoucherController@edit');
        Route::get('payment_voucher/{id}/delete', 'Finance\Pay_VoucherController@destroy');
        Route::get('payment_voucher/{id}/{type}/show_finance', 'Finance\Pay_VoucherController@show_finance');
        Route::get('payment_voucher/{id}/{type}/edit_finance', 'Finance\Pay_VoucherController@edit_finance');
        Route::post("get_payment", 'Finance\Pay_VoucherController@ajax_data');
        Route::get('payment_voucher/find_vendor', 'Finance\Pay_VoucherController@find_vendor');
        Route::get('payment_voucher/find_so', 'Finance\Pay_VoucherController@find_so');
        Route::get('download/payment_check/{payment}', 'Finance\Pay_VoucherController@download_checked')->name('payment_checked.download');
        Route::post('download/payment_check_double/{payment}', 'Finance\Pay_VoucherController@download_checked_double');
        Route::post('download/download_double/{payment}', 'Finance\Pay_VoucherController@download_doublecheck');
        Route::post('payment_voucher/store','Finance\Pay_VoucherController@store')->name('payment.store');
        Route::post('payment_voucher/store_finance', 'Finance\Pay_VoucherController@store_finance')->name('payment_finance.store');
        Route::resource('payment_voucher', 'Finance\Pay_VoucherController');


        //Route::get("settlement/settlementmenustotal", 'Finance\SettlementController@totalsmenustotalsmenus');


        Route::get('settlement/{set}/ajukan', 'Finance\SettlementController@ajukan')->name('set.ajukan');
        Route::post('settlement/complete_sets', 'Finance\SettlementController@completed')->name('set.complete');
        Route::post('settlement/add_note', 'Finance\SettlementController@add_note');
        Route::get('settlement/{id}/all_done', 'Finance\SettlementController@all_done');
        Route::post('settlement/saveadd_note', 'Finance\SettlementController@saveadd_note')->name('set.pay_back');
        Route::post('settlement/process', 'Finance\SettlementController@proccess_completed');
        Route::get('settlement/print_sets/{id}', 'Finance\SettlementController@print_settlement')->name('settlement.print');
        Route::get('settlement/{set}/{user}/approve', 'Finance\SettlementController@approve')->name('set.approve');
        Route::get('settlement/{ser}/{user}/reject', 'Finance\SettlementController@reject')->name('set.reject');
        Route::post('settlement/add_kegiatan', 'Finance\SettlementController@add_kegiatan');
        Route::post('settlement/get_cash','Finance\SettlementController@get_cash');
        Route::post('settlement/get_value','Finance\SettlementController@get_value');
        Route::post('settlement/blank_form','Finance\SettlementController@blank_forms');
        Route::post("settlement/set_update", 'Finance\SettlementController@set_update');
        Route::get('settlement/{id}/edit','Finance\SettlementController@edit');
        Route::get('settlement/{id}/delete','Finance\SettlementController@destroy');
        Route::get('settlement/{id}/show','Finance\SettlementController@show');
        Route::get('find_cash' , 'Finance\SettlementController@find_cash');
        Route::post("settlement/submit", 'Finance\SettlementController@save_data');
        Route::get('settlement/{id}/create', 'Finance\SettlementController@create_seattlement');
        Route::post("get_settlement", 'Finance\SettlementController@ajax_data');

        //totalsmenustotalsmenus

        Route::get("settlement/settlementmenustotal", 'Finance\SettlementController@totalsmenustotalsmenus');

        //totalsmenustotalsmenus

        //totalsmenusdetail

        Route::get("settlement/{id}/setmenutotaldetails", 'Finance\SettlementController@totalsmenusdetails');

        //totalsmenusdetail

        Route::post("settlement/edit_set", 'Finance\SettlementController@editby_Finance');
        Route::get("settlement/{id}/edit_finance", 'Finance\SettlementController@EditFinance');
        Route::get("settlement/{id}/reject_finance", 'Finance\SettlementController@reject_finance');
        Route::post("settlement/delete_items", 'Finance\SettlementController@deleteItem');
        Route::resource('settlement', 'Finance\SettlementController');

        // route post

        Route::post('new_code', 'Finance\PettyCashController@modal_call');
        Route::post('saveNewCode', 'Finance\PettyCashController@saveNewCode');
        Route::get('find_code', 'Finance\PettyCashController@find_code');
        Route::get('pettycash/{id}/show','Finance\PettyCashController@show');
        Route::post('pettycash/upload_file', 'Finance\PettyCashController@upload_file')->name('petty_cash.upload');
        Route::post("pettycash/pettycash_list", 'Finance\PettyCashController@ajax_data');
        Route::post("pettycash/ajax_detail", 'Finance\PettyCashController@ajax_detail');
        Route::post("pettycash/ajax_dokumen", 'Finance\PettyCashController@ajax_dokumen');
        Route::get("pettycash/{id}/detail", 'Finance\PettyCashController@pettycash_detail');
        Route::post('/','Finance\PettyCashController@store')->name('pettycash.save');
        Route::get('pettycash/dokumen/{id}/show', 'Finance\PettyCashController@dokumen_detail');
        Route::get("pettycash/dokumen/{id}/create", 'Finance\PettyCashController@create_dokumen');
        Route::get("pettycash/{id}/edit_detail", 'Finance\PettyCashController@edit_detail');
        Route::get("pettycash/dokumen/{id}/edit_dokumen", 'Finance\PettyCashController@edit_dokumen');
        Route::post("pettycash/save_edit_detail", 'Finance\PettyCashController@save_edit_detail')->name('pettycash.saveEdit');
        Route::post("pettycash/dokumen/seve_edit_dokumen", 'Finance\PettyCashController@save_edit_dokumen')->name('pettycash.saveEditDok');
        Route::get('pettycash/{id}/delete_detail', 'Finance\PettyCashController@delete_detail');
        Route::get('pettycash/dokumen/{id}/delete_dokumen', 'Finance\PettyCashController@delete_dokumen');
        Route::get('pettycash/{type}/create','Finance\PettyCashController@create');
        Route::resource('pettycash', 'Finance\PettyCashController');


        Route::post("ajax_list", 'Finance\PengajuanPettyCashController@ajax_data');
        Route::get('pengajuan_pettycash/{id}/{type}/{usr}/approve', 'Finance\PengajuanPettyCashController@approvals');
        Route::post('pengajuan_pettycash/add_purpose','Finance\PengajuanPettyCashController@add_purpose');
        Route::post('/','Finance\PengajuanPettyCashController@store')->name('pengajuan-pettycash.save');
        Route::post('/','Finance\PengajuanPettyCashController@update')->name('pengajuan-petty_cash.update');
        Route::get('pengajuan_pettycash/{id}/show','Finance\PengajuanPettyCashController@show');
        Route::get('pengajuan_pettycash/{id}/delete','Finance\PengajuanPettyCashController@delete');
        Route::get('pengajuan_pettycash/download_pettycash/{id}/print', 'Finance\PengajuanPettyCashController@pdf_pettycash')->name('pettycash.download');
        Route::post('pengajuan_pettycash/add_files', 'Finance\PengajuanPettyCashController@add_files');
        Route::post('pengajuan_pettycash/save_files', 'Finance\PengajuanPettyCashController@saveFile');
        Route::resource('pengajuan_pettycash', 'Finance\PengajuanPettyCashController');


        Route::get('othercost/{id}/show','Finance\OtherCostController@show');
        Route::post("othercost/getLists", 'Finance\OtherCostController@ajax_data');
        Route::resource('othercost', 'Finance\OtherCostController');



        Route::get('tax/{type}/{id}/show','Finance\TaxController@show');
        Route::get('tax/{type}/{id}/edit','Finance\TaxController@edit');
        Route::put('tax/update', 'HR\MassLeaveController@update')->name('tax.saveUpdate');
        Route::post("listTax", 'Finance\TaxController@ajax_data');
        Route::post("listTaxPph", 'Finance\TaxController@ajax_pph');
        Route::get('tax/{id}/delete', 'Finance\TaxController@destroy');
        Route::resource('tax', 'Finance\TaxController');


        Route::post("listTaxPph", 'Finance\PPH_Controller@ajax_pph');
        Route::resource('tax_pph', 'Finance\PPH_Controller');

        Route::get('neraca/labarugi', 'Finance\NeracaController@indexLabarugi');
        Route::post('neraca/labarugi/detail', 'Finance\NeracaController@detailLabarugi');
        Route::post('neraca/rincian', 'Finance\NeracaController@rincian');
        Route::post('neraca/detail', 'Finance\NeracaController@detail');
        Route::resource('neraca', 'Finance\NeracaController');


        ///// CODE ACCOUNTING
        Route::post("list_code", 'Finance\CodeController@ajax_code');
        Route::post('code_accounting/create', 'Finance\CodeController@create');
        Route::get('code_accounting/{id}/show','Finance\CodeController@show');
        Route::get('code_accounting/{id}/delete', 'Finance\CodeController@destroy');
        Route::resource('code_accounting', 'Finance\CodeController');
        
    });

    Route::prefix('lookweb')->group(function () {
        Route::post("loginchange", 'API\AndroidAbsensiController@loginurl');
    });


    Route::prefix('migration')->group(function () {

        Route::post('get_backup', 'Sales\SalesMigrationController@ajax_data');
        Route::get('backup/ex_quo/{all}', 'Sales\SalesMigrationController@ex_quo')->where('all', '.*');
        Route::post('backup/filterData/{all}', 'Sales\SalesMigrationController@filter_data')->where('all', '.*');
        Route::post('backup/document_migrate', 'Sales\SalesMigrationController@document');
        Route::post('backup/document_save', 'Sales\SalesMigrationController@document_save');
        Route::post('backup/document_upload', 'Sales\SalesMigrationController@document_upload');
        Route::post('backup/saveFile', 'Sales\SalesMigrationController@saveFile');
        Route::resource('backup', 'Sales\SalesMigrationController');

        Route::post('get_purchaseold', 'Purchasing\PurchaseMigrateController@ajax_data');
        Route::resource('purchaseold', 'Purchasing\PurchaseMigrateController');

        Route::post("get_inold", 'Warehouse\WarehouseMigrateController@ajax_data');
        Route::resource('warehouse/inboundold', 'Warehouse\WarehouseMigrateController');
        Route::post("get_outold", 'Warehouse\WarehouseOutMigrateController@ajax_data');
        Route::resource('warehouse/outboundold', 'Warehouse\WarehouseOutMigrateController');
    });

    Route::prefix('upload')->group(function () {
        Route::post('file/upload/upload_file', 'Upload\UploadController@upload_file')->name('file.upload');
        Route::post('file/get_upload', 'Upload\UploadController@new_upload_file');
        Route::post("file/get_list", 'Upload\UploadController@ajax_data');
        Route::get('file/{id}/detail', 'Upload\UploadController@show');
        Route::post('file/delete_file','Upload\UploadController@delete_file');
        Route::get('file/{id}/edit_file','Upload\UploadController@edit_file');
        Route::put('file/saveUpdate','Upload\UploadController@saveUpdate')->name('saveUpdate.update');
        Route::resource('file', 'Upload\UploadController');
    });

    Route::prefix('receptionist')->group(function () {
        Route::post("ajax_listbook", 'Receptionist\ReceptionistController@ajax_data');
        Route::post("room_list", 'Receptionist\ReceptionistController@ListRoom');
        Route::post("form_booking", 'Receptionist\ReceptionistController@FormBooking');
        Route::post("Editform_booking", 'Receptionist\ReceptionistController@EditFormBooking');
        Route::post("delete_data", 'Receptionist\ReceptionistController@delete_form');
        Route::post('/', 'Receptionist\ReceptionistController@store')->name('booking.store');
        Route::post("saveUpdate", 'Receptionist\ReceptionistController@saveUpdate')->name('edit_form.saveUpdate');
        Route::resource('booking_room', 'Receptionist\ReceptionistController');
    });
});
