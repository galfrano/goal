<?php
namespace View;
use \Xml\Loader;
class InvoiceView extends CrudView{

	function showUpdateForm($entity, $id, $children = []){
		parent::showUpdateForm($entity, $id, $children);
		$this->html->get(['id'=>'actions'])->a(['href'=>self::getUrl(['action'], ['print']), 'class'=>'btn btn-default'])->say('print');
		return $this;}

	function showInvoice(){
		list($this->doctype, $this->html) = Loader::tags(file_get_contents(\APP_PATH.'/View/html/invoice.html'));
		$this->setAssetsPath();
		return $this;}
}
