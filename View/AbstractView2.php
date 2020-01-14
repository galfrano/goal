<?php
namespace View;
use \Xml\Loader;
use \Xml\Tag;
abstract class AbstractView2 implements ViewInterface{

	protected $html, $doctype, $dom;
	protected static $urlMap;
	public function __construct($tpl = false){
		is_string($tpl) ?: $tpl = \Configuration\TPL;
		list($this->doctype, $this->html) = Loader::tags(file_get_contents(\Configuration\PATH.'/View/html/'.$tpl.'.html'));}

	protected static function template($template){
		return current(Loader::tags(file_get_contents(\Configuration\PATH.'/View/html/'.$template.'.html')));}

	public function output(){
		echo $this->doctype, $this->html;}

	public static function getUrl($paths = [], $replace = []){
		$parts = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
		foreach($paths as $key => $path){
			if(!empty($path) && !empty($i = static::$urlMap[$path])){
				$parts[$i] = $replace[$key];}}
		return '/'.implode('/', array_filter($parts)).'/';}

	protected function menuBar($sections = [], $path = '/'){
		$bar = new Tag(['div', 'class'=>'topbar']);
		$menu = $bar->div();
		for($x = 0, $c = count($sections); $x<$c; $menu->a(['href'=>$path.$sections[$x].'/1'])->say($sections[$x++]));
		$this->html->get('body')->text($bar);}

	public function addOn($id, $buttons){
		$tag = $this->html->get(['id'=>$id]);
		foreach($buttons as $name=>$button){
			$tag->a(['href'=>self::getUrl().'action='.$button, 'class'=>'btn btn-success'])->say($name);}
		return $this;}

	public function load($data){
		foreach($data as $id=>$pop){
			$tag = $this->html->get(['id'=>$id]);
			!empty($tag) or die('ID '.$id.' not found');
			is_array(current($pop)) ? $tag->iterate($pop) : $tag->populate($pop);}}}
