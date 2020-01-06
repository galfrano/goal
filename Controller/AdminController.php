<?php

namespace Controller;

class AdminController extends CrudController2{
	protected static $entities = ['categories', 'users'];
//	protected static $children = ['categories'=>['customers']];

	function __construct($table, $path){
		$this->isAvailable($table);
		parent::__construct($table, $path);}

	function isAvailable($table){
		if(!in_array($table, self::$entities, true)){
			throw new \Exception('No such entity available for Admin');}}
}
