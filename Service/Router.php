<?php

namespace Service;

use \Controller\LoginController;
use \Xml\Tag;

//TODO: add some validations

class Router{
	use \Utility\Killable;

	protected static $controllers = [
		'warehouse'=>'Application\WarehouseController',
		'data_entry'=>'Application\DataEntryController',
		'reports'=>'Application\ReportsController',
		'user_administration'=>'Controller\UserAdminController',
	];
	function __construct(){
		$this->user = User::i()->getSession() ;
		!$this->user && new LoginController;
		$this->handlePath();
	}
	private function handlePath(){
		$controller = Path::getParam('controller');
		key_exists($controller, self::$controllers) or self::kill('No such path '.$controller);
		$class = self::$controllers[$controller];
		!empty(Path::getParam('section')) || Path::setSection(current($class::getSubMenu()));
		new $class();
	}
}
