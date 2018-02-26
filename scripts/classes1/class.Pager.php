<?php
 class Pager extends dbFunctions
  {
  /***********************************************************************************
   * int findStart (int limit)
   * Returns the start offset based on $_GET['page'] and $limit
   ***********************************************************************************/
   function findStart($limit,$page_name='page')
    {
     if ((!isset($_GET[$page_name])) || ($_GET[$page_name] == "1") || ($_GET[$page_name] < 1))
      {
       $start = 0;
       $_GET[$page_name] = 1;
      }
     else
      {
       $start = ($_GET[$page_name]-1) * $limit;
      }

     return $start;
    }
  /***********************************************************************************
   * int findPages (int count, int limit)
   * Returns the number of pages needed based on a count and a limit
   ***********************************************************************************/
   function findPages($count, $limit)
    {
     $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;

     return $pages;
    }
  /***********************************************************************************
   * string pageList (int curpage, int pages)
   * Returns a list of pages in the format of " < [pages] > "
   ***********************************************************************************/
	function pageList($curpage, $pages,$parameter="",$page_url="",$ajax_pagination=false,$set_div='txtResult',$page_name='page')
	{
		$page_url = ($page_url=='' ? $_SERVER['PHP_SELF'] : $page_url);
		$page_list  = "";

	     if ($ajax_pagination==false)
	     {
	     	/* Print the first and previous page links if necessary */
	     	if (($curpage != 1) && ($curpage))
		       $page_list .= "  <a href=\"".$page_url."?".$parameter."&".$page_name."=1\" title=\"First Page\" class=\"links\"><<</a> ";

	     	if (($curpage-1) > 0)
		       $page_list .= "<a href=\"".$page_url."?".$parameter."&".$page_name."=".($curpage-1)."\" title=\"Previous Page\" class=\"links\">Prev</a> ";

		     /* Print the numeric page list; make the current page unlinked and bold */
		     //echo $my_pages = ceil($pages/2);
		     for ($i=1; $i<=$pages; $i++)
		     {
		       	if ($i == $curpage)
		        	 $page_list .= "<b>".$i."</b>";
		       	else
		        	 $page_list .= "<a href=\"".$page_url."?".$parameter."&".$page_name."=".$i."\" title=\"Page ".$i."\" class=\"links\">".$i."</a>";

		       	$page_list .= " ";
		     }

		     /* Print the Next and Last page links if necessary */
		     if (($curpage+1) <= $pages)
		       		$page_list .= "<a href=\"".$page_url."?".$parameter."&".$page_name."=".($curpage+1)."\" title=\"Next Page\" class=\"links\">Next</a> ";


		     if (($curpage != $pages) && ($pages != 0))
		       		$page_list .= "<a href=\"".$page_url."?".$parameter."&".$page_name."=".$pages."\" title=\"Last Page\" class=\"links\">>></a> ";
		}
		else
		{
	     	/* Print the first and previous page links if necessary */
	     	//if (($curpage != 1) && ($curpage))
		       //$page_list .= '  <a href="javascript:sk_ajax_request(\''.$page_url.'\',\''.$parameter.'&'.$page_name.'=1&pagin=1\',\''.$set_div.'\')" title="First Page" class="links"><<</a> ';

	     	if (($curpage-1) > 0)
		       $page_list .= '  <a href="javascript:sk_ajax_request(\''.$page_url.'\',\''.$parameter.'&'.$page_name.'='.($curpage-1).'&pagin=1\',\''.$set_div.'\')" title="Previous Page" class="links"><img src="images/back-icn.gif" alt="Previous" title="Previous" border="0"> Previous</a>  &nbsp; ';

			$page_list .= "<b> Page &nbsp;</b>";
		    /* Print the numeric page list; make the current page unlinked and bold */
		    for ($i=1; $i<=$pages; $i++)
		    {
		       	if ($i == $curpage)
		        	 $page_list .= "<b>".$i."</b>";
		       	else
		       	{
			      // $page_list .= '  <a href="javascript:sk_ajax_request(\''.$page_url.'\',\''.$parameter.'&'.$page_name.'='.$i.'&pagin=1\',\''.$set_div.'\')" title="Page '.$i.'" class="links">'.$i.'</a> ';
		       	}

		       	$page_list .= " ";
		    }
			$page_list .= " of <b>". ($i-1) ."</b>";
		    /* Print the Next and Last page links if necessary */
			if (($curpage+1) <= $pages)
				$page_list .= ' &nbsp; <a href="javascript:sk_ajax_request(\''.$page_url.'\',\''.$parameter.'&'.$page_name.'='.($curpage+1).'&pagin=1\',\''.$set_div.'\')" title="Next Page" class="links">Next <img src="images/arrow2.gif" alt="Next" title="Next" border="0"/></a> ';

			//if (($curpage != $pages) && ($pages != 0))
				//$page_list .= '  <a href="javascript:sk_ajax_request(\''.$page_url.'\',\''.$parameter.'&'.$page_name.'='.($pages).'&pagin=1\',\''.$set_div.'\')" title="Last Page" class="links">>></a> ';
		}

     $page_list .= "\n";
     return $page_list;
    }

	/***********************************************************************************
   * string nextPrev (int curpage, int pages)
   * Returns "Previous | Next" string for individual pagination (it's a word!)
   ***********************************************************************************/
   function nextPrev($curpage, $pages,$parameter)
    {
     $next_prev  = "";

     if (($curpage-1) <= 0)
      {
       $next_prev .= "Previous";
      }
     else
      {
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage-1)."\" class=\"rightnavlinks\">Previous</a>";
      }

     $next_prev .= " | ";

     if (($curpage+1) > $pages)
      {
       $next_prev .= "Next";
      }
     else
      {
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage+1)."\" class=\"rightnavlinks\">Next</a>";
      }

     return $next_prev;
    }

   function nextPrevCols($curpage,$pages,$parameter,$cols)
    {
     $next_prev  = "<td>&lt;&lt;";

     if (($curpage-1) <= 0)
      {
       $next_prev .= "Previous";
      }
     else
      {
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage-1)."\" class=\"rightnavlinks\">Previous</a>";
      }
	 $colspan=$cols-2;
     $next_prev .= " </td><td colspan=$colspan>&nbsp;</td><td align=right> ";

     if (($curpage+1) > $pages)
      {
       $next_prev .= "Next";
      }
     else
      {
       $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?".$parameter."&page=".($curpage+1)."\" class=\"rightnavlinks\">Next</a>";
      }
     $next_prev  .= "&gt;&gt;</td>";

     return $next_prev;
    }


  }
?>
