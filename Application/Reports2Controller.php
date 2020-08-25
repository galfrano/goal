<?php

namespace Application;
use \Controller\Routeable;
use \View\Report2View as ReportView;
use \Model\Journaled as Entity;
use \Service\Path;

class Reports2Controller implements Routeable{

	use \Utility\Calculate;

	function __construct(){
		$this->entity = new Entity('sales3');
		$this->view = new ReportView();
		!empty($sections = static::getSubMenu()) && $this->view->subMenuBar($sections);
		$method = str_replace('-', '', Path::getParam('section'));
		$this->$method();
	}
	static function getSubMenu(){
		return ['sales', 'stock', 'all-sales'];
	}
	function sales(){
$this->entity = new Entity('sales2');
		$this->entity->rpp = -1;
		list($from, $to) = ['2020-06-01', '2020-06-30'];
//[Path::getParam('page'), Path::getParam('action')];
		//$filters = ['occurence'];
		$data = $this->entity->getList(1);//$page = Path::getParam('page')
		$calc = self::groupAddMultiply($data, 'price', 'product', 'amount');
		$this->view->showCalc($calc)->output();
	}
	function allSales(){
		$this->entity->rpp = -1;
		list($c, $s, $from, $to) = array_values(Path::getParams());
		$data = $this->entity->setRange('occurence', $from, $to)->getList($page = Path::getParam('page'));
//var_dump($calc);die;
		$this->view->showAllSales(self::totalCash($data), self::groupProducts($data), $this->entity->pages(), $page)->output();
	//	$this->view->showCalc($calc)->output();
	}
	function stock(){
		$this->sales();
}}
