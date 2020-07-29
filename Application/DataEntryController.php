<?php

namespace Application;
use \View\InvoiceView;
use \View\CrudView;
use \Model\Entity;
use \Utility\Reports;
use \Service\Path;
use \Controller\CrudController;

class DataEntryController extends CrudController{
	protected static $children = ['products'=>['prices'], 'invoices'=>['invoice_lines']];
	protected static $sections = ['categories', 'customers', 'products', 'warehouses', 'inbound', 'invoices', 'product_relocation', 'cancelled_invoices', 'outbound'];

	function __construct(){
		$this->isAvailable(Path::getParam('section'));
		parent::__construct();
	}
	static function getSubMenu(){
		return self::$sections;
	}
	function isAvailable($section){
		if(!in_array($section, self::$sections, true)){
			throw new \Exception('No such entity available for Admin: '.$section);
		}
		elseif($section === 'invoices'){
			self::$actions[] = 'print';
			$this->view = new InvoiceView();
		}
		elseif($section === 'cancelled_invoices'){
			$this->entity = new Entity($this->table = 'invoices');
		}
		elseif($section === 'inbound'){ //TODO: correct dirty hack
			CrudView::$delete = false;
		}
	}
	function get(){
		$this->table !== 'invoices' ?: $this->filter = ['cancelled'=>'No'];
		Path::getParam('section') !== 'cancelled_invoices' ?: $this->filter = ['cancelled'=>'yes'];
		if($this->table === 'invoices' && Path::getParam('action') === 'print' && Path::getParam('id')){
			$this->view->showInvoice($this->entity, Path::getParam('id'))->output();
		}
		else{
			parent::get();
		}
	}
}
/*
admin/reports/view/sales/20200115-20201131	(view name, dates/filters)
admin/cancel


* admin/products
* admin/customers
* admin/categories (crud)
* admin/products (product -prices crud)
* admin/inbound (inbound crud)
* admin/invoices (invoice crud)
* admin/warehouses (custom controller)

*/
