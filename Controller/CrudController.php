<?php

namespace Controller;
use Model\Entity;
use View\CrudView as View;

//TODO: add security!!!!
/*abstract */class CrudController{

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
/*		elseif(!empty($_GET['ajax'])){
			$this->ajax($_GET['ajax'], $_GET['']);}*/
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

/*	function ajax($child, $id = null){
		$childEntity = new Entity($child);
		echo $this->view->childForm($this->entity, $childEntity, is_null($id)?:$childEntity->get($id));}*/}
