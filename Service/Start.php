<?php

namespace Service;
//for($dir = opendir('.'); $f = readdir($dir); print($f."\n"));
session_start();

include(dirname(__DIR__).'/Configuration/Main.php');

function Start(){
		new Router(array_values(array_filter(explode('/', $_SERVER['REQUEST_URI']), function($folder){
			return $folder && !in_array($folder, array_filter(explode('/', \MAIN_URL)), true);})));}
