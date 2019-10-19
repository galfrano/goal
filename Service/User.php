<?php

namespace Service;
use Model\Entity;
/*
interface UserInterface{
	static function i();
	static function authenticate($username, $password):boolean|array;
	static function encrypt():array;
}
*/

class User /*implements UserInterface*/{

	private static $i, $entity, $key = 'user', $passwd = 'passwd';

	static function i(){
		return is_null(self::$i) ? (self::$i = new static) : self::$i;}

	private static function entity(){
		return is_null(self::$entity) ? (self::$entity = new Entity('user')) : self::$entity;}

	static function getSession(){
		return empty($_SESSION[self::$key]) ? false : $_SESSION[self::$key];}

	static function authenticate($user, $password){
		$user = current(self::$user()->getList(1, ['email'=>$user]));
		return $_SESSION[self::$key] = !$user ? $user : password_verify($password, $user[self::$passwd]);}

	static function encrypt($user){
		return [self::$passwd=>password_hash($user[self::$passwd], \PASSWORD_DEFAULT)]+$user;}

}
