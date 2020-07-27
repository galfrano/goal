<?php

namespace Controller;

use Service\User;
use Model\Entity;

//TODO: FIXME: Security!!! add filter to remaining post-get

class UserAdminController extends CrudController{

	static $sections = ['users'];

/*	private $userKey = 'user', $pk = 'id', $session;

	function __construct($table, $path = []){
		$this->session = User::getSession();
		$this->filter[$this->userKey] = $this->session[$this->pk];
		$this->entity = new Entity($this->table = $table);
		unset($this->entity->user);
		empty($_POST) || $_POST += $this->filter;
		parent::__construct($table, $path);
	}*/
	static function getSubMenu(){
		return self::$sections;
	}
	function getList(){
	}
}
