<?php

namespace Service;

use Controller\CrudController;
use Controller\LoginController;

class Router{
	protected $paths = ['crud', ];
	private $user;
	function __construct($path){
		$this->user = User::i();
		$this->isLoggedIn();
		$this->section();}

	function isLoggedIn(){
		return is_null($this->user) || 1 ? new LoginController : false ;
}

	function section(){
		
		//is_null($this->user) ? $this->login
		
}

	function getPath(){
		}

	function login(){
		}
}
