<?php
namespace View;
use \Xml\Tag;
class ReportView extends AbstractView{

	public $session;

	protected static $urlMap = ['page'=>2, 'action'=>3, 'id'=>4];

	function __construct($sections = [], $path = ''){
		parent::__construct();
		$this->menuBar($sections, $path);}

	function showSales($data){
		$wrapper = $this->html->get('body')->div(['class'=>'wrapper']);
		if($data){
			$wrapper->text($this->tableize($data));
			$shops = $this->salesPerShop($data);
			foreach($shops[0] as $wid => $shop){
				$wrapper->h3()->text($shop);
				$wrapper->text($this->tableize($shops[1][$wid]));}}
		return $this;}

	function showStock($sales, $inbound, $products){
		$wrapper = $this->html->get('body')->div(['class'=>'wrapper']);
		$shops = $this->salesPerShop($sales);
		$data = $this->stockPerShop($inbound, $shops[1], $products);
		foreach($shops[0] as $wid => $shop){
			$wrapper->h3()->text($shop);
			$wrapper->text($this->tableize($data[$wid]));}
		return $this;}

	protected function stockPerShop($inbound, $sales, $products){
		$stock = [];
		foreach($inbound as $row){
			!empty($stock[$row['warehouse']]) ?: $stock[$row['warehouse']] = [];
			!empty($stock[$row['warehouse']][$row['product']]) ?: $stock[$row['warehouse']][$row['product']] = ['product'=>$products[$row['product']], 'stock'=>0];
			$stock[$row['warehouse']][$row['product']]['stock'] += $row['amount'];}
		foreach($sales as $wid => $saleRows){
			foreach($saleRows as $row){
				!empty($stock[$wid][$row['pid']]) ?: $stock[$wid][$row['pid']] = ['product'=>$products[$row['pid']], 'stock'=>0];
				$stock[$wid][$row['pid']]['stock'] -= $row['sales'];}}
		return $stock;}

	protected function salesPerShop($data){
		$shopData = [];
		$shops = [];
		foreach($data as $row){
			!empty($shops[$row['wid']]) ?: $shops[$row['wid']] = $row['warehouse'];
			!empty($shopData[$row['wid']]) ?: $shopData[$row['wid']] = [];
			!empty($shopData[$row['wid']][$row['pid']]) ?: $shopData[$row['wid']][$row['pid']] = ['pid'=>$row['pid'], 'product'=>$row['product'], 'sales'=>0];
			$shopData[$row['wid']][$row['pid']]['sales'] += $row['sales'];}
		return [$shops, $shopData];}}
