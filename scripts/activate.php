<?php
include("config.php");

$status = 1;
if(isset($_GET['act']) and base64_decode($_GET['act']) == 'activateAccount' and isset($_GET['flag']) and $_GET['flag']!='')
{
	$id = base64_decode($_GET['flag']);
	$chkExists = $objMisc->getRow('select * from udm_user where id="'.$id.'"');
	if(is_array($chkExists) and count($chkExists) > 0)
	{
		if($chkExists['status'] != 'A')
		{
			$row = '';
			$row['status'] = 'A';
			$objMisc->update('udm_user',$row,'id="'.$id.'"');
			
			////////////////////code for sending mail for registration//////////
			$strEmailTemplate = file_get_contents(SITE_URL . 'views/mail.html');
			$from = ADMIN_NAME . "<" . ADMIN_EMAIL . ">";
			$to = $chkExists['first_name'] . " ".$chkExists['last_name'] . "<" . $chkExists['email'] . ">";
			$subject = 'Your Account on '.SITE_NAME;
			$body = "<p>Dear ".$chkExists['first_name']." ".$chkExists['last_name'].",</p>";
			$body .= "<p>Welcome to ".SITE_NAME.". Your email has been validated successfully. Following are your login details:</p>
			<p><b>Email:</b>  ".$chkExists['email']."<br>
			<b>Username:</b>  ".$chkExists['username']."<br>
			<b>Password:</b>  Choosen by you.</p><br/>
			
			<p>Please login to access your account.</p>";

		
			$headers = "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
			$headers .= "To: ".$to."\n";
			$headers .= "Bcc: ".BCC_EMAIL."" . "\n";
			$headers .= "From: ".$from."\n";
			
			$emailBody = str_replace('#BASEURL#',BASE_URL,$strEmailTemplate);
			$emailBody = str_replace('#SITE_NAME#',SITE_NAME,$emailBody);
			$emailBody = str_replace('#YEAR#',date('Y'),$emailBody);
			$emailBody = str_replace('#BODY#',$body,$emailBody);
			
			@mail("",$subject, $emailBody, $headers);
			////////////////////////////////////////////////////////////////////
						
			$_SESSION['sMsg'] = "Email validated Successfully. Please login to access your account.";
			header('location: '.SITE_URL.'#!/login');
			exit;
		}
		else
		{
			$_SESSION['sMsg'] = "Your email is already validated. Please login to access your account.";
			header('location: '.SITE_URL);
			exit;
		}
	}
	else
		$status = 0;
}
else
	$status = 0;
	
if($status == 0)
{
	$_SESSION['eMsg'] = "This link is expired.";
	header('location: '.SITE_URL);
	exit;
}
else
{
	header('location: '.SITE_URL);
	exit;
}
?>