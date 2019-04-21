<?php
namespace View;
use \Xml\Tag;
class CrudView extends AbstractView{

	public $session;

	function __construct(){
		parent::__construct();
		$this->menuBar();}

	function showList($entity, $page = 1, $search = []){
		$div = $this->html->get('body')->div(['class'=>'wrapper']);
		$div->div()->a(['href'=>self::getUrl().'id=0', 'class'=>'btn btn-success'])->say('new');
		$table = $div->table(['class'=>'table table-striped']);
		$row0 = $table->tr();
		$catalogs = $entity->getParents();
		foreach($entity->fields as $column){
			$row0->th()->say($column);}
		$row0->th()->say('action');
		foreach($entity->getList($page, $search) as $line){
			$row = $table->tr();
			foreach($line as $col => $value){
				$row->td()->text(key_exists($col, $catalogs) ? $catalogs[$col][$value] : $value);}
			$td = $row->td();
			$td->a(['href'=>self::getUrl().'id='.$line[$entity->pk], 'class'=>'btn btn-info btn-xs'])->say('edit');
			$td->a(['href'=>self::getUrl().'delete='.$line[$entity->pk], 'class'=>'btn btn-danger btn-xs'])->say('delete');}
		$pager = $div->div(['class'=>'pager']);
		for($p = 1, $pages = $entity->pages(); $p <= $pages; $pager->a(['class'=>'btn btn-xs btn-'.($page == $p ? 'default active' : 'primary'), 'href'=>self::getUrl('page').'page='.$p])->text($p++));
		return $this;}

	function childForm($entity, $child, $id = false){
		$children = $entity->schema['children'];
		$omit = $children[$child->tn][$entity->pk];
		$table = new Tag(['table', 'class'=>'table table-bordered', 'id'=>'child-table']);
		$tr0 = $table->tr();
		$tr0->th()->span(['class'=>'btn btn-xs btn-success', 'id'=>'plus'])->text('+');
		$tr1 = Tag::getById('extra')->table()->tr(['id'=>'def-row']);
		$types = $child->types;
		$catalogs = $child->getParents();
		foreach($child->fields as $th){
			if($omit == $th){
				$tr1->td()->span(['class'=>'btn btn-xs btn-danger minus'])->text('-');}
			else{
				$tr0->th()->say($th);
				if(!key_exists($th, $catalogs)){
					$attributes = ['name'=>static::camel($child->tn).'[]['.$th.']', 'class'=>'form-control']+$this->typeAttributes($types[$th])+($th == $entity->pk ? ['disabled'=>1] : []);
					$tr1->td()->input($attributes);}
				else{
					$select = $tr1->td()->select(['name'=>static::camel($child->tn).'[]['.$th.']']);
					foreach($catalogs[$th] as $value=>$name){
						$select->option(['value'=>$value])->text($name);}}}}
		if($id){
			$childRecords = $child->getList(false, [$children[$child->tn][$entity->pk]=>$id]);
			foreach($childRecords as $i => $record){
				$clone = clone(Tag::getById('def-row'));
				for($a = array_merge($clone->select, $clone->input, $clone->textarea), $l = count($a), $x = 0; $x<$l; $a[$x++]->indexName($i));
				$table->text($clone);}}
		return $table;}

	function showUpdateForm($entity, $id, $callback = false){
		$form = $this->form($entity);
		$table = $form->table[0];
		//!is_callable($callback) ?: $callback($form) ;
		$children = \Config::children($entity->tn);
//		$form->div[1]->a(['href'=>\Helper\View::getUrl().'action=printInvoice', 'class'=>'btn btn-warning btn-sm print'])->say('print');
		foreach($children as $name => $child){
			$tr = $table->tr();
			$tr->th()->say($name);
			$tr->td()->text($this->childForm($entity, $entity->child($child), $id));}
		$this->fillForm($entity->get($id, $children));//TODO:
		$table->tr()->th(['colspan'=>2, 'class'=>'button-cell'])->button(['class'=>'btn btn-info'])->say('update');
		$this->html->get('body')->text($form);
		return $this;}

	function showCreateForm($entity, $callback = false){//TODO: review need for callback
		$form = $this->form($entity);
		$table = $form->table[0];
		$children = \Config::children($entity->tn);//var_dump($children);die;
		foreach($children as $name => $child){
			$tr = $table->tr();
				$tr->th()->say($name);
				$tr->td()->text($this->childForm($entity, $entity->child($child)));}
//		!is_callable($callback) ?: $callback($form) ;
		$table->tr()->th(['colspan'=>2, 'class'=>'button-cell'])->button(['class'=>'btn btn-success'])->say('create');
		$this->html->get('body')->text($form);
		return $this;}

	function showLoginForm($callback = false){
		$head = ['email', 'password'];
		$form = $this->form($head);
		$table = $form->table[0];
		is_callable($callback) ?: $callback($form) ;
		$table->tr()->th(['colspan'=>2, 'class'=>'button-cell'])->button(['class'=>'btn btn-primary'])->say('login');
		$this->html->get('body')->text($form);
		return $this;}

	protected function fillForm($data){//var_dump($data); die;
		foreach($data as $name=>$value){
			if($element = Tag::getByName($name)){
				$element->val($value);}}}

	//TODO: include data types
	protected function form($entity){
		$div = new Tag(['div', 'class'=>'form-wrapper']);
		$div->div(['id'=>'actions'])->a(['href'=>self::getUrl('id'), 'class'=>'btn btn-primary'])->say('back');
		$div->div[0]->div(['id'=>'extra']);
		$form = $div->form(['method'=>'post']);
		$table = $form->table(['class'=>'table table-bordered']);
		$catalogs = $entity->getParents();
		$types = $entity->types;
		foreach($entity->fields as $th){
			$tr = $table->tr();
			$tr->th()->say($th);
			if(!key_exists($th, $catalogs)){
				$tr->td()->input(['name'=>$th, 'class'=>'form-control']+$this->typeAttributes($types[$th])+($th == $entity->pk ? ['disabled'=>1] : []), true);}
			else{
				$select = $tr->td()->select(['name'=>$th]);
				foreach($catalogs[$th] as $id=>$name){
					$select->option(['value'=>$id])->text($name);}}}
		return $div;}

	protected static function camel($field){
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));}

	protected function typeAttributes($type){//remove styles
		if(substr($type, 0, 3) == 'int'){
			for($digits = str_replace(['int(', ')', ' unsigned'], '', $type), $max = 1; $digits--; $max *= 10);
			$attributes = ['type'=>'number', 'min'=>0, 'max'=>$max-1];}
		else{
			$attributes = $type == 'date' ? ['type'=>'date'] : ['type'=>'text'] ;}
		return $attributes;}}
