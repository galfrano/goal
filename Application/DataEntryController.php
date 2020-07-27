<?php

namespace Application;
use View\InvoiceView;
use View\CrudView;
use Model\Entity;
use Utility\Reports;
use Controller\CrudController;

class DataEntryController extends CrudController{
	protected static $children = ['products'=>['prices'], 'invoices'=>['invoice_lines']];
	protected static $sections = ['categories', 'customers', 'products', 'warehouses', 'inbound', 'invoices'];
	protected static $path = '/admin/';

	static function getSubMenu(){
		return self::$sections;
	}
	function isAvailable($table){
		if(!in_array($table, self::$sections, true)){
			throw new \Exception('No such entity available for Admin: '.$table);
		}
		elseif($table === 'invoices'){
			self::$actions[] = 'print';
			$this->view = new InvoiceView(static::$sections, static::$path);
		}
		elseif($table === 'inbound'){ //TODO: correct dirty hack
			CrudView::$delete = false;
		}
	}
	function get(){
		$this->table !== 'invoices' ?: $this->filter = ['cancelled'=>'No'];
		if($this->table === 'invoices' && $this->action === 'print' && $this->id){
			$this->view->showInvoice($this->entity, $this->id)->output();
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
