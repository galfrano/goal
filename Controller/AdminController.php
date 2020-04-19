<?php

namespace Controller;
use \View\InvoiceView;
use \View\CrudView;
use Model\Entity;

class AdminController extends CrudController{
	use Reports;
//	protected static $entities = ['categories', 'users', 'products', 'customers', 'warehouses', 'inbound', 'invoices', 'sales'];
	protected static $children = ['products'=>['prices'], 'invoices'=>['invoice_lines']];
	protected static $sections = ['users', 'categories', 'customers', 'products', 'warehouses', 'inbound', 'invoices', 'sales', 'stock'];
	protected static $path = '/admin/';
	

	function __construct($table, $path){
		$this->isAvailable($table);
		parent::__construct($table, $path);}

	function isAvailable($table){
		if(!in_array($table, self::$sections, true)){
			throw new \Exception('No such entity available for Admin');}
		elseif($table === 'invoices'){
			self::$actions[] = 'print';
			$this->view = new InvoiceView(static::$sections, static::$path);}
		elseif($table === 'stock'){
			$this->table = 'stock';
			$this->entity = new Entity('sales');}
		elseif($table === 'inbound'){ //TODO: correct dirty hack
			CrudView::$delete = false;}}

	function get(){
		if($this->table === 'sales'){
			$this->sales();}
		elseif($this->table === 'stock'){
			$this->stock();}
		elseif($this->table === 'invoices' && $this->action === 'print' && $this->id){
			$this->view->showInvoice($this->entity, $this->id)->output();}
		else{
			parent::get();}}
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
