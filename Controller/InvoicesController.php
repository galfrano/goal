<?php
//example of custom controller
namespace Controller;
use View\InvoiceView;
use Model\Entity;
class InvoicesController extends CrudController{

	protected $actions = ['print'=>'printInvoice'];

	function printInvoiceAction(){
		$data['invoice'] = $this->entity->get($_GET['id']);
		$product = (new Entity('invoice_products'));
		$catalogs = $product->getParents();
		$data['total1'] = ['total_no_dph'=>0, 'total_dph'=>0];
		$data['products'] = $product->getList(false, ['invoice'=>$data['invoice']['id']], function(&$line) use($catalogs, &$data){
			$line['name'] = $catalogs['product'][$line['product']];
			$line['percentage'] = '21%';
			$line['price_dph'] = $line['price_no_dph']*1.21;
			$line['total'] = $line['price_dph']*$line['quantity'];
			$data['total1']['total_no_dph'] = ($line['price_no_dph']*$line['quantity'])+$data['total1']['total_no_dph'];
			$data['total1']['total_dph'] = $line['total']+$data['total1']['total_dph'];});
		$data['customer'] = (new Entity('customers'))->get($data['invoice']['customer']);
		$data['customer']['address'] = str_replace(', ', "\n", $data['customer']['address']);
		$data['total1']['dph'] = number_format($data['total1']['total_dph']-$data['total1']['total_no_dph'], 2);
		$data += ['total2'=>$data['total1'], 'total3'=>$data['total1']];
		(new InvoiceView($data))->output();}

	function read($id){
		echo $id == 0 ?
			$this->view->showCreateForm($this->entity)->output() :
			$this->view->showUpdateForm($this->entity, $id)->addOn('actions', $this->actions)->output() ;}

}
