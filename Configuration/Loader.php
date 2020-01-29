<?php

spl_autoload_register(function($className){
	
	$path = dirname(__DIR__).'/'.str_replace('\\', '/', $className).'.php';
	if(is_file($path)){
		include_once($path);}
/*	elseif(!is_bool(strpos($className, 'Controller'))){
		include_once(dirname(__DIR__).'/'.'Controller/CrudController.php');
		class_alias('Controller\CrudController', $className);}*/

});
