<?php

namespace Controller;

use Service\User;
use Model\Entity;

class UserController extends CrudController{

	private $userKey = 'user', $session;

	function __construct($table, $path = []){
		$this->session = User::getSession();
		$this->entity = new Entity($this->table = $table);
		unset($this->entity->user);
		parent::__construct($table, $path);}
	
}
