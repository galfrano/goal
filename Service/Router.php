<?php

namespace Service;

use \Controller\LoginController;
use \Xml\Tag;

//TODO: add some validations

class Router{
	use \Utility\Killable;
	
	protected static $controllers = [
		'data_entry'=>'Application\DataEntryController',
		'reports'=>'Application\ReportsController',
		'user_administration'=>'Controller\UserAdminController',
	];
	function __construct(){
		$this->user = !empty($_POST['logout']) ? User::i()->logout() : User::i()->getSession() ;
		!$this->user && new LoginController;
		$this->setLanguage();
		$this->handlePath();
	}
	public function setLanguage(){
		if(!empty($_POST['changeUserLanguage'])){
			User::updateLanguage($this->user['language'] = $_POST['changeUserLanguage']);
		}
		Tag::setLanguage($this->user['language']);
	}
	private function handlePath(){
		$controller = Path::getParam('controller');
		key_exists($controller, self::$controllers) or self::kill('No such path '.$controller);
		$class = self::$controllers[$controller];
		!empty(Path::getParam('section')) || Path::setSection(current($class::getSubMenu()));
		new $class();
	}
}
