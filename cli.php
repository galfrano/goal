<?php
//only usage for now is php cli.php schema and php cli.php pass somePassword

include(__DIR__.'/Configuration/Constants.php');
include(__DIR__.'/Model/Pdoh.php');
include(__DIR__.'/Model/Schema.php');
class FileManager{

	static function delete($dirName){
		for($dir = opendir($dirName); $file = readdir($dir); is_file($name = $dirName.'/'.$file) ? unlink($name) : (in_array($file, ['.', '..']) ?: self::delete($name)));
		rmdir($dirName);}}


if(!empty($argv[1])){
	if($argv[1] === 'schema'){
		$schemaDir = './Model/'.\Configuration\D;
		!is_dir($schemaDir) ?: FileManager::delete($schemaDir);
		new Model\Schema(new Model\Pdoh());}
	elseif(!empty($argv[2]) && $argv[1] === 'pass'){
		echo password_hash($argv[2], PASSWORD_DEFAULT)."\n";}}
