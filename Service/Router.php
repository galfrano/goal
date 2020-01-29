<?php

namespace Service;

//use Controller\CrudController;
use Controller\CrudController;
use Controller\AdminController;
use Controller\LoginController;
use Controller\ReportController;

class Router{
	protected static $controllers = ['crud'=>'CrudController', 'admin'=>'AdminController', 'sales'=>'SalesRepController', 'invoices'=>'InvoicesController'], $defaultPath = '/admin/users/1';
	private $user;
	function __construct($path){
		$this->user = User::i()->getSession(!empty($_POST['logout']));
		!$this->user && new LoginController;
		$this->section($path);}

//'crud/users/4/edit/35'
	function section($path){
		if(empty($path) || count($path) < 3){
			header('location: '.\MAIN_URL.static::$defaultPath);}
/*		elseif($path[0] === 'crud' && $path[1] === 'users' ){
			new CrudController('users', [@$path[2], @$path[3], @$path[4]]);}*/
		elseif($path[0] === 'admin'){
			new AdminController($path[1], [@$path[2], @$path[3], @$path[4]]);}
		else{
			echo 'Path not found ';
			var_dump($path);}}}
