<?php

namespace Controller;

class AdminController extends CrudController{
	protected static $entities = ['categories', 'users', 'products', 'customers', 'warehouses', 'inbound', 'invoices'];
	protected static $children = ['products'=>['prices'], 'invoices'=>['invoice_lines']];
	protected static $sections = ['categories', 'users', 'products', 'customers', 'warehouses', 'inbound', 'invoices'];
	protected static $path = '/admin/';

	function __construct($table, $path){
		$this->isAvailable($table);
		parent::__construct($table, $path);}

	function isAvailable($table){
		if(!in_array($table, self::$entities, true)){
			throw new \Exception('No such entity available for Admin');}}
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
