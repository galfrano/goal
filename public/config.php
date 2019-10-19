<?php

class Config{
	const
//--------------------------------------------------DB--------------------------------------------------\\
		D = 'sf',
		H = 'localhost',
		U = 'sf',
		P = '$pass123',
//--------------------------------------------------OTHER--------------------------------------------------\\
		PATH = '../../goal',
		LANG = 'es',
		TPL = 'tpl';
	static
		$sections = ['users', 'projects', 'invoices'],
		$special = ['children'=>['invoices'=>['products'=>'invoice_products']], 'actions'=>['invoices'=>[1=>['print'=>'&action=printInvoice']]]];

	static function children($parent){
		return empty(self::$special['children'][$parent]) ? [] : self::$special['children'][$parent] ;}

	static function loadClass($className){
		$path = __DIR__.'/'.self::PATH.'/'.str_replace('\\', '/', $className).'.php';
		if(is_file($path)){
			include_once($path);}
		elseif(!is_bool(strpos($className, 'Controller'))){
			include_once(__DIR__.'/'.self::PATH.'/Controller/CrudController.php');
			class_alias('Controller\CrudController', $className);}}

	static function format($format, $text){
		if($format == 'money'){
			return number_format($text, 2).' Kč';}
		if($format == 'address'){
			return str_replace(', ', "\n", $text);}}}

/*
reserved words (words that could cause incompatibility with system strings):

delete
ajax
section
page

*/
