<?php
namespace View;
use \Xml\Loader;
use \Xml\Tag;
abstract class AbstractView implements ViewInterface{

	protected $html, $doctype, $dom;

	public function __construct($tpl = false){
		is_string($tpl) ?: $tpl = \Configuration\TPL;
		list($this->doctype, $this->html) = Loader::tags(file_get_contents(\Configuration\PATH.'/View/html/'.$tpl.'.html'));}

	protected static function template($template){
		return current(Loader::tags(file_get_contents(\Configuration\PATH.'/View/html/'.$template.'.html')));}

	public function output(){
		echo $this->doctype, $this->html;}

	public static function getUrl($remove = false){
		$get = '?';
		foreach($_GET as $k => $v){
			$remove == $k ?: $get .= $k.'='.$v.'&';}
		return $get;}

	protected function menuBar(){
		$sections = [];
		$bar = new Tag(['div', 'class'=>'topbar']);
		$menu = $bar->div();
		for($x = 0, $c = count($sections); $x<$c; $menu->a(['href'=>'?section='.$sections[$x]])->say($sections[$x++]));
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
