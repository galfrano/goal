<?php
echo password_hash('dario8110', PASSWORD_DEFAULT);
die("\n");

include(__DIR__.'/Configuration/Constants.php');
include(__DIR__.'/Model/Pdoh.php');
include(__DIR__.'/Model/Schema.php');
class FileManager{

	static function delete($dirName){
		for($dir = opendir($dirName); $file = readdir($dir); is_file($name = $dirName.'/'.$file) ? unlink($name) : (in_array($file, ['.', '..']) ?: self::delete($name)));
		rmdir($dirName);}}


if(!empty($argv[1])){
	FileManager::delete('./Model/'.\Configuration\D);
	new Model\Schema(new Model\Pdoh());}
