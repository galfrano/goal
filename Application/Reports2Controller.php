<?php

namespace Application;
use \Controller\Routeable;
use \View\Report2View as ReportView;
use \Model\Journaled as Entity;
use \Service\Path;

class Reports2Controller implements Routeable{

	use \Utility\Calculate;

	function __construct(){
		$this->entity = new Entity('sales2');
		$this->view = new ReportView();
		!empty($sections = static::getSubMenu()) && $this->view->subMenuBar($sections);
		$method = str_replace('-', '', Path::getParam('section'));
		$this->$method();
	}
	static function getSubMenu(){
		return ['sales', 'stock', 'all-sales'];
	}
	function sales(){
		$this->entity->rpp = -1;
		list($from, $to) = ['2020-06-01', '2020-06-30'];
//[Path::getParam('page'), Path::getParam('action')];
		//$filters = ['occurence'];
		$data = $this->entity->getList(1);//$page = Path::getParam('page')
		$calc = self::groupAddMultiply($data, 'price', 'product', 'amount');
		$this->view->showCalc($calc)->output();
	}
	function allSales(){
		$data = $this->entity->setRange('occurence', '2020-06-01')->getList($page = Path::getParam('page'));
//var_dump($calc);die;
		$this->view->showAllSales($data, $this->entity->pages(), $page)->output();
	//	$this->view->showCalc($calc)->output();
	}
	function stock(){
		$this->sales();
}}
