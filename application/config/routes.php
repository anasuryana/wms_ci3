<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['printlabel_do'] = 'RCV/printlabel';
$route['printlabel_fg'] = 'SER/printfglabel';
$route['printlabel_fgstatus'] = 'SER/printfgstatuslabel';
$route['printlabel_fgrtnstatus'] = 'SER/print_return_control_label';
$route['printlabel_rm'] = 'SER/printrmlabel';
$route['print_serahterima_rcqc'] = 'SER/print_st_rcqc';
$route['printlabel_si'] = 'SI/printlabel';
$route['printlabel_sioth'] = 'SI/printlabeloth';
$route['printpending_doc'] = 'PND/printdoc';
$route['printreleaserm_doc'] = 'RLS/printdoc';
$route['printPO_doc'] = 'PO/print';
$route['printpendingser_doc'] = 'PND/printdocser';
$route['printreleaseser_doc'] = 'RLS/printdocser';
$route['printscraping_doc'] = 'SCR/printdoc';
$route['printscrapingser_doc'] = 'SCR/printdocser';
$route['printserah_terima_return_doc'] = 'RCV/print_serah_terima_return';
$route['splresult_doc'] = 'SPL/printresult';
$route['ex_receiving_rm'] = 'RCV/export_to_spreadsheet';
$route['printlabel_ret'] = 'RETPRD/printlabel';
$route['printdeliverydocs'] = 'DELV/printdocs';
$route['printdeliverydocs_rm'] = 'DELV/printdocs_rm';
$route['printdeliveryepro'] = 'DELV/print_pickingdoc';
$route['ex_do_bom'] = 'DELV/get_do_bom_as_xls';
$route['ex_shipping_mega'] = 'DELV/export_to_so_mega_as_xls';
$route['printdocs_2'] = 'DELV/out_rm_sp_xls';
$route['printpickingresult_doc'] = 'SI/print_pickingdoc';
$route['laporan_pembukuan_masuk_xlsx'] = 'RCV/dr_pab_inc_as_excel';
$route['laporan_pembukuan_keluar_xlsx'] = 'DELV/dr_pab_out_as_excel';
$route['logx'] = 'ITH/getlogexport';
$route['return_from_plant'] = 'RETPRD/getconfirmation_xls';
$route['smtstock'] = 'ITH/smtstock';
$route['change_password'] = 'User/form_change_password';
$route['smtdelivered_rm'] = 'ITH/getRMFromDeliveredFG';
$route['ex_stock_recap'] = 'ITH/get_stock_recap_as_xls';
$route['ex_stock_detail'] = 'ITHHistory/get_stock_detail_as_xls';
$route['delivery_doc_as_xls'] = 'DELV/doc_as_xls';
$route['delivery_doc_rm_as_xls'] = 'DELV/doc_rm_as_xls';
$route['delivery_doc_as_omi_xls'] = 'DELV/doc_as_omi_xls';
$route['conversion_test_doc_as_xls'] = 'SER/conversion_test_as_xls';
$route['kka_mega_as_xls'] = 'ITH/report_kka_mega';
$route['po_list'] = 'PO/report';
$route['master_hscode_as_xls'] = 'MSTITM/searchrm_exim_xls';
$route['master_hscode_fg_as_xls'] = 'MSTITM/searchfg_exim_xls';
$route['double_key_transaction'] = 'ITHHistory/double_unique_tx';
$route['logx/(:any)'] = 'ITH/getlogexport/$1';
$route['404_override'] = '';
$route['translate_uri_dashes']  = FALSE;