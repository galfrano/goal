<?php

namespace Configuration;

function format($format, $text){
	if($format == 'money'){
		return number_format($text, 2).' Kč';}
	if($format == 'address'){
		return str_replace(', ', "\n", $text);}}
