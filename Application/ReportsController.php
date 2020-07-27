<?php

namespace Application;
use \Controller\Routeable;
use \View\ReportView;
use \Model\Entity;
use \Service\Path;

class ReportsController implements Routeable{

	function __construct(){
		$this->entity = new Entity('sales');
		$this->view = new ReportView();
		!empty($sections = static::getSubMenu()) && $this->view->subMenuBar($sections);
		Path::getParam('section') == 'sales' ? $this->sales() : $this->stock() ;
	}
	static function getSubMenu(){
		return ['sales', 'stock'];
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
