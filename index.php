<?php
session_start();
include('config.php');
class FileManager{

	static function delete($dirName){
		for($dir = opendir($dirName); $file = readdir($dir); is_file($name = $dirName.'/'.$file) ? unlink($name) : (in_array($file, ['.', '..']) ?: self::delete($name)));
		rmdir($dirName);}}
//&purgeDatabase=true
if(!empty($_GET['purgeDatabase'])){
	FileManager::delete('./Model/'.\Config::D);}


spl_autoload_register(function($className){
	$path = __DIR__.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
	if(is_file($path)){
		include_once($path);}
	elseif(!is_bool(strpos($className, 'Controller'))){
		include_once(__DIR__.DIRECTORY_SEPARATOR.'Controller/CrudController.php');
		class_alias('Controller\CrudController', $className);}});

//new Controller\LoginController;
/**/
$s = (empty($_GET['section']) ? Config::$sections[0] : $_GET['section']);
$controller = 'Controller\\'.str_replace(' ', '', ucwords(str_replace('_', ' ', $s))).'Controller';
new $controller(strtolower($s));
/** /

print((new Helper\Xml(file_get_contents('tpl.html')))->tags[0]);

/** /
function dump($table, $rows = 100){
	$entity = new \Model\Entity($table);
	$types = $entity->types;
	for($x = 1; $tokens = [], $x<=$rows; $x++){
		foreach($types as $col => $type){
			$parts = explode('(', $type);
			$parts[0] == 'int' ?: $tokens[$col] = $col.'-'.$x;}
		$entity->add($tokens);}}
dump('customers');
/** /
use Helper\Xml;
$xml = new Xml(file_get_contents('tpl.html'));
echo $xml->tags[0], $xml->tags[1];
/** /
$html = new Helper\Html('!doctype');
$lala = ' lala';
$html->extra($lala);
echo $html;
/**/
