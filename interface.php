<?php

interface HtmlElement{
	function __construct(/*string|array */$tag);
//	function append(string|array|HtmlElement $tag):HtmlElement;
	function __call($tag, $parameters);
}

class Html{

	protected $tag, $attributes, $text = '';

	protected $children = [];

	private static $single = ['img', 'meta'], $tab = 0, $ids = [];

	function __construct($tag){
		list($this->tag, $this->attributes) =  is_array($tag) ? [array_shift($tag), $tag] : [$tag, []] ;
		empty($tag['id']) ?: static::$ids[$tag['id']] = $this;
/*		$*/}


	function __get($tag){
		return $this->find($tag);}

	function find($tag, &$result = []){
		$this->tag != $tag ?: $result[] = $this;
		foreach($this->children as $child){
			!is_object($child) ?: $child->find($tag, $result);}
		return $result;}

	function tab(){
		return str_repeat("\t", self::$tab);}

	function __toString(){
		$string = '<'.$this->tag.$this->attr();
		if(in_array($this->tag, self::$single)){
			$string .= ' />';}
		else{
			$string .= '>'.$this->children().'</'.$this->tag.'>';}
		return $this->tab().$string;}

	function __set($attribute, $value){
		$this->attributes[$attribute] = $value;}

	function children(){
		list($nl, $string) = [false, ''];
		self::$tab++;
		foreach($this->children as $child){
			if(is_object($child)){
				$nl ?: $nl = true;
				$string .= "\n".(string) $child;}
			else{
				$string .= $child;}}
		self::$tab--;
		empty($this->children[0]) || !is_object(end($this->children)) ?: $string .= $this->tab();
		return $string.($nl ? "\n".$this->tab() : '');}

	private function attr(){
		$attr = '';
		foreach($this->attributes as $name => $value){
			$attr .= ' '.$name.'="'.$value.'"';}
		return $attr;}

	function getById($id){
		return self::$ids[$id];}

	function child($tag){
		return $this->children[] = new static($tag);}

	function text($text){
		$this->children[] = $text;
		return $this;}

/*	function __call($tag, $params = []){
		array_unshift($params, $tag);
		return $this->child($params);}*/
}

$one = new Html('html');
$one->child(['head', 'fd'=>'fasd', 'fas'=>'dsAf', 'id'=>'head']);
$one->child(['body'])->child('div');
$one->getById('head')->child(['meta', 'charset'=>'utf-8']);
$one->div[0]->child('p')->text('lala')->child('span');//->text('fcsada');
$one->p[0]->text('text');
//echo '<!doctype html>'."\n";
$one->div[0]->child('p');
//echo $one;


class View/* implements ViewInterface*/{

	protected $tpl, $dom;

	public $session;

	function __construct(){}

	static function getUrl($remove = false){
		if(!empty($remove)){
			unset($_GET[$remove]);}
		$get = '?';
		foreach($_GET as $k => $v){
			$get .= $k.'='.$v.'&';}
		return $get;}

	private function output($html){
		printf($this->tpl, $this->menuBar().$html);}

	private function menuBar(){
		$sections = \Config::$sections;
		$bar = new Html('div', ['class'=>'topbar']);
		$menu = $bar->div;
		for($x = 0, $c = count($sections); $x<$c; $menu->child('a', ['href'=>'?section='.$sections[$x]])->say($sections[$x++]));
		return $bar;}

	function showList($entity){
		$div = new Html('div', ['class'=>'wrapper']);
		$div->div->child('a', ['href'=>self::getUrl().'id=0'])->child('button', ['class'=>'btn btn-success'])->say('new');
		$table = $div->child('table', ['class'=>'table table-striped']);
		$row0 = $table->tr;
		$catalogs = $entity->getParents();
		foreach($entity->fields as $column){
			$row0->th->say($column);}
		$row0->th->say('action');
		foreach($entity->getList() as $line){
			$row = $table->tr;
			foreach($line as $col => $value){
				$row->td->text(key_exists($col, $catalogs) ? $catalogs[$col][$value] : $value);}
			$td = $row->td;
			$td->child('a', ['href'=>self::getUrl().'id='.$line[$entity->pk]])->child('button', ['class'=>'btn btn-info btn-xs'])->say('edit');
			$td->child('a', ['href'=>self::getUrl().'delete='.$line[$entity->pk]])->child('button', ['class'=>'btn btn-danger btn-xs'])->say('delete');}
		$this->output($div);}

