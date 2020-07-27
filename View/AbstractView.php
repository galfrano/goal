<?php
namespace View;
use \Xml\Loader;
use \Xml\Tag;
use \Service\User;
use \Service\Path;
abstract class AbstractView implements ViewInterface{

	protected $html, $doctype, $dom;
	protected static $urlMap;

	public function __construct($tpl = false){
		is_string($tpl) ?: $tpl = \Configuration\TPL;
		list($this->doctype, $this->html) = Loader::tags(file_get_contents(__DIR__.'/html/'.$tpl.'.html'));
		$this->setAssetsPath();
		$this->menuBar(User::getUserMenu());
	}
	protected static function template($template){
		return current(Loader::tags(file_get_contents(__DIR__.'/html/'.$template.'.html')));
	}
	public function output(){
		echo $this->doctype, $this->html;
	}
	protected function setAssetsPath(){
		$head = $this->html->get('head');
		foreach($head->find('link') as &$link){
			$link->href = \Configuration\MAIN_URL.$link->href;
		}
		foreach($head->find('script') as &$script){
			$script->src = \Configuration\MAIN_URL.$script->src;
		}
	}
	private function menuBar($controllers){
		$bar = new Tag(['div', 'class'=>'topbar']);
		$menu = $bar->div();
		$controllers && $bar->div()->form(['method'=>'post'])->button(['name'=>'logout', 'value'=>1, 'class'=>'btn btn-warning'])->say('logout');
		for($x = 0, $c = count($controllers); $x<$c; $menu->a(['href'=>\Configuration\MAIN_URL.'/'.$controllers[$x]])->say($controllers[$x++]));
		$this->html->get('body')->text($bar);
	}
	public function subMenuBar($sections){
		$bar = $this->html->get('body')->div(['class'=>'sidebar']);
		for($x = 0, $c = count($sections); $x<$c; $bar->div()->a(['href'=>\Configuration\MAIN_URL.'\\'.Path::getParam('controller').'/'.$sections[$x]])->say($sections[$x++]));
	}
	public function addOn($id, $buttons){
		$tag = $this->html->get(['id'=>$id]);
		foreach($buttons as $name=>$button){
			$tag->a(['href'=>Path::getUrl().'action='.$button, 'class'=>'btn btn-success'])->say($name);
		}
		return $this;
	}
	protected function tableize($data){
		$head = array_keys(current($data));
		$table = new Tag(['table', 'class'=>'table table-striped']);
		$thr = $table->tr();
		foreach($head as $th){
			$thr->th()->say($th);
		}
		foreach($data as $row){
			$tdr = $table->tr();
			foreach($row as $td){
				$tdr->td()->text($td);
			}
		}
		return $table;
	}
	public function load($data){
		foreach($data as $id=>$pop){
			$tag = $this->html->get(['id'=>$id]);
			!empty($tag) or die('ID '.$id.' not found');
			is_array(current($pop)) ? $tag->iterate($pop) : $tag->populate($pop);
		}
	}
}
