<?php

namespace Service;
use \Model\Entity;
use \Xml\Tag;
/*
interface UserInterface{
	static function i();
	static function authenticate($username, $password):boolean|array;
	static function encrypt():array;
	static function getSession():array|boolean
}
*/

class User /*implements UserInterface*/{

	private static $i, $entity, $sessionKey, $settingsKey, $table, $user, $passwd; //$table, $user and $passwd are related to the db directly

	private function __construct(){
		list(static::$sessionKey, static::$settingsKey, static::$table, static::$user, static::$passwd) = array_values(\Configuration\USER);
		self::handlePost();
		!empty($_SESSION[self::$sessionKey]['language']) && Tag::setLanguage($_SESSION[self::$sessionKey]['language']);
	}
	static function i(){
		return is_null(self::$i) ? (self::$i = new static) : self::$i;
	}
	static function handlePost(){
		!empty($_POST['changeUserLanguage']) && self::updateLanguage($_POST['changeUserLanguage']);;
		!empty($_POST['logout']) && self::logout();
	}
	private static function entity(){
		return is_null(self::$entity) ? (self::$entity = new Entity(self::$table)) : self::$entity;
	}
	static function getSession(){
		return empty($_SESSION[self::$sessionKey]) ? false : $_SESSION[self::$sessionKey];
	}
	static function authenticate($username, $password){
		$user = self::entity()->get([self::$user=>$username]);
		return $_SESSION[self::$sessionKey] = !$user || !password_verify($password, $user[self::$passwd]) ? false : $user ;
	}
	static function settings($settings){
//		self::$user['settings'] =
	}
	static function updateLanguage($lang){
		self::entity()->edit(['language'=>$lang], $_SESSION[self::$sessionKey]['id']);
		$_SESSION[self::$sessionKey]['language'] = $lang;
	}
	static function getUserMenu(){

		return self::getSession() ? ['user_administration', 'data_entry', 'reports', 'warehouse'] : [] ;
	}
	static function getDefault(){
		return self::getSession() ? ['data_entry', false, 1, false, false] : [] ;
	}
	static function encrypt($passwd){
		return password_hash($passwd, \PASSWORD_DEFAULT);
	}
	static function logout(){
		return $_SESSION[self::$sessionKey] = false;
	}
}
