<?php
require_once('config.php');
$errors = array();  // array to hold validation errors
$data = array();        // array to pass back data

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'check-success-error-msg')
{
	if(isset($_SESSION['sMsg']) and $_SESSION['sMsg']!='')
	{
		$data['msg'] = $_SESSION['sMsg'];
		$data['msg_type'] = 'success';
		$data['msg_type_full'] = 'Success';
		$data['success'] = true;
		
		$_SESSION['sMsg'] = '';
		unset($_SESSION['sMsg']);
	}
	else if(isset($_SESSION['eMsg']) and $_SESSION['eMsg']!='')
	{
		$data['msg'] = $_SESSION['eMsg'];
		$data['msg_type'] = 'error';
		$data['msg_type_full'] = 'Error';
		$data['success'] = true;
		
		$_SESSION['eMsg'] = '';
		unset($_SESSION['eMsg']);
	}
	else
		$data['success'] = false;
	
	$data['TODAY_DATE'] = date('D jS M');
	$data['SITE_URL'] = SITE_URL;
	$data['ROOT_PATH'] = ROOT_PATH;
		
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'updateRating')
{
	$vis_id = $_REQUEST['vis_id'];
	$dis_id = $_REQUEST['dis_id'];
	$rating = $_REQUEST['rating'];
	
	$row = '';
	$row['rating'] = $rating;
	
	$chkAlreadyRated = $objMisc->getRow('select id from udm_ratings where user_id="'.$_SESSION['user_login'].'" and decisionspace_id="'.$dis_id.'" and vis_id="'.$vis_id.'"');
	if(is_array($chkAlreadyRated) and count($chkAlreadyRated) > 0)
	{
		$objMisc->update('udm_ratings',$row,'id="'.$chkAlreadyRated['id'].'"');
	}
	else
	{
		$row['user_id'] = $_SESSION['user_login'];
		$row['decisionspace_id'] = $dis_id;
		$row['vis_id'] = $vis_id;
		$row['dt_rated'] = date('Y-m-d H:i:s');
		
		$objMisc->insert('udm_ratings',$row);
	}
	
	$num_ratings = $total_rating = 0;
	$rating_rec = $objMisc->getRow('select count(1) cnt,sum(rating) total from udm_ratings where decisionspace_id="'.$dis_id.'" and vis_id="'.$vis_id.'"');
	if(is_array($rating_rec) and count($rating_rec) > 0)
	{
		$num_ratings = $rating_rec['cnt'];
		$total_rating = $rating_rec['total'];
		
		$average_rating = $objMisc->roundRating($total_rating/$num_ratings);
		
		$row = '';
		$row['average_rating'] = $average_rating;
		$objMisc->update('udm_decisionspace_vis',$row,'decisionspace_id="'.$dis_id.'" and vis_id="'.$vis_id.'"');
	}
	
	$rec = $objMisc->getRow('select id,average_rating from udm_decisionspace_vis where decisionspace_id="'.$dis_id.'" and vis_id="'.$vis_id.'"');
	$rating = '<fieldset class="rating_static"><input type="radio" id="stars5_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="5" disabled="disabled" '.(( $rec['average_rating']==5)?'checked="checked"':'').' /><label class = "full" for="stars5_'.$rec['id'].'" title="Awesome - 5 stars"></label><input type="radio" id="stars4half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="4 and a half" disabled="disabled" '.(( $rec['average_rating']==4.5)?'checked="checked"':'').' /><label class="half" for="stars4half_'.$rec['id'].'" title="Pretty good - 4.5 stars"></label><input type="radio" id="stars4_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="4" disabled="disabled" '.(( $rec['average_rating']==4)?'checked="checked"':'').' /><label class = "full" for="stars4_'.$rec['id'].'" title="Pretty good - 4 stars"></label><input type="radio" id="stars3half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="3 and a half" disabled="disabled"  '.(( $rec['average_rating']==3.5)?'checked="checked"':'').' /><label class="half" for="stars3half_'.$rec['id'].'" title="Meh - 3.5 stars"></label><input type="radio" id="stars3_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="3" disabled="disabled" '.(( $rec['average_rating']==3)?'checked="checked"':'').' /><label class = "full" for="stars3_'.$rec['id'].'" title="Meh - 3 stars"></label><input type="radio" id="stars2half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="2 and a half" disabled="disabled" '.(( $rec['average_rating']==2.5)?'checked="checked"':'').' /><label class="half" for="stars2half_'.$rec['id'].'" title="Kinda bad - 2.5 stars"></label><input type="radio" id="stars2_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="2" disabled="disabled" '.(( $rec['average_rating']==2)?'checked="checked"':'').' /><label class = "full" for="stars2_'.$rec['id'].'" title="Kinda bad - 2 stars"></label><input type="radio" id="stars1half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="1 and a half" disabled="disabled" '.(( $rec['average_rating']==1.5)?'checked="checked"':'').' /><label class="half" for="stars1half_'.$rec['id'].'" title="Meh - 1.5 stars"></label><input type="radio" id="stars1_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="1" disabled="disabled" '.(( $rec['average_rating']==1)?'checked="checked"':'').' /><label class = "full" for="stars1_'.$rec['id'].'" title="Sucks big time - 1 star"></label><input type="radio" id="starshalf_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="half" disabled="disabled" '.(( $rec['average_rating']==0.5)?'checked="checked"':'').' /><label class="half" for="starshalf_'.$rec['id'].'" title="Sucks big time - 0.5 stars"></label></fieldset> <span class="rating-txt">('.$num_ratings.' rating(s))</span> &nbsp; <a href="javascript:;" data-toggle="modal" data-target="#ratingDialog" onclick="loadDynamicRating('.$dis_id.','.$rec['id'].');">Change</a>';
	
	echo $rating;
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'loadDynamicRating')
{
	$vis_id = $_REQUEST['vis_id'];
	$dis_id = $_REQUEST['dis_id'];
	
	$rating = 0;
	$rec = $objMisc->getRow('select rating from udm_ratings where decisionspace_id="'.$dis_id.'" and vis_id="'.$vis_id.'" and user_id="'.$_SESSION['user_login'].'"');
	if(is_array($rec) and count($rec) > 0)
		$rating = $rec['rating'];
	
	$rating_listing = '<link href="css/rating_static.css" rel="stylesheet" />
					   <fieldset class="review_rating">
							<input type="radio" id="review_star5" name="review_rating" ng-model="review_rating" value="5" '.(($rating==5)?'checked="checked"':'').' />
							<label class = "full" for="review_star5" title="5 stars"></label>
							
							<input type="radio" id="review_star4half" name="review_rating" ng-model="review_rating" value="4.5" '.(($rating==4.5)?'checked="checked"':'').' />
							<label class="half" for="review_star4half" title="4.5 stars"></label>
							
							<input type="radio" id="review_star4" name="review_rating" ng-model="review_rating" value="4" '.(($rating==4)?'checked="checked"':'').' />
							<label class = "full" for="review_star4" title="4 stars"></label>
							
							<input type="radio" id="review_star3half" name="review_rating" ng-model="review_rating" value="3.5" '.(($rating==3.5)?'checked="checked"':'').' />
							<label class="half" for="review_star3half" title="3.5 stars"></label>
							
							<input type="radio" id="review_star3" name="review_rating" ng-model="review_rating" value="3" '.(($rating==3)?'checked="checked"':'').' />
							<label class = "full" for="review_star3" title="3 stars"></label>
							
							<input type="radio" id="review_star2half" name="review_rating" ng-model="review_rating" value="2.5" '.(($rating==2.5)?'checked="checked"':'').' />
							<label class="half" for="review_star2half" title="2.5 stars"></label>
							
							<input type="radio" id="review_star2" name="review_rating" ng-model="review_rating" value="2" '.(($rating==2)?'checked="checked"':'').' />
							<label class = "full" for="review_star2" title="2 stars"></label>
							
							<input type="radio" id="review_star1half" name="review_rating" ng-model="review_rating" value="1.5" '.(($rating==1.5)?'checked="checked"':'').' />
							<label class="half" for="review_star1half" title="1.5 stars"></label>
							
							<input type="radio" id="review_star1" name="review_rating" ng-model="review_rating" value="1" '.(($rating==1)?'checked="checked"':'').' />
							<label class = "full" for="review_star1" title="Sucks big time - 1 star"></label>
							
							<input type="radio" id="review_starhalf" name="review_rating" ng-model="review_rating" value="0.5" '.(($rating==0.5)?'checked="checked"':'').' />
							<label class="half" for="review_starhalf" title="Sucks big time - 0.5 stars"></label>
						</fieldset>';
	
	echo $rating_listing;
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'chkAddFeatToVis')
{
	$vis_id = $_REQUEST['vis_id'];
	$dis_id = $_REQUEST['dis_id'];
	$chkvals = $_REQUEST['chkvals'];
	
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='' and $vis_id!='' and $dis_id!='')
	{
		$valsArr = explode('####',$chkvals);
		
		$row = '';
		if(in_array('Rating',$valsArr))
			$row['chk_rating_added'] = 1;
		else
			$row['chk_rating_added'] = 0;
		
		if(in_array('LikeDislike',$valsArr))
			$row['chk_likedislikes_added'] = 1;
		else
			$row['chk_likedislikes_added'] = 0;
		
		if(in_array('Discussion',$valsArr))
			$row['chk_comments_added'] = 1;
		else
			$row['chk_comments_added'] = 0;
		
		$objMisc->update('udm_decisionspace_vis',$row,'decisionspace_id="'.$dis_id.'" and vis_id="'.$vis_id.'"');
		
		echo 'success';
	}
	else
		echo 'error';
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'removeVisFromDs')
{
	$vis_id = $_REQUEST['vis_id'];
	$dis_id = $_REQUEST['dis_id'];
	
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='' and $vis_id!='' and $dis_id!='')
	{
		//////for deleting all the comments
		$objMisc->exec_query('delete from udm_decisionspace_comments where ds_vis_id="'.$vis_id.'" and decisionspace_id="'.$dis_id.'"');
		
		//////for deleting connectivity with decision space
		$objMisc->exec_query('delete from udm_decisionspace_vis where vis_id="'.$vis_id.'" and decisionspace_id="'.$dis_id.'"');
		
		echo 'success';
	}
	else
		echo 'error';
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'getVis')
{
	$vis_id = $_REQUEST['vis_id'];
	$rec = $objMisc->getRow('select * from udm_visctrl where id="'.$vis_id.'"');
	if(is_array($rec) and count($vis_id) > 0)
	{
		$vis_img = '';
		$image_path = SITE_URL . 'uploads/' . stripslashes($rec['image']);
		if(@getimagesize($image_path))
		{
			$vis_img = '<img src="'.$image_path.'" style="max-width:150px;" /><br/><br/>';
		}
		
		echo 'success####'.stripslashes($rec['id']).'####'.stripslashes($rec['title']).'####'.stripslashes($rec['url']).'####'.$vis_img.'####'.stripslashes($rec['description']);
	}
	else
		echo 'error';
	exit;
}

