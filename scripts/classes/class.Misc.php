<?php
class Cms extends BaseClass
{
	/**
	 * Constructor for the class
	 *
	 * @return Cms
	 */
	function __construct($db)
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

	/*function save_image($imageName,$fname,$dir)
	{
		//$imageName = preg_replace('/[^A-Za-z0-9-]+/', '-',$imageName);
		$name_arr = explode('.',$_FILES[$fname]['name']);
		$file_name = preg_replace('/[^A-Za-z0-9-]+/', '_',$name_arr[0]) . '.' . $name_arr[1];
		if(isset($_FILES[$fname]["error"]))
		{
			if ($_FILES[$fname]["error"] == UPLOAD_ERR_OK)
			{
				$str=$_FILES[$fname]['name'];
	
				$the_file = $_FILES[$fname]["tmp_name"];

				$to_file = "$dir/$file_name";
				//echo $the_file."----".$to_file;exit;
				move_uploaded_file($the_file, $to_file) or die('unable to upload');
				chmod($to_file, 0777);
				
				return $file_name;
			}
		}
	}*/
	
	function save_imageMultiple($imageName,$fname,$dir,$cnt)
	{
		$imageName = preg_replace('/[^A-Za-z0-9-]+/', '-',$imageName);
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
	function exec_query($strSql)
	{
		return $this->dbFunc->dbQuery($strSql);	
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
		$text = $this->smart_wordwrap($text,60,' ');
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
		$text = $this->smart_wordwrap($text,60,' ');
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
	
	function get_snippet($keyword, $txt)
	{
		$snippet='';
		$span = 15;
		preg_match_all("#(\W.{0,$span}\W)($keyword)(\W.{0,$span}\W)#i",trim($txt), $matches);
		//echo '<pre>'.trim($txt);print_r($matches);exit;
		foreach($matches[0] as $match)
		{
			if(!$match = trim($match)) continue;
			if(isset($snippet)) $snippet .= ' "...$match..." '; else $snippet = ' "...$match..." ';
		}
		$snippet = preg_replace("#($keyword)#i", '<b>$1</b>', $snippet);
		
		return $snippet;
	}
	
	function excerpt($query,$text)
	{
		//words
		$words = join('|', explode(' ', preg_quote($query)));
		
		//lookahead/behind assertions ensures cut between words
		$s = '\s\x00-/:-@\[-`{-~'; //character set for start/end of words
		preg_match_all('#(?<=['.$s.']).{1,100}(('.$words.').{1,30})+(?=['.$s.'])#uis', $text, $matches, PREG_SET_ORDER);
		
		//delimiter between occurences
		$results = array();
		foreach($matches as $line) {
			$results[] = $line[0];//htmlspecialchars($line[0], 0, 'UTF-8');
		}
		$result = '...'.join('......', $results).'...';
		
		$result = $this->showLessText($result,280);
		//highlight
		$result = preg_replace('#'.$words.'#iu', "<b>\$0</b>", $result);
		
		return $result;
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
	
	function displayMsgDate($date)
	{
		return date('j M Y H:i:s',strtotime($date));
	}
	
	function displayMsgShortDate($date)
	{
		return date('j M H:i',strtotime($date));
	}
	
	function getAge($dob)
	{
	
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
	
	function smart_wordwrap($string, $width = 75, $break = "\n")
	{
		// split on problem words over the line length
		$pattern = sprintf('/([^ ]{%d,})/', $width);
		$output = '';
		$words = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	
		foreach ($words as $word) {
			if (false !== strpos($word, ' ')) {
				// normal behaviour, rebuild the string
				$output .= $word;
			} else {
				// work out how many characters would be on the current line
				$wrapped = explode($break, wordwrap($output, $width, $break));
				$count = $width - (strlen(end($wrapped)) % $width);
	
				// fill the current line and add a break
				$output .= substr($word, 0, $count) . $break;
	
				// wrap any remaining characters from the problem word
				$output .= wordwrap(substr($word, $count), $width, $break, true);
			}
		}
	
		// wrap the final output
		return wordwrap($output, $width, $break);
	}
	
	function censorName($name)
	{
		return substr($name,0,3) . 'XXXXX';
	}
	
	function encryptor($action, $string)
	{
		$output = false;
	
		$encrypt_method = "AES-256-CBC";
		//pls set your unique hashing key
		$secret_key = 'thiruvananthapuram';
		$secret_iv = 'keralakollam';
	
		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
	
		//do the encyption given text/string/number
		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			//decrypt the given text/string/number
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
	
		return $output;
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