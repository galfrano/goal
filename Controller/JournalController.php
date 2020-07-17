<?php

namespace Controller;

class JournalController extends CrudController{

	protected static $fields;
	protected static $actions = ['edit', 'delete', 'new', 'inactive'];

	function setFields($fields){
		static::$fields = Configuration\JOURNALED;
	}

	protected function get(){
		if(!empty($this->action) && $this->action === 'inactive'){
			$this->filter([self::$inactive=>'yes']);
			$this->view->showList($this->entity, $this->page, $this->filter)->output();
		}
		else{
			if(empty($this->action)){
				$this->filter([self::$inactive=>'yes']);
			}
			parent::get();
		}
	}
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
}
