<?php
namespace Controller;
use Model\Entity;
use View\UserView as View;
class LoginController{

	protected $entity, $table = 'users';

	function __construct(){
		$this->entity = new Entity($this->table);
		$this->view = new View;
		$this->notLoggedIn();}

	function notLoggedIn(){
//		$this->view->showSignUpForm()->output();
		$this->view->showLoginForm()->output();
		exit;}}
