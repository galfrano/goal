<?php 
return array (
  'pk' => 'id',
  'columns' => 
  array (
    'name' => 'varchar(511)',
    'address' => 'varchar(1023)',
    'dic' => 'varchar(31)',
    'ic' => 'varchar(31)',
    'id' => 'int(11) unsigned',
  ),
  'parents' => 
  array (
  ),
  'children' => 
  array (
    'invoices' => 
    array (
      'id' => 'customer',
    ),
  ),
);