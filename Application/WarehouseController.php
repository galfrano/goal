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

	function __construct(){
		$this->isAvailable(Path::getParam('section'));
		parent::__construct();
	}
	static function getSubMenu(){
		return self::$sections;
	}
	function isAvailable($section){
		if(!in_array($section, self::$sections, true)){
			throw new \Exception('No such entity available: '.$section);
		}
		elseif($section === 'inbound'){ //TODO: correct dirty hack
			CrudView::$delete = false;
		}
	}
}
