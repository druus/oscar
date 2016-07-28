<?php
#**************************************************************
# $Header: /home/CVS/sony/drive_data/auth.php,v 1.1.1.1 2003/02/12 11:23:59 web Exp $
#
# $Author: web $
# $Date: 2003/02/12 11:23:59 $
# Description 	Authentication code
# History
# ------------------------------------------------------------
# $Log: auth.php,v $
# Revision 1.1.1.1  2003/02/12 11:23:59  web
# Initial release
#
#**************************************************************

include_once("config/admin.conf.php");

$auth = false; // Assume user is not authenticated

$user = $_SERVER['PHP_AUTH_USER'];
$pwd = $_SERVER['PHP_AUTH_PW'];
//$user = 'druus';
//$pwd = 'an1974';

if (isset( $user ) && isset( $pwd )) {
// $DBSERVER, $DBNAME, $DBUSER, $DBPASSWD
    // Connect to MySQL
    $db = new mysqli( $DBSERVER, $DBUSER, $DBPASSWD, $DBNAME );

    if ( mysqli_connect_error() ) {
        die( 'Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() );
    }

    // Formulate the query
    $hashedPassword = hash('sha256', $pwd);
    $sql = "SELECT * FROM users WHERE
            username = '$user' AND password = '" . $hashedPassword . "'";

    // Execute the query and put results in $result
    $result = $db->query( $sql );

    // Get number of rows in $result.
    $num = $result->num_rows;

    //$mysqli_close();

    if ( $num != 0 ) {

        // A matching row was found - the user is authenticated.

        $auth = true;

		// Store the user name as a session variable
        //$loggeduser = $username;
        $_SESSION['username'] = $user;
        $_SESSION['password'] = $pwd;
    }

}

if ( ! $auth ) {

	header('Cache-Control: no-cache');
	Header('Pragma: no-cache');
	Header('Expires: Sat, Jan 01 2000 01:01:01 GMT');
	header('WWW-Authenticate: Basic realm=AQ Intranet');
	header('HTTP/1.0 401 Unauthorized');
	echo "<H2>Authorization Required.</H2>\n";
	echo "<BR><BR>Click <A HREF=\"javascript:history.back(-1)\">here</A> to go back.\n";
	exit;

}

?>
