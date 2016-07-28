<?php
//*****************************************************************************
// $Header: /srv/cvsroot/AQasset/admin/useradm.php,v 1.2 2005/04/14 14:04:34 druus Exp $
//
// $Author: druus $
//
// Description	Administration of users
//
// --------------------------------------
// History
// -------
// $Log: useradm.php,v $
// Revision 1.2  2005/04/14 14:04:34  druus
// Modified how the authentication is handled. Instead of using Apache login with environmental variables, use PHPSESSID sessions.
//
// Revision 1.1.1.1  2005/04/01 06:55:06  druus
// Initial release.
//
//*****************************************************************************

// Read the configuration file if exists
include_once("./config/admin.conf.php");

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
<HEAD>
<TITLE>OSCAR User Admin</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<META http-equiv="Expires" content="Tue, 20 Aug 1996 14:25:27 GMT">
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="Daniel Ruus">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<LINK HREF="./style.css" TYPE="text/css" REL="stylesheet">

<!-- Create a special style for text input boxes -->
<STYLE TYPE="text/css">
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
</STYLE>

</HEAD>

<BODY BACKGROUND="images/tatami.gif">

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
	<td><?php include("navigate.php");?></td>
	<td>

<?php

($_SERVER['REQUEST_METHOD'] == 'GET') ? $values = $_GET : $values = $_POST;


$module = dirname(__FILE__);
$module_name = basename(dirname(__FILE__));
$index = 0; // 0 - right hand menu turned off / 1 - right hand menu turned on



# Create a connection to the user database
$db = mysql_connect($DBSERVER, $DBUSER, $DBPASSWD);
if (!mysql_select_db($DBNAME, $db)) {
        $errorMessage = mysql_error($db);
        print "ERROR:<BR>\n";
        print $errorMessage;
        die;
}



// Act upon the commands that MIGHT have been passed. No command - list data.

if ($values['cmd'] == "" || $values['cmd'] == "listall")
{
	if (isset($values['users']))
	{
		if ($values['users'] == "user")
			$selectUsers = "SELECT * FROM users WHERE role = 'User' ORDER BY username";
		else if ($values['users'] == "admin")
			$selectUsers = "SELECT * FROM users WHERE role IN ('SuperUser','Admin') ORDER BY username";
		else if ($values['users'] == "all")
			$selectUsers = "SELECT * FROM users ORDER BY username";
		else
			die("Unable to compile query.");
	}
	else
		$selectUsers = "SELECT * FROM users ORDER BY username";

?>

<TABLE ALIGN="center" WIDTH="500" BORDER="0" CELLSPACING="0">
<TR>
	<TD ALIGN="center" COLSPAN="3">
	<H3>User Admin Page</H3>
	</TD>
</TR>
<TR>
	<TD ALIGN="left" COLSPAN="3">
	This utility is used to create and amend the list of users that will appear in the
	list of contact names in the asset register.
	</TD>
</TR>
</TABLE>
<?php
	$userResult = mysql_query($selectUsers) or db_error($db);
	$numUsers = mysql_num_rows($userResult);

	if ($numUsers == 0)
		print "No users found.<BR>\n";
	else
	{
		print "<TABLE CELLSPACING=\"0\" CELLPADDING=\"2\">\n";
		print "<TR>\n\t<TD COLSPAN=\"5\"><A HREF=\"" . $PHP_SELF . "?cmd=newuser\">Create new user</A></TD>\n</TR>\n";
		print "<TR BGCOLOR=\"6780B8\">\n";
		print "\t<TD><FONT COLOR=\"white\">User name</FONT></TD><TD><FONT COLOR=\"white\">Surname</FONT></TD><TD><FONT COLOR=\"white\">First name</FONT></TD><TD><FONT COLOR=\"white\">Privileges</FONT></TD>\n";
		while ($listuser = mysql_fetch_array($userResult))
			print "<TR>\n\t<TD><A HREF=\"" . $PHP_SELF . "?cmd=edit&user_id=" . $listuser['id'] . "\">" . $listuser['username'] . "</A></TD><TD>" . $listuser['surname'] . "</TD><TD>" . $listuser['name'] . "</TD><TD>" . $listuser['role'] . "</TD>\n</TR>\n";
	}
?>
<TR>
	<TD COLSPAN="5">
	<HR ALIGN="centre" WIDTH="75%">
	<A HREF="<?php echo $_SERVER['PHP_SELF']?>?cmd=newuser">Create new user</A>
	</TD>
</TR>
</TABLE>

<?php
} // EOS listall

