<?php
class Cms extends BaseClass
{
	/**
	 * Constructor for the class
	 *
	 * @return Cms
	 */
	function Cms($db)
	{
		parent::__construct($db);
	}

	function save_image($imageName,$fname,$dir)
	{
		$imageName = str_replace(' ','_',$imageName);
		if(isset($_FILES[$fname]["error"]))
		{
			if ($_FILES[$fname]["error"] == UPLOAD_ERR_OK)
			{
				$str=$_FILES[$fname]['name'];
	
				$the_file = $_FILES[$fname]["tmp_name"];

				$to_file = "$dir/$imageName";
				//echo $the_file."----".$to_file;
				move_uploaded_file($the_file, $to_file) or die('unable to upload');
				chmod($to_file, 0777);
				
			}
		}
	}
	
	function save_imageMultiple($imageName,$fname,$dir,$cnt)
	{
		$imageName = str_replace(' ','_',$imageName);
		if(isset($_FILES[$fname]["error"][$cnt]))
		{
			if ($_FILES[$fname]["error"][$cnt] == UPLOAD_ERR_OK)
			{
				$str=$_FILES[$fname]['name'][$cnt];
	
				$the_file = $_FILES[$fname]["tmp_name"][$cnt];

				$to_file = "$dir/$imageName";
				
				move_uploaded_file($the_file, $to_file) or die('unable to move');
				chmod($to_file, 0777);
				
			}
		}
	}
	
	//////////////////////  Common Functions  ////////////////////////////////
	function query($strSql)
	{
		return $this->dbFunc->dbFetchRow($strSql);	
	}
	function GiveValue($strSql)
	{
		return $this->dbFunc->dbFetchOne($strSql);	
	}
	function getRow($strSql)
	{
		return $this->dbFunc->dbFetchRow($strSql);	
	}
	function getRowSelFields($fields,$table,$wherecondition)
	{
		$strSql = "SELECT $fields FROM $table WHERE $wherecondition";
		return $this->dbFunc->dbFetchRow($strSql);	
	}
	function insert($table,$set)
	{
		return $this->dbFunc->dbInsert($table,$set);
	}
	function update($table,$set,$where)
	{
		return $this->dbFunc->dbUpdate($table, $set, $where);
	}
	function delete($table,$where) 
	{
		$rows_affected = $this->dbFunc->dbDelete($table, $where);
		return $rows_affected;
	}
	function getAllRecords($strSql) 
	{
		return $this->dbFunc->dbFetchAllAssoc($strSql);
	}
	function getAllRecordsPaging($fields,$table,$where) 
	{
		$strSql = "SELECT $fields FROM $table WHERE $where";
		return $this->dbFunc->dbFetchAll_page($strSql,$this->rec_pp,$this->paging_params);
	}
	function getAllRecordsPagingAjax($strSql) 
	{
		return $this->dbFunc->dbFetchAll_pageAjax($strSql,$this->rec_pp,$this->paging_params);
	}
	function getAllRecordsPagingAjaxByArrray($arr) 
	{
		return $this->dbFunc->arrayAll_pageAjax($arr,$this->rec_pp,$this->paging_params);
	}
	function array_remove_empty($arr)
	{
    	$narr = array();
    	while(list($key, $val) = each($arr))
    	{
        	if (is_array($val)){
            $val = array_remove_empty($val);
            // does the result array contain anything?
            if (count($val)!=0){
                // yes :-)
                $narr[$key] = $val;
            }
	        }
	        else {
	            if (trim($val) != ""){
	                $narr[$key] = $val;
	            }
	        }
	    }
	    unset($arr);
	    return $narr;
	}
	/////////////////////////////////////////////////////////////////////////
	///////function to remove special characters./////////
	function just_clean($string)
	{
		// Replace other special chars
		$specialCharacters = array(
		"#" => "",
		"$" => "",
		"%" => "",
		"&" => "",
		"@" => "",
		"." => "",
		"€" => "",
		"+" => "",
		"=" => "",
		"§" => "",
		"\\" => "",
		"/" => "_",
		";" => "",
		"(" => "",
		")" => "",
		"," => "",
		"'" => "",
		'"' => ""
		);
		
		while (list($character, $replacement) = each($specialCharacters)) {
			$string = str_replace($character, $replacement, $string);
		}
		return $string;
	}	
	
	function showLessText($text,$length,$allowedTags='')
	{
		$textLen = strlen($text);
		if(!empty($allowedTags))
			$txtLess = substr(strip_tags($text,$allowedTags),0,$length);
		else
			$txtLess = substr(strip_tags($text),0,$length);
		if($textLen > $length)
			$txtLess.="....";
			
		return $txtLess;
	}
	