//////////////delete visualisation/////////////////////
if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'deleteVis')
{
	$id = $_REQUEST['id'];
	
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='' and $id!='')
	{
		//////for deleting all the comments
		$objMisc->exec_query('delete from udm_decisionspace_comments where ds_vis_id="'.$id.'"');
		
		//////for deleting connectivity with decision space
		$objMisc->exec_query('delete from udm_decisionspace_vis where vis_id="'.$id.'"');
		
		//////for deleting visualisation
		$objMisc->exec_query('delete from udm_visctrl where id="'.$id.'"');
		
		echo 'success';
	}
	else
		echo 'error';
	exit;
}

//////////////get notifications/////////////////////
if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'getNotifications')
{
	$page = (isset($_REQUEST['page']) and $_REQUEST['page']!='')?$_REQUEST['page']:1;
	$rec_pp = 20;
	
	$previousPage = $page-1;
	$nextPage = $page+1;
	
	$startLimit = ($page-1)*$rec_pp;
	//$and='';
	$num_recs = $total_pages = 0;
	
	$totalRecs = $objMisc->getRow('select count(1) cnt from udm_notifications where user_id="'.$_SESSION['user_login'].'" order by dt_added desc');
	if(isset($totalRecs['cnt']) and $totalRecs['cnt']!='')
	{
		$num_recs = @$totalRecs['cnt'];
		$div_val = $num_recs/$rec_pp;
		$page_val = floor($div_val);
		$decimal_val = $div_val - $page_val;
		if($decimal_val > 0)
		{
			$page_val++;
		}
	}
	
	$getRecs = $objMisc->getAllRecords('select * from udm_notifications where user_id="'.$_SESSION['user_login'].'" order by  dt_added desc limit '.$startLimit.','.$rec_pp.'');
	$listing = '';
	if(is_array($getRecs) and count($getRecs) > 0)
	{
		$x=0;
		foreach($getRecs as $rec)
		{
			$class = '';
			if($rec['chk_read']==0)
				$class = 'unread';
			
			$enc_id = $objMisc->encodeNumber($rec['decisionspace_id']);
			$url = SITE_URL . SITE_URL_SUFFIX . 'decision-space/' . $enc_id;
			if($rec['vis_id']!=0)
				$url .= '#dsvis-'.$rec['vis_id'];
			$listing .= '<div class="notification-rec">
							<a href="'.$url.'" class="'.$class.'">'.stripslashes($rec['message']).'</a>
						</div>';
			$x++;
		}
	}
	else
	{
		$listing .= '<div class="no-recs-found"><p>No notification(s) yet.</p></div>';	
	}
	
	if($totalRecs['cnt'] > $rec_pp)
	{
		$listing .= '<div class="page__pagination text-center">';
		if($page!=1)
		{
			$prevPage = $page-1;
			$listing .= '<a href="javascript:;" class="pagination-prev" onclick="init_search('.$prevPage.');"><i class="fa fa-caret-left"></i></a>';
		}
		else
		{
			$listing .= '<span class="pagination-prev"><i class="fa fa-caret-left"></i></span>';
		}
				
		$start_val = 1;
		if($page > 4)
			$start_val = $page-3;
		
		if($page==$page_val)
			$start_val = $page-4;
		
		if($start_val < 1)
			$start_val = 1;
			
		$to_val = $start_val + 4;
		if($to_val > $page_val)
			$to_val = $page_val;
		
		for($y=$start_val;$y<=$to_val;$y++)
		{
			if($y==$page)
				$listing .= '<span class="current">'.$y.'</span>';
			else
				$listing .= '<a href="javascript:;" onclick="init_search('.$y.')">'.$y.'</a>';
		}
		
		if($page!=$page_val)
		{
			$nextPage = $page+1;
			$listing .= '<a href="javascript:;" class="pagination-next" onclick="init_search('.$nextPage.');"><i class="fa fa-caret-right"></i></a>';
		}
		else
		{
			$listing .= '<span class="pagination-next"><i class="fa fa-caret-right"></i></span>';
		}
		$listing .= '</div>';
	}
	
	///////mark all notifications as read///////////////
	$row = '';
	$row['chk_read']=1;
	$objMisc->update('udm_notifications',$row,'user_id="'.$_SESSION['user_login'].'"');
	///////////////////////////////////////////////////
	
	$data['listing'] = $listing;
	$data['success'] = true;
	
	echo json_encode($data);
	exit;
}

//////////////for loading decision space visualisation starts/////////////////////
if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'getDSVisualisations')
{
	$listing = '';
	$allRecs = $objMisc->getAllRecords('select vis_id from udm_decisionspace_vis where decisionspace_id="'.$_REQUEST['dsid'].'"');
	if(is_array($allRecs) and count($allRecs) > 0)
	{
		foreach($allRecs as $rec)
		{
			$listing .= getDsVis($rec['vis_id'],$_REQUEST['dsid']);
		}
	}
	else
		$listing .= '<div class="no-recs-found">No bundles added yet. Please add visualisation to add bundle.</div>';
	
	echo $listing;
	exit;
}
//////////////for loading decision space visualisation ends///////////////////////

//////////////for add visualisation to decision space starts/////////////////////
if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'addVisualisationToDS')
{
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		//////////check if visualisation is already added////////////
		$chkAlrAdded = $objMisc->getRow('select 1 from udm_decisionspace_vis where decisionspace_id="'.$_REQUEST['dsid'].'" and vis_id="'.$_REQUEST['visid'].'"');
		if(is_array($chkAlrAdded) and count($chkAlrAdded) > 0)
		{
			echo 'already-added';
		}
		else
		{
			$row = array(
							'user_id' => $_SESSION['user_login'],
							'decisionspace_id' => $_REQUEST['dsid'],
							'vis_id' => $_REQUEST['visid'],
							'dt_added' => date('Y-m-d H:i:s')
						);
			$objMisc->insert('udm_decisionspace_vis',$row);
			$ds_vis_id = mysqli_insert_id($db);
			//$ds_vis_id = mysql_insert_id();
			
			//////add to notifications for all the members and admin///////
			$allMembers = $objMisc->getAllRecords('select id from udm_user where roles="Admin" or email in (select email from udm_decisionspace_invitations where decisionspace_id="'.$_REQUEST['dsid'].'")');
			if(is_array($allMembers) and count($allMembers) > 0)
			{
				$recDs = $objMisc->getRow('select title from udm_decisionspace where id="'.$_REQUEST['dsid'].'"');
				foreach($allMembers as $member)
				{
					$rowN = array(
									'user_id' => $member['id'],
									'decisionspace_id' => $_REQUEST['dsid'],
									'vis_id' => $_REQUEST['visid'],
									'message' => 'A visualisation added to decision space - ' . stripslashes($recDs['title']),
									'dt_added' => date('Y-m-d H:i:s')
								 );
					$objMisc->insert('udm_notifications',$rowN);
				}
			}
			///////////////////////////////////////////////////////////////
			
			$listing = getDsVis($_REQUEST['visid'],$_REQUEST['dsid']);
		
			echo 'success####'.$listing;
		}
	}
	else
		echo 'login-error';
	exit;
}
//////////////for add visualisation to decision space ends/////////////////////

