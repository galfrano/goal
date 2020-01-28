<?php
namespace View;
use \Xml\Tag;
class ReportView extends AbstractView{

	public $session;

	protected static $urlMap = ['page'=>2, 'action'=>3, 'id'=>4];
/*
	protected static urlMap(){
		}*/

	function __construct($sections = [], $path = ''){
		parent::__construct();
		$this->menuBar($sections, $path);}

	function showSales($data){
		$wrapper = $this->html->get('body')->div(['class'=>'wrapper']);
		if($data){
			$wrapper->text($this->tableize($data));
			$shops = $this->perShop($data);
			foreach($shops[0] as $wid => $shop){
				$wrapper->h3()->text($shop);
				$wrapper->text($this->tableize($shops[1][$wid]));}}
		return $this;}

	function tableize($data){
		$head = array_keys(current($data));
		$table = new Tag(['table', 'class'=>'table table-striped']);
		$thr = $table->tr();
		foreach($head as $th){
			$thr->th()->say($th);}
		foreach($data as $row){
			$tdr = $table->tr();
			foreach($row as $td){
				$tdr->td()->text($td);}}
		return $table;}

	function perShop($data){
		$shopData = [];
		$shops = [];
		foreach($data as $row){
			!empty($shops[$row['wid']]) ?: $shops[$row['wid']] = $row['warehouse'];
			!empty($shopData[$row['wid']]) ?: $shopData[$row['wid']] = [];
			!empty($shopData[$row['wid']][$row['pid']]) ?: $shopData[$row['wid']][$row['pid']] = ['product'=>$row['product'], 'sales'=>0];
			$shopData[$row['wid']][$row['pid']]['sales'] += $row['sales'];}
		return [$shops, $shopData];}}
