<?php
class BaseClass 
{
	var $myFunc;
	var $dbFunc;
	var $rec_pp;
	var $paging_params="";

	function __construct($db)
	{
		//parent::__construct();
		//$this->myFunc = new ExtraFunctions();
		$this->dbFunc = new dbFunctions($db);
		$this->rec_pp = 10;
	}
}
?>
