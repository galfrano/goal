<?php 
return array (
  'pk' => 'id',
  'columns' => 
  array (
    'invoice_number' => 'varchar(15)',
    'creation_date' => 'date',
    'transaction_date' => 'date',
    'payment_date' => 'date',
    'customer' => 'int(11) unsigned',
    'discount' => 'int(2)',
    'id' => 'int(11) unsigned',
  ),
  'parents' => 
  array (
    'customer' => 
    array (
      'customers' => 'id',
    ),
  ),
  'children' => 
  array (
    'invoice_products' => 
    array (
      'id' => 'invoice',
    ),
  ),
);