<?php

namespace Service;
use Model\Entity;
/*
interface UserInterface{
	static function i();
	static function authenticate($username, $password):boolean|array;
	static function encrypt():array;
	static function getSession():array|boolean
}
*/

class User /*implements UserInterface*/{

	private static $i, $entity, $sessionKey = 'user', $table = 'users', $user = 'email', $passwd = 'passwd'; //$table, $user and $passwd are related to the db directly

	static function i(){
		return is_null(self::$i) ? (self::$i = new static) : self::$i;}

	private static function entity(){
		return is_null(self::$entity) ? (self::$entity = new Entity(self::$table)) : self::$entity;}

	static function getSession($logout = false){
		if($logout){
			self::logout();}
		return empty($_SESSION[self::$sessionKey]) ? false : $_SESSION[self::$sessionKey];}

	static function authenticate($username, $password){
		$user = self::entity()->get([self::$user=>$username]);
		return $_SESSION[self::$sessionKey] = !$user || !password_verify($password, $user[self::$passwd]) ? !!$user : $user ;}

	static function encrypt($user){
		return [self::$passwd=>password_hash($user[self::$passwd], \PASSWORD_DEFAULT)]+$user;}

	static function logout(){
		$_SESSION[self::$sessionKey] = false;}}