function getDsVis($visid,$dis_id)
{
	global $objMisc;
	
	$listing = '';
	$rec = $objMisc->getRow('select uv.*,udv.chk_rating_added,udv.chk_likedislikes_added,udv.chk_comments_added,udv.average_rating,udv.num_likes,udv.num_dislikes from udm_visctrl uv left join udm_decisionspace_vis udv on (uv.id=udv.vis_id) where uv.id="'.$visid.'" and udv.decisionspace_id="'.$dis_id.'"');
	if(is_array($rec) and count($rec) > 0)
	{
		$comments_list = file_get_contents(SITE_URL . 'scripts/process.php?act=loadDsComments&id='.$rec['decisionspace_id'].'&ds_vis_id='.$rec['id']);
		
		$img_html = '';
		if($rec['image']!='')
			$img_html = '<div class="vis-img"><img src="'.SITE_URL.'uploads/'.$rec['image'].'" /></div>';
		else if($rec['url']!='')
		{
			$iframe_url = stripslashes($rec['url']);//'https://www.google.com/search?q='.stripslashes($rec['url']).'&btnI=Im+Feeling+Lucky';
			$chkImage = getimagesize($iframe_url);
			if(is_array($chkImage))
			{
				$iheight  = $chkImage[1]+20;
				if($iheight > 600)
					$iheight = 600;
				$iframeStyle = 'style="height:'.$iheight.'px; border:none;"';
			}
			else
				$iframeStyle = 'style="height:500px;"';
			$img_html = '<div class="vis-iframe"><iframe id="iframe-'.$visid.'" src="'.$iframe_url.'" '.$iframeStyle.'></iframe></div>';
		}
		
		$link_txt = '';
		if($rec['url']!='')
			$link_txt = '<a href="'.stripslashes($rec['url']).'" target="_blank" class="vis-url">'.stripslashes($rec['url']).'</a>';
		
		$rate_txt = 'Rate Now';
		$chkAlrRated = $objMisc->GiveValue('select count(1) cnt from udm_ratings where decisionspace_id="'.$rec['decisionspace_id'].'" and  vis_id="'.$visid.'" and user_id="'.$_SESSION['user_login'].'"');
		if($chkAlrRated > 0)
		{
			$rate_txt = 'Change';
		}
		
		$num_ratings = $objMisc->GiveValue('select count(1) cnt from udm_ratings where decisionspace_id="'.$rec['decisionspace_id'].'" and  vis_id="'.$visid.'"');
		$rating = '<fieldset class="rating_static"><input type="radio" id="stars5_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="5" disabled="disabled" '.(( $rec['average_rating']==5)?'checked="checked"':'').' /><label class = "full" for="stars5_'.$rec['id'].'" title="Awesome - 5 stars"></label><input type="radio" id="stars4half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="4 and a half" disabled="disabled" '.(( $rec['average_rating']==4.5)?'checked="checked"':'').' /><label class="half" for="stars4half_'.$rec['id'].'" title="Pretty good - 4.5 stars"></label><input type="radio" id="stars4_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="4" disabled="disabled" '.(( $rec['average_rating']==4)?'checked="checked"':'').' /><label class = "full" for="stars4_'.$rec['id'].'" title="Pretty good - 4 stars"></label><input type="radio" id="stars3half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="3 and a half" disabled="disabled"  '.(( $rec['average_rating']==3.5)?'checked="checked"':'').' /><label class="half" for="stars3half_'.$rec['id'].'" title="Meh - 3.5 stars"></label><input type="radio" id="stars3_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="3" disabled="disabled" '.(( $rec['average_rating']==3)?'checked="checked"':'').' /><label class = "full" for="stars3_'.$rec['id'].'" title="Meh - 3 stars"></label><input type="radio" id="stars2half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="2 and a half" disabled="disabled" '.(( $rec['average_rating']==2.5)?'checked="checked"':'').' /><label class="half" for="stars2half_'.$rec['id'].'" title="Kinda bad - 2.5 stars"></label><input type="radio" id="stars2_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="2" disabled="disabled" '.(( $rec['average_rating']==2)?'checked="checked"':'').' /><label class = "full" for="stars2_'.$rec['id'].'" title="Kinda bad - 2 stars"></label><input type="radio" id="stars1half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="1 and a half" disabled="disabled" '.(( $rec['average_rating']==1.5)?'checked="checked"':'').' /><label class="half" for="stars1half_'.$rec['id'].'" title="Meh - 1.5 stars"></label><input type="radio" id="stars1_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="1" disabled="disabled" '.(( $rec['average_rating']==1)?'checked="checked"':'').' /><label class = "full" for="stars1_'.$rec['id'].'" title="Sucks big time - 1 star"></label><input type="radio" id="starshalf_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="half" disabled="disabled" '.(( $rec['average_rating']==0.5)?'checked="checked"':'').' /><label class="half" for="starshalf_'.$rec['id'].'" title="Sucks big time - 0.5 stars"></label></fieldset> <span class="rating-txt">('.$num_ratings.' rating(s))</span> &nbsp; <a href="javascript:;" data-toggle="modal" data-target="#ratingDialog" onclick="loadDynamicRating('.$rec['decisionspace_id'].','.$rec['id'].');">'.$rate_txt.'</a>';
		
		$otherMetaClass = $commentsClass = $ratingClass = $likeDislikeClass = 'hide';
		if($rec['chk_rating_added']==1)
			$otherMetaClass = $ratingClass = '';
		if($rec['chk_likedislikes_added']==1)
			$otherMetaClass = $likeDislikeClass = '';
		if($rec['chk_comments_added']==1)
			$commentsClass = '';
		
		$listing .= '<div class="vis-outer" id="dsvis-'.$rec['id'].'">
						<div class="vis-title">'.stripslashes($rec['title']).' <a href="javascript:;" class="remove-vis" onclick="removeVisFromDs('.$rec['id'].','.$rec['decisionspace_id'].');" title="Remove this Visualisation from here"><i class="fa fa-trash-o" aria-hidden="true"></i></a> <a href="javascript:;" class="add-feature" onclick="addFeatureToDs('.$rec['id'].','.$rec['decisionspace_id'].');" title="Add feature to this Visualisation" data-toggle="modal" data-target="#addFeatureDialog"><i class="fa fa-plus-square" aria-hidden="true"></i></a></div>
						'.$img_html.'
						'.$link_txt.'
						<p>'.stripslashes($rec['description']).'</p>
						<div class="other-meta vis-meta-'.$rec['id'].' '.$otherMetaClass.'">
							<div class="row">
								<div class="col-xs-6">
									<div class="rating-cont '.$ratingClass.'">'.$rating.'</div>
								</div>
								<div class="col-xs-6 like-dislike-outer '.$likeDislikeClass.'">
									<div class="like-dislike-cont">
										<a href="javascript:;" class="dislike-cont" onclick="likeDislikeVis(\'Dislike\','.$rec['id'].');"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> <span class="num-dislikes-'.$rec['id'].'">'.$rec['num_dislikes'].'</span>
									</div>
									<div class="like-dislike-cont">
										<a href="javascript:;" class="like-cont" onclick="likeDislikeVis(\'Like\','.$rec['id'].');"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> <span class="num-likes-'.$rec['id'].'">'.$rec['num_likes'].'</span>
									</div>
								</div>
							</div>
						</div>
						<div class="vis-comments comments vis-comments-'.$rec['id'].' '.$commentsClass.'">
							<div class="comment-form">
								<form class="frm-main" name="commentvis'.$rec['id'].'" id="commentvis'.$rec['id'].'" method="post" novalidate="novalidate" autocomplete="off" onsubmit="return chkVisCommentFrm('.$rec['id'].','.$rec['decisionspace_id'].')">
									<div class="row">
										<div class="col-md-10 col-sm-9">
											<textarea name="message'.$rec['id'].'" id="message'.$rec['id'].'" placeholder="Write a comment" value="" onkeyup="chkEmptyMsg('.$rec['id'].');"></textarea>
										</div>
										<div class="col-md-2 col-sm-3">
											<button type="submit" class="btn btn-md btn-primary disabled-button btn-submit'.$rec['id'].'">Post</button>
										</div>
									</div>
								</form>
							</div>
							<div class="comments-archive vis-comments-archive-'.$rec['id'].'">'.$comments_list.'</div>
						</div>
					</div>';
	}
	return $listing;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'getDsVis')
{
	$rec = $objMisc->getRow('select chk_comments_added,chk_rating_added,chk_likedislikes_added from udm_decisionspace_vis where decisionspace_id="'.$_REQUEST['dis_id'].'" and vis_id="'.$_REQUEST['vis_id'].'"');
	
	echo $rec['chk_comments_added'].'####'.$rec['chk_rating_added'].'####'.$rec['chk_likedislikes_added'];
	exit;
}

//////////////get top topics for home page sidebar starts/////////////////////
if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'loadTopTopics')
{
	///////for mostly viewed/////////
	$listing = '';
	$recs = $objMisc->getAllRecords('select * from udm_decisionspace where published=1 and deleted=0 order by num_views desc limit 0,5');
	if(is_array($recs) and count($recs) > 0)
	{
		$listing .= '<ul>';
		foreach($recs as $rec)
		{
			$enc_id = $objMisc->encodeNumber($rec['id']);
			$listing .= '<li><a href="'.SITE_URL.SITE_URL_SUFFIX.'decision-space/'.$enc_id.'">'.stripslashes($rec['title']).'</a></li>';
		}
		$listing .= '</ul>';
	}
	else
	{
		$listing .= '<div class="no-recs-found">No data available.</div>';
	}
	
	///////my decision spaces/////////
	$listing1 = '';
	$recs = $objMisc->getAllRecords('select * from udm_decisionspace where user_id="'.$_SESSION['user_login'].'" and published=1 and deleted=0 order by num_views desc limit 0,10');
	if(is_array($recs) and count($recs) > 0)
	{
		$listing1 .= '<ul>';
		foreach($recs as $rec)
		{
			$enc_id = $objMisc->encodeNumber($rec['id']);
			$listing1 .= '<li><a href="'.SITE_URL.SITE_URL_SUFFIX.'decision-space/'.$enc_id.'">'.stripslashes($rec['title']).'</a></li>';
		}
		$listing1 .= '</ul>';
	}
	else
	{
		$listing1 .= '<div class="no-recs-found">No data available.</div>';
	}
	
	echo $listing . '####' . $listing1;
	exit;
}
//////////////get top topics for home page sidebar starts/////////////////////

//////////////Submit decision space comment starts/////////////////////
if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'submitDSComment')
{
	$msg = '';
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		$row = array(
					'user_id' => $_SESSION['user_login'],
					'decisionspace_id' => $_REQUEST['dspid'],
					'message' => addslashes($_REQUEST['message'])
				);
		if(isset($_REQUEST['ds_vis_id']) and $_REQUEST['ds_vis_id']!='')
		{
			$row['ds_vis_id'] = $_REQUEST['ds_vis_id'];
		}
				
		$row['dt_added'] = date('Y-m-d H:i:s');
		$objMisc->insert('udm_decisionspace_comments', $row);
		
		$recDs = $objMisc->getRow('select title,user_id from udm_decisionspace where id="'.$_REQUEST['dspid'].'"');
		
		//////add to notifications for all the members and admin///////
		$allMembers = $objMisc->getAllRecords('select id,first_name,last_name from udm_user where (roles="Admin" or email in (select email from udm_decisionspace_invitations where decisionspace_id="'.$_REQUEST['dspid'].'") or id="'.$recDs['user_id'].'") and id!="'.$_SESSION['user_login'].'"');
		//echo '<pre>';print_r($allMembers);exit;
		if(is_array($allMembers) and count($allMembers) > 0)
		{
			foreach($allMembers as $member)
			{
				$rowN = array(
								'user_id' => $member['id'],
								'decisionspace_id' => $_REQUEST['dspid'],
								'message' => 'A comment posted by '.stripslashes($_SESSION['user_arr']['first_name'].' '.$_SESSION['user_arr']['last_name']).' on decision space - ' . stripslashes($recDs['title']),
								'dt_added' => date('Y-m-d H:i:s')
							 );
				if(isset($_REQUEST['ds_vis_id']) and $_REQUEST['ds_vis_id']!='')
				{
					$rowN['vis_id'] = $_REQUEST['ds_vis_id'];
				}
				$objMisc->insert('udm_notifications',$rowN);
			}
		}
		///////////////////////////////////////////////////////////////
		
		$data['success'] = true;
		$data['msg'] = 'Comment added successfully';
	}
	else
	{
		$data['success'] = false;
		$data['msg'] = 'Please login to do this action.';
	}
	
	echo json_encode($data);
	exit;
}
//////////////Submit decision space comment ends/////////////////////

//////////////get decision space comments starts/////////////////////
if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'loadDsComments')
{
	$dsid = $_REQUEST['dsid'];
	
	$and = '';
	if(isset($_REQUEST['ds_vis_id']) and $_REQUEST['ds_vis_id']!='')
	{
		$and .= ' and ds_vis_id="'.$_REQUEST['ds_vis_id'].'"';
		$ds_vis_id = $_REQUEST['ds_vis_id'];
	}
	else
	{
		$and .= ' and ds_vis_id=0';
		$ds_vis_id = 0;
	}
	$page = (isset($_REQUEST['page']) and $_REQUEST['page']!='')?$_REQUEST['page']:1;
	$rec_pp = 5;
		
	$start_limit = ($page-1)*$rec_pp;
	
	$getRecs = $objMisc->getAllRecords('select c.*,u.first_name,u.last_name,u.photo,u.gender from udm_decisionspace_comments c left join udm_user u on (u.id=c.user_id) where c.decisionspace_id="'.$_REQUEST['id'].'" '.$and.' order by c.dt_added desc limit '.$start_limit.','.$rec_pp.'');
	if(is_array($getRecs) and count($getRecs) > 0)
	{
		$x=0;
		foreach($getRecs as $rec)
		{
			$memberPhoto = SITE_URL . 'uploads/' . stripslashes($rec['photo']);
			if(!@getimagesize($memberPhoto))
			{
				if($rec['gender'] == 'Male')
					$memberPhoto = SITE_URL .'images/user-male.png';
				else
					$memberPhoto = SITE_URL .'images/user-female.png';
			}
			
			$listing .= '<div class="msg-rec">
							<div class="photo"><img src="'.$memberPhoto.'" /></div>
							<div class="msg">
								<b>'.stripslashes($rec['first_name'] . ' ' . $rec['last_name']).'</b><br/>
								'.stripslashes($rec['message']).'
								<div class="msg-time">
									'.$objMisc->displaySubmitTime($rec['dt_added']).'
								</div>
							</div>
						 </div>';
		}
		
		$next_start_limit = ($page)*$rec_pp;
		$cntNext = $objMisc->getRow('select c.*,u.first_name,u.last_name,u.photo,u.gender from udm_decisionspace_comments c left join udm_user u on (u.id=c.user_id) where c.decisionspace_id="'.$_REQUEST['id'].'" '.$and.' order by c.dt_added desc limit '.$next_start_limit.','.$rec_pp.'');
		if(is_array($cntNext) and count($cntNext) > 0)
		{
			if($ds_vis_id!=0)
				$class = 'view-more-vis-comments-'.$ds_vis_id;
			else
				$class = 'view-more-dis-comments';
			$listing .= '<div class="view-more-msgs '.$class.'"><a class="btn btn-success" href="javascript:;" onclick="loadDsComments('.$_REQUEST['id'].','.($page+1).','.$ds_vis_id.')">View more messages</a></div>';
		}
	}
	else if($page==1)
	{
		$listing .= '<div class="no-recs-found"><p>No comment(s) yet.</p></div>';	
	}
	
	echo $listing;
	exit;
}
//////////////get decision space comments ends/////////////////////

