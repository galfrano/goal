<?php
//TODO: Sanitize!!!!!
namespace Application;
use \Controller\Routeable;
use \View\ReportView;
use \Model\Journaled as Entity;
use \Service\Path;

class ReportsController implements Routeable{

	use \Utility\Calculate;

	function __construct(){
		$this->entity = new Entity('sales3');
		$this->view = new ReportView();
		!empty($sections = static::getSubMenu()) && $this->view->subMenuBar($sections);
		$method = str_replace('-', '', Path::getParam('section'));
		$this->$method();
	}
	static function getSubMenu(){
		return [/*'sales', */'stock', 'all-sales'];
	}
/*	function sales(){
		$this->entity = new Entity('sales2');
		$this->entity->rpp = -1;
		list($from, $to) = ['2020-06-01', '2020-06-30'];
		$data = $this->entity->getList(1);//$page = Path::getParam('page')
		$calc = self::groupAddMultiply($data, 'price', 'product', 'amount');
		$this->view->showCalc($calc)->output();
	}*/
	function allSales(){
		empty($_POST) || $this->setDates();//header('location ./ll');
		$this->entity->rpp = -1;
		list($c, $s, $from, $to) = array_values(Path::getParams());
		$data = $this->entity->setRange('occurence', $from, $to)->getList($page = Path::getParam('page'));
		$this->view->showAllSales(self::totalCash($data), self::groupProducts($data), $this->entity->pages(), $page)->output();
	}
	function setDates(){
		$from = empty($_POST['from']) ? '0000-00-00' : $_POST['from'];
		$to = empty($_POST['to']) ? '9999-12-31' : $_POST['to'];
		$path = \Configuration\MAIN_URL.'/reports/all-sales';
		header("location:  $path/$from/$to");
	}
	function stock(){
		$this->entity = new Entity('stock');
		$this->entity->rpp = -1;
		$this->view->showStock($this->entity->getList())->output();
}}
