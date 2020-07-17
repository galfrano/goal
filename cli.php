<?php
//only usage for now is php cli.php schema and php cli.php pass somePassword

include(__DIR__.'/Configuration/Constants.php');
include(__DIR__.'/Model/Pdoh.php');
include(__DIR__.'/Model/Schema.php');
class FileManager{

	static function delete($dirName){
		for($dir = opendir($dirName); $file = readdir($dir); is_file($name = $dirName.'/'.$file) ? unlink($name) : (in_array($file, ['.', '..']) ?: self::delete($name)));
		rmdir($dirName);
	}
}

class Cli{

	static function i($args){
		$fx = !empty($args[1]) && method_exists('Cli', $args[1]) ? $args[1] : 'help';
		static::$fx($args);
	}
	static function help(){
		echo 'Usage:'."\n".'php cli.php [schema, pass, load]'."\n";
	}
	static function schema(){
		$schemaDir = './Model/'.\Configuration\D;
		!is_dir($schemaDir) ?: FileManager::delete($schemaDir);
		new Model\Schema(new Model\Pdoh());
	}
	static function pass($args){
		if(!empty($args[2])){
			echo password_hash($args[2], PASSWORD_DEFAULT)."\n";
		}
	}
	static function load(){
		echo 'sudo mysqldump -u '.\Configuration\U.' -p\''.\Configuration\D.'\' ';
	}
}


Cli::i($argv);