//////////////get search results/////////////////////
if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'getSearchResults')
{
	if(isset($_REQUEST['keyword']) and $_REQUEST['keyword']!='undefined' and $_REQUEST['keyword']!='')
	{
		$and .= ' and (d.title like "%'.$_REQUEST['keyword'].'%" or d.description like "%'.$_REQUEST['keyword'].'%")';
	}
	
	$and .= ' and (d.type="Public" or d.id in (select udi.decisionspace_id from udm_decisionspace_invitations udi left join udm_user u on (u.email=udi.email) where u.id="'.$_SESSION['user_login'].'"))';
	
	$page = (isset($_REQUEST['page']) and $_REQUEST['page']!='')?$_REQUEST['page']:1;
	$rec_pp = 10;
	
	$previousPage = $page-1;
	$nextPage = $page+1;
	
	$startLimit = ($page-1)*$rec_pp;
	//$and='';
	$num_recs = $total_pages = 0;
	
	$totalRecs = $objMisc->getRow('select count(1) cnt from udm_decisionspace d left join udm_user u on (u.id=d.user_id) where d.published=1 and d.deleted=0 '.$and.' order by d.created_date desc');
	if(isset($totalRecs['cnt']) and $totalRecs['cnt']!='')
	{
		$num_recs = @$totalRecs['cnt'];
		$div_val = $num_recs/$rec_pp;
		$page_val = floor($div_val);
		$decimal_val = $div_val - $page_val;
		if($decimal_val > 0)
		{
			$page_val++;
		}
	}
	
	$getRecs = $objMisc->getAllRecords('select d.*,u.first_name,u.last_name from udm_decisionspace d left join udm_user u on (u.id=d.user_id) where d.published=1 and d.deleted=0 '.$and.' order by d.created_date desc limit '.$startLimit.','.$rec_pp.'');
	$listing = '';
	if(is_array($getRecs) and count($getRecs) > 0)
	{
		$x=0;
		foreach($getRecs as $rec)
		{
			$enc_id = $objMisc->encodeNumber($rec['id']);
			
			$img_path = SITE_URL . 'uploads/' . stripslashes($rec['path']);
			if(!@getimagesize($img_path))
			{
				$img_path = '';
			}
			
			$listing .= '<div class="search-rec">';
			if($img_path != '')
			{
			   $listing .= '<div class="photo">
								<a href="'.SITE_URL.SITE_URL_SUFFIX.'decision-space/'.$enc_id.'" title="'.stripslashes($rec['title']).'" style="background-image:url('.$img_path.');"></a>
							</div>';
			}  
			   $listing .= '<h2><a href="'.SITE_URL.SITE_URL_SUFFIX.'decision-space/'.$enc_id.'" title="'.stripslashes($rec['title']).'">'.stripslashes($rec['title']).'</a></h2>
							<p>'.$objMisc->showLessText(stripslashes($rec['description']),480).'</p>
							<div class="rec">
								<div class="row">
									<div class="col-sm-4">
										<b>Author: </b> '.stripslashes($rec['first_name'] . ' ' . $rec['last_name']).'
									</div>
									<div class="col-sm-4">
										<b>Created on: </b> '.$objMisc->formatDate($rec['created_date']).'
									</div>
									<div class="col-sm-4">
										<b>Last updated</b> '.$objMisc->formatDate($rec['updated']).'
									</div>
								</div>
							</div>
						</div>';
			$x++;
		}
	}
	else
	{
		$listing .= '<div class="no-recs-found"><p>No record(s) found.</p></div>';	
	}
	
	if($totalRecs['cnt'] > $rec_pp)
	{
		$listing .= '<div class="page__pagination text-center">';
		if($page!=1)
		{
			$prevPage = $page-1;
			$listing .= '<a href="javascript:;" class="pagination-prev" onclick="init_search('.$prevPage.');"><i class="fa fa-caret-left"></i></a>';
		}
		else
		{
			$listing .= '<span class="pagination-prev"><i class="fa fa-caret-left"></i></span>';
		}
				
		$start_val = 1;
		if($page > 4)
			$start_val = $page-3;
		
		if($page==$page_val)
			$start_val = $page-4;
		
		if($start_val < 1)
			$start_val = 1;
			
		$to_val = $start_val + 4;
		if($to_val > $page_val)
			$to_val = $page_val;
		
		for($y=$start_val;$y<=$to_val;$y++)
		{
			if($y==$page)
				$listing .= '<span class="current">'.$y.'</span>';
			else
				$listing .= '<a href="javascript:;" onclick="init_search('.$y.')">'.$y.'</a>';
		}
		
		if($page!=$page_val)
		{
			$nextPage = $page+1;
			$listing .= '<a href="javascript:;" class="pagination-next" onclick="init_search('.$nextPage.');"><i class="fa fa-caret-right"></i></a>';
		}
		else
		{
			$listing .= '<span class="pagination-next"><i class="fa fa-caret-right"></i></span>';
		}
		$listing .= '</div>';
	}
	
	$data['listing'] = $listing;
	$data['success'] = true;
	
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'pubunpub_listing')
{
	$id = $_REQUEST['id'];
	$status = $_REQUEST['status'];
	
	$row = '';
	$row['published'] = $status;
	$objMisc->update('udm_decisionspace',$row,'id="'.$id.'"');
	
	echo 'success';
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'get-decision-space')
{
	$id = $_REQUEST['id'];
	if(isset($_REQUEST['type']) and $_REQUEST['type']=='enc')
		$id = $objMisc->decodeNumber($id);
	
	$rec = $objMisc->getRow('select * from udm_decisionspace where id='.$id);
	if(is_array($rec) and count($rec) > 0)
	{
		///////check permission for current user/////////
		$user_type = '';
		if($_SESSION['user_type'] == 'Admin')
			$user_type = 'Admin';
		else if($rec['user_id'] == $_SESSION['user_login'])
			$user_type = 'Owner';
		else
		{
			$get_user_type = $objMisc->getRow('select udi.role from udm_decisionspace_invitations udi left join udm_user u on (u.email=udi.email) where u.id="'.$_SESSION['user_login'].'" and udi.decisionspace_id="'.$id.'"');
			if(is_array($get_user_type) and count($get_user_type) > 0)
			{
				$user_type = $get_user_type['role'];
			}
		}
		/////////////////////////////////////////////////
		
		if($user_type=='' and $rec['type']=='Private')
			$data['success'] = false;
		else
		{
			$rec['SITE_URL'] = SITE_URL;
			
			$img_path = SITE_URL . 'uploads/' . stripslashes($rec['path']);
			$img_tag = '<div class="detail-img"><img src="'.SITE_URL . 'uploads/' . stripslashes($rec['path']).'" /></div>';
			if(!@getimagesize($img_path))
			{
				$img_tag = '';
			}
			
			///////////for updating views///////////
			$row = '';
			$row['num_views'] = $rec['num_views']+1;
			$objMisc->update('udm_decisionspace',$row,'id="'.$id.'"');
			////////////////////////////////////////
			
			$rec_user = $objMisc->getRow('select title,first_name,last_name from udm_user where id="'.$rec['user_id'].'"');
			$chkAlrRated = $objMisc->GiveValue('select count(1) cnt from udm_ratings where decisionspace_id="'.$id.'" and user_id="'.$_SESSION['user_login'].'"');
			$num_ratings = $objMisc->GiveValue('select count(1) cnt from udm_ratings where decisionspace_id="'.$id.'"');
			$rating = '<fieldset class="rating_static"><input type="radio" id="stars5_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="5" disabled="disabled" '.(( $rec['average_rating']==5)?'checked="checked"':'').' /><label class = "full" for="stars5_'.$rec['id'].'" title="Awesome - 5 stars"></label><input type="radio" id="stars4half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="4 and a half" disabled="disabled" '.(( $rec['average_rating']==4.5)?'checked="checked"':'').' /><label class="half" for="stars4half_'.$rec['id'].'" title="Pretty good - 4.5 stars"></label><input type="radio" id="stars4_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="4" disabled="disabled" '.(( $rec['average_rating']==4)?'checked="checked"':'').' /><label class = "full" for="stars4_'.$rec['id'].'" title="Pretty good - 4 stars"></label><input type="radio" id="stars3half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="3 and a half" disabled="disabled"  '.(( $rec['average_rating']==3.5)?'checked="checked"':'').' /><label class="half" for="stars3half_'.$rec['id'].'" title="Meh - 3.5 stars"></label><input type="radio" id="stars3_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="3" disabled="disabled" '.(( $rec['average_rating']==3)?'checked="checked"':'').' /><label class = "full" for="stars3_'.$rec['id'].'" title="Meh - 3 stars"></label><input type="radio" id="stars2half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="2 and a half" disabled="disabled" '.(( $rec['average_rating']==2.5)?'checked="checked"':'').' /><label class="half" for="stars2half_'.$rec['id'].'" title="Kinda bad - 2.5 stars"></label><input type="radio" id="stars2_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="2" disabled="disabled" '.(( $rec['average_rating']==2)?'checked="checked"':'').' /><label class = "full" for="stars2_'.$rec['id'].'" title="Kinda bad - 2 stars"></label><input type="radio" id="stars1half_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="1 and a half" disabled="disabled" '.(( $rec['average_rating']==1.5)?'checked="checked"':'').' /><label class="half" for="stars1half_'.$rec['id'].'" title="Meh - 1.5 stars"></label><input type="radio" id="stars1_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="1" disabled="disabled" '.(( $rec['average_rating']==1)?'checked="checked"':'').' /><label class = "full" for="stars1_'.$rec['id'].'" title="Sucks big time - 1 star"></label><input type="radio" id="starshalf_'.$rec['id'].'" name="prod_rating_'.$rec['id'].'" value="half" disabled="disabled" '.(( $rec['average_rating']==0.5)?'checked="checked"':'').' /><label class="half" for="starshalf_'.$rec['id'].'" title="Sucks big time - 0.5 stars"></label></fieldset> <span class="rating-txt">('.$num_ratings.' ratings)</span> &nbsp; <a href="javascript:;">Rate Now</a>';
			
			$data['rec'] = $rec;
			$data['rating'] = $rating;
			$data['author'] = stripslashes($rec_user['title'] . ' ' . $rec_user['first_name'] . ' ' . $rec_user['last_name']);
			$data['created_on'] = $objMisc->formatDate($rec['created_date']);
			$data['last_updated'] = $objMisc->formatDate($rec['updated']);
			$data['path'] = stripslashes($rec['path']);
			$data['img_tag'] = $img_tag;
			$data['title'] = stripslashes($rec['title']);
			$data['description'] = stripslashes($rec['description']);
			$data['user_type'] = $user_type;
			$data['success'] = true;
		}
	}
	else
		$data['success'] = false;
	
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'get-visualisations')
{
	$id = $_REQUEST['id'];
	
	$listing = '';
	//$allRecs = $objMisc->getAllRecords('select id,title from udm_visctrl where decisionspace_id='.$id.' and deleted=0 and id not in (select vis_id from udm_decisionspace_vis where decisionspace_id="'.$id.'")');
	$allRecs = $objMisc->getAllRecords('select id,title,user_id from udm_visctrl where decisionspace_id='.$id.' and deleted=0');
	if(is_array($allRecs) and count($allRecs) > 0)
	{
		foreach($allRecs as $rec)
		{
			/////////check if visualisation added to decision space////////////
			$alrAddedChk = '';
			$alrAdded = 'N';
			$chkVisAdded = $objMisc->getRow('select 1 from udm_decisionspace_vis where decisionspace_id="'.$id.'" and vis_id="'.$rec['id'].'"');
			if(is_array($chkVisAdded) and count($chkVisAdded) > 0)
			{
				$alrAdded = 'Y';
				$alrAddedChk = '<i class="fa fa-check fl-rt" aria-hidden="true"></i>';
			}
			///////check permission for current user/////////
			$user_type = '';
			if($_SESSION['user_type'] == 'Admin')
				$user_type = 'Admin';
			else if($rec['user_id'] == $_SESSION['user_login'])
				$user_type = 'Owner';
			else
			{
				$get_user_type = $objMisc->getRow('select udi.role from udm_decisionspace_invitations udi left join udm_user u on (u.email=udi.email) where u.id="'.$_SESSION['user_login'].'" and udi.decisionspace_id="'.$id.'"');
				if(is_array($get_user_type) and count($get_user_type) > 0)
				{
					$user_type = $get_user_type['role'];
				}
			}
			
			$editdel_link = '';
			if($user_type!='Viewer' and $user_type!='')
			{
				$editdel_link = '<a href="javascript:;" data-toggle="modal" data-target="#editVisDialog" class="edit-icon" onclick="editVis('.$rec['id'].');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								<a href="javascript:;" class="remove-icon" onclick="chkDelVis('.$rec['id'].',\''.$alrAdded.'\');"><i class="fa fa-times-circle" aria-hidden="true"></i></a>';
			}
			/////////////////////////////////////////////////
			
			$listing .= '<li draggable="true" ondragstart="dragModal(event)"><a href="javascript:;" id="vis-'.$rec['id'].'" class="cursor-move">'.stripslashes($rec['title']).$alrAddedChk.'</a>'.$editdel_link.'</li>';
		}
	}
	else
		$listing .= '<li class="no-recs"><a>No visualisation(s) yet.</a></li>';
	
	$data['listing'] = $listing;
	$data['success'] = true;
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'update-visualisation')
{
	$msg = '';
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		$timestamp = time();
		$path = '';
		
		if(isset($_FILES['image1']) and $_FILES['image1']['name']!='') 
		{
			if(!is_dir(ROOT_PATH . "uploads/visualisation/" . $_SESSION['user_login']))
			{
				@mkdir(ROOT_PATH . "uploads/visualisation/" . $_SESSION['user_login']);
			}
			
			// Define a destination
			$targetFolder = 'uploads/visualisation/'.$_SESSION['user_login'].'/'; // Relative to the root
			$root_path = ROOT_PATH . $targetFolder;
			
			$file_name = str_replace(' ','_',$_FILES['image1']['name']);
			$tempFile = $_FILES['image1']['tmp_name'];
			$targetFile = $root_path . $timestamp . $file_name;
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($file_name);
			
			if (in_array(strtolower($fileParts['extension']),$fileTypes)) 
			{
				@move_uploaded_file($tempFile,$targetFile);
				
				$path = 'visualisation/'.$_SESSION['user_login'].'/' . $timestamp  . $file_name;
			}
			else
			{
				$msg = 'error';
				$_SESSION['eMsg'] = 'The file type is invalid. Please upload only .jpg, .jpeg, .gif or .png files.';
			}
		}
		
		$row = array(
					'title' => addslashes($_POST['title1']),
					'url' => addslashes($_POST['url1']),
					'description' => addslashes($_POST['description1'])
				);
				
		if($path!='')
		{
			$row['image'] = $path;
		}
		
		$objMisc->update('udm_visctrl',$row,'id="'.$_REQUEST['vis_id'].'"');
		
		$msg = 'success';
		$_SESSION['sMsg'] = 'Visualisation updated successfully';
	}
	else
	{
		$msg = 'error';
		$_SESSION['eMsg'] = 'Please login to do this action.';
	}

	header('location: '. SITE_URL . SITE_URL_SUFFIX . 'decision-space/'.$objMisc->encodeNumber($_REQUEST['vdsid']));
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'add-visualisation')
{
	$msg = '';
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		$timestamp = time();
		$path = '';
		
		if(isset($_FILES['image']) and $_FILES['image']['name']!='') 
		{
			if(!is_dir(ROOT_PATH . "uploads/visualisation/" . $_SESSION['user_login']))
			{
				@mkdir(ROOT_PATH . "uploads/visualisation/" . $_SESSION['user_login']);
			}
			
			// Define a destination
			$targetFolder = 'uploads/visualisation/'.$_SESSION['user_login'].'/'; // Relative to the root
			$root_path = ROOT_PATH . $targetFolder;
			
			$file_name = str_replace(' ','_',$_FILES['image']['name']);
			$tempFile = $_FILES['image']['tmp_name'];
			$targetFile = $root_path . $timestamp . $file_name;
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($file_name);
			
			if (in_array(strtolower($fileParts['extension']),$fileTypes)) 
			{
				@move_uploaded_file($tempFile,$targetFile);
				
				$path = 'visualisation/'.$_SESSION['user_login'].'/' . $timestamp  . $file_name;
			}
			else
			{
				$msg = 'error';
				$_SESSION['eMsg'] = 'The file type is invalid. Please upload only .jpg, .jpeg, .gif or .png files.';
			}
		}
		
		$row = array(
					'user_id' => $_SESSION['user_login'],
					'decisionspace_id' => $_POST['dsid'],
					'title' => addslashes($_POST['title']),
					'url' => addslashes($_POST['url']),
					'description' => addslashes($_POST['description'])
				);
				
		if($path!='')
		{
			$row['image'] = $path;
		}
		
		$row['created_date'] = date('Y-m-d H:i:s');
		$objMisc->insert('udm_visctrl', $row);
		$vis_id = mysqli_insert_id($db);
		//$vis_id = mysql_insert_id();
		
		$msg = 'success';
		$_SESSION['sMsg'] = 'Visualisation added successfully';
	}
	else
	{
		$msg = 'error';
		$_SESSION['eMsg'] = 'Please login to do this action.';
	}

	header('location: '. SITE_URL . SITE_URL_SUFFIX . 'decision-space/'.$objMisc->encodeNumber($_POST['dsid']));
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'addFeatureToDS')
{
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		$chkAlrAdded = 'N';
		$recDis = $objMisc->getRow('select chk_rating_added,chk_likedislikes_added from udm_decisionspace where id="'.$_REQUEST['dsid'].'"');
		if(is_array($recDis) and count($recDis) > 0)
		{
			if($_REQUEST['type'] == 'Rating' and $recDis['chk_rating_added']==1)
				$chkAlrAdded = 'Y';
			else if($_REQUEST['type'] == 'LikeDislikes' and $recDis['chk_likedislikes_added']==1)
				$chkAlrAdded = 'Y';
		}
		
		if($chkAlrAdded != 'Y')
		{
			$row = '';
			if($_REQUEST['type'] == 'Rating')
				$row['chk_rating_added'] = 1;
			else
				$row['chk_likedislikes_added'] = 1;
			
			$objMisc->update('udm_decisionspace',$row,'id="'.$_REQUEST['dsid'].'"');
		}
		
		echo 'success####' . $chkAlrAdded;
	}
	else
		echo 'error';
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'likeDislikeVis')
{
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		$chkAlrDone = $objMisc->getRow('select count(1) cnt from udm_likes_dislikes where user_id="'.$_SESSION['user_login'].'" and decisionspace_id="'.$_REQUEST['dsid'].'" and vis_id="'.$_REQUEST['vis_id'].'" and type="'.$_REQUEST['type'].'"');
		if(is_array($chkAlrDone) and count($chkAlrDone) > 0 and $chkAlrDone['cnt'] > 0)
		{
			echo 'already_done';
		}
		else
		{
			$row = array(
							'user_id' => $_SESSION['user_login'],
							'decisionspace_id' => $_REQUEST['dsid'],
							'vis_id' => $_REQUEST['vis_id'],
							'type' => $_REQUEST['type'],
							'dt_added' => date('Y-m-d H:i:s')
						);
			$objMisc->insert('udm_likes_dislikes',$row);
			
			$num_recs = $objMisc->GiveValue('select count(1) cnt from udm_likes_dislikes where decisionspace_id="'.$_REQUEST['dsid'].'" and vis_id="'.$_REQUEST['vis_id'].'" and type="'.$_REQUEST['type'].'"');
			if($_REQUEST['type']=='Like')
			{
				$objMisc->exec_query('update udm_decisionspace_vis set num_likes="'.$num_recs.'" where decisionspace_id="'.$_REQUEST['dsid'].'" and vis_id="'.$_REQUEST['vis_id'].'"');
			}
			else
			{
				$objMisc->exec_query('update udm_decisionspace_vis set num_dislikes="'.$num_recs.'" where decisionspace_id="'.$_REQUEST['dsid'].'" and vis_id="'.$_REQUEST['vis_id'].'"');
			}
			
			echo 'success####'.$num_recs;
		}
	}
	else
		echo 'error';
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'likeDislikeDS')
{
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		$chkAlrDone = $objMisc->getRow('select count(1) cnt from udm_likes_dislikes where user_id="'.$_SESSION['user_login'].'" and decisionspace_id="'.$_REQUEST['dsid'].'" and type="'.$_REQUEST['type'].'"');
		if(is_array($chkAlrDone) and count($chkAlrDone) > 0 and $chkAlrDone['cnt'] > 0)
		{
			echo 'already_done';
		}
		else
		{
			$row = array(
							'user_id' => $_SESSION['user_login'],
							'decisionspace_id' => $_REQUEST['dsid'],
							'type' => $_REQUEST['type'],
							'dt_added' => date('Y-m-d H:i:s')
						);
			$objMisc->insert('udm_likes_dislikes',$row);
			
			$num_recs = $objMisc->GiveValue('select count(id) cnt from udm_likes_dislikes where decisionspace_id="'.$_REQUEST['dsid'].'" and type="'.$_REQUEST['type'].'"');
			if($_REQUEST['type']=='Like')
			{
				$objMisc->exec_query('update udm_decisionspace set num_likes="'.$num_recs.'" where id="'.$_REQUEST['dsid'].'"');
			}
			else
			{
				$objMisc->exec_query('update udm_decisionspace set num_dislikes="'.$num_recs.'" where id="'.$_REQUEST['dsid'].'"');
			}
			
			echo 'success####'.$num_recs;
		}
	}
	else
		echo 'error';
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'goto-decision-spaces')
{
	$qstr = '';
	if(isset($_REQUEST['dtype']) and $_REQUEST['dtype']=='other')
		$qstr = '?dtype=other';
	header('location: '. SITE_URL  . SITE_URL_SUFFIX . 'decision-spaces'.$qstr);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'create-decision-space')
{
	echo '<pre>';print_r($_FILES);print_r($_REQUEST);exit;
	$msg = '';
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		$timestamp = time();
		$path = '';
		
		if(isset($_FILES['path']) and $_FILES['path']['name']!='') 
		{
			if(!is_dir(ROOT_PATH . "uploads/ds/" . $_SESSION['user_login']))
			{
				@mkdir(ROOT_PATH . "uploads/ds/" . $_SESSION['user_login']);
			}
			
			// Define a destination
			$targetFolder = 'uploads/ds/'.$_SESSION['user_login'].'/'; // Relative to the root
			$root_path = ROOT_PATH . $targetFolder;
			
			$file_name = str_replace(' ','_',$_FILES['path']['name']);
			$tempFile = $_FILES['path']['tmp_name'];
			$targetFile = $root_path . $timestamp . $file_name;
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($file_name);
			
			if (in_array(strtolower($fileParts['extension']),$fileTypes)) 
			{
				move_uploaded_file($tempFile,$targetFile) or die('unable to upload');
				
				$path = 'ds/'.$_SESSION['user_login'].'/' . $timestamp  . $file_name;
			}
			else
			{
				$msg = 'error';
				$_SESSION['eMsg'] = 'The file type is invalid. Please upload only .jpg, .jpeg, .gif or .png files.';
			}
		}
		
		$row = array(
					'user_id' => $_SESSION['user_login'],
					'type' => addslashes($_POST['type']),
					'title' => addslashes($_POST['title']),
					'description' => addslashes($_POST['description']),
					'updated' => date('Y-m-d H:i:s')
				);
				
		if($path!='')
		{
			$row['path'] = $path;
		}
			
		///////if decision space is present/////////
		if(isset($_REQUEST['decision_space_id']) and $_REQUEST['decision_space_id']!='')
		{
			if($path!='')
			{
				$rec = $objMisc->getRow('select path from udm_decisionspace where id="'.$_REQUEST['decision_space_id'].'"');
				/////for deleting the previous file starts/////
				if(@$rec['path']!='')
				{
					$full_path = ROOT_PATH . 'uploads/' . $rec['path'];
					@chmod($full_path,0777);
					if(is_file($full_path) === true)
					{
						@unlink($full_path);
					}
				}
				/////for deleting the previous file ends/////
			}
			
			$objMisc->update('udm_decisionspace', $row, 'id="'.$_REQUEST['decision_space_id'].'"');
			
			$decision_space_id = $_REQUEST['decision_space_id'];
			
			$msg = 'success';
			$_SESSION['sMsg'] = 'Decision space updated successfully';
		}
		else
		{
			$row['created_date'] = date('Y-m-d H:i:s');
			$objMisc->insert('udm_decisionspace', $row);
			$decision_space_id = mysqli_insert_id($db);
			//$decision_space_id = mysql_insert_id();
			
			$msg = 'success';
			$_SESSION['sMsg'] = 'Decision space added successfully';
		}
		
		////////for inviting members to decision space/////////
		inviteMembers($decision_space_id);
	}
	else
	{
		$msg = 'error';
		$_SESSION['eMsg'] = 'Please login to do this action.';
	}
	
	//exit;
	$qstr = '';
	if(isset($_REQUEST['dtype']) and $_REQUEST['dtype']!='')
		$qstr = '?dtype=other';
	
	if($msg == 'success')
		header('location: '. SITE_URL . SITE_URL_SUFFIX . 'decision-spaces'.$qstr);
	else if(isset($_REQUEST['decision_space_id']) and $_REQUEST['decision_space_id']!='')
		header('location: '. SITE_URL . SITE_URL_SUFFIX . 'ds/'.$_REQUEST['decision_space_id'] . $qstr);
	else
		header('location: '. SITE_URL . SITE_URL_SUFFIX . 'ds/add');
	exit;
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'invite-members-to-decision-space')
{
	inviteMembers($_REQUEST['decision_space_id']);
	$_SESSION['sMsg'] = 'Members invited successfully.';
	header('location: '. SITE_URL . SITE_URL_SUFFIX . 'decision-spaces');
	exit;
}

