<?php
namespace View;
use \Xml\Tag;
class Report2View extends AbstractView{

	function showAllSales($data, $pages, $page){
		$div = $this->html->get('body')->div(['class'=>'wrapper']);
		if($data){
			$div->h2()->say('summary');
			$div->text($this->tableize($data));
		}
		self::pager($div->div(['class'=>'pager']), $pages, $page);
		return $this;
	}
	function showCalc($data){
		$div = $this->html->get('body')->div(['class'=>'wrapper']);
		$div->text($this->tableize($data));
		return $this;
	}
}
