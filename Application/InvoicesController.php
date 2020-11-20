<?php

namespace Application;
use \View\InvoiceView;
use \View\CrudView;
use \Model\Entity;
use \Utility\Reports;
use \Service\Path;
use \Controller\CrudController;

class InvoicesController extends CrudController{
	protected static $children = ['invoices'=>['invoice_lines']];
	protected static $sections = ['invoices', 'cancelled_invoices'];

	function isAvailable($section){
		parent::isAvailable($section);
		if($section === 'invoices'){
      $this->filter = ['cancelled'=>'No'];
			self::$actions[] = 'print';
			$this->view = new InvoiceView();
		}
		elseif($section === 'cancelled_invoices'){
			$this->entity = new Entity($this->table = 'invoices');
      $this->filter = ['cancelled'=>'yes'];
		}
	}
	function get(){
		Path::getParam('action') === 'print' && Path::getParam('id') ? $this->view->showInvoice($this->entity, Path::getParam('id'))->output() : parent::get();
  }
}