function inviteMembers($decision_space_id)
{
	global $objMisc;
	
	if(isset($_POST['member_name']) and is_array($_POST['member_name']) and count($_POST['member_name'])>='')
	{
		$x=0;
		foreach($_POST['member_email'] as $email)
		{
			$name = $_POST['member_name'][$x];
			$type = $_POST['member_type'][$x];
			if($name!='' and $email!='' and $type!='')
			{
				$email = trim($email);
				$chkAlrAdded = $objMisc->getRow('select id from udm_decisionspace_invitations where email="'.$email.'" and  decisionspace_id="'.$decision_space_id.'"');
				if(!is_array($chkAlrAdded) or count($chkAlrAdded) <= 0)
				{
					$row = array(
									'user_id' => $_SESSION['user_login'],
									'decisionspace_id' => $decision_space_id,
									'name' => addslashes($name),
									'email' => addslashes($email),
									'role' => $type,
									'dt_send' => date('Y-m-d H:i:s')
								);
					$objMisc->insert('udm_decisionspace_invitations',$row);
					
					///////check if user already registered then add to notifications////////////
					$chkRegistered = $objMisc->getRow('select id from udm_user where email="'.$email.'"');
					if(is_array($chkRegistered) and count($chkRegistered) > 0)
					{
						$recDs = $objMisc->getRow('select title from udm_decisionspace where id="'.$decision_space_id.'"');
						$rowN = array(
										'user_id' => $_SESSION['user_login'],
										'decisionspace_id' => $chkRegistered['id'],
										'message' => 'You have been invited to decision space - ' . stripslashes($recDs['title']),
										'dt_added' => date('Y-m-d H:i:s')
									 );
						$objMisc->insert('udm_notifications',$rowN);
					}
					///////////////////////////////////////////////////
					
					/////////code for sending email confirmation email/////////
					$strEmailTemplate = file_get_contents(SITE_URL.'views/mail.html');
					
					$subject = 'Invitation to join Decision Space on ' . SITE_NAME;
					
					$body = '<p>Dear '.$name.',</p>';
					$body .= '<p>You have been invited to join a Decision Space on '.SITE_NAME.'.</p>';
					$body .= '<p>Please <a href="'.SITE_URL . SITE_URL_SUFFIX .'decisionspace/'.$aRow['id'].'">click here</a> to join the conversation.<br/></p>';
					
					$from = ADMIN_NAME . '<' . ADMIN_EMAIL . '>';
					
					$emailBody = str_replace('#BASEURL#',BASE_URL,$strEmailTemplate);
					$emailBody = str_replace('#SITE_NAME#',SITE_NAME,$emailBody);
					$emailBody = str_replace('#YEAR#',date('Y'),$emailBody);
					$emailBody = str_replace('#BODY#',$body,$emailBody);
					
					$headers = "MIME-Version: 1.0\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
					$headers .= "To: ".$email."\n";
					$headers .= "Bcc: ".BCC_EMAIL."" . "\n";
					$headers .= "From: ".$from."\n";
					
					@mail("",$subject, $emailBody, $headers);
					///////////////////////////////////////////////////////////
				}
			}
			$x++;
		}
	}
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'viewDecisionSpaceMembers')
{
	$rec_admin = $objMisc->getRow('select first_name,last_name,email from udm_user where id=1');
	$rec_ds = $objMisc->getRow('select user_id from udm_decisionspace where id='.$_REQUEST['id']);
	$rec_owner = $objMisc->getRow('select first_name,last_name,email from udm_user where id='.$rec_ds['user_id']);
	
	$listing = '<form class="frm-main">';
	
	$listing .= '<div class="row" style="padding:6px 0 0;">
					<div class="col-sm-3">
						'.stripslashes($rec_admin['first_name']).' '.stripslashes($rec_admin['last_name']).'
					</div>
					<div class="col-sm-5">
						'.stripslashes($rec_admin['email']).'
					</div>
					<div class="col-sm-3">
						Admin
					</div>
					<div class="col-sm-1">
					</div>
				</div>';
	
	$listing .= '<div class="row" style="padding:12px 0 20px;">
					<div class="col-sm-3">
						'.stripslashes($rec_owner['first_name']).' '.stripslashes($rec_owner['last_name']).'
					</div>
					<div class="col-sm-5">
						'.stripslashes($rec_owner['email']).'
					</div>
					<div class="col-sm-3">
						Owner
					</div>
					<div class="col-sm-1">
					</div>
				</div>';
	
	$allMembers = $objMisc->getAllRecords('select * from udm_decisionspace_invitations where decisionspace_id="'.$_REQUEST['id'].'"');
	if(is_array($allMembers) and count($allMembers) > 0)
	{
		foreach($allMembers as $member)
		{
			$listing .= '<div class="row mem-rec-'.$member['id'].'">
							<div class="col-sm-3">
								'.stripslashes($member['name']).'
							</div>
							<div class="col-sm-5">
								'.stripslashes($member['email']).'
							</div>
							<div class="col-sm-3">
								<select name="member_type'.$member['id'].'" id="member_type'.$member['id'].'" onchange="updateDecisionSpaceMemberType('.$member['id'].',this.value);">
									<option value="Viewer" '.(($member['role']=='Viewer')?'selected="selected"':'').'>Viewer</option>
									<option value="Editor"'.(($member['role']=='Editor')?'selected="selected"':'').'>Editor</option>
								</select>
							</div>
							<div class="col-sm-1">
								<a href="javascript:;" onclick="removeDecisionSpaceMember('.$member['id'].');">x</a>
							</div>
						</div>';
		}
	}
	/*else
	{
		$listing .= '<div class="row"><div class="col-sm-12"><div class="loading-icon">No member(s) yet.</div></div></div>';
	}*/
	echo '</form>' . $listing;
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'updateDecisionSpaceMemberType')
{
	$id = $_REQUEST['id'];
	$chkExists = $objMisc->getRow('select id from udm_decisionspace_invitations where id="'.$id.'"');
	if(is_array($chkExists) and count($chkExists) > 0 and isset($_REQUEST['val']) and ($_REQUEST['val']=='Viewer' or $_REQUEST['val']=='Editor'))
	{
		$row = '';
		$row['role'] = $_REQUEST['val'];
		$objMisc->update('udm_decisionspace_invitations',$row,'id="'.$id.'"');
		
		echo 'success';
	}
	else
		echo 'error';
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'removeDecisionSpaceMember')
{
	$id = $_REQUEST['id'];
	$chkExists = $objMisc->getRow('select id from udm_decisionspace_invitations where id="'.$id.'"');
	if(is_array($chkExists) and count($chkExists) > 0)
	{
		$objMisc->delete('udm_decisionspace_invitations','id="'.$id.'"');
		echo 'success';
	}
	else
		echo 'error';
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'get-decision-spaces')
{
	$aColumns = array('title','updated');
	$aColumnsSort = array('title','updated');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
	//Table Name
	$sTable = "udm_decisionspace";
	
	// Paging
	$sLimit = "";
	if ( isset( $_GET['start'] ) && $_GET['length'] != '-1' )
		$sLimit = "LIMIT ".intval( $_GET['start'] ).", ".intval( $_GET['length'] );
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if(isset($_GET['order']) and isset($_GET['order'][0]) and count($_GET['order'][0]) > 0)
	{
		$colNum = $_GET['order'][0]['column'];
		$sOrder = "ORDER BY " .  $aColumns[$colNum] . " " . $_GET['order'][0]['dir'];
	}
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( isset($_GET['search']) && count($_GET['search']) > 0 and $_GET['search']['value']!="")
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= "".$aColumns[$i]." LIKE '%".addslashes($_GET['search']['value'])."%' OR ";
		}
		
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}

	/* Custom Where	Clause		 */
	if ( $sWhere == "" )
		$sWhere = "WHERE ";
	else
		$sWhere .= " AND ";
	
	$edit_qstr = '';
	if(isset($_REQUEST['dtype']) and $_REQUEST['dtype']!='')
	{
		$sWhere .= 'id in (select udi.decisionspace_id from udm_decisionspace_invitations udi left join udm_user u on (u.email=udi.email) where u.id="'.$_SESSION['user_login'].'" and role="Member")';
		$edit_qstr = '?dtype=other';
	}
	else if($_SESSION['user_type']=='Admin')
		$sWhere .= "1 ";
	else if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
		$sWhere .= "user_id=".$_SESSION['user_login']." ";
	else
		$sWhere .= "0 ";


	/*SQL queries Get data to display*/
	$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns)).",id,published,user_id FROM $sTable $sWhere $sOrder $sLimit";
	
	$sCntQuery = "SELECT count(1) as numFoundRows FROM $sTable $sWhere $sOrder";
		
	$q = $sQuery;
	
	$rResult = $objMisc->getAllRecords($sQuery);	
	
	if (!is_array($rResult)) echo $sQuery; 
	
	$aResultFilterTotal = $objMisc->getRow($sCntQuery);	
	$iFilteredTotal = (is_array($aResultFilterTotal) && isset($aResultFilterTotal['numFoundRows']))?$aResultFilterTotal['numFoundRows']:0;

	/* Total data set length */
	$sQuery = "SELECT COUNT(1) as cntTotal FROM $sTable $sWhere $sOrder";
	
	$aResultTotal = $objMisc->getRow($sQuery);	
	//echo '<pre>';print_r($rResult);exit;
	$iTotal = (is_array($aResultTotal) && isset($aResultTotal['cntTotal']))?$aResultTotal['cntTotal']:0;
	
	//Output
	$output = array(
		"draw" => intval((isset($_GET['sEcho'])?$_GET['sEcho']:0)),
		"recordsTotal" => $iTotal,
		"recordsFiltered" => $iFilteredTotal,
		"data" => array()
	);//"q" => $q,
		
	foreach($rResult as $aRow)
	{
		$rec_owner = $objMisc->getRow('select first_name,last_name from udm_user where id="'.$aRow['user_id'].'"');
		
		$cntInvitations = $objMisc->GiveValue('select count(1) cnt from udm_decisionspace_invitations where decisionspace_id="'.$aRow['id'].'"');
		$totalMembers = $cntInvitations+2;//1 for admin and 1 for owner
		$txtMembers = '<a href="javascript:;" data-toggle="modal" data-target="#myModal" class="member-icon admin-user" title="Admin" onclick="viewMembers('.$aRow['id'].');"><i class="fa fa-user-o" aria-hidden="true"></i></a><a href="javascript:;" data-toggle="modal" data-target="#myModal" class="member-icon owner-user" title="'.stripslashes($rec_owner['first_name'] . ' ' . $rec_owner['last_name']).'" onclick="viewMembers('.$aRow['id'].');"><i class="fa fa-user-o" aria-hidden="true"></i></a>';
		$getMembers = $objMisc->getAllRecords('select name,email from udm_decisionspace_invitations where decisionspace_id="'.$aRow['id'].'" limit 0,3');
		if(is_array($getMembers) and count($getMembers) > 0)
		{
			foreach($getMembers as $member)
			{
				$reg_class = '';
				$chkAlrReg = $objMisc->getRow('select id from udm_user where email="'.$member['email'].'"');
				if(is_array($chkAlrReg) and count($chkAlrReg) > 0)
					$reg_class = 'registered';
				
				$txtMembers .= '<a href="javascript:;" data-toggle="modal" data-target="#myModal" class="member-icon '.$reg_class.'" title="'.stripslashes($member['name']).'" onclick="viewMembers('.$aRow['id'].');"><i class="fa fa-user-o" aria-hidden="true"></i></a>';
			}
		}
		
		if($totalMembers > 5)
		{
			$more_mem = $totalMembers - 5;
			$txtMembers .= '<a href="javascript:;" data-toggle="modal" data-target="#myModal" class="member-icon more-mem-icon" title="View all" onclick="viewMembers('.$aRow['id'].');">+'.$more_mem.'</a>';
		}
		
		$pubUnpubLink = '';
		/*if($_SESSION['user_type']=='Admin')
		{*/
			$pubUnpubLink .= '<span class="pubUnpubLink_'.$aRow['id'].'">';
			if($aRow['published']==1)
				$pubUnpubLink .= '<a href="javascript:;" class="font16" onclick="pubUnpubDS('.$aRow['published'].','.$aRow['id'].',\'Decision space\');" title="Click here to Unpublish this Decision space"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>';
			else
				$pubUnpubLink .= '<a href="javascript:;" class="font16" onclick="pubUnpubDS('.$aRow['published'].','.$aRow['id'].',\'Decision space\');"title="Click here to Publish this Decision space"><i class="fa fa-square-o" aria-hidden="true"></i></a>';
			$pubUnpubLink .= '</span> &nbsp;';
		/*}*/
		
		$enc_id = $objMisc->encodeNumber($aRow['id']);
		$row = array();
		$row[] = '<a href="'.SITE_URL.SITE_URL_SUFFIX.'decision-space/'.$enc_id.'" title="'.stripslashes($aRow['title']).'">'.stripslashes($aRow['title']).'</a>';
		$row[] = date('d/m/Y',strtotime($aRow['updated']));
		$row[] = $txtMembers;
		$row[] = '<a href="javascript:;" data-toggle="modal" data-target="#myModal" onclick="inviteMembers('.$aRow['id'].');"><i class="fa fa-plus-circle" aria-hidden="true"></i> Invite Member</a>';
		$row[] = $pubUnpubLink . '<a href="'.SITE_URL.SITE_URL_SUFFIX.'ds/'.$aRow['id'].$edit_qstr.'" class="font16"><i class="fa fa-edit"></i></a> <a href="javascript:;" class="font16" onclick="deleteDecisionSpace('.$aRow['id'].',this);"><i class="fa fa-remove"></i></a>';
		
		$output['data'][] = $row;
	}
	
	echo json_encode( $output );
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'register-user')
{
	if($_REQUEST['first_name']=='')
		$errors['first_name'] = 'First name is required.';
	if($_REQUEST['last_name']=='')
		$errors['last_name'] = 'Last name is required.';
	if($_REQUEST['organisation']=='')
		$errors['organisation'] = 'Organisation is required.';
	if($_REQUEST['username']=='')
		$errors['username'] = 'Username is required.';
	if($_REQUEST['email']=='')
		$errors['email'] = 'Email is required.';
	if($_REQUEST['password']=='')
		$errors['password'] = 'Password is required.';
	if(!$_REQUEST['confirm_password'])
		$errors['confirm_password'] = 'Password mismatch.';
	
	if(count($errors) <= 0)
	{
		$row = array(
						'roles' => 'User',
						'title' => addslashes($_REQUEST['title']),
						'first_name' => addslashes($_REQUEST['first_name']),
						'middle_name' => addslashes($_REQUEST['middle_name']),
						'last_name' => addslashes($_REQUEST['last_name']),
						'organisation' => addslashes($_REQUEST['organisation']),
						'gender' => addslashes($_REQUEST['gender']),
						'street' => addslashes($_REQUEST['street']),
						'city' => addslashes($_REQUEST['city']),
						'state' => addslashes($_REQUEST['state']),
						'country' => addslashes($_REQUEST['country']),
						'postcode' => addslashes($_REQUEST['postcode']),
						'phone' => addslashes($_REQUEST['phone']),
						'username' => addslashes($_REQUEST['username']),
						'email' => addslashes($_REQUEST['email']),
						'password' => sha1($_REQUEST['password']),
						'status' => 'D',
						'created_date' => date('Y-m-d H:i:s')
					);
	
		$chkEmailExists = $objMisc->getRow('select 1 from udm_user where email="'.addslashes($_REQUEST['email']).'"');
		if(is_array($chkEmailExists) and count($chkEmailExists) > 0)
			$errors['email'] = 'Email already exists.';
		
		$chkUsernameExists = $objMisc->getRow('select 1 from udm_user where username="'.addslashes($_REQUEST['username']).'"');
		if(is_array($chkUsernameExists) and count($chkUsernameExists) > 0)
			$errors['username'] = 'Username already exists.';
		
		if(count($errors) <= 0)
		{
			$objMisc->insert('udm_user',$row);
			$userId = mysqli_insert_id($db);
			//$userId = mysql_insert_id();
			
			//////add to notifications if member of decision space///////
			$allInvs = $objMisc->getAllRecords('select * from udm_decisionspace_invitations where email="'.$_REQUEST['email'].'"');
			if(is_array($allInvs) and count($allInvs) > 0)
			{
				foreach($allInvs as $inv)
				{
					$recDs = $objMisc->getRow('select title from udm_decisionspace where id="'.$inv['decisionspace_id'].'"');
					$rowN = array(
									'user_id' => $inv['id'],
									'decisionspace_id' => $inv['decisionspace_id'],
									'message' => 'You have been invited to decision space - ' . stripslashes($recDs['title']),
									'dt_added' => date('Y-m-d H:i:s')
								 );
					$objMisc->insert('udm_notifications',$rowN);
				}
			}
			///////////////////////////////////////////////////////////////
			
			/////////code for sending email confirmation email/////////
			$strEmailTemplate = file_get_contents(SITE_URL.'views/mail.html');
			
			$subject = 'Successful registration as '.ucfirst($_REQUEST['type']).' on ' . SITE_NAME;
			
			$body = '<p>Dear ' . $_REQUEST['first_name'] . ' ' . $_REQUEST['last_name'] . ',</p>';
			$body .= '<p>Congratulations!! You have sucessfully registered on '.SITE_NAME.'.</p>';
			$body .= '<p>But currently your account is inactive. Please <a href="'.SITE_URL.'scripts/activate.php?act='.base64_encode('activateAccount').'&flag='.base64_encode($userId).'">click here</a> to validate your email or copy the following link and paste in the browser:<br/><br/>'.SITE_URL.'scripts/activate.php?act='.base64_encode('activateAccount').'&flag='.base64_encode($userId).'<br/></p>';
			
			$from = ADMIN_NAME . '<' . ADMIN_EMAIL . '>';
			
			$emailBody = str_replace('#BASEURL#',BASE_URL,$strEmailTemplate);
			$emailBody = str_replace('#SITE_NAME#',SITE_NAME,$emailBody);
			$emailBody = str_replace('#YEAR#',date('Y'),$emailBody);
			$emailBody = str_replace('#BODY#',$body,$emailBody);
			
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
			$headers .= "To: ".$_REQUEST['first_name']." ".$_REQUEST['last_name']."<".$_REQUEST['email'].">\n";
			$headers .= "Bcc: ".BCC_EMAIL."" . "\n";
			$headers .= "From: ".$from."\n";
			
			@mail("",$subject, $emailBody, $headers);
			///////////////////////////////////////////////////////////
			
			$data['success'] = true;
			$data['msg'] = 'Registered successfully.';
		}
	}
	
	if(count($errors) > 0)
	{
		$data['success'] = false;
		$data['msg'] = 'Some error occured.';
		$data['errors'] = $errors;
	}
	
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'member-login')
{
	if($_REQUEST['username']=='')
		$errors['username'] = 'Username is required.';
	if($_REQUEST['password']=='')
		$errors['password'] = 'Password is required.';
	
	$data['msg'] = 'Some error occured.';
	
	if(count($errors) <= 0)
	{
		$chkLogin = $objMisc->getRow('select * from udm_user where (username="'.addslashes($_REQUEST['username']).'" or email="'.addslashes($_REQUEST['username']).'") and password="'.addslashes(sha1($_REQUEST['password'])).'"');
		if(!is_array($chkLogin) or count($chkLogin) <= 0)
		{
			$errors['msg'] = 'Error';
			$data['msg'] = 'Incorrect Username/Password.';
		}
		else if($chkLogin['status']=='D')
		{
			$errors['msg'] = 'Error';
			$data['msg'] = 'Your profile is inactive. Please check your email and click on link to activate your account.';
		}
		else if($chkLogin['deleted']=='Yes')
		{
			$errors['msg'] = 'Error';
			$data['msg'] = 'You have deleted your account. Please contact admin for further details.';
		}
		else if($chkLogin['approved']=='N')
		{
			$errors['msg'] = 'Error';
			$data['msg'] = 'Your account is not approved yet. Please contact admin for further details.';
		}
		else
		{
			/////update last login/////
			$row = '';
			$row['last_login'] = date('Y-m-d H:i:s');
			$objMisc->update('udm_user',$row,'id="'.$chkLogin['id'].'"');
			///////////////////////////
			
			$data['success'] = true;
			$data['msg'] = 'Logged in successfully.';
			$data['user_type'] = stripslashes($chkLogin['roles']);
			
			$chkLogin['ROOT_PATH'] = ROOT_PATH;
			$chkLogin['SITE_URL'] = SITE_URL;
			$chkLogin['SITE_NAME'] = SITE_NAME;
			$_SESSION['user_login'] = $chkLogin['id'];
			$_SESSION['loggedin_name'] = $data['loggedin_name'] = stripslashes($chkLogin['first_name']) . ' ' . stripslashes($chkLogin['last_name']);
			$_SESSION['user_type'] = $data['user_type'] = stripslashes($chkLogin['roles']);
			$_SESSION['user_arr'] = $data['user_arr'] = $chkLogin;
		}
	}
	
	if(count($errors) > 0)
	{
		$data['success'] = false;
		$data['errors'] = $errors;
	}
	
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'check-login')
{
	if(isset($_SESSION['user_login']) and $_SESSION['user_login']!='')
	{
		/////update last login/////
		$row = '';
		$row['last_login'] = date('Y-m-d H:i:s');
		$objMisc->update('udm_user',$row,'id="'.$_SESSION['user_login'].'"');
		///////////////////////////
		
		//////to get the number of unread notifications//////////
		$numUnreadMsgs = $objMisc->GiveValue('select count(1) cnt from udm_notifications where user_id="'.$_SESSION['user_login'].'" and  chk_read=0');
		////////////////////////////////////////////////////
		
		$data['profile_link'] = SITE_URL . SITE_URL_SUFFIX . 'home';
		$data['user_type'] = $_SESSION['user_type'];
		$data['loggedin_name'] = $_SESSION['loggedin_name'];
		$data['user_arr'] = $_SESSION['user_arr'];
		$data['numUnreadMsgs'] = $numUnreadMsgs;
		$data['success'] = true;
	}
	else
		$data['success'] = false;
	
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'logout')
{
	$data['success'] = true;
	$data['logout_url'] = SITE_URL. 'scripts/process.php?act=logout_url';
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'logout_url')
{
	$eMsg = $sMsg = '';
	if(isset($_SESSION['sMsg']) and $_SESSION['sMsg']!='')
		$sMsg = $_SESSION['sMsg'];
	if(isset($_SESSION['eMsg']) and $_SESSION['eMsg']!='')
		$eMsg = $_SESSION['eMsg'];
	
	$_SESSION['user_login'] = $_SESSION['loggedin_name'] = $_SESSION['user_type'] = $_SESSION['user_arr'] = '';
	unset($_SESSION['user_login']);
	unset($_SESSION['loggedin_name']);
	unset($_SESSION['user_type']);
	unset($_SESSION['user_arr']);
	session_unset();
	session_destroy();
	
	if($sMsg != '')
	{
		session_start();
		$_SESSION['sMsg'] = $sMsg;
	}
	
	if($eMsg != '')
	{
		session_start();
		$_SESSION['eMsg'] = $eMsg;
	}
	
	header('location: ' . SITE_URL);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'updateProfile')
{
	if($_REQUEST['first_name']=='')
		$errors['first_name'] = 'First name is required.';
	if($_REQUEST['last_name']=='')
		$errors['last_name'] = 'Last name is required.';
	if($_REQUEST['organisation']=='')
		$errors['organisation'] = 'Organisation is required.';
	
	if(count($errors) <= 0)
	{
		$row = array(
						'title' => addslashes($_REQUEST['title']),
						'first_name' => addslashes($_REQUEST['first_name']),
						'middle_name' => addslashes($_REQUEST['middle_name']),
						'last_name' => addslashes($_REQUEST['last_name']),
						'organisation' => addslashes($_REQUEST['organisation']),
						'gender' => addslashes($_REQUEST['gender']),
						'street' => addslashes($_REQUEST['street']),
						'city' => addslashes($_REQUEST['city']),
						'state' => addslashes($_REQUEST['state']),
						'country' => addslashes($_REQUEST['country']),
						'postcode' => addslashes($_REQUEST['postcode']),
						'phone' => addslashes($_REQUEST['phone']),
						'updated_on' => date('Y-m-d H:i:s')
					);
	
		if(count($errors) <= 0)
		{
			$objMisc->update('udm_user',$row,'id="'.$_SESSION['user_login'].'"');
			
			$data['success'] = true;
			$data['msg'] = 'Profile updated successfully.';
			
			$user_arr = $objMisc->getRow('select * from udm_user where id='.$_SESSION['user_login']);
			$user_arr['password'] = '';
			unset($user_arr['password']);
			
			$user_arr['ROOT_PATH'] = ROOT_PATH;
			$user_arr['SITE_URL'] = SITE_URL;
			$user_arr['SITE_NAME'] = SITE_NAME;
			$_SESSION['user_arr'] = $data['user_arr'] = $user_arr;
		}
	}
	
	if(count($errors) > 0)
	{
		$data['success'] = false;
		$data['msg'] = 'Some error occured.';
		$data['errors'] = $errors;
	}
	
	echo json_encode($data);
	exit;
}

