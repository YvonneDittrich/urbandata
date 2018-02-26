<?php
require_once $addl_path ."classes/class.Pager.php";
class dbFunctions 
{
	var $ajax_pagin;
	var $set_div;	// Used for the Pager class where we specify the id that will be updated by the ajax call.
	var $page_name;
// 	function dbFunctions()
// 	{
// 		parent::__construct();
// 		$this->ajax_pagin = false;
// 		$this->page_name = 'page';
// 	}

	function dbQuery($strSql)
	{
		mysql_query($strSql);
	}

	function dbInsert($table, $row)
	{
		$query="INSERT INTO ".$table." SET ";
		$count=count($row);
		$i=1;
		while(list($key,$value) = each($row))
		{
			if($i==$count)
			{
			$query.="".$key."='".$value."'";
			}
			else 
			{
			$query.="".$key."='".$value."',";	
			}
			$i++;
		}
		//echo $query;exit;
		return mysql_query($query);
	}

	function dbUpdate($table, $set, $where)
	{
		$query="Update ".$table." SET ";
		$count=count($set);
		$i=1;
		while(list($key,$value) = each($set))
		{
			if($i==$count)
			{
			$query.="".$key."='".$value."'";
			}
			else 
			{
			$query.="".$key."='".$value."',";	
			}
			$i++;
		}
		$query.=" where ".$where."";
		return mysql_query($query);
	}

	function dbDelete($table, $where)
	{
		$query="Delete from  ".$table." where ".$where."";
		return mysql_query($query);
	}

//Returns only single value as string, for first column of first row.
	function dbFetchOne($strSql)
	{
		$row=mysql_query($strSql);
		$result=mysql_fetch_row($row);
		return $result[0];
		
	}

// Returns the first row from the SQL
	function dbFetchRow($strSql)
	{
		$row=mysql_query($strSql);
		$result=mysql_fetch_array($row);
		return $result;
	}

/*	function to return a array with the associative records for compatibility with the older functions
	returns --associative array containing the records in key=>value format		*/
	function dbFetchAll($strSql,$rec_pp=0,$parameters="",$page_url="")
	{
		$row=mysql_query($strSql);
		$numrows=mysql_num_rows($row);
		$record = array();
		for($counter=0;$counter<$numrows;$counter++)
		{
			$record[]=mysql_fetch_array($row);
		}
		
		return $record;
	}
	
	function dbFetchAllAssoc($strSql,$rec_pp=0,$parameters="",$page_url="")
	{
		$row=mysql_query($strSql);
		$numrows=mysql_num_rows($row);
		$record = array();
		for($counter=0;$counter<$numrows;$counter++)
		{
			$record[]=mysql_fetch_assoc($row);
		}
		
		return $record;
	}

