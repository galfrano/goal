<?php

namespace Service;

//use Application\AdminController;
use Controller\LoginController;
use Controller\UserController;

class Router{
	
	protected static $controllers = ['data_entry'=>'Application\DataEntryController', 'reports'=>'Application\ReportsController', 'user_administration'=>'Controller\UserAdminController'], $defaultPath = '/admin/categories/1';
	private $user;
	use \Utility\Path;
	function __construct(){
		$this->user = !empty($_POST['logout']) ? User::i()->logout() : User::i()->getSession() ;
		!$this->user && new LoginController;
		$this->handlePath();
	}
/*	private function section($path){//'crud/users/4/edit/35'
		empty($path) && header('location: '.\MAIN_URL.static::$defaultPath);
		count($path) >= 3 ?: $path = $path+
var_dump($path);die;
		if( || count($path) < 3){
			}
		elseif($path[0] === 'admin'){
			new AdminController($path[1], [@$path[2], @$path[3], @$path[4]]);
		}
		elseif($path[0] === 'sales-rep'){
			new UserController($path[1], [@$path[2], @$path[3], @$path[4]]);
		}
		else{
			echo 'Path not found ';
			var_dump($path);
		}
	}*/
	private function handlePath(){
		$controller = self::$controllers[Path::getParam('controller')];
		//var_dump(current($controller::getSubMenu()));
		!empty(Path::getParam('section')) || Path::setSection(current($controller::getSubMenu()));
		new $controller();
	}
}
