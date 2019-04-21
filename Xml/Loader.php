<?php
//TODO: consider adding single quote support
namespace Xml;
class Loader{

	public $tags;

	function __construct($str){
		$this->build(str_replace(["\t", "\n"], '', $str));}

	static function tags($str){
		$xml = new self($str);
		return $xml->tags;}

	function build($str){//TODO: re-do this completely
		$this->tags = [];
		$extra = '';
		$current = false;
		$switch = ['tag'=>false, 'attributes'=>false, 'parent'=>false, 'quotes'=>false, 'close'=>false];
		for($l = strlen($str), $tag = [], $t = 0, $x = 0, $text = '';  $x<$l; $x++){
			if(!$switch['tag'] && !$switch['close']){//no tag open
				if($str[$x] != '<' || $str[$x+1] == ' '){//no tag present
					$text .= $str[$x];}
				else{
					$current ? $current->text($text) : (!strlen($text) ?: $this->tags[] = $text);
					$text = '';
					if($str[$x+1] == '/'){//closing
						$switch['close'] = true;}
					else{//opening
						$switch['tag'] = true;
						$tag[$index = 0] = '';}}}
			elseif($switch['close']){//if close
				if($str[$x] == '>'){//close closing tag
					$current = $current->parent();
					$switch['close'] = false;}}
			elseif($str[$x] == '"'){//switch quotes
				$switch['quotes'] = !$switch['quotes'];}
			elseif(!$switch['quotes'] && $str[$x] == '>'){//close opening tag ;
				if($current){
					!$switch['attributes'] || $index == '/' ?: $extra =  $extra.' '.$index;
					$switch['attributes'] = false;
					($str[$x-1] != '/' && $tag[0] != '!doctype') ? $current = $current->child($tag)->extra($extra) : $current->child($tag)->extra($extra);}
				else{
					!$switch['attributes'] || $index == '/' ?: $extra =  $extra.' '.$index;
					$switch['attributes'] = false;
					($str[$x-1] != '/' && $tag[0] != '!doctype') ? $current = $this->tags[] = (new Tag($tag))->extra($extra) : $this->tags[] = (new Tag($tag))->extra($extra);}
				$switch['tag'] = $switch['attributes'] = false;
				$tag = [];}
			elseif(!$switch['attributes']){//write tag or value / switch attribute & reset index
				$str[$x] == ' ' && !$switch['quotes'] ? list($switch['attributes'], $index) = [true, ''] : $tag[$index] .= $str[$x];}
			elseif($switch['attributes']){//write attribute
				if($str[$x] == ' '){
					$extra .= ' '.$index;}
				$str[$x] == '=' ? list($switch['attributes'], $tag[$index]) = [false, ''] : $index .= $str[$x];}}}}
