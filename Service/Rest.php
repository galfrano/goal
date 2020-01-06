<?php

namespace Service;

interface RestInterface{
	function get($id);
	function getList();
	function post($data);
	function put($data, $id);
	function delete($id);
}

class Rest implements RestInterface{
	function __construct(){
		$this->readServerRequest();
		$this->route();}
	
	
}
