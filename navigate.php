<?php
//session_start();

// Include some useful functions
include_once("include/authenticate.inc.php");
#include_once("config/asset.conf.php");

# Create a connection to the database
$db = new mysqli($DBSERVER, $DBUSER, $DBPASSWD, $DBNAME);

/* check connection */
if ($db->connect_errno) {
    printf("Connect failed: %s\n", $db->connect_error);
    exit();
}


$cmd = $values['cmd'];

// Check to see if the user is logged in with administrative rights.
$priv = authenticate();

if ($priv != FALSE)
{
	//$Authorised = true;
	switch ($priv)
	{
		case "Admin":
		case "SuperUser":
			$Authorised = true;
			break;
		default:
		    unset($Authorised);
	}
}
else
	unset($Authorised);

?>

<div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li <?php if ($cmd == "" || !isset($cmd)) echo "class=\"active\"";?>><a href="index.php">Home<span class="sr-only">(current)</span></a></li>
            <li <?php if ($cmd == "new") echo "class=\"active\"";?>><a href="index.php?cmd=new">New Asset</a></li>
            <li><a href="javascript:DisplayFloorLayout(0,0,0)">View Floor Plan</a></li>
            <li <?php if ($cmd == "search" || $cmd == "search_criteria" || $cmd == "edit") echo "class=\"active\"";?>><a href="index.php?cmd=search">Search</a></li>
          </ul>

<?php
if ($priv != FALSE)
{
?>
        <ul class="nav nav-sidebar">
          <li><a href="user_settings.php?cmd=chngpwd">Change password</a></li>
        </ul>
<?php
}
?>
<?php
if ($priv == "Admin" || $priv == "SuperUser")
{
?>

        <ul class="nav nav-sidebar">
            <li><a href="categoryadm.php">Categories</a></li>
            <li><a href="clientadm.php">Clients</a></li>
            <li><a href="departmentadm.php">Departments</a></li>
            <li><a href="sw_licenseadm.php">Software Licenses</a></li>
            <li><a href="statusadm.php">Status Levels</a></li>
            <li><a href="supplieradm.php">Suppliers</a></li>
            <li><a href="index.php?cmd=invoices">Invoices</a></li>
            <li><a href="useradm.php">Users</a></li>
        </ul>

<?php
}
?>
        <ul class="nav nav-sidebar">
            <li><a href="javascript:about();">About...</a></li>
        </ul>

<?php
if ($priv != FALSE)
{
?>

        <ul class="nav nav-sidebar">
            <li><a href="login.php?cmd=logout">Logout</a></li>
        </ul>
<?php
}
else
{
?>

        <ul class="nav nav-sidebar">
            <li><a href="login.php">Login</a></li>
        </ul>
<?php
}
?>

    </div>

