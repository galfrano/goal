<?php

namespace Xml;

class Tag{

	protected  $tag, $attributes, $extra = '';

	protected $children = [], $callbacks = ['parent'=>false];

	static $single = ['img', 'meta', 'input', 'link', 'hr', 'br', '!doctype'], $unique = ['html', 'body', 'head'], $tab = 0, $ids = [], $names = [], $tpl = [], $language;

	function __construct($tag, $parent = false){
		!$parent ?: $this->callbacks['parent'] = $parent;
		!is_null(self::$language) ?: self::$language = include \Configuration\LANG;
		list($this->tag, $this->attributes) =  is_array($tag) ? [array_shift($tag), $tag] : [$tag, []] ;
		empty($tag['id']) || isset(static::$ids[$tag['id']]) ?: static::$ids[$tag['id']] = $this;
		empty($tag['name']) || isset(static::$names[$tag['name']]) ?: static::$names[$tag['name']] = $this;}

	function __clone(){
		$children = $this->children;
		$this->children = [];
		unset($this->attributes['id']);
		foreach($children as $child){
			$this->children[] = is_object($child) ? clone $child : $child ;}}

	function indexName($index){
		if(!empty($this->attributes['disabled'])){
			unset($this->attributes['disabled']);
			$this->attributes['type'] = 'hidden';}
		$this->attributes['name'] = str_replace('[]', '['.$index.']', $this->attributes['name']);
		static::$names[$this->attributes['name']] = $this;}

	function __get($tag){
		return $this->find($tag);}

/*	function __unset($name){
		unset($this->attributes[$name]);}*/

	function val($val){
		if($this->tag == 'input'){
			$this->attributes['value'] = $val;}
		elseif($this->tag =='textarea'){
			$this->text($val);}
		elseif($this->tag == 'select'){
			$this->find(['value'=>$val])[0]->selected = true;}}

	function find($tag, &$result = []){//find all matches
		$match = $this->match($tag, $result);
		foreach($this->children as $child){
			!is_object($child) ?: $child->find($tag, $result);}
		return $result;}

	function get($tag, &$result = false){//find first match only
		if($registered = $this->registered($tag)){
			$result = $registered;}
		elseif($this->match($tag, $result)){
			$result = $this;}
		elseif($result === false){
			foreach($this->children as $child){
				!is_object($child) ?: $child->get($tag, $result);}}
		return $result;}

	function registered($tag){
		$registered = false;
		if(is_array($tag)){
			if(key_exists('id', $tag)){
				$registered = self::getById($tag['id']);}
			elseif(key_exists('name', $tag)){
				$registered = self::getByName($tag['name']);}
//			elseif(key_exists('data-tpl', $tag)){
//				empty(self::$tpl[$tag['data-tpl']]) ?: $registered = self::$tpl[$tag['data-tpl']];}
}
		return $registered ;}

	function match($tag, &$result = []){
		if(is_string($tag)){
			$match = $this->tag == $tag;}
		elseif(is_array($tag)){
			$match = true;
			foreach($tag as $attr => $value){
				((isset($this->attributes[$attr]) && $this->attributes[$attr] == $value) || ($attr == 0 && $value == $this->tag)) ?: $match = false ;}}
		!$match || !is_array($result) ?: $result[] = $this;
		return $match;}

	function tab(){
		return str_repeat("\t", self::$tab);}

	function __toString(){
		$string = '<'.$this->tag.$this->attr().$this->extra;
		if(in_array($this->tag, self::$single)){
			$string .= $this->tag == '!doctype' ? '>'."\n" : ' />';}
		else{
			$string .= '>'.$this->children().'</'.$this->tag.'>';}
		return $this->tab().$string;}

	function __set($attribute, $value){
		$this->attributes[$attribute] = $value;
		if(in_array($attribute, ['name', 'id'])){
			$property = $attribute.'s';
			static::$$property[$value] = $this;}}

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
		!$nl ?: $string .= "\n".$this->tab();
		return $string;}

	private function attr(){
		$attr = '';
		foreach($this->attributes as $name => $value){
			$attr .= ' '.$name.'="'.$value.'"';}
		return $attr;}

	static function getById($id){
		return empty(self::$ids[$id]) ? NULL : self::$ids[$id] ;}

	static function getByName($name){
		return empty(self::$names[$name]) ? NULL : self::$names[$name] ;}

	function child($tag){
		return $this->children[] = new static($tag, function(){
			return $this;});}

	function extra(&$extra){
		$this->extra .= $extra;
		$extra = '';
		return $this;}

	function text($text){
		$text === '' ?: $this->children[] = $this->format($text);
		return $this;}

	function format($text){
		return !key_exists('data-format', $this->attributes) ? $text : \Configuration\format($this->attributes['data-format'], $text);}

	function populate($population){//maybe allow method chaining
		foreach($population as $name => $value){
			$tag = $this->get(['data-tpl'=>$name]);
			if($tag){
				$tag->text($value);}}}

	function iterate($pop){//maybe allow method chaining
		for($ch = count($this->children)-1, $c = count($pop), $x = 0; $x+1>=$c ?: $this->children[] = clone(end($this->children)), $x<$c; $this->children[$ch+$x]->populate($pop[$x++]));}

	function say($text){
		$this->children[] = key_exists($text, self::$language) ? self::$language[$text] : ucwords(str_replace('_', ' ', $text)) ;}

	function __call($tag, $params = []){
		if(key_exists($tag, $this->callbacks)){//i.e. parent
			return is_callable($this->callbacks[$tag]) ? $this->callbacks[$tag]($params) : false ;}
		else{
			$params = count($params) ? $params[0] : $params ;//we need the single parameter $tag which is an array
			array_unshift($params, $tag);
			return $this->child($params);}}}
