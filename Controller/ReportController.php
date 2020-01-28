<?php

namespace Controller;
use \View\ReportView;
use \Model\Entity;
class ReportController{

	function __construct(){
		$this->entity = new Entity('sales');
		$data = $this->entity->getList();
		(new ReportView(['sales', 'stock'], 'reports'))->showSales($data)->output();}}