# Edit an asset
if ($values['cmd'] == "edit" || $values['cmd'] == "newuser")
{

	// Is the user logged in as Administrator? If so, make sure he/she is allowed to
	// update data. This is found by checking the cookie "admin", which is set if the user
	// is looged as an admin.
	if (!isset($_COOKIE['admin']) || $_COOKIE['admin'] == "")
		unset($Authorised);
	else if ($_COOKIE['admin'] > "")
		$Authorised = true;
	else
		unset($Authorised);

	// Is a new user to be created, or are we loading data from an existing user?
	if ($values['cmd'] == "edit")
	{
		// Get details about the selected user
		$query = "SELECT * FROM users WHERE id = " . $values['user_id'] . " LIMIT 1";

		$query_result = mysql_query($query, $db) or die("<H4><FONT COLOR=\"red\">DB Error</FONT></H4>Unable to execute query<BR>$query");

		$num_rows = mysql_num_rows($query_result) or db_error($db, true);

		if ($num_rows == 0)
		{
			#
		    # No matching entry found, so don't do anything else.
			#
			print "<H1>No matching entry found!</H1>\n";
			print "Click <A HREF=\"javascript:history.go(-1);\">here</A> to try again.\n";
		}
		else
		{
			#
		    # Multiple matches found. Let the user select one of them.
			#
			print "<H3 ALIGN=\"center\">Edit User</H3>\n";
			//print "<A HREF=\"javascript:history.go(-1);\">Click here to go back to the previous page</A><BR>\n";

			$userDetails = mysql_fetch_array($query_result);
		}
	}
	else
		print "<H3 ALIGN=\"center\">Create New User</H3>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF;?>" METHOD="post">
<?php
if ($values['cmd'] == "edit")
{
	print "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"updateuser\">\n";
	print "<INPUT TYPE=\"hidden\" NAME=\"user_id\" VALUE=\"" . $userDetails['id'] . "\">\n";
}
else
	print "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"set_password\">\n";
?>

<TABLE BORDER="0" ALIGN="center">
<TR BGCOLOR="6780B8">
	<TD COLSPAN="4"><FONT COLOR="white">User details</FONT></TD>
</TR>
<TR>
	<TD>User name</TD>
	<TD><INPUT TYPE="text" NAME="user_name" CLASS="input-style" VALUE="<?php echo $userDetails['username'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></TD>
	<TD></TD>
	<TD></TD>
</TR>
<TR>
	<TD>First name</TD>
	<TD><INPUT TYPE="text" NAME="first_name" CLASS="input-style" VALUE="<?php echo $userDetails['name'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></TD>
	<TD></TD>
	<TD></TD>
</TR>
<TR>
	<TD>Surname</TD>
	<TD><INPUT TYPE="text" NAME="surname" CLASS="input-style" VALUE="<?php echo $userDetails['surname'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></TD>
	<TD></TD>
	<TD></TD>
</TR>
<TR>
	<TD>Privileges</TD>
	<TD>
		<SELECT NAME="role" CLASS="input-style" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'">
<?php
	$priv_options = array('User','Admin','SuperUser','Disabled');

	foreach ($priv_options as $priv)
	{
		if ($priv == $userDetails['role'])
			print "\t<OPTION VALUE=\"" . $priv . "\" SELECTED>" . $priv . "</OPTION>\n";
		else
			print "\t<OPTION VALUE=\"" . $priv . "\">" . $priv . "</OPTION>\n";
	}
?>
	  </SELECT>
  </TD>
	<TD>&nbsp;</TD>
	<TD>&nbsp;</TD>
</TR>


<?php
if ($values['cmd'] == "edit")
{
?>
	<TD COLSPAN="4">
		<INPUT TYPE="submit" VALUE="Save">&nbsp;
		<INPUT TYPE="button" VALUE="Cancel" onClick="javascrpt:history.back(1);">&nbsp;&nbsp;&nbsp;
		<A HREF="<?php echo $_SERVER['PHP_SELF'];?>?cmd=delete_user&user_id=<?php echo $userDetails['id'];?>" onClick="return confirmSubmit('Are you sure you want to delete this user?');">
		<IMG SRC="images/delete_small.gif" BORDER="0" ALT="Delete user">Delete user
		</A>&nbsp;&nbsp;
		<A HREF="<?php echo $_SERVER['PHP_SELF'];?>?cmd=change_password&user_id=<?php echo $userDetails['id'];?>">Change password</A>
	</TD>
<?php
}
else
{
?>
	<TD COLSPAN="2"><INPUT TYPE="button" VALUE="Cancel" onClick="javascrpt:history.back(1);">&nbsp;<INPUT TYPE="submit" VALUE="Continue -->"></TD>
<?php
}
?>
</TABLE>
</FORM>

<?php

	//}

}

