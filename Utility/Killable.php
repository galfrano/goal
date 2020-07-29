<?php

namespace Utility;

trait Killable{

	static function kill($errorMessage){
		throw new \Exception(get_called_class().': '.$errorMessage);
	}
	static function promise($try, $success, $recover){
	}
}
