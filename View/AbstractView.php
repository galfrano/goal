<?php
namespace View;
use \Xml\Loader;
use \Xml\Tag;
abstract class AbstractView implements ViewInterface{

	protected $html, $doctype, $dom;
	protected static $urlMap;
	public function __construct($tpl = false){
		is_string($tpl) ?: $tpl = \Configuration\TPL;
		list($this->doctype, $this->html) = Loader::tags(file_get_contents(\APP_PATH.'/View/html/'.$tpl.'.html'));
		$this->setAssetsPath();}

	protected static function template($template){
		return current(Loader::tags(file_get_contents(\APP_PATH.'/View/html/'.$template.'.html')));}

	public function output(){
		echo $this->doctype, $this->html;}

	protected function setAssetsPath(){
		$head = $this->html->get('head');
		foreach($head->find('link') as &$link){
			$link->href = \MAIN_URL.$link->href;}
		foreach($head->find('script') as &$script){
			$script->src = \MAIN_URL.$script->src;}}

	public static function getUrl($paths = [], $replace = []){
		$remove = array_filter(explode('/', \MAIN_URL));
		$parts = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI']), function($part) use($remove){
			return !empty($part) && !in_array($part, $remove, true);}));
		foreach($paths as $key => $path){
			if(!empty($path) && !empty($i = static::$urlMap[$path])){
				$parts[$i] = $replace[$key];}}
		return \MAIN_URL.'/'.implode('/', array_filter($parts)).'/';}

	protected function menuBar($sections = [], $path = ''){
		$bar = new Tag(['div', 'class'=>'topbar']);
		$menu = $bar->div();
		$bar->div()->form(['method'=>'post'])->button(['name'=>'logout', 'value'=>1, 'class'=>'btn btn-warning'])->say('logout');
		for($x = 0, $c = count($sections); $x<$c; $menu->a(['href'=>\MAIN_URL.$path.$sections[$x].'/1'])->say($sections[$x++]));
		$this->html->get('body')->text($bar);}

	public function addOn($id, $buttons){
		$tag = $this->html->get(['id'=>$id]);
		foreach($buttons as $name=>$button){
			$tag->a(['href'=>self::getUrl().'action='.$button, 'class'=>'btn btn-success'])->say($name);}
		return $this;}

	protected function tableize($data){
		$head = array_keys(current($data));
		$table = new Tag(['table', 'class'=>'table table-striped']);
		$thr = $table->tr();
		foreach($head as $th){
			$thr->th()->say($th);}
		foreach($data as $row){
			$tdr = $table->tr();
			foreach($row as $td){
				$tdr->td()->text($td);}}
		return $table;}

	public function load($data){
		foreach($data as $id=>$pop){
			$tag = $this->html->get(['id'=>$id]);
			!empty($tag) or die('ID '.$id.' not found');
			is_array(current($pop)) ? $tag->iterate($pop) : $tag->populate($pop);}}}