	function showLessTextWithViewMore($id,$text,$length,$allowedTags='')
	{
		$textLen = strlen($text);
		if(!empty($allowedTags))
			$txtLess = substr(strip_tags($text,$allowedTags),0,$length);
		else
			$txtLess = substr(strip_tags($text),0,$length);
			
		if($textLen > $length)
		{
			$txtLess .= '.... <a href="javascript:;" onClick="$(\'.more_txt_'.$id.'\').show();$(\'.less_txt_'.$id.'\').hide();">View More</a>';
			$txtMore = 	$text . '   <a href="javascript:;" onClick="$(\'.more_txt_'.$id.'\').hide();$(\'.less_txt_'.$id.'\').show();">View Less</a>';
			
			$txt = '<span class="more_txt_'.$id.'" style="display:none;">'.$txtMore.'</span><span class="less_txt_'.$id.'">'.$txtLess.'</span>';
		}
		else
			$txt = $txtLess;
			
		return $txt;
	}
	
	function getRoundedOfftime($hours)
	{
		if($hours < 24)
		{
			return round($hours) ." hours";
		}
		else
		{
			$days = $hours/24;
			$perHours = $hours%24;
			if($perHours != 0)
			{
					$daysArr = explode(".",$days);
					if($days < 5)
					{
						$hoursCal = round($perHours*60/100);
						
						return $daysArr[0] . " Days ".$hoursCal." hrs";
					}
					else
					{
						if($perHours > 12)
						{
							$daysCal = $daysArr[0]+1;
							return $daysCal . " Days"; 
						}
						else
						{
							return $daysArr[0] . ".5 Days"; 
						}
					}
			}
			else
				return round($days) . " Day(s)";
		}
	}
	function getExpStr($date)
	{
		$expStr = '';
		$dtAddedArr = getdate(strtotime($date));
		$dtCurrentArr = getdate(time());
		
		if($dtCurrentArr['mon'] < $dtAddedArr['mon'])
		{
			$numYearsExp = $dtCurrentArr['year'] - $dtAddedArr['year'] - 1;
			$numMonthsExp = $dtCurrentArr['mon'] - $dtAddedArr['mon'] + 12;
		}
		else
		{
			$numYearsExp = $dtCurrentArr['year'] - $dtAddedArr['year'];
			$numMonthsExp = $dtCurrentArr['mon'] - $dtAddedArr['mon'];
		}
		if($numYearsExp > 0)
		{
			$expStr .= $numYearsExp . ' years ';	
			if($numMonthsExp > 0)
			$expStr .= ', ';
		}
		if($numMonthsExp > 0)
			$expStr .= $numMonthsExp . ' months';
			
		if($numYearsExp <= 0 and $numMonthsExp <= 0)
		{
			
		}
		return $expStr;
	}
	
	function formatDate($date)
	{
		//return date('jS M Y',strtotime($date));
		return date('d/m/Y',strtotime($date));
	}
	
	function formatDateSmall($date)
	{
		return date('jS M Y',strtotime($date));
	}
	
	function formatDateTime($date)
	{
		return date('d/m/Y H:i:s',strtotime($date));
	}
	
	function formatDateTime12Hr($date)
	{
		return date('j M Y h:i:s A',strtotime($date));
	}
	
	function displaySubmitTime($datetime)
	{
		$hours = round((time() - strtotime($datetime))/3600);
		
		if($hours < 24)
		{
			if($hours <= 0)
				return "Just now";
			else if($hours == 1)
				return $hours ." hour ago";
			else
				return $hours ." hours ago";
		}
		else if($hours >= 24 and $hours < 48)
		{
			return 'Yesterday';
		}
		else if($hours<30*24)
		{
			return round($hours/24) . ' days ago.';
		}
		else if($hours<30*24*365)
		{
			$months = round($hours/(30*24));
			if($months==1)
				return $months . ' month ago.';
			else
				return $months . ' months ago.';
		}
		else
		{
			$years = round($hours/(30*24*365));
			if($months==1)
				return $years . ' year ago.';
			else
				return $years . ' years ago.';
		}
	}
	
	function chkPostVal($postId)
	{
		if(isset($_POST[$postId]) and $_POST[$postId]!='')
			return $_POST[$postId];
		else
			return '';
	}
	
	function chkCheckVal($postId)
	{
		if(isset($_POST[$postId]) and $_POST[$postId]!='')
			return 'checked="checked"';
		else
			return '';
	}
	
	function encodeNumber($num)
	{
		return ($num*USER_ENC_NUM)+USER_ENC_NUM_ADD;
	}
	
	function decodeNumber($enc)
	{
		return ($enc-USER_ENC_NUM_ADD)/USER_ENC_NUM;
	}
	
	function roundRating($rating)
	{
		$arr = explode('.',round($rating,1));
		if(is_array($arr) and count($arr)==2)
		{
			if($arr[1]<3)
				return $arr[0];
			else if($arr[1] < 8)
				return $arr[0].'.5';
			else
				return ($arr[0]+1);
		}
		else
			return $rating;
	}
}
?>