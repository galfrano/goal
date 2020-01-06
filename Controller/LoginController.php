<?php
namespace Controller;
use Model\Entity;
use Service\User;
use View\UserView as View;
class LoginController{

	protected $entity;
	protected static $table = 'users', $user = 'email', $passwd = 'password'; //$user and password are related to the form, not to the db

	function __construct(){
		$this->entity = new Entity(self::$table);
		$this->view = new View;
		$error = false;
		if(!empty($_POST) && !empty($_POST[self::$user]) && !empty($_POST[self::$passwd])){
			if(User::i()->authenticate($_POST[self::$user], $_POST[self::$passwd])){
				header('Refresh:0');}
			else{
				$error = true;}}
		$this->notLoggedIn($error);}

	function notLoggedIn($error){
		$this->view->showLoginForm($error)->output();
		exit;}

	function logout(){
		}
}