if(isset($_REQUEST['act']) and $_REQUEST['act'] == 'updateChangePassword')
{
	if($_REQUEST['old_password']=='')
		$errors['old_password'] = 'Old password is required.';
	if($_REQUEST['password']=='')
		$errors['password'] = 'Password is required.';
	if($_REQUEST['confirm_password']=='')
		$errors['confirm_password'] = 'Confirm password is required.';
	if(!$_REQUEST['confirm_password'])
		$errors['confirm_password'] = 'Password mismatch.'.$_REQUEST['password'].' - '.$_REQUEST['confirm_password'];
	
	if(count($errors) <= 0)
	{
		$chkPassword = $objMisc->getRow('select id from udm_user where id="'.$_SESSION['user_login'].'" and password="'.addslashes(sha1($_REQUEST['old_password'])).'"');
		if(!is_array($chkPassword) or count($chkPassword) <= 0)
		{
			$errors['msg'] = 'Error';
			$data['msg'] = 'Old password is incorrect.';
			$data['success'] = false;
		}
		else
		{
			$row = array(
							'password' => addslashes(sha1($_REQUEST['password']))
						);
		
			$objMisc->update('udm_user',$row,'id='.$_SESSION['user_login']);
			$data['msg'] = 'Password updated successfully.';
			$data['success'] = true;
		}
	}
	else
	{
		$data['success'] = false;
		$data['msg'] = 'Some error occured.';
		$data['errors'] = $errors;
	}
	
	echo json_encode($data);
	exit;
}

/////////function for resizing image/////////
function resize($temp_name,$name,$type,$new_file_name,$dir,$width,$height)
{
  /* Get original image x y*/
  list($w, $h) = getimagesize($temp_name);
  /* calculate new image size with ratio */
  $ratio = max($width/$w, $height/$h);
  $h = ceil($height / $ratio);
  $x = ($w - $width / $ratio) / 2;
  $w = ceil($width / $ratio);
  /* new file name */
  $path = $dir . $new_file_name;
  /* read binary data from image file */
  $imgString = file_get_contents($temp_name);
  /* create image from string */
  $image = imagecreatefromstring($imgString);
  $tmp = imagecreatetruecolor($width, $height);
  imagecopyresampled($tmp, $image,
	0, 0,
	$x, 0,
	$width, $height,
	$w, $h);
  /* Save image */
  switch ($type) 
  {
	case 'image/jpeg':
	  imagejpeg($tmp, $path, 100);
	  break;
	case 'image/png':
	  imagepng($tmp, $path, 0);
	  break;
	case 'image/gif':
	  imagegif($tmp, $path);
	  break;
	default:
	  exit;
	  break;
  }
  return $path;
  /* cleanup memory */
  imagedestroy($image);
  imagedestroy($tmp);
}
?>