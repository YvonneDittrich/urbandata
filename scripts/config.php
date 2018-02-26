<?php
ob_start();
session_start();
ini_set("display_errors",0);
$setup_at = $_SERVER['HTTP_HOST'];

global $db;

if($setup_at == 'localhost')
{
	define('SITE_URL','http://localhost/urbandata/');
	define('BASE_URL','http://localhost/urbandata/');
	define('ROOT_PATH',"D:/wamp64/www/urbandata/");
	
	define('SITE_URL_SUFFIX','#!/');
	
	define('BCC_EMAIL','ajay546k@gmail.com');
	
	define('USER_ENC_NUM',96582);
	define('USER_ENC_NUM_ADD',25623);
	
	$server = 'localhost';
	$user = 'root';
	$pass = '';
	$base = 'urbandata';
}
else
{
	define('SITE_URL','http://ud2d.itu.dk/');
	define('BASE_URL','http://ud2d.itu.dk/');
	define('ROOT_PATH',"/var/www/html/");
	
	define('SITE_URL_SUFFIX','#!/');
	
	define('BCC_EMAIL','ajay546k@gmail.com');
	
	define('USER_ENC_NUM',96582);
	define('USER_ENC_NUM_ADD',25623);
	
	$server = 'localhost';
	$user = 'root';
	$pass = 'ch4ng3m3';
	$base = 'urbandata';
}

$db = new mysqli($server,$user,$pass,$base);

/*$db=mysql_connect($server,$user,$pass);
mysql_select_db($base,$db);*/

$addl_path = '';
include_once("classes/class.dbFunctions.php");
require_once("classes/class.coreClasses.php");
include_once("classes/class.Misc.php");

$objMisc = new Cms($db);

$rec_constant = $objMisc->getRow('select * from site_constant where id=1');

define('ADMIN_NAME',stripslashes($rec_constant['admin_name']));
define('ADMIN_EMAIL',stripslashes($rec_constant['admin_email']));

define('SUPPORT_NAME',stripslashes($rec_constant['admin_name']));
define('SUPPORT_EMAIL',stripslashes($rec_constant['admin_email']));

define('SITE_NAME',stripslashes($rec_constant['site_name']));
?>