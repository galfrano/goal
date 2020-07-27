<?php

namespace Utility;

trait Path{

	//public static $controller, $section, $page, $id;
	protected static $params;

	protected static function setParams(){
		if(!empty($params)){
			return static::$params;
		}
		$params = array_values(array_filter(explode('/', str_replace(\Configuration\MAIN_URL, '', $_SERVER['REQUEST_URI']))));
		return static::$params = array_combine(['controller', 'section', 'page', 'action', 'id'], $params+static::getDefault());
		/*static::$controller = !empty($params[0]) ? $params[0] : $default[0];
		static::$section = !empty($params[1]) ? $params[1] : $default[1];
		static::$page = !empty($params[2]) && is_numeric($path[2]) ? $path[2] : $default[2];
		static::$action = !empty($path[3]) && in_array($path[3], static::$actions, true) ? $path[3] : $default[3];
		static::$id = !empty($path[4]) && is_numeric($path[4]) ? $path[4] : $default[4];*/
	}
	public static function getParam($name){
		return key_exists($name, static::$params) ? static::$params[$name] : false ;
	}
	public static function getUrl($paths = [], $replace = []){
		$parts = self::setParams();
		foreach($paths as $key => $path){
			if(!empty($path) && !empty($i = static::$urlMap[$path])){
				$parts[$i] = $replace[$key];
			}
		}
		return \Configuration\MAIN_URL.'/'.implode('/', array_filter($parts)).'/';
	}
//	public static getUrl($paths, $replace){
		
	//}
}
