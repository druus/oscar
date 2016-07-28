<?php
$VERSION="2.1.1";
$AUTHOR="Daniel Ruus";
$MODIFIED="14/12/2005";

// Read the configuration file if exists
include_once("./config/admin.conf.php");
include_once("./include/dbfunctions.inc.php");
include_once("./include/functions.inc.php");

// Check that the config file has been read!
if (!isset($DBSERVER))
	die("Unable to read contents of configuration file.");

// We need to login before using this page.
//require("adminauth.php");
require("./include/authenticate.inc.php");

// Check if we are actually logged in.
if (!authenticate())
{
	echo "<h3>Not authorised!</h3>\n";
	echo "Click 'Quit Admin' to return to the asset index page and login first.\n";
	die();
}

include("/srv/www/htdocs/common/utils.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<title>AQE Intranet Administration - Software License Admin, version 1.0 </title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="expires" content="tue, 20 aug 1996 14:25:27 gmt">
<meta name="generator" content="Editplus">
<meta name="author" content="Daniel Ruus">
<meta name="keywords" content="">
<meta name="description" content="">
<link href="./style.css" type="text/css" rel="stylesheet">

<!-- create a special style for text input boxes -->
<style type="text/css">
.input-style
{
    font-family : MS San-Serif,Verdana,Arial,Helvetica;
    font-size : 12px;
    color : black;
    border-style : solid;
    border-width : 1px;
    margin-left : 0px;
    margin-top : 0px;

}
</style>

</head>

<body background="images/tatami.gif">

<script language="JavaScript" type="text/javascript">
<!-- Hide for old browsers
function LaunchAdminWindow(adminPath)
{
	newWindow = window.open(adminPath,'newWin','width=600,height=400,scrollbars=yes,resize=yes');
}

function confirmSubmit(text)
{
	var agree=confirm(text);

	if (agree)
		return true ;
	else
		return false ;

}
// Finished hiding -->
</script>

<table align="left" border="0">
<tr valign="top">
    <td colspan="2">
        <img src="images/aqe_asset_logo.png" alt="Absolute Quality (Europe) Ltd Asset Register">
	</td>
</tr>
<tr valign="top">
	<td><?php include("navigate.php");?></td>
	<td>

<?php

($_SERVER['REQUEST_METHOD'] == 'GET') ? $values = $_GET : $values = $_POST;

// Create a connection to the database
$mydb = new mysqldb($DBSERVER, $DBNAME, $DBUSER, $DBPASSWD);
if (! $mydb)
	print_error("Unable to connect to database.<br/><b>MySQL error</b><br/>Errno: " . mysql_errno() . "<br/>Error: " . mysql_error(), "error");


//*****************************************************************************
// Function     change_pwd_prompt()
// Arguments    $db - database identifier
// Return value none
// Description  Display a 3 entry password prompt screen
//*****************************************************************************
function change_pwd_prompt($db)
{
	// Display 3 password input boxes - one for current password, 2 for new password
?>
<form action="<?= $_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="cmd" value="chkpwd">
<table align="center" width="500" border="0" cellspacing="0">
<tr>
	<td align="center" colspan="3">
	<h4>Change Password</h4>
	</td>
</tr>
</table>

<table cellspacing="0" cellpadding="2">
<tr>
	<td>Enter current password:</td>
	<td><input type="password" class="input-style" name="curr_pwd" size="20" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	
</tr>
<tr>
	<td>Enter new password:</td>
	<td><input type="password" class="input-style" name="new_pwd_1" size="20" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>

</tr>
<tr>
	<td>Enter new password again:</td>
	<td><input type="password" class="input-style" name="new_pwd_2" size="20" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>

</tr>
<tr>
	<td>&nbsp;</td>
	<td align="center"><input type="submit" value="Change password"></td>
</tr>
</table>
</form>
<?php

} // EOF change_pwd_prompt()


//*****************************************************************************
// Function     chkpwd()
// Arguments    $db - database identifier
//              $values - array containing users entries
// Return value none
// Description  Check that the "old password" entry matches the current one,
//              and that the two "new passwords" match each other.
//*****************************************************************************
function check_passwords($db, $values)
{
	// Start by verifying that the two new passwords match each other. If not,
	// there is little point in continuing and querying the database.
	if ($values['new_pwd_1'] != $values['new_pwd_2'])
	{
        print_error("Mismatch between the two new passwords entered. Ensure the password match!", "warn");
	}
	else
	{
		//print_error("New passwords match!", "info");
		
		// Perform a query to see if the "old password" entered matches what's
		// in the user table for the currently logged in user.
		$checkPwd = "SELECT user_name FROM user WHERE user_name = '" . $_SESSION['username'] . "' AND pwd = MD5('" . $values['curr_pwd'] . "') ";
		$db->setsql($checkPwd);
		if (! $db->selectquery())
		{
			print_error("Unable to correctly retreive information for the currently logged in user.<br/>Database error message is: " . mysql_error(), "error");
		}
		else
		{
			// If we come here, we should have retreived data for the logged in user. If one row is returned,
			// the entered password was correct, but if zero rows are returned the password is wrong.
			if ($db->numberrows == 0)
			{
				print_error("Incorrect password entered, unable to change password for current user " . $_SESSION['username'], "warn");
			}
			else
			{
				//print_error("About to change the password for user " . $_SESSION['username'], "info");
				$changePwd = "UPDATE user SET pwd = MD5('" . $values['new_pwd_1'] . "') WHERE user_name = '" . $_SESSION['username'] . "'";
				$db->setsql($changePwd);
				if (! $db->insertquery())
				{
					print_error("Unable to update password for user " . $_SESSION['username'] . "<br/>Database error message is: " . mysql_error(), "error");
				}
				else
				{
					// Change the session variable for the current user
					$_SESSION['password'] = $values['new_pwd_1'];
					print_error("Password changed successfully for user " . $_SESSION['username'] . ".", "info");
				}
			}
		}
	}
} // EOF change_passwords()



# Free the resultset to save memory
@mysql_free_result($query_result);



?>

<?php
// Call the required function depending on the selection made previously

switch ($values['cmd'])
{
	case "chkpwd":
	    check_passwords($mydb, $values);
	    break;
	    
	case "updtepwd":
	    change_pwd_db($mydb, $values);
	    break;

	case "chngpwd":
	default:
	    change_pwd_prompt($mydb);
	    
}
?>
</body>
</html>

