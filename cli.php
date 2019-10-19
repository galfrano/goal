<?php

include('config.php');
class FileManager{

	static function delete($dirName){
		for($dir = opendir($dirName); $file = readdir($dir); is_file($name = $dirName.'/'.$file) ? unlink($name) : (in_array($file, ['.', '..']) ?: self::delete($name)));
		rmdir($dirName);}}


if(!empty($argv[1])){
	FileManager::delete('./Model/'.\Config::D);}
