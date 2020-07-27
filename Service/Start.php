<?php

namespace Service;

session_start();

include(dirname(__DIR__).'/Configuration/Main.php');


//the filter passes to Router all elements of the URI that are not part of MAIN_URL
function Start(){
		new Router(array_values(array_filter(explode('/', $_SERVER['REQUEST_URI']), function($folder){
			return $folder && !in_array($folder, array_filter(explode('/', \Configuration\MAIN_URL)), true);})));}

\Service\Start();
