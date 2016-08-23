<?php

include_once('classes/Authenticate.class.php');

$auth = false; // Assume user is not authenticated

$user = $_SERVER['PHP_AUTH_USER'];
$pwd = $_SERVER['PHP_AUTH_PW'];

if (isset( $user ) && isset( $pwd )) {

  $login = new Authenticate();
  $username = $login->login( $user, $pwd );

  if ( $username !== false ) {
    $auth = true;
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
