<?php

namespace Application;
use \View\InvoiceView;
use \View\CrudView;
use \Model\Entity;
use \Service\Path;
use \Controller\CrudController;

class WarehouseController extends CrudController{
	protected static $children = [];
	protected static $sections = ['warehouses', 'inbound', 'outbound', 'product_relocation'];

	function isAvailable($section){
		parent::isAvailable($section);
		if($section === 'inbound'){ //TODO: implement toolbar control
			CrudView::$delete = false;
		}
	}
}
