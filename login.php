<?php
$AR_VERSION="1.0.1";
$AR_AUTHOR="Daniel Ruus";
$AR_MODIFIED="04/10/2006";

// Register this session
session_start();

// Add HTTP authentication
include('auth.php');

// Include useful functions
include_once("include/dbfunctions.inc.php");
//include_once("include/authenticate.inc.php");
include_once('classes/Authenticate.class.php');
include_once("config/admin.conf.php"); // Remove in the near future


($_SERVER['REQUEST_METHOD'] == 'GET') ? $values = $_GET : $values = $_POST;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>AQ Asset Login</title>

	<link href="./style.css" rel="stylesheet" type="text/css" />

	<style type="text/css">
	.input-style
	{
		font-family : MS San-Serif,Verdana,Arial,Helvetica;
		font-size : 12px;
		color : black;
		background-color : #FFFFFF;
		border-color : #000000;
		border-style : solid;
		border-width : 1px;
		margin-left : 0px;
		margin-top : 0px;

	}
	</style>

</head>
<body>
<?php

if ($values['cmd'] == "login")
{
	// Check if login works
	//$user = new User($values['username']);

	// Store the user name as a session variable
	//$loginRes = user_login($values['username'], $values['password'], $auth);
	$auth = new Authenticate();
	$loginRes = $auth->login($values['username'], $values['password']);

	if ($loginRes == FALSE)
	{
		print_error("<b>Unable to authenticate user.</b><br/>Ensure user name and password is correct. Both are case sensitive.", "info");
		//echo "Ensure user name and password is correct. Both are case sensitive.<br/>\n";
	}


	//echo "Click <a href=\"" . $_SERVER['PHP_SELF'] . "\">here</a> to continue.<br/>\n";
	//die();
}


if ($values['cmd'] == "logout")
{
	//user_logout();
	$auth = new Authenticate();
	$auth->logout();
	echo "Logging out user '" . $_SESSION['username'] . "'.<br/>\n";

	echo "Click <a href=\"index.php\">here</a> to continue.<br/>\n";
	//die();

?>
<a href="index.php">Return to Asset Index page</a><br/>
<META HTTP-EQUIV="refresh" CONTENT="0;url='index.php'">
<?php
	//die();
}


?>

<?php
// Is the user logged in?
if (!isset($_SESSION['username']))
{
	user_show_login();
}
else
{
?>
Logged in as user '<?= $_SESSION['username']?>'<br/>
<br/><br/>
<a href="index.php">Click here to continue...</a><br/>

<META HTTP-EQUIV="refresh" CONTENT="0;url='index.php'">
<?php
}

?>
<br/><br/>
<!--
Session ID: <?= session_id() ?><br/>
Referrer  : <?= $_SERVER['HTTP_REFERER'] ?><br/>
-->
</body>
</html>
