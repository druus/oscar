<?php
$AR_VERSION="0.0.1";
$AR_AUTHOR="Daniel Ruus";
$AR_MODIFIED="23/11/2005";

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
<title>OSCAR Supplier Admin</title>
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

<body>

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

// Create a connection to the database
$mydb = new mysqldb($DBSERVER, $DBNAME, $DBUSER, $DBPASSWD);
if (! $mydb)
	print_error("Unable to connect to database.<br/><b>MySQL error</b><br/>Errno: " . mysql_errno() . "<br/>Error: " . mysql_error(), "error");


//*****************************************************************************
// Function     display_suppliers()
// Arguments    $db - database identifier
// Return value none
// Description  List all existing software license types
//*****************************************************************************
function display_suppliers($db)
{
	$selectCategory = "SELECT * FROM aq_supplier ORDER BY supplier";
?>

<table align="center" width="500" border="0" cellspacing="0">
<tr>
	<td align="center" colspan="3">
	<h3>Supplier Admin Page</h3>
	</td>
</tr>
</table>

<table cellspacing="0" cellpadding="2">
<tr>
	<td colspan="3">
	<a href="<?php echo $_SERVER['PHP_SELF']?>?cmd=newsupplier">Create a new supplier entry</a>
	</td>
</tr>
<?php
	$catResult = mysql_query($selectCategory) or db_error($db);
	$numCat = mysql_num_rows($catResult);

	if ($numCat == 0)
		print "No suppliers found.<BR>\n";
	else
	{
?>

<tr bgcolor="#6780b8">
	<td><font color="white">Supplier</font></td>
	<td><font color="white">Description</font></td>
	<td><font color="white">Telephone</font></td>
	<td><font color="white">Email</font></td>
</tr>
<?php
		while ($listcat = mysql_fetch_array($catResult))
		{
?>
<tr>
	<td><a href="<?= $PHP_SELF?>?cmd=edit&supp_id=<?= $listcat['supp_id']?>"><?= $listcat['supplier']?></a></td>
	<td><?= substr($listcat['description'], 0, 40)?></td>
	<td><?= $listcat['telephone_main']?></td>
	<td><?= $listcat['email_main']?></td>
</tr>
<?php
		}
			//print "<tr>\n\t<td><a href=\"" . $PHP_SELF . "?cmd=edit&id=" . $listcat['id'] . "\">" . $listcat['license_name'] . "</a></td></td><td>" . substr($listcat['license_description'], 0, 50) . "</td><td>" . $listcat['active'] . "</td></tr>\n";
	}
?>
<tr>
	<td colspan="3">
	<hr width="75%">
	<a href="<?php echo $_SERVER['PHP_SELF']?>?cmd=newsupplier">Create a new supplier entry</a>
	</td>
</tr>
</table>

<?php

} // EOF display_licenses()


