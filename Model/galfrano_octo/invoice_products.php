<?php 
return array (
  'pk' => 'id',
  'columns' => 
  array (
    'invoice' => 'int(11) unsigned',
    'product' => 'int(11) unsigned',
    'quantity' => 'int(3)',
    'price_no_dph' => 'float(9,2)',
    'id' => 'int(11) unsigned',
  ),
  'parents' => 
  array (
    'invoice' => 
    array (
      'invoices' => 'id',
    ),
    'product' => 
    array (
      'products' => 'id',
    ),
  ),
  'children' => 
  array (
  ),
);