<?php
namespace Controller;
use Model\Entity;
use Helper\View;
class LoginController{

	protected $entity, $table = 'customers';

	function __construct(){
		$this->entity = new Entity($this->table);
		$this->view = new View;
		$this->notLoggedIn();}

	function notLoggedIn(){
		$this->view->showLoginForm();
		exit;}}
