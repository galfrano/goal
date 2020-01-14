<?php

namespace Service;

//use Controller\CrudController;
use Controller\CrudController;
use Controller\AdminController;
use Controller\LoginController;

class Router{
	protected static $controllers = ['crud'=>'CrudController', 'admin'=>'AdminController', 'sales'=>'SalesRepController'], $defaultPath = '/admin/users/1';
	private $user;
	function __construct($path){
		$this->user = User::i()->getSession(!empty($_POST['logout']));
		!$this->user && new LoginController;
		$this->section($path);}

//'crud/users/4/edit/35'
	function section($path){
		if(empty($path)){
			header('location: .'.\MAIN_URL.static::$defaultPath);}
		elseif($path[0] === 'crud' && $path[1] === 'users' ){
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
