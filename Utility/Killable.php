<?php

namespace Utility;

trait Killable{

	static function kill($errorMessage){
		throw new Exception(get_class(self).': '.$errorMessage);
	}
	static function promise($try, $success, $recover){
	}
}
