<?php
/*
*** main create-read-update-delete functionality will derive from here ***

TODO: for now only interface is getSubMenu since this data will be contained in the inheriting classes;

TODO: review if this is the right place for security
post-get api:
g show list
g show create form
p create action -> route to last
g show edit from
p update action -> keep current page
p delete action
*/

namespace Controller;
use \Model\Entity;
use \View\CrudView;
use \Service\Path;

abstract class CrudController implements Routeable{

	protected static $actions = ['edit', 'delete', 'new'], $children = [], $sections = [], $path;
	protected $table, $action, $entity, $view, $childrenList = [], $filter = [];

	function __construct(){
		//FIXME: add security
		!empty($this->entity) || $this->entity = new Entity($this->table = Path::getParam('section'));
		key_exists($this->table, static::$children) && $this->childrenList = static::$children[$this->table];
		!empty($this->view) || $this->view = new CrudView();
		!empty($sections = static::getSubMenu()) && $this->view->subMenuBar($sections);
		method_exists($this, 'preRoute') && $this->preRoute();
		!empty($_POST) ? $this->post($_POST) : $this->get();
	}
	protected function get(){
		if(!Path::getParam('action')){
			$this->view->showList($this->entity, Path::getParam('page'), $this->filter)->output();
		}
		elseif(Path::getParam('action') === 'new'){
			$this->view->showCreateForm($this->entity, $this->childrenList)->output();
		}
		elseif(Path::getParam('action') === 'edit' && Path::getParam('id')){
			$this->view->showUpdateForm($this->entity, Path::getParam('id'), $this->childrenList)->output();
		}
	}
	// handles delete, new, update
	protected function post($post){
		if(Path::getParam('action') === 'new'){
			$this->entity->add($post);
		}
		elseif(Path::getParam('action') === 'edit' && Path::getParam('id')){
			$this->entity->edit($post, Path::getParam('id'));
		}
		elseif(Path::getParam('action') === 'delete' && Path::getParam('id')){
			$this->entity->delete(Path::getParam('id'));
		}
		header('location: '.Path::getUrl(['action', 'id'], ['', '']));
	}
}
