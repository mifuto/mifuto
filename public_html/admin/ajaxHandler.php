<?php
// die("dsfdfdfd");
require_once("config.php");
require_once("classes/addClasses.php");
require_once("classes/CommandHandler.php");
require_once("classes/OnlineAlbumClass.php");
// require_once("classes/SignatureAlbumClass.php");
require_once("classes/UserClass.php");
require_once("classes/CommentsClass.php");
require_once("classes/SubscriptionClass.php");
require_once("classes/sendMailClass.php");
require_once("classes/Blog.php");
require_once("classes/DashboardClass.php");
require_once("classes/EnquiriesClass.php");
require_once("classes/BlogsClass.php");
require_once("classes/StoriesClass.php");
require_once("classes/EmailTemplatesClass.php");
require_once("classes/CinematographyClass.php");
require_once("classes/ServicesClass.php");
require_once("classes/WeddingFilmsClass.php");
require_once("classes/CareerClass.php");
require_once("classes/SystemManageClass.php");

require_once("razorpay/Razorpay.php");

// die("dsfdfdfd");

$function=$_POST['function'];
$method=$_POST['method'];

$fn = new $function($dbc);
$fn->$method();

function ajaxResponse($status,$data){
	$resp = array();
	$resp["status"]=$status;
	$resp["data"]=$data;
	echo json_encode($resp);
	die();
}	

?>