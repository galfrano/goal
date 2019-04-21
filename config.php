<?php

class Config{
	const
//--------------------------------------------------DB--------------------------------------------------\\
/**/
//remote constants were here, local constants below
/** /
D = 'octo',
H = 'localhost',
U = 'root',
P = '',
/**/
//--------------------------------------------------OTHER--------------------------------------------------\\
LANG = 'es',
TPL = 'tpl.html';
	static
$sections = ['customers', 'products', 'invoices'],
//$sections = ['products', 'cities', 'city_products', 'vaccines'],
$special = ['children'=>['invoices'=>['products'=>'invoice_products']], 'actions'=>['invoices'=>[1=>['print'=>'&action=printInvoice']]]];
	static function children($parent){
		return empty(self::$special['children'][$parent]) ? [] : self::$special['children'][$parent] ;}

	static function format($format, $text){
		if($format == 'money'){
			return number_format($text, 2).' Kč';}
		if($format == 'address'){
			return str_replace(', ', "\n", $text);}}
		
}

/*
reserved words (words that could cause incompatibility with system strings):

delete
ajax
section
page

*/
