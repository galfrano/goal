<?php

namespace Service;

class Path{
	protected static $params;

	public static function getParams(){
		if(!empty(static::$params)){
			return static::$params;
		}
		$keys = ['controller', 'section', 'page', 'action', 'id'];
		$params = array_values(array_filter(explode('/', str_replace(/*explode('/', \Configuration\MAIN_URL)*/'dario', '', $_SERVER['REQUEST_URI'])))); //FIXME!!!!
		return static::$params = array_combine($keys, $params+User::getDefault());
	}
	public static function getParam($name){
		self::getParams();
		return key_exists($name, static::$params) ? static::$params[$name] : false ;
	}
	public static function setSection($section){
		static::$params['section'] = $section;
	}
	public static function getUrl($paths = [], $replace = []){
		$parts = self::getParams();
		foreach($paths as $key => $path){
			if(key_exists($path, $parts)){
				$parts[$path] = $replace[$key];
			}
		}
		return \Configuration\MAIN_URL.'/'.implode('/', array_filter($parts)).'/';
	}

}
