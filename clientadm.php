<?php
//*****************************************************************************
// $Header: /srv/cvsroot/AQasset/admin/clientadm.php,v 1.2 2005/04/14 14:04:01 druus Exp $
//
// $Author: druus $
//
// Description	Provides a front end for the AQ intranet admin page,
//				where various administrative tasks such as managing
//				users are done.
//
// --------------------------------------
// History
// -------
// $Log: clientadm.php,v $
// Revision 1.2  2005/04/14 14:04:01  druus
// Modified how the authentication is handled. Instead of using Apache login with environmental variables, use PHPSESSID sessions.
//
// Revision 1.1.1.1  2005/04/01 06:55:06  druus
// Initial release.
//
//*****************************************************************************
error_reporting( E_ALL );

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
	echo "Click 'Quick Admin' to return to the asset index page and login first.\n";
	die();
}

include("/srv/www/htdocs/common/utils.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>OSCAR Client Admin</TITLE>
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

<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- Hide for old browsers
function confirmSubmit(text)
{
	var agree=confirm(text);

	if (agree)
		return true ;
	else
		return false ;

}
// Finished hiding -->
</SCRIPT>

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

if ($values['cmd'] == "" | $values['cmd'] == "listall")
{
		$selectUsers = "SELECT * FROM clients ORDER BY client";
?>

<TABLE ALIGN="center" WIDTH="500" BORDER="0" CELLSPACING="0">
<TR>
	<TD ALIGN="center" COLSPAN="3">
	<H3>Client Admin Page</H3>
	</TD>
</TR>
</TABLE>

<?php
	$userResult = mysql_query($selectUsers) or db_error($db);
	$numUsers = mysql_num_rows($userResult);

	if ($numUsers == 0)
		print "No clients found.<BR>\n";
	else
	{
?>
<TABLE CELLSPACING="0" CELLPADDING="2">
<TR>
	<TD COLSPAN="3">
	<A HREF="<?php echo $_SERVER['PHP_SELF']?>?cmd=newclient">Create a new Client</A>
	</TD>
</TR>
<TR BGCOLOR="6780B8">
	<TD><FONT COLOR="white">Client</FONT></TD>
	<TD><FONT COLOR="white">Information</FONT></TD>
	<TD><FONT COLOR="white">Active?</FONT></TD>
</TR>
<?php
		while ($listuser = mysql_fetch_array($userResult))
			print "<TR>\n\t<TD><A HREF=\"" . $PHP_SELF . "?cmd=edit&client_id=" . $listuser['cid'] . "\">" . $listuser['client'] . "</A></TD><TD>" . substr($listuser['info'], 0, 50) . "</TD><TD>" . $listuser['active'] . "</TD></TR>\n";
	}
?>
<TR>
	<TD COLSPAN="3">
	<HR WIDTH="75%">
	<A HREF="<?php echo $_SERVER['PHP_SELF']?>?cmd=newclient">Create a new Client</A>
	</TD>
</TR>
</TABLE>

<?php

} // EOS listall

# Edit an asset
if ($values['cmd'] == "edit" || $values['cmd'] == "newclient")
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
		$query = "SELECT * FROM clients WHERE cid = " . $values['client_id'] . " LIMIT 1";

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
			print "<H3 ALIGN=\"center\">Edit Client</H3>\n";
			//print "<A HREF=\"javascript:history.go(-1);\">Click here to go back to the previous page</A><BR>\n";

			$statusDetails = mysql_fetch_array($query_result);
		}
	}
	else
		print "<H3 ALIGN=\"center\">Create New Client</H3>\n";
?>
<FORM ACTION="<?php echo $PHP_SELF;?>" METHOD="post">
<?php
if ($values['cmd'] == "edit")
{
	print "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"updateclient\">\n";
	print "<INPUT TYPE=\"hidden\" NAME=\"client_id\" VALUE=\"" . $statusDetails['cid'] . "\">\n";
}
else
	print "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"create_new_client\">\n";
?>