//*****************************************************************************
// Function     edit_supplier()
// Arguments    $db - database identifier
//              $cmd - command (edit or new)
//              $id - software license ID
// Return value none
// Description  Modify the data for a selected supplier
//*****************************************************************************
function edit_supplier($db, $cmd, $id = 0)
{
	// Is a new user to be created, or are we loading data from an existing user?
	if ($cmd == "edit")
	{
		// Get details about the selected user
		$query = "SELECT * FROM aq_supplier WHERE supp_id = " . $id . " LIMIT 1";

		$query_result = mysql_query($query) or die("<H4><FONT COLOR=\"red\">DB Error</FONT></H4>Unable to execute query<BR>$query" . "<br/>" . mysql_error($db));
		//$query_result = mysql_query($query, $db);
		


		$num_rows = mysql_num_rows($query_result) or die("Bah, something went wrong with a database query.<br/>The error is: " . mysql_error($db));

		if ($num_rows == 0)
		{
			#
		    # No matching entry found, so don't do anything else.
			#
			print "<h1>No matching entry found!</h1>\n";
			print "Click <a href=\"javascript:history.go(-1);\">here</a> to try again.\n";
		}
		else 
		{
			#
		    # Multiple matches found. Let the user select one of them.
			#
			print "<h3 align=\"center\">Edit Supplier</h3>\n";
			//print "<A HREF=\"javascript:history.go(-1);\">Click here to go back to the previous page</A><BR>\n";

			$statusDetails = mysql_fetch_array($query_result);
		}
	}
	else
		print "<h3 align=\"center\">Create New Supplier</h3>\n";
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<?php
if ($cmd == "edit")
{
	print "<input type=\"hidden\" name=\"cmd\" value=\"updatesupplier\">\n";
	print "<input type=\"hidden\" name=\"supp_id\" value=\"" . $statusDetails['supp_id'] . "\">\n";
}
else
	print "<input type=\"hidden\" name=\"cmd\" value=\"create_new_supplier\">\n";
?>

<table border="0" align="center">
<tr bgcolor="#6780b8">
	<td colspan="4"><font color="white">Supplier Details</font></td>
</tr>
<tr>
	<td valign="top">Supplier</td>
	<td colspan="3"><input type="text" name="supplier" class="input-style" size="32" value="<?php echo $statusDetails['supplier'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Description</td>
	<td colspan="3"><textarea name="description" rows="3" cols="64" class="input-style" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"><?php echo $statusDetails['description'] ?></textarea></td>
</tr>

<tr>
	<td valign="top">Address</td>
	<td colspan="3"><textarea name="address" rows="4" cols="64" class="input-style" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"><?php echo $statusDetails['address'] ?></textarea></td>
</tr>

<tr>
	<td valign="top">City</td>
	<td><input type="text" name="city" class="input-style" size="32" value="<?php echo $statusDetails['city'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top">Post Code</td>
	<td><input type="text" name="post_code" class="input-style" size="12" value="<?php echo $statusDetails['post_code'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Country</td>
	<td><input type="text" name="country" class="input-style" size="32" value="<?php echo $statusDetails['country'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td></td>
	<td></td>
</tr>

<tr>
	<td valign="top" nowrap>Telephone (main)</td>
	<td><input type="text" name="telephone_main" class="input-style" size="32" value="<?php echo $statusDetails['telephone_main'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top" nowrap>Fax (main)</td>
	<td><input type="text" name="fax_main" class="input-style" size="12" value="<?php echo $statusDetails['fax_main'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top" nowrap>Email (main)</td>
	<td><input type="text" name="email_main" class="input-style" size="40" value="<?php echo $statusDetails['email_main'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top" nowrap>Website</td>
	<td><input type="text" name="website" class="input-style" size="40" value="<?php echo $statusDetails['website'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<!--***************************************************************************
**
** CONTACT DETAILS
**
****************************************************************************-->

<tr bgcolor="#6780b8">
	<td colspan="4"><font color="white">Contact Details</font></td>
</tr>
<tr>
	<td valign="top">Contact Name</td>
	<td><input type="text" name="contact_name_1" class="input-style" size="40" value="<?php echo $statusDetails['contact_name_1'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top" colspan="2"><!--Preferred Contact?&nbsp;
	<input type="checkbox" name="contact_name_1" class="input-style" size="40" value="<?php echo $statusDetails['contact_name_1'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'">--></td>
</tr>

<tr>
	<td valign="top">Job Title</td>
	<td colspan="3"><input type="text" name="contact_title_1" class="input-style" size="32" value="<?php echo $statusDetails['contact_title_1'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Telephone</td>
	<td><input type="text" name="contact_tel_1" class="input-style" size="32" value="<?php echo $statusDetails['contact_tel_1'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top">Fax</td>
	<td><input type="text" name="contact_fax_1" class="input-style" size="32" value="<?php echo $statusDetails['contact_fax_1'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Email</td>
	<td colspan="3"><input type="text" name="contact_email_1" class="input-style" size="64" value="<?php echo $statusDetails['contact_email_1'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<!--
<tr bgcolor="#6780b8">
	<td colspan="4"><font color="white">Contact Details (2nd contact)</font></td>
</tr>
<tr>
	<td valign="top">Contact Name</td>
	<td><input type="text" name="contact_name_2" class="input-style" size="40" value="<?php echo $statusDetails['contact_name_2'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top" colspan="2">Preferred Contact?&nbsp;
	<input type="checkbox" name="contact_name_2" class="input-style" size="40" value="<?php echo $statusDetails['contact_name_2'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Job Title</td>
	<td colspan="3"><input type="text" name="contact_title_2" class="input-style" size="32" value="<?php echo $statusDetails['contact_title_2'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Telephone</td>
	<td><input type="text" name="contact_tel_2" class="input-style" size="32" value="<?php echo $statusDetails['contact_tel_2'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top">Fax</td>
	<td><input type="text" name="contact_fax_2" class="input-style" size="32" value="<?php echo $statusDetails['contact_fax_2'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Email</td>
	<td colspan="3"><input type="text" name="contact_email_2" class="input-style" size="64" value="<?php echo $statusDetails['contact_email_2'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>


<tr bgcolor="#6780b8">
	<td colspan="4"><font color="white">Contact Details (3rd contact)</font></td>
</tr>
<tr>
	<td valign="top">Contact Name</td>
	<td><input type="text" name="contact_name_3" class="input-style" size="40" value="<?php echo $statusDetails['contact_name_3'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top" colspan="2">Preferred Contact?&nbsp;
	<input type="checkbox" name="contact_name_3" class="input-style" size="40" value="<?php echo $statusDetails['contact_name_3'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Job Title</td>
	<td colspan="3"><input type="text" name="contact_title_3" class="input-style" size="32" value="<?php echo $statusDetails['contact_title_3'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Telephone</td>
	<td><input type="text" name="contact_tel_3" class="input-style" size="32" value="<?php echo $statusDetails['contact_tel_3'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
	<td valign="top">Fax</td>
	<td><input type="text" name="contact_fax_3" class="input-style" size="32" value="<?php echo $statusDetails['contact_fax_3'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>

<tr>
	<td valign="top">Email</td>
	<td colspan="3"><input type="text" name="contact_email_3" class="input-style" size="64" value="<?php echo $statusDetails['contact_email_3'] ?>" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'"></td>
</tr>
-->


<!--
<tr>
	<td>License Type</td>
	<td><select name="type" class="input-style" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'">
<?php
$licTypes = array("Not Specified", "Commercial", "GPL", "Open Source", "Freeware - Other");
foreach ($licTypes as $license)
{
	if ($license == $statusDetails['type'])
	    print "\t<OPTION VALUE=\"" . $license . "\" SELECTED>" . $license . "</OPTION>\n";
	else
	    print "\t<OPTION VALUE=\"" . $license . "\">" . $license . "</OPTION>\n";
}

?>
	</select>
	</td>
</tr>

<tr>
	<td>Active?</td>
	<td><select name="active" class="input-style" onFocus="style.backgroundColor='#FFFFCC'" onBlur="style.backgroundColor='#FFFFFF'">
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
-->

<TR>
<?php
if ($values['cmd'] == "edit")
{
?>
	<TD COLSPAN="4">
		<INPUT TYPE="submit" VALUE="Save">&nbsp;
		<INPUT TYPE="button" VALUE="Cancel" onClick="javascript:history.back(1);">&nbsp;&nbsp;&nbsp;
		<A HREF="<?php echo $_SERVER['PHP_SELF'];?>?cmd=delete_status&cat_id=<?php echo $statusDetails['id'];?>" onClick="return confirmSubmit('Are you sure you want to delete this Category?');">
		<IMG SRC="images/delete_small.gif" BORDER="0" ALT="Delete Category">Delete Category
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


//*****************************************************************************
// Function     createsupplier()
// Arguments    $db - database identifier
//              $values - array with all the information about the license in
// Return value none
// Description  Create a new supplier entry
//*****************************************************************************
function createsupplier($db, $values)
{
	// Create an INSERT statement for a new Status level
	$supplier = $values['supplier'];
	$description = $values['description'];
	$address = $values['address'];
	$city = $values['city'];
	$post_code = $values['post_code'];
	$country = $values['country'];
	$telephone_main = $values['telephone_main'];
	$fax_main = $values['fax_main'];
	$email_main = $values['email_main'];
	$website = $values['website'];
	$contact_name_1 = $values['contact_name_1'];
	$contact_title_1 = $values['contact_title_1'];
	$contact_preferred_1 = $values['contact_preferred_1'];
	$contact_tel_1 = $values['contact_tel_1'];
	$contact_fax_1 = $values['contact_fax_1'];
	$contact_email_1 = $values['contact_email_1'];
	$entry_created_by = $_SESSION['username'];

	$createNew =<<< EOQ
INSERT INTO aq_supplier
(supplier, description, address, city, post_code, country, telephone_main, fax_main,
email_main, website, contact_name_1, contact_title_1, contact_preferred_1,
contact_tel_1, contact_fax_1, contact_email_1, entry_created, entry_created_by)
VALUES ('$supplier', '$description', '$address', '$city', '$post_code', '$country',
'$telephone_main', '$fax_main', '$email_main', '$website',
'$contact_name_1',
'$contact_title_1',
'$contact_preferred_1',
'$contact_tel_1',
'$contact_fax_1',
'$contact_email_1',
NOW(),
'$entry_created_by')
EOQ;
	//die("Query: $createNew");
	$createResult = mysql_query($createNew, $db);
	
	if (! $createResult)
	{
	    print_error("Unable to create new software license entry!<br/>Database error: " . mysql_error($db) . "<br/>SQL statement: " . $createResult . "<br/>", "error");
	    die();
	}
	    
	print "License <b>'" . $values['license_name'] . "'</b> successfully created.<BR>\n";
	print "<A HREF=\"" . $_SERVER['PHP_SELF'] . "?cmd=\">Click here to return to the main screen.<BR></A>\n";
	// Reload the screen
	print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"2;url=" . $_SERVER['PHP_SELF'] . "?cmd=\">\n";

}


//*****************************************************************************
// Function     updatesupplier()
// Arguments    $db - database identifier
//              $cmd - command (edit or new)
//              $id - software license ID
// Return value none
// Description  Modify the data for a selected license type
//*****************************************************************************
function updatesupplier($db, $values)
{

	$supp_id = $values['supp_id'];
	$supplier = $values['supplier'];
	$description = $values['description'];
	$address = $values['address'];
	$city = $values['city'];
	$post_code = $values['post_code'];
	$country = $values['country'];
	$telephone_main = $values['telephone_main'];
	$fax_main = $values['fax_main'];
	$email_main = $values['email_main'];
	$website = $values['website'];
	$contact_name_1 = $values['contact_name_1'];
	$contact_title_1 = $values['contact_title_1'];
	$contact_preferred_1 = $values['contact_preferred_1'];
	$contact_tel_1 = $values['contact_tel_1'];
	$contact_fax_1 = $values['contact_fax_1'];
	$contact_email_1 = $values['contact_email_1'];
	$entry_modified_by = $_SESSION['username'];

	$query =<<< EOQ
UPDATE aq_supplier
SET supplier = '$supplier',
description = '$description',
address = '$address',
city = '$city',
post_code = '$post_code',
country = '$country',
telephone_main = '$telephone_main',
fax_main = '$fax_main',
email_main = '$email_main',
website = '$website',
contact_name_1 = '$contact_name_1',
contact_title_1 = '$contact_title_1',
contact_preferred_1 = '$contact_preferred_1',
contact_tel_1 = '$contact_tel_1',
contact_fax_1 = '$contact_fax_1',
contact_email_1 = '$contact_email_1',
entry_modified = NOW(),
entry_created_by = '$entry_modified_by'
WHERE supp_id = $supp_id
EOQ;

	//die("Query: $query");
	$query_result = mysql_query($query, $db);

	if (!$query_result)
	{
		print_error("Unable to update details for license type.<br/>Database error: <b>" . mysql_error() . "</b><br/><br/>Query: " . $query . "<br/>", "error");
		die();
	}

	# Reload the previous page...
	print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $PHP_SELF . "?cmd=\">\n";

} // End of UpdateUser


if ($values['cmd'] == "delete_status")
{

	$query = "DELETE FROM category ";
	$query .= "WHERE id = " . $values['cat_id'];

	die("Query: $query");
	$query_result = mysql_query($query, $db) or db_error($db, true);

	print "Category '" . $values['category'] . "' successfully removed.<BR>\n";
	print "<A HREF=\"" . $PHP_SELF . "?cmd=\">Click here to return to the main screen.<BR></A>\n";
	// Reload the previous page...
	//print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $PHP_SELF . "?cmd=\">\n";

} // End of DeleteUser

# Free the resultset to save memory
#@mysql_free_result($query_result);



?>

<?php
// Call the required function depending on the selection made previously

switch ($values['cmd'])
{
	case "newsupplier":
	    edit_supplier($mydb, "new");
	    break;
	    
	case "edit":
	    edit_supplier($mydb, "edit", $values['supp_id']);
	    break;
	    
	case "updatesupplier":
	    updatesupplier($mydb, $values);
	    break;

	case "create_new_supplier":
	    createsupplier($mydb, $values);
	    break;
	    
	case "listall":
	default:
	    display_suppliers($mydb);
	    
}
?>
</body>
</html>

