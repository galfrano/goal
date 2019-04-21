<?php 
return array (
  'pk' => 'id',
  'columns' => 
  array (
    'name' => 'varchar(255)',
    'price_no_dph' => 'float(9,2)',
    'price_dph' => 'float(9,2)',
    'id' => 'int(11) unsigned',
  ),
  'parents' => 
  array (
  ),
  'children' => 
  array (
    'city_products' => 
    array (
      'id' => 'product',
    ),
    'entries' => 
    array (
      'id' => 'product',
    ),
    'invoice_products' => 
    array (
      'id' => 'product',
    ),
  ),
);