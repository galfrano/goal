<?php
namespace View;
use \Xml\Tag;
class ReportView extends AbstractView{

	function showAllSales($total, $data, $pages, $page){
		$div = $this->html->get('body')->div(['class'=>'wrapper']);
		$toolbar = $div->div(['class'=>'toolbar'])->form(['method'=>'post']);
		$toolbar->span()->say('from');
		$toolbar->input(['type'=>'date', 'name'=>'from']);
		$toolbar->span()->say('to');
		$toolbar->input(['type'=>'date', 'name'=>'to']);
		$toolbar->button(['class'=>'btn btn-primary'])->say('filter');
		if($data){
			$div->h2()->say('summary');
			$div->h3()->say($total);
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
	function showStock($data){
		$div = $this->html->get('body')->div(['class'=>'wrapper']);
		$div->h2()->say('stock');
		$div->text($this->tableize($data));
		return $this;
	}
}
