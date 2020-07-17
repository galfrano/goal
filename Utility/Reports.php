<?php

namespace Utility;
use \View\ReportView;
use \Model\Entity;

trait Reports{

	function sales(){
		$this->entity->rpp = 0;
		$data = $this->entity->getList();
		(new ReportView(self::$sections, self::$path))->showSales($data)->output();
	}
	function stock(){
		$warehouses = (new Entity('warehouses'))->getList();
		$this->entity->rpp = 0;
		$sales = $this->entity->getList();
		$inbound = new Entity('inbound');
		$inbound->rpp = 0;
		$products = (new Entity('products'))->catalog();
		(new ReportView(self::$sections, self::$path))->showStock($warehouses, $sales, $inbound->getList(), $products)->output();
	}
}
