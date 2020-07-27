<?php

namespace Utility;
use \View\ReportView;
use \Model\Entity;
use \Service\Path;

trait Reports{

	function __construct(){
		if(in_array(Path::getParam('section'), ['sales', 'stock'], true)){
			$this->view = new ReportView();
		}
		parent::__construct();
	}

	function sales(){
		$this->entity->rpp = 0;
		$data = $this->entity->getList();
		$this->view->showSales($data)->output();
	}
	function stock(){
		$warehouses = (new Entity('warehouses'))->getList();
		$this->entity->rpp = 0;
		$sales = $this->entity->getList();
		$inbound = new Entity('inbound');
		$inbound->rpp = 0;
		$products = (new Entity('products'))->catalog();
		$this->view->showStock($warehouses, $sales, $inbound->getList(), $products)->output();
	}
}
