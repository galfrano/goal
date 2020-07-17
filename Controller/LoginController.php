<?php
namespace Controller;
use Model\Entity;
use Service\User;
use View\UserView as View;
class LoginController{

	protected $entity;
	protected static $user = 'email', $passwd = 'password'; //$user and $passwd are related to the form, not to the db
	
	function __construct(){
		$this->entity = new Entity(\Configuration\USER['usersTable']);
		$this->view = new View;
		$this->login();
	}
	function login(){
		if(!empty($_POST[self::$user]) && !empty($_POST[self::$passwd])){
			User::i()->authenticate($_POST[self::$user], $_POST[self::$passwd]) ? header('Refresh:0') : $this->notLoggedIn(true) ;
		}
		$this->notLoggedIn(false);
	}
	function notLoggedIn($error){
		$this->view->showLoginForm($error)->output();
		exit;
	}
	function signUp(){
		$this->view->showSignUpForm()->output();
	}
}
