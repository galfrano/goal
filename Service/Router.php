<?php

namespace Service;

//use Controller\CrudController;
use Controller\CrudController2 as CrudController;
use Controller\AdminController as AdminController;
use Controller\LoginController;

class Router{
	protected static $controllers = ['crud'=>'CrudController2', 'admin'=>'AdminController', 'sales'=>'SalesRepController'];
	private $user;
	function __construct($path){
		$this->user = User::i()->getSession();
		!$this->user && new LoginController;
		$this->section($path);}

//'crud/users/4/edit/35'
	function section($path){
		if($path[0] === 'crud' && $path[1] === 'users' ){
			new CrudController('users', [@$path[2], @$path[3], @$path[4]]);}
		elseif($path[0] === 'admin'){
			new AdminController($path[1], [@$path[2], @$path[3], @$path[4]]);}
		else{
			var_dump($path);}

//		var_dump($this->user, $path);
//		die('logged in');
		
}

//	function processPath($path){
//		empty($path[0]) || empty(self::$controllers[$path[0]]) }

	function getPath(){
		}

	function login(){
		}
}