<TABLE BORDER="0" ALIGN="center">
<TR BGCOLOR="6780B8">
	<TD COLSPAN="4"><FONT COLOR="white">Client details</FONT></TD>
</TR>
<TR>
	<TD nowrap>Client</TD>
	<TD colspan="3"><INPUT TYPE="text" NAME="client" CLASS="input-style" VALUE="<?php echo $statusDetails['client'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></TD>
</TR>
<TR>
	<TD nowrap>Client full name</TD>
	<TD colspan="3"><INPUT TYPE="text" NAME="client_full_name" SIZE="60" CLASS="input-style" VALUE="<?php echo $statusDetails['client_full_name'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></TD>
</TR>
<TR>
	<TD nowrap>Information</TD>
	<TD colspan="3"><INPUT TYPE="text" NAME="info" SIZE="60" CLASS="input-style" VALUE="<?php echo $statusDetails['info'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></TD>
</TR>
<TR>
	<TD nowrap>Active?</TD>
	<TD><SELECT NAME="active" CLASS="input-style" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'">
<?php

	if ($statusDetails['active'] == "Yes")
	{
		print "\t<OPTION VALUE=\"Yes\" SELECTED>Yes</OPTION>\n";
		print "\t<OPTION VALUE=\"No\">No</OPTION>\n";
  	}
	else
	{
		print "\t<OPTION VALUE=\"Yes\">Yes</OPTION>\n";
		print "\t<OPTION VALUE=\"No\" SELECTED>No</OPTION>\n";
  	}

?>
	</SELECT>
	</TD>
</TR>
<TR>
<?php
if ($values['cmd'] == "edit")
{
?>
	<TD COLSPAN="4">
		<INPUT TYPE="submit" VALUE="Save">&nbsp;
		<INPUT TYPE="button" VALUE="Cancel" onClick="javascript:history.back(1);">&nbsp;&nbsp;&nbsp;
		<A HREF="<?php echo $_SERVER['PHP_SELF'];?>?cmd=delete_client&cid=<?php echo $statusDetails['cid'];?>" onClick="return confirmSubmit('Are you sure you want to delete this Client?');">
		<IMG SRC="images/delete_small.gif" BORDER="0" ALT="Delete Status">Delete Client
		</A>&nbsp;&nbsp;
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

if ($values['cmd'] == "create_new_client")
{
	// Create an INSERT statement for a new Status level
	$createNew = "INSERT INTO clients ";
	$createNew .= "(client, client_full_name, info, active) ";
	$createNew .= "VALUES ('" . $values['client'] . "', ";
	$createNew .= "'" . $values['client_full_name'] . "', ";
	$createNew .= "'" . $values['info'] . "', ";
	$createNew .= "'" . $values['active'] . "') ";

	//die("Query: $createNew");
	$createResult = mysql_query($createNew, $db) or db_error($db, true);

	print "New client '" . $values['client'] . "' successfully created.<BR>\n";
	print "<A HREF=\"" . $PHP_SELF . "?cmd=\">Click here to return to the main screen.<BR></A>\n";
	// Reload the screen
	//print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $PHP_SELF . "?cmd=\">\n";

}

if ($values['cmd'] == "updateclient")
{

	$query = "UPDATE clients ";
	$query .= "SET client = '" . $values['client'] . "', ";
	$query .= "client_full_name = '" . $values['client_full_name'] . "', ";
	$query .= "info = '" . $values['info'] . "', ";
	$query .= "active = '" . $values['active'] . "' ";
	$query .= "WHERE cid = " . $values['client_id'];

	print "Query: $query<br/>\n";
	$query_result = mysql_query($query, $db) or db_error($db, true);

	# Reload the previous page...
	print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $PHP_SELF . "?cmd=\">\n";

} // End of UpdateUser


if ($values['cmd'] == "delete_client")
{

	$query = "DELETE FROM clients ";
	$query .= "WHERE cid = " . $values['cid'];

	//die("Query: $query");
	$query_result = mysql_query($query, $db) or db_error($db, true);

	print "Client " . $values['client'] . " successfully removed.<BR>\n";
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

