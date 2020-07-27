<?php
namespace View;
use \Xml\Loader;
use \Model\Entity;
class InvoiceView extends CrudView{

	function showUpdateForm($entity, $id, $children = []){
		parent::showUpdateForm($entity, $id, $children);
		$this->html->get(['id'=>'actions'])->a(['href'=>self::getUrl(['action'], ['print']), 'class'=>'btn btn-default'])->say('print');
		return $this;
	}
	function showInvoice($e, $id){
		$invoice = (new Entity('invoices'))->get($id);
//		$invoice
		$customer = ((new Entity('customers'))->get($invoice['customer']));
		$data = ['occurence'=>substr($invoice['occurence'], 0, 10), 'payment_method'=>$invoice['payment_method'], 'address'=>nl2br($customer['address']), 'customer'=>$customer['business_name'], 'ic'=>$customer['ic'], 'dic'=>$customer['dic'], 'lines'=>[]];
		$data['payment_method'] = $invoice['payment_method'] === 'cash' ? 'Hotovost' : 'Převodem';
		$total = 0;
//$res = $entity->get($id);
		$linesE = new Entity('invoice_lines');
		$linesE->rpp = 100;
		$pricesE = new Entity('invoice_prices');
		$products = $linesE->getParents()['product'];
		foreach($linesE->getList(1, ['invoice'=>$id]) as $line){
			$price = current($pricesE->getList(1, ['invoice_line'=>$line['id']]));
			$sum = $line['amount']*$price['price'];
			$total += $sum;
			$data['lines'][] = ['product'=>$products[$line['product']], 'amount'=>$line['amount'], 'price'=>self::money($price['price']), 'sum'=>self::money($sum)];}
		$data['to_pay'] = $data['payment_method'] === 'cash' ? $data['occurence'] : self::add10Days($data['occurence']);
		$data['total'] = self::money($total);
		$data['dph'] = self::money($total*0.21);
		$data['final'] = self::money($total*1.21);
		$data['invoice_number'] = str_pad($invoice['id'], 4, "0", \STR_PAD_LEFT);
//var_dump($data); die;
		list($this->doctype, $this->html) = Loader::tags(file_get_contents(__DIR__.'/html/invoice.html'));
		$this->setAssetsPath();
		$this->html->populate($data);
		$this->html->get(['id'=>'products'])->iterate($data['lines']);
		return $this;
	}
	private static function add10Days($date){
		$parts = explode('-', $date);
		return date('Y-m-d', mktime(0, 0, 0, $parts[1], $parts[2]+10, $parts[0]));
	}
	private static function money($num){
		return number_format($num, 2, '.', ',').'Kč';
	}
}