if ($values['cmd'] == "set_password" || $values['cmd'] == "change_password")
{

	if ($values['cmd'] == "set_password")
		print "<H3 ALIGN=\"center\">Create New User</H3>\n";
	else
		print "<H3 ALIGN=\"center\">Change password</H3>\n";
?>

<FORM ACTION="<?php echo $PHP_SELF;?>" METHOD="post">

<?php
if ($values['cmd'] == "set_password")
	print "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"create_new_user\">\n";
else
{
	print "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"update_password\">\n";
	print "<INPUT TYPE=\"hidden\" NAME=\"user_id\" VALUE=\"" . $values['user_id'] . "\">\n";
}
?>
<INPUT TYPE="hidden" NAME="user_name" VALUE="<?php echo $values['user_name'];?>">
<INPUT TYPE="hidden" NAME="first_name" VALUE="<?php echo $values['first_name'];?>">
<INPUT TYPE="hidden" NAME="surname" VALUE="<?php echo $values['surname'];?>">
<INPUT TYPE="hidden" NAME="email" VALUE="<?php echo $values['email'];?>">
<INPUT TYPE="hidden" NAME="extension" VALUE="<?php echo $values['extension'];?>">
<INPUT TYPE="hidden" NAME="priv" VALUE="<?php echo $values['priv'];?>">
<INPUT TYPE="hidden" NAME="allow_floor_plan" VALUE="<?php echo $values['allow_floor_plan'];?>">
<INPUT TYPE="hidden" NAME="notes" VALUE="<?php echo $values['notes'];?>">


<TABLE BORDER="0" ALIGN="center">
<TR BGCOLOR="6780B8">
	<TD COLSPAN="2">Specify a password twice</TD>
</TR>
<TR>
	<TD>Password</TD>
	<TD><INPUT TYPE="password" NAME="pwd1" CLASS="input-style" onFocus="pwd1.style.backgroundColor='#FFFFCC'" onBlur="pwd1.style.backgroundColor='#FFFFFF'"></TD>
</TR>
<TR>
	<TD>Again</TD>
	<TD><INPUT TYPE="password" NAME="pwd2" CLASS="input-style" onFocus="style.backgroundColor='#FFFFCC'" onBlur="pwd2.style.backgroundColor='#FFFFFF'"></TD>
</TR>
<TR>
	<TD COLSPAN="2">
		<INPUT TYPE="button" VALUE="Cancel" onClick="javascript:location = 'useradm.php';">&nbsp;&nbsp;&nbsp;
		<INPUT TYPE="button" VALUE="<-- Back" onClick="javascript:history.back(1);">&nbsp;
<?php
if ($values['cmd'] == "set_password")
	print "\t\t<INPUT TYPE=\"submit\" VALUE=\"Set password!\">\n";
else
	print "\t\t<INPUT TYPE=\"submit\" VALUE=\"Change password!\">\n";
?>

	</TD>
</TR>
</TABLE>
</FORM>

<?php
}