	function dbFetchAllbypage($resultset)
	{
		$numrows=mysql_num_rows($resultset);
		$record = array();
		for($counter=0;$counter<$numrows;$counter++)
		{
			$record[]=mysql_fetch_array($resultset);
		}
		
		return $record;
	}	

/*	$rec_pp -> Records per page*/
	function dbFetchAll_page($strSql, $rec_pp=0,$parameters="",$page_url="")
	{
		$pagging = new mysqlPaging($strSql,$rec_pp);
		$resultset = $pagging->returnQuery();
		$record = $this->dbFetchAllbypage($resultset);
		$paglist = $pagging->printPagesNums($parameters);	
		
		$return[0] = $record;
		$return[1] = $paglist;
		$return[2] = $pagging->total;
		return $return;

// 		if($parameters == "")
// 			$parameters = (isset($this->parameters)) ? $this->parameters : "";
// 
// 		$rec_pp=($rec_pp>0) ? $rec_pp : $this->rec_pp;
// 		$ajax_pagination = $this->ajax_pagin;
// 		$set_div = isset($this->set_div) ? $this->set_div : 'txtResult';
// 		$page_name = isset($this->page_name) ? $this->page_name : 'page';
// 		
// 			($this->debugQueries == 1)? print $strSql."<br>": print "";
// 			$result = $this->dbFetchAll($strSql);
// 			//return $result;
// 
// 			$p=new Pager;
// 			if($result)
// 			{
// 				if(count($result))
// 				{
// 					$num=count($result);
// 					//if($custPage==0)
// 					//{
// 
// 
// 					/*
// 					if(!isset($_REQUEST["page"]) || ($_REQUEST["page"]=="") )
// 						$_REQUEST["page"]=1;
// 					*/
// 
// 					if(!isset($_REQUEST[$page_name]) || ($_REQUEST[$page_name]=="") )
// 						$_REQUEST[$page_name]=1;
// 
// 					//}
// 					/*else
// 					{
// 					$_REQUEST["page"]=$custPage;
// 					$_GET["page"]=$custPage;
// 					}*/
// 					$limit =$rec_pp;
// 					$getpages=$p->findPages($num,$limit);
// 					if(isset($_REQUEST[$page_name]) && $_REQUEST[$page_name]>$getpages)
// 					{
// 						$_REQUEST[$page_name]=$getpages;
// 						$_GET[$page_name]=$getpages;
// 					}
// 
// 					/* store page in temp session variable*/
// 					//$_SESSION['tempPage']=$_GET["page"];
// 
// 					$start = $p->findStart($limit,$page_name);
// 					$paging_query = $strSql." Limit $start,$limit";
// 					$resultPaging=$this->dbFetchAll($paging_query);
// 				}
// 			
// 			$arrMain=array();
// 			/*if($maintainSearch)
// 			{
// 		 		if($condition!=null)
// 				   $arrMain[0]=$p->pageList($_REQUEST['page'],$getpages,"action=".$action."&srchString=".$condition);
// 			}
// 			else */
// 			$arrMain[0]=($getpages>1)?$p->pageList($_REQUEST[$page_name],$getpages,$parameters,$page_url,$ajax_pagination,$set_div,$page_name) : "";
// 
// 			$arrMain[1]=$resultPaging;
// 			$arrMain[2]=count($result);
// 
// 			//myPrintR($arrMain);
// 			return $arrMain;
// 			/*$arrMain[0]	-- 	string of pagination
// 			  $arrMain[1]	-- 	Array of records	*/
// 
// 	
		
	}
	function dbFetchAll_pageAjax($strSql, $rec_pp=0,$parameters="",$page_url="")
	{
		 $this->ajax_pagin = true;
 		if($parameters == "")
 			$parameters = (isset($this->parameters)) ? $this->parameters : "";
 
 		$rec_pp=($rec_pp>0) ? $rec_pp : $this->rec_pp;
 		$ajax_pagination = $this->ajax_pagin;
 		$set_div = isset($this->set_div) ? $this->set_div : 'txtResult';
 		$page_name = isset($this->page_name) ? $this->page_name : 'page';
 		
 			($this->debugQueries == 1)? print $strSql."<br>": print "";
 			$result = $this->dbFetchAll($strSql);
 			//return $result;
 
 			$p=new Pager;
 			if($result)
 			{
 				if(count($result))
 				{
 					$num=count($result);
 					//if($custPage==0)
 					//{
 
 
 					
// 					if(!isset($_REQUEST["page"]) || ($_REQUEST["page"]=="") )
// 						$_REQUEST["page"]=1;
// 					
 
 					if(!isset($_REQUEST[$page_name]) || ($_REQUEST[$page_name]=="") )
 						$_REQUEST[$page_name]=1;
 
 					}
 					else
 					{
 					$_REQUEST["page"]=$custPage;
 					$_GET["page"]=$custPage;
 					}
 					$limit =$rec_pp;
 					$getpages=$p->findPages($num,$limit);
 					if(isset($_REQUEST[$page_name]) && $_REQUEST[$page_name]>$getpages)
 					{
 						$_REQUEST[$page_name]=$getpages;
 						$_GET[$page_name]=$getpages;
 					}
 
 					/* store page in temp session variable*/
 					//$_SESSION['tempPage']=$_GET["page"];
 
 					$start = $p->findStart($limit,$page_name);
 					$paging_query = $strSql." Limit $start,$limit";
 					$resultPaging=$this->dbFetchAll($paging_query);
 				}
 			
 			$arrMain=array();
 			if($maintainSearch)
 			{
 		 		if($condition!=null)
 				   $arrMain[0]=$p->pageList($_REQUEST['page'],$getpages,"action=".$action."&srchString=".$condition);
 			}
 			else 
 			$arrMain[0]=($getpages>1)?$p->pageList($_REQUEST[$page_name],$getpages,$parameters,$page_url,$ajax_pagination,$set_div,$page_name) : "";
 
 			$arrMain[1]=$resultPaging;
 			$arrMain[2]=count($result);
 
 			//myPrintR($arrMain);
 			return $arrMain;
 //			$arrMain[0]	-- 	string of pagination
// 			  $arrMain[1]	-- 	Array of records	
 
 	
		
	}
	
