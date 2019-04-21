<?php
namespace View;
class InvoiceView extends AbstractView{

	function __construct($data){
		parent::__construct('invoice.html');
//var_dump($data); die;
//		var_dump($this->html->get(['id'=>'customer'])); die;
		$this->load2($data);}

	function load2($data){
		$this->html->get('title')->text('Invoice #'.$data['invoice']['invoice_number']);
		$this->load($data);


}}
