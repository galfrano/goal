<?php
namespace View;
use \Xml\Loader;
use \Xml\Tag;
use \Service\User;
use \Service\Path;
abstract class AbstractView implements ViewInterface{

	protected $html, $doctype, $dom;
	protected static $languages = ['en', 'es', 'cs'];

	public function __construct($tpl = false){
		is_string($tpl) ?: $tpl = \Configuration\TPL;
		list($this->doctype, $this->html) = Loader::tags(file_get_contents(__DIR__.'/html/'.$tpl.'.html'));
		$this->setAssetsPath();
		($controllers = User::getUserMenu()) && $this->menuBar($controllers);
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
	protected static function isActive($k, $v){
		return Path::getParam($k) === $v ? ['class'=>'active'] : [] ;
	}
//TODO: consider making public
	private function menuBar($controllers){
		$bar = new Tag(['div', 'class'=>'topbar']);
		$menu = $bar->div();
		$select = $bar->div()->form(['method'=>'post', 'id'=>'changeLanguage'])->select(['name'=>'changeUserLanguage', 'id'=>'chlang', 'class'=>'']);
		$select->option()->say('change_language');
		array_walk(self::$languages, function($lang)use($select){
			$select->option(['value'=>$lang])->say($lang);
		});
		$bar->div()->form(['method'=>'post'])->button(['name'=>'logout', 'value'=>1, 'class'=>'btn btn-warning'])->say('logout');
		for($x = 0, $c = count($controllers); $x<$c; $menu->div()->a(['href'=>\Configuration\MAIN_URL.'/'.$controllers[$x]]+self::isActive('controller', $controllers[$x]))->say($controllers[$x++]));
		$this->html->get('body')->text($bar);
	}
//TODO
	public function toolBar($forms){
	}
	public function subMenuBar($sections){
		$bar = $this->html->get('body')->div(['class'=>'sidebar']);
		$baseUrl = \Configuration\MAIN_URL.'\\'.Path::getParam('controller').'/';
		for($x = 0, $c = count($sections); $x<$c; $bar->div()->a(['href'=>$baseUrl.$sections[$x]]+self::isActive('section', $sections[$x]))->say($sections[$x++]));
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
	protected static function pager($pager /*Tag*/, $pages, $page){
		for($p = 1; $p <= $pages; $pager->a(['class'=>'btn btn-xs btn-'.($page == $p ? 'default active' : 'primary'), 'href'=>Path::getUrl(['page'], [$p])])->text($p++));
	}
	public function load($data){
		foreach($data as $id=>$pop){
			$tag = $this->html->get(['id'=>$id]);
			!empty($tag) or die('ID '.$id.' not found');
			is_array(current($pop)) ? $tag->iterate($pop) : $tag->populate($pop);
		}
	}
}
