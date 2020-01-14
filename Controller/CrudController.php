<?php

/*
*** main create-read-update-delete functionality will derive from here ***

TODO: review interface after implementing tests -> whether this is an antipattern is not clear to me yet
	for now there will be no interface other than the constructor
	it will take an table and an array of options. actions inside the controller will be handled internally.
	$entity/$table should NOT be optional, this could cause misusage
*/

namespace Controller;
use Model\Entity;
use View\CrudView;

// $path = explode('/', 'crud/users/4/edit/35')
/*
interface Controller{
	function __construct($entity, $options);
}*/

/*abstract*/ class CrudController{
	protected static $actions = ['edit', 'delete', 'new'];
	protected static $children = [];
	protected static $sections = [];
	protected static $path = '/';
	protected $table, $page, $action, $id;
	protected $entity, $view, $childrenList = [];

	function __construct($table, $path = []){
		$this->entity = new Entity($table);
		if(key_exists($table, static::$children)){
			$this->childrenList = static::$children[$table];}
		$this->view = new CrudView(static::$sections, static::$path);
		$this->setPath($path);
		!empty($_POST) ? $this->post($_POST) : $this->get();}

	protected function setPath($path){
//		var_dump($path);die;
		$this->page = !empty($path[0]) && is_numeric($path[0]) ? $path[0] : 1;
		$this->action = !empty($path[1]) && in_array($path[1], self::$actions, true) ? $path[1] : false;
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

/*
g show list
g show create form
p create action -> route to last
g show edit from
p update action -> keep current page
p delete action
*/
//TODO: add security!!!!
/*abstract class CrudController{

	protected $entity, $table, $view, $actions;

	function __construct($table = null){
		!is_null($this->table) ?: $this->table = $table;
		!is_null($this->table) or die('$table is null');
		$this->entity = new Entity($this->table);
		$this->catalogs = $this->entity->getParents();
//		var_dump($this->catalogs); die;
		$this->view = new View;
		$this->route();}

	function route(){
		if(!empty($_GET['action'])){
			$action = $_GET['action'].'Action';
			$this->$action();}
		elseif(!empty($_GET['delete'])){
			$this->entity->delete($_GET['delete']);
			header('location: ./'.View::getUrl('delete'));}
		elseif(!empty($_GET['ajax'])){
			$this->ajax($_GET['ajax'], $_GET['']);}
		elseif(!empty($_POST)){
//			var_dump($_POST); die;
			empty($_GET[$this->entity->pk]) ? $this->entity->add($_POST) : $this->entity->edit($_POST, $_GET[$this->entity->pk]) ;
			header('location: ./'.View::getUrl($this->entity->pk));}
		else{
			isset($_GET[$this->entity->pk]) ? $this->read($_GET[$this->entity->pk]) : $this->readList() ;}}

	//TODO: search, pagination
	function readList(){
		echo $this->view->showList($this->entity, empty($_GET['page']) ? 1 : $_GET['page'])->output();}

	function read($id){
		echo $id == 0 ?
			$this->view->showCreateForm($this->entity)->output() :
			$this->view->showUpdateForm($this->entity, $id)->output() ;}

	function ajax($child, $id = null){
		$childEntity = new Entity($child);
		echo $this->view->childForm($this->entity, $childEntity, is_null($id)?:$childEntity->get($id));}}*/
