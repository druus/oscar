<?php
$VERSION="0.0.1";
$AUTHOR="Daniel Ruus";
$MODIFIED="15/11/2005";
//
// $Header: /srv/cvsroot/AQasset/include/authenticate.inc.php,v 1.1 2005/05/23 12:49:25 druus Exp $
//

session_start();

// Get the current base directory
$dir = basename(dirname($_SERVER['PHP_SELF']),"/");

// Read in the database configurations. Depending on which path is currently used,
// amend the search path for the conf file.
if ($dir == "admin")
	require_once("../config/admin.conf.php");
else
	require_once("config/admin.conf.php");


// Create a connection to the database
$auth = @mysql_connect($DBSERVER, $DBUSER, $DBPASSWD);
if (!@mysql_select_db($DBNAME, $auth)) {
        $errorMessage = @mysql_error($auth);
		print_error("Failed to select database '$DBNAME'.", "error");
        die;
}

function authenticate()
{
	if (isset($_SESSION['username']) && isset($_SESSION['password']))
	{
		$hashedPassword = hash('sha256', $_SESSION['password']);
		$privQuery = "SELECT role FROM users WHERE username = '" . $_SESSION['username'] . "' AND password = '" . $hashedPassword . "'";
		$privResult = mysql_query($privQuery) or die(print_error("Unable to execute query <br/>" . $privQuery . ".<br/>" . mysql_error(), "error"));
		$privRows = mysql_num_rows($privResult);

		$priv = mysql_fetch_array($privResult) or die("Unable to fetch data from executed query <br/>" . $privQuery . ".<br/>" . mysql_error());

		return $priv[0];
	}
	return false;
} // EOF authenticate()


//*******************************************************************
// Function		user_show_login()
// Arguments	none
// Description	Provide user with form to enter user name and password
// Return value	none
//*******************************************************************
function user_show_login()
{
?>
<table align="center" width="320" border="0" cellspacing="2" cellpadding="2">
<tr>
	<td colspan="2" align="center"><img src="images/aqe_asset_logo.png" width="540" height="50" alt="Absolute Quality (Europe) Ltd">&nbsp;<h1>Login to AQ Asset</h1></td>
</tr>
<tr>
	<td width="120" valign="top">
		<img src="images/login.jpg" width="120" height="100">
	</td>
	<td align="left">
		<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
		<input type="hidden" name="cmd" value="login">
		<table>
		<tr>
			<td>Username:</td>
			<td><input type="text" name="username" size="20" class="input-style"></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password" size="16" class="input-style"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Login"></td>
		</tr>
		</table>
		</form>
	</td>
</tr>
</table>

<?php

} // EOF user_show_login()


//*******************************************************************
// Function		user_login()
// Arguments	$values - arrary containing details of user name and
//				password as entered in function user_show_login()
// Description	Search the user database table to see if the credentials
//				entered are valid. If so, register the user in the
//				current session.
// Return value	User name if login works, FALSE otherwise
//*******************************************************************
function user_login($username, $password, $db)
{

	// Check if login works
	//$user = new User($values['username']);
	$hashedPassword = hash('sha256', $password);
	$privQuery = "SELECT username, role FROM users WHERE username = '" . $username . "' AND password = '" . $password . "' ";
	$privResult = mysql_query($privQuery) or die("Unable to execute query <br/>" . $privQuery . ".<br/>" . mysql_error());
	$privRows = mysql_num_rows($privResult);

	//die("QUERY: $privQuery<br/>\n");
	if ($privRows == 0)
		return FALSE;
	else
	{
		$privFetch = mysql_fetch_array($privResult) or die("Unable to fetch data from executed query <br/>" . $privQuery . ".<br/>" . mysql_error());
		$priv = $privFetch['priv'];
		$uname = $privFetch['user_name'];

		// Store the user name as a session variable
		//$loggeduser = $username;
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		echo "You are logged in as '" . $username . "'.<br/>\n";

		return $uname;
 	}

} // EOF user_login()


//*******************************************************************
// Function		user_logout()
// Arguments	none
// Description	Clear the session variables user_name and password
// Return value	TRUE if it works, FALSE otherwise
//*******************************************************************
function user_logout()
{
	// Remove the user name from the session variable
	$username = $_SESSION['username'];
	unset($_SESSION['username']);
	unset($_SESSION['password']);
	echo "Logging out user '" . $username . "'.<br/>\n";

	echo "Click <a href=\"index.php\">here</a> to continue.<br/>\n";
	//die();

} // EOF user_logout()


//*******************************************************************
// Function		check_permissions()
// Arguments    $asset - asset number to check permissions for
//              $db - database identifier
// Description	Check what permissions the current user has for
//              the given asset
// Return value	none - no permissions at all
//              read - user can read the asset details
//              write - user can modify the asset
//*******************************************************************
function check_permissions($db, $asset)
{
	// First of all, check that the user has logged in correctly
	$loggedIn = authenticate();
	if ($loggedIn != FALSE)
    {
		// Create an SQL statement to retreive ACL data linked to the asset
		$getPerm = "SELECT access FROM acl WHERE acl_asset = " . $asset . " AND acl_gid = (SELECT id FROM user WHERE user_name = '" . $_SESSION['username'] . "') LIMIT 1";

		//echo "Query is: " . $getPerm . "<br/>\n";
		$resPerm = mysql_query($getPerm) or print_error("Unable to execute query '" . $getPerm . "'.<br/>Database reported:" . mysql_error(), "error");
		if ( mysql_num_rows( $resPerm ) == 0 ) {
			return "none";
		}
		else {
			$fetchPerm = mysql_fetch_array($resPerm);
			return $fetchPerm[0];
		}
	}

	// Default return value if no match
	return "none";
}
?>