	//TODO: include data types
	private function form($entity){
		$div = new Html('div', ['class'=>'form-wrapper']);
		is_null($entity->pk) ?: $div->div->child('a', ['href'=>self::getUrl('id')])->child('button', ['class'=>'btn btn-primary'])->say('back');
		$form = $div->child('form', ['method'=>'post']);
		$table = $form->child('table', ['class'=>'table table-bordered']);
		$catalogs = $entity->getParents();
		$types = $entity->types;
		foreach($entity->fields as $th){
			$tr = $table->tr;
			$tr->th->say($th);
			if(!key_exists($th, $catalogs)){
				$tr->td->child('input', ['name'=>$th, 'class'=>'form-control']+$this->typeAttributes($types[$th])+($th == $entity->pk ? ['disabled'=>1] : []), true);}
			else{
				$select = $tr->td->child('select', ['name'=>$th]);
				foreach($catalogs[$th] as $id=>$name){
					$select->child('option', ['value'=>$id])->text($name);}}}
		return $div;}

	function childForm($entity, $child, $values = []){
		$children = $entity->schema['children'];
		$omit = current($children[$child->tn]);
		$table = new Html('table', ['class'=>'table table-bordered', 'id'=>'child-table']);
		$tr0 = $table->tr;
		$tr0->th->child('span', ['class'=>'btn btn-xs btn-success', 'id'=>'plus'])->text('+');
		$tr1 = $table->child('tr', ['id'=>'def-row']);
		$types = $child->types;
		$catalogs = $child->getParents();
		foreach($child->fields as $th){
			if($omit == $th){
				$tr1->td;}
			else{
				$tr0->th->say($th);
				if(!key_exists($th, $catalogs)){
					$attributes = ['name'=>$child->tn.'['.$th.'][]', 'class'=>'form-control']+$this->typeAttributes($types[$th])+($th == $entity->pk ? ['disabled'=>1] : []);
					!key_exists($th, $values) ?: $attributes['value'] = $values[$th];
					$tr1->td->child('input', $attributes, true);}
				else{
					$select = $tr1->td->child('select', ['name'=>$child->tn.'['.$th.'][]']);
					foreach($catalogs[$th] as $id=>$name){
						$select->child('option', ['value'=>$id])->text($name);}}}}
		return $table;}

	function typeAttributes($type){//remove styles
		if(substr($type, 0, 3) == 'int'){
			for($digits = str_replace(['int(', ')', ' unsigned'], '', $type), $max = 1; $digits--; $max *= 10);
			$attributes = ['type'=>'number', 'min'=>0, 'max'=>$max-1];}
		else{
			$attributes = $type == 'date' ? ['type'=>'date'] : ['type'=>'text'] ;}
		return $attributes;}

	function showUpdateForm($entity, $id, $callback = false){
		$form = $this->form($entity);
		$table = $form->children[1]->children[0];
		$data = array_values($entity->get($id));
		foreach($table->children as $i=>$tr){
			$tr->children[1]->children[0]->value = $data[$i];}
		!is_callable($callback) ?: $callback($form) ;
		$table->tr->child('th', ['colspan'=>2, 'class'=>'button-cell'])->child('button', ['class'=>'btn btn-info'])->text('Update');
		$this->output($form);}

	function showCreateForm($entity, $callback = false){
		$form = $this->form($entity);
		$table = $form->children[1]->children[0];
		!is_callable($callback) ?: $callback($form) ;
		$table->tr->child('th', ['colspan'=>2, 'class'=>'button-cell'])->child('button', ['class'=>'btn btn-success'])->text('Create');
		$this->output($form);}

	function showLoginForm($callback = false){
		$head = ['email', 'password'];
		$form = $this->form($head);
		$table = $form->children[0]->children[0];
		is_callable($callback) ?: $callback($form) ;
		$table->tr->child('th', ['colspan'=>2, 'class'=>'button-cell'])->child('button', ['class'=>'btn btn-primary'])->text('Login');
		$this->output($form);}}

var_dump(ctype_upper('NVOICEPRODUCT'));