if ($values['cmd'] == "create_new_user")
{
	if ($values['pwd1'] != $values['pwd2'])
	{
		print "<H1>Passwords to not match</H1>\n";
		print "<A HREF=\"javascript:history.back(1);\">Click here to go back</A>\n";
	}
	else
	{
		// Ensure we get a decent value from the checkbox allow_floor_plan!
		if ($values['allow_view_floorplan'] == "on")
			$floorplan = "Yes";
		else
			$floorplan = "No";

		$hashedPassword = hash('sha256', $values['pwd1']);
		$createNew = "INSERT INTO users ";
		$createNew .= "(username, surname, name, role, pwd, created) ";
		$createNew .= "VALUES ('" . $values['username'] . "', ";
		$createNew .= "'" . $values['surname'] . "', ";
		$createNew .= "'" . $values['name'] . "', ";
		$createNew .= "'" . $values['role'] . "', ";
		$createNew .= "'" . $hashedPassword . "', ";
		$createNew .= "NOW() ) ";

		$createResult = mysql_query($createNew, $db) or db_error($db, true);

		// Reload the screen
		print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $PHP_SELF . "?cmd=\">\n";
	}
}

if ($values['cmd'] == "updateuser")
{

	// Ensure we get a decent value from the checkbox allow_floor_plan!
	if ($values['allow_view_floorplan'] == "on")
		$floorplan = "Yes";
	else
		$floorplan = "No";

	$query = "UPDATE users ";
	$query .= "SET username = '" . $values['user_name'] . "', ";
	$query .= "surname = '" . $values['surname'] . "', ";
	$query .= "name = '" . $values['first_name'] . "', ";
	$query .= "role = '" . $values['role'] . "', ";
	$query .= "date_updated = NOW() ";
	$query .= "WHERE id = " . $values['user_id'];

	//die("Query: $query");
	$query_result = mysql_query($query, $db) or db_error($db, true);

	# Reload the previous page...
	print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $PHP_SELF . "?cmd=\">\n";

} // End of UpdateUser

if ($values['cmd'] == "update_password")
{
	if ($values['pwd1'] != $values['pwd2'])
	{
		print "<H1><FONT COLOR=\"red\">Passwords to not match</FONT></H1>\n";
		print "<A HREF=\"javascript:history.back(1);\">Click here to go back</A>\n";
	}
	else if ($values['pwd1'] == "")
	{
		print "<H1><FONT COLOR=\"red\">Blank passwords not allowed</FONT></H1>\n";
		print "<A HREF=\"javascript:history.back(1);\">Click here to go back</A>\n";
	}
	else
	{
		$hashedPassword = hash('sha256', $values['pwd1']);
		$updatePwd = "UPDATE users SET pwd = '" . $hashedPassword . "', updated = NOW() WHERE id = " . $values['user_id'];

		$updateResult = mysql_query($updatePwd) or db_error($db, true);

		// If the logged in user is changing his/her own password, ensure the password in the session
		// variable is also changed, otherwise the user will be locked out. For this, we need to check
		// the user table for a match
		$checkUser = "SELECT id FROM users WHERE username = '" . $_SESSION['username'] . "' ";
		$resUser = mysql_query($checkUser, $db) or db_error($db, true);
		$numUsers = mysql_num_rows($resUser);

		if ($numUsers == 1)
		{
			$fetchUser = mysql_fetch_array($resUser);
			if ($fetchUser['id'] == $values['user_id'])
			{
				$_SESSION['password'] = $values['pwd1'];
			}
		}

		print "<H1><FONT COLOR=\"blue\">Password changed</FONT></H1>\n";
		print "<A HREF=\"javascript:location = 'useradm.php';\">Click here to go back</A>\n";
	}

} // End of UpdatePassword

if ($values['cmd'] == "delete_user")
{

	$query = "DELETE FROM user ";
	$query .= "WHERE id = " . $values['user_id'];

	//die("Query: $query");
	$query_result = mysql_query($query, $db) or db_error($db, true);

	print "User " . $values['user_name'] . " successfully removed.<BR>\n";
	print "<A HREF=\"" . $PHP_SELF . "?cmd=\">Click here to return to the main screen.<BR></A>\n";
	// Reload the previous page...
	//print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $PHP_SELF . "?cmd=\">\n";

} // End of DeleteUser

# Free the resultset to save memory
@mysql_free_result($query_result);

?>
</td>
</tr>
</table>
</body>
</html>
