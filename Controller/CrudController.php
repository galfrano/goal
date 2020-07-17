<?php
/*
*** main create-read-update-delete functionality will derive from here ***

TODO: foronly interface is getMenu since this data will be contained in the inheriting classes;
	it will take a table and an array of options. actions inside the controller will be handled until the final output.

TODO: review if this is the right place for security

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

interface Routeable{
	function getMenu()/*: array*/;
}

abstract class CrudController implements Routeable{

	protected static $actions = ['edit', 'delete', 'new'], $children = [], $sections = [], $path;
	protected $table, $page, $action, $id, $entity, $view, $childrenList = [], $filter = [];

	function __construct($table, $path = []){
		!empty($this->entity) || $this->entity = new Entity($this->table = $table);
		key_exists($table, static::$children) && $this->childrenList = static::$children[$table];
		!empty($this->view) || $this->view = new CrudView(static::$sections, static::$path);
		$this->setPath($path);
		!empty($_POST) ? $this->post($_POST) : $this->get();
	}

	protected function setPath($path){
		$this->page = !empty($path[0]) && is_numeric($path[0]) ? $path[0] : 1;
		$this->action = !empty($path[1]) && in_array($path[1], static::$actions, true) ? $path[1] : false;
		$this->id = !empty($path[2]) && is_numeric($path[2]) ? $path[2] : false;
	}
	/*protected function filter($section, $filters){ //TODO: review wtf
		$this->filter =  key_exists($section, $filters) ? $filters[$section] : [];
	}*/
	// handles view list, view edit, view new
	// for now, if we have an id with an empty action we will not handle it.
	protected function get(){
		if(!$this->action){
			$this->view->showList($this->entity, $this->page, $this->filter)->output();
		}
		elseif($this->action === 'new'){
			$this->view->showCreateForm($this->entity, $this->childrenList)->output();
		}
		elseif($this->action === 'edit' && $this->id){
			$this->view->showUpdateForm($this->entity, $this->id, $this->childrenList)->output();
		}
	}
	// handles delete, new, update
	protected function post($post){
		if($this->action === 'new'){
			$this->entity->add($post);
		}
		elseif($this->action === 'edit' && $this->id){
			$this->entity->edit($post, $this->id);
		}
		elseif($this->action === 'delete' && $this->id){
			$this->entity->delete($this->id);
		}
		header('location: '.CrudView::getUrl(['action', 'id'], ['', '']));
	}
//	public function getME
}
