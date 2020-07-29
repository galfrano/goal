<?php

namespace Model;

class Journaled extends Entity{

	protected $created, $modified, $createdBy, $modifiedBy, $inactive, $group;

	//public $schema, $tn, $rpp = 20, $columns = '*';

	function __construct($table){
		parent::__construct($table);
		$this->inactive = array_search('tinyint(1)', $this->schema['columns']);
		list($this->createrBy, $this->modifiedBy) = array_keys(array_filter($this->schema['parents'], function($parent){
			return key_exists(Configuration\USER['usersTable'], $parent);
		}));
		list($this->created, $this->modified) = array_keys($this->schema['columms'], 'timestamp');
	}
/*
	function getList($page = 1, $filters = [], $callback = false){
		$this->inactive && $filters[$this->inactive] = 0;
		return parent::getList($page, $filters, $callback);
	}
	function getInactiveList($page = 1, $filters = [], $callback = false){
		$this->inactive && $filters[$this->inactive] = 1;
		return parent::getList($page, $filters, $callback);
	}
	function getListByUser($id){
		$this->createrBy && $filters[$this->createrBy] = $id;
		return parent::getList($page, $filters, $callback);
	}
*/
//	function getByUser
	function get($id, $children = [], $default = true){
		$this->columns = '*';
		parent::get($id, $children, $default);
	}

	protected function handleFields($post){
		
		return $post;
	}
	function add($post){
		parent::add(handleFiels($post));
	}
	function edit($post, $id){
		parent::edit(handleFiels($post), $id);
	}
	function delete($id){
		empty($this->inactive) ? parent::delete($id) : parent::edit([$this->inactive=>1], $id);
	}
}