	function arrayAll_pageAjax($arr, $rec_pp=0,$parameters="",$page_url="")
	{
		$this->ajax_pagin = true;
		
		if($parameters == "")
 			$parameters = (isset($this->parameters)) ? $this->parameters : "";
 		
 		$rec_pp=($rec_pp>0) ? $rec_pp : $this->rec_pp;
 		$ajax_pagination = $this->ajax_pagin;
 		$set_div = isset($this->set_div) ? $this->set_div : 'txtResult';
 		$page_name = isset($this->page_name) ? $this->page_name : 'page';
 		
 			$result = $arr;
 			
 			$p=new Pager;
 			if($result)
 			{
 				if(count($result))
 				{
 					$num=count($result);
 					 
 					if(!isset($_REQUEST[$page_name]) || ($_REQUEST[$page_name]=="") )
 						$_REQUEST[$page_name]=1;
 
 					}
 					else
 					{
 					$_REQUEST["page"]=$custPage;
 					$_GET["page"]=$custPage;
 					}
 					$limit =$rec_pp;
 					$getpages=$p->findPages($num,$limit);
 					if(isset($_REQUEST[$page_name]) && $_REQUEST[$page_name]>$getpages)
 					{
 						$_REQUEST[$page_name]=$getpages;
 						$_GET[$page_name]=$getpages;
 					}
 
 					/* store page in temp session variable*/
 					//$_SESSION['tempPage']=$_GET["page"];
 
 					$start = $p->findStart($limit,$page_name);
 					$resultPaging=array_slice($arr,$start,$limit);
 				}
 			
 			$arrMain=array();
 			if($maintainSearch)
 			{
 		 		if($condition!=null)
 				   $arrMain[0]=$p->pageList($_REQUEST['page'],$getpages,"action=".$action."&srchString=".$condition);
 			}
 			else 
 			$arrMain[0]=($getpages>1)?$p->pageList($_REQUEST[$page_name],$getpages,$parameters,$page_url,$ajax_pagination,$set_div,$page_name) : "";
 
 			$arrMain[1]=$resultPaging;
 			$arrMain[2]=count($result);
 
 			//myPrintR($arrMain);
 			return $arrMain;
 	}
	
	function dbFetchAll_page1($strSql, $rec_pp=0,$parameters="",$page_url="")
	{
		$pagging1 = new mysqlPaging1($strSql,$rec_pp);
		$resultset = $pagging1->returnQuery();
		$record = $this->dbFetchAllbypage($resultset);
		$paglist = $pagging1->printPagesNums($parameters);	
		
		$return[0] = $record;
		$return[1] = $paglist;
		$return[2] = $pagging1->total;
		return $return;
	}

/*	*/
	function dbFetchCol($strSql)
	{
//		try{
//			($this->debugQueries == 1)? print $strSql."<br>": print "";
//			$result = $this->dbObj->fetchCol($strSql);
//			//$log_row = print_r($result,true);
//		//	Sys_Log::Log("-- FetchColumn: $strSql, Result: $log_row", basename(__FILE__), __LINE__, 'debug');
//			return $result;
//		}
//		catch(Zend_Db_Exception $e)
//		{
//		 	$errMsg ="<br><b>An exception occured operation could not be completed. <br></b>For: " .$strSql
//		 			."<br>File:  " .basename(__FILE__)
//		 			."<br>Method:  " .basename(__METHOD__)
//		 			."<br>Line:  " .basename(__LINE__) ;
//		 	fnErrorMessage($errMsg, $e);
//		}
	}
}
?>