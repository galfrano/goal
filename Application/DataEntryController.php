<?php

namespace Application;
use \View\InvoiceView;
use \View\CrudView;
use \Model\Entity;
use \Utility\Reports;
use \Service\Path;
use \Controller\CrudController;

class DataEntryController extends CrudController{
	protected static $children = ['products'=>['prices']];
	protected static $sections = ['categories', 'customers', 'products'];

}
