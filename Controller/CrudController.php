<?php

/*
*** main create-read-update-delete functionality will derive from here ***

TODO: review interface after implementing tests -> whether this is an antipattern is not clear to me yet
	for now there will be no interface other than the constructor
	it will take an table and an array of options. actions inside the controller will be handled internally.
	$entity/$table should NOT be optional, this could cause misusage

TODO: add security!!!!

g show list
g show create form
p create action -> route to last
g show edit from
p update action -> keep current page
p delete action
*/

namespace Controller;
use Model\Entity;
use View\CrudView;

// $path = explode('/', 'crud/users/4/edit/35')
/*
interface Controller{
	function __construct($entity, $options);

}*/

abstract class CrudController{
	protected static $actions = ['edit', 'delete', 'new'], $children = [], $sections = [], $path;
	protected $table;
	protected $page, $action, $id;
	protected $entity, $view, $childrenList = [];

	function __construct($table, $path = []){
		!empty($this->entity) || $this->entity = new Entity($this->table = $table);
		if(key_exists($table, static::$children)){
			$this->childrenList = static::$children[$table];}
		!empty($this->view) || $this->view = new CrudView(static::$sections, static::$path);
		$this->setPath($path);
		!empty($_POST) ? $this->post($_POST) : $this->get();}

	protected function setPath($path){
		$this->page = !empty($path[0]) && is_numeric($path[0]) ? $path[0] : 1;
		$this->action = !empty($path[1]) && in_array($path[1], static::$actions, true) ? $path[1] : false;
		$this->id = !empty($path[2]) && is_numeric($path[2]) ? $path[2] : false;}

	// handles view list, view edit, view new
	// for now, if we have an id with an empty action we will not handle it.
	protected function get(){
		if(!$this->action){
			$this->view->showList($this->entity, $this->page)->output();}
		elseif($this->action === 'new'){
			$this->view->showCreateForm($this->entity, $this->childrenList)->output();}
		elseif($this->action === 'edit' && $this->id){
			$this->view->showUpdateForm($this->entity, $this->id, $this->childrenList)->output();}}

	// handles delete, new, update
	protected function post($post){
		if($this->action === 'new'){
			$this->entity->add($post);}
		elseif($this->action === 'edit' && $this->id){
			$this->entity->edit($post, $this->id);}
		elseif($this->action === 'delete' && $this->id){
			$this->entity->delete($this->id);}
		header('location: '.CrudView::getUrl(['action', 'id'], ['', '']));}
}
