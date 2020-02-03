<?php

namespace Controller;
use \View\ReportView;
use \Model\Entity;
trait Reports{

	function sales(){
		$this->entity->rpp = 0;
		$data = $this->entity->getList();
		(new ReportView(self::$sections, self::$path))->showSales($data)->output();}

	function stock(){
		$this->entity->rpp = 0;
		$sales = $this->entity->getList();
		$inbound = new Entity('inbound');
		$inbound->rpp = 0;
		$products = (new Entity('products'))->catalog();
		(new ReportView(self::$sections, self::$path))->showStock($sales, $inbound->getList(), $products)->output();}}
