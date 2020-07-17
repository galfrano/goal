<?php
namespace View;
use \Xml\Tag;
class CrudView extends AbstractView{

	public $session;

	protected static $urlMap = ['page'=>2, 'action'=>3, 'id'=>4];

	public static $delete = true, $edit = true;

	// FIXME
	function __construct($sections = [], $path = ''){
		parent::__construct();
		$this->menuBar($sections, $path);}

	function showList($entity, $page = 1, $search = []){
		$div = $this->html->get('body')->div(['class'=>'wrapper']);
		$div->div()->a(['href'=>self::getUrl(['action'], ['new']), 'class'=>'btn btn-success'])->say('new');
		$table = $div->table(['class'=>'table table-striped']);
		$row0 = $table->tr();
		$catalogs = $entity->getParents();
		foreach($entity->fields as $column){
			$row0->th()->say($column);}
		$row0->th(['class'=>'action'])->say('action');
		foreach($entity->getList($page, $search) as $line){
			$row = $table->tr();
			foreach($line as $col => $value){
				$row->td()->text(key_exists($col, $catalogs) ? $catalogs[$col][$value] : $value);}
			$td = $row->td(['class'=>'actions']);
			static::$edit && $td->a(['href'=>self::getUrl(['action'], ['edit']).$line[$entity->pk], 'class'=>'btn btn-info btn-xs'])->say('edit');
			static::$delete && $td->form(['method'=>'post', 'action'=>self::getUrl().'delete/'.$line[$entity->pk]])->button(['class'=>'btn btn-danger btn-xs', 'name'=>'delete', 'value'=>1])->say('delete');}
		$pager = $div->div(['class'=>'pager']);
		for($p = 1, $pages = $entity->pages(); $p <= $pages; $pager->a(['class'=>'btn btn-xs btn-'.($page == $p ? 'default active' : 'primary'), 'href'=>self::getUrl(['page'], [$p])])->text($p++));
		return $this;}

	function showUpdateForm($entity, $id, $children = []){
		$form = self::form($entity);
		$table = $form->table[0];
		foreach($children as $child){
			$tr = $table->tr();
			$tr->th()->say($child);
			$tr->td()->text(self::childForm($entity, $entity->child($child), $id));}
		self::fillForm($entity->get($id, $children));
		$table->tr()->th(['colspan'=>2, 'class'=>'button-cell'])->button(['class'=>'btn btn-info'])->say('update');
		$this->html->get('body')->text($form);
		return $this;}

	function showCreateForm($entity, $children = []){
		$form = self::form($entity);
		$table = $form->table[0];
		foreach($children as $child){
			$tr = $table->tr();
				$tr->th()->say($child);
				$tr->td()->text(self::childForm($entity, $entity->child($child)));}
		$table->tr()->th(['colspan'=>2, 'class'=>'button-cell'])->button(['class'=>'btn btn-success'])->say('create');
		$this->html->get('body')->text($form);
		return $this;}

	protected static function childForm($entity, $child, $id = false){
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
					$attributes = ['name'=>static::camel($child->tn).'[]['.$th.']', 'class'=>'form-control']+self::typeAttributes($types[$th])+($th == $entity->pk ? ['disabled'=>1] : []);
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

	//TODO: include more data types
	protected static function form($entity){
		$div = new Tag(['div', 'class'=>'form-wrapper']);
		$div->div(['id'=>'actions'])->a(['href'=>self::getUrl(['action', 'id'], ['', '']), 'class'=>'btn btn-primary'])->say('back');
		$div->div[0]->div(['id'=>'extra']);
		$form = $div->form(['method'=>'post']);
		$table = $form->table(['class'=>'table table-bordered']);
		$catalogs = $entity->getParents();
		$types = $entity->types;
		foreach($entity->fields as $th){
			$tr = $table->tr();
			$tr->th()->say($th);
			if(substr($types[$th], 0, 4) === 'enum'){
				$cell = $tr->td();
				self::radios($cell, $th, $types[$th]);}
			elseif(key_exists($th, $catalogs)){
				$select = $tr->td()->select(['name'=>$th]);
				foreach($catalogs[$th] as $id=>$name){
					$select->option(['value'=>$id])->text($name);}}
			elseif($types[$th] !== 'timestamp'){
				$tr->td()->child(self::field($th, $types[$th])+($th == $entity->pk ? ['disabled'=>1] : []));}}
		return $div;}

	protected static function fillForm($data){
		foreach($data as $name=>$value){
			Tag::setValue($name, $value);}}

	protected static function radios(&$cell, $name, $type){//asumption: enum('val1','val2') //TODO: correct pass by reference
		$enum = explode('\',\'', substr($type, 6, -2));
		foreach($enum as $k => $val){
			$id = $name.'_'.$k;
			$cell->label(['for'=>$id])->say($val);
			$cell->input(['type'=>'radio', 'value'=>$val, 'id'=>$id, 'name'=>$name]);}}

	protected static function camel($field){
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));}

	protected static function field($name, $type){
		$attributes = $type === 'text' ? ['textarea'] : ['input'] ;
		$attributes += ['name'=>$name, 'class'=>'form-control'];
		if(substr($type, 0, 3) == 'int'){
			for($digits = str_replace(['int(', ')', ' unsigned'], '', $type), $max = 1; $digits--; $max *= 10);
			$attributes += ['type'=>'number', 'min'=>0, 'max'=>$max-1];}
		elseif($name === 'email'){
			$attributes += ['type'=>'email'];}
		elseif($type === 'varchar(255)'){
			$attributes += ['type'=>'password'];}
		else{
			$attributes += $type == 'date' ? ['type'=>'date'] : ['type'=>'text'] ;}
		return $attributes;}

	protected static function typeAttributes($type){
		if(substr($type, 0, 3) == 'int'){
			for($digits = str_replace(['int(', ')', ' unsigned'], '', $type), $max = 1; $digits--; $max *= 10);
			$attributes = ['type'=>'number', 'min'=>0, 'max'=>$max-1];}
		elseif($type == 'text'){
			$attributes = [0 =>'textarea'];}
		else{
			$attributes = $type == 'date' ? ['type'=>'date'] : ['type'=>'text'] ;}
		return $attributes;}}
