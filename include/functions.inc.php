<?php
$VERSION="1.1.3";
$AUTHOR="Daniel Ruus";
$MODIFIED="13/10/2006";
//
// Asset functions
//

//****************************************************************************
// Function        asset_form($db, $asset = 0)
// Argumentsi      $db - database connection
//                 $asset - asset number to open form for, defaults to 0 (blank form)
// Return value    none
// Description     Print out an asset form
// Created by      Daniel Ruus
// Modified        2015-08-05
// Version         1.1
//****************************************************************************
function asset_form($db, $asset = 0, $categories, $license, $status)
{
	// Let's get the user pemissions and authentication values
	$permission = check_permissions($db, $asset);
	$auth = authenticate();
	
	if ( $asset > 0 && $permission == "none" && !( $auth == "SuperUser" || $auth == "Admin" ) ) {
		echo "Not authorized to view this asset!<br/>\n";
		return;
	}

    //$WriteProt = "onFocus='javascript:blur();'";
	// Has the user specified a specific asset number, or do we show a blank form?
	if ($asset == 0)
	{
		$action = "new";
	}
	else
	{
		$action = "edit";
		// Get the details for the specified asset number from the database
		$mySql = "SELECT * FROM asset a WHERE asset = " . $asset . " ";
		$db->setsql($mySql);
		$x = $db->selectquery();
		
		// Check how many rows were returned by the query. If more than 1, something really 
		// odd is going on. If none it mean the asset simply doesn't exist!
		$rows = $db->numberrows;
		
		if ($rows == 0)
		{
			print_error("No information can be found for asset '" . $asset . "'.", "info");
			return FALSE;
		} 
		else if ($rows > 1)
		{
			print_error("INCORRECT NUMBER OF ROWS RETURNED FROM DATABASE!!!<br/>Expected one row, found " . $rows . " rows for asset " . $asset . ".", "warn");
			return FALSE;
		}
		else
		{
			// If we end up here, we should have found exactly one entry for the given asset
			// number in the database. 
			$resAsset = $db->result[0];
		}
	}
?>
<!--**************************************************************************
**
** ASSET FORM
**
***************************************************************************-->
<form name="asset" action="<?= $_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="name" value="Asset">
<?php
if ($action == "new")
{
?>
<input type="hidden" name="cmd" value="CreateNewAsset">
<?php
}
else
{
?>
<input type="hidden" name="cmd" value="UpdateAssetEntry">
<input type="hidden" name="asset" value="<?= $asset?>">
<?php
}
?>

<table width="95%" align="center" border="0" bgcolor="#f3f3f3">
<tr>
    <td colspan="4" align="center" valign="middle"><h2><?php if ($action == "new") echo "NEW ASSET"; else echo "ASSET " . $asset;?></h2></td>
</tr>

<tr>
    <td colspan="4">
    <table width="98%" cellspacing="0" cellpadding="0" border="0">
    <tr>
<?php
if ($permission == "write" || $auth == "SuperUser")
{
?>
      <td bgcolor="#CCCCCC" width="32" align="center">
          <abbr title="New">
          <a href="<?= $_SERVER['PHP_SELF']?>?cmd=new" onMouseOver="document.img_new.src='images/New_Active.gif';" onMouseOut="document.img_new.src='images/New_Inactive.gif';">
          <img src="images/New_Inactive.gif" border="0" name="img_new"></a>
          </abbr>
      </td>

      <td bgcolor="#CCCCCC" width="32" align="center">
          <abbr title="Save">
          <a href="#" onClick="document.forms['asset'].submit();" onMouseOver="document.img_edit.src='images/Edit_Active.gif';" onMouseOut="document.img_edit.src='images/Edit_Inactive.gif';">
          <img src="images/Edit_Inactive.gif" border="0" name="img_edit" /></a>
          </abbr>
      </td>

      <td bgcolor="#CCCCCC" width="32" align="center">
          <abbr title="Duplicate asset">
          <a href="<?= $_SERVER['PHP_SELF']?>?cmd=DuplicateAsset&amp;asset=<?= $resAsset['asset']?>" onClick="return confirmSubmit('Are you sure you want to duplicate this asset?');" onMouseOver="document.img_duplicate.src='images/Duplicate_Active.gif';" onMouseOut="document.img_duplicate.src='images/Duplicate_Inactive.gif';">
          <img src="images/Duplicate_Inactive.gif" border="0" name="img_duplicate" /></a>
          </abbr>
      </td>

      <td bgcolor="#CCCCCC" >
<?php
if ( $auth == "SuperUser" ) {
?>
        &nbsp;<abbr title="Edit permissions"><a href="javascript:EditPermissions(<?= $asset?>);">Permissions</a></abbr>
<?php
}
else {
    echo "&nbsp;";
}
?>
      </td>
<?php
}
else
{
?>
      <td bgcolor="#CCCCCC" width="32" align="center">
        <abbr title="New">
          <img src="images/New_Disabled.gif" />
        </abbr>
      </td>
      <td bgcolor="#CCCCCC" width="32" align="center">
        <abbr title="Save">
          <img src="images/Edit_Disabled.gif" />
        </abbr>
      </td>
      <td bgcolor="#CCCCCC" width="32" align="center">
        <abbr title="Duplicate asset">
          <img src="images/Duplicate_Disabled.gif" />
        </abbr>
      </td>
      <td bgcolor="#CCCCCC" >
          &nbsp;
      </td>

	</td>
<?php
}
?>
    </tr>
    </table>
</tr>

<TR>
	<TD>Asset</TD>
	<TD>
		<INPUT TYPE="text" SIZE="6" NAME="searchstring" CLASS="input-style" VALUE="<?= $resAsset['asset']?>">
		&nbsp;&nbsp;&nbsp;&nbsp;Productcode&nbsp;<input type="text" size="6" name="productcode" class="input-style" value="<?= $resAsset['productcode']?>">
	</TD>

	<TD>Introduction</TD>
	<TD>
		<INPUT TYPE="text" NAME="introduced" CLASS="input-style" VALUE="<?= $resAsset['introduced']?>"  >
	</TD>
</TR>

<tr>
    <td><abbr title="Manufacturer of the product.">Make</abbr></td>
    <td>
        <INPUT TYPE="text" NAME="manufacturer" SIZE="40" CLASS="input-style" VALUE="<?= $resAsset['manufacturer']?>" >
    </td>

	<TD>Model</TD>
	<TD>
		<INPUT TYPE="text" NAME="model" SIZE="40" CLASS="input-style" VALUE="<?= $resAsset['model']?>" >
	</TD>
</TR>

<tr>
    <td><abbr title="Serial number of the asset, if applicable.">Serial No</abbr></td>
    <td>
        <input type="text" name="serial" class="input-style" value="<?= $resAsset['serial']?>" >
    </td>
    <td><abbr title="Each asset should belong to a specified category, indicating roughly what the asset is, eg CD-ROM disk, computer hardware or similar.">Category</abbr></td>
    <td>
<?php
/**
 * test with array of categories
 */
?>
    <select name="category" class="input-style">
<?php
    foreach( $categories as $cat ) {
        if ( $cat['id'] == $resAsset['category'] ) {
            $selected = "selected"; // Pre-select entry in drop down list
        } else {
            $selected = "";
        }
        echo "        <option value=\"" . $cat['id'] . "\" " . $selected . ">" . $cat['category'] . "</option>\n";
    }
?>
    </select>

<?php
/* Disabled block 2015-08-06, trying to use an existing array containing the categories
// Retrieve list of categories
$getCat = "SELECT id, category FROM category WHERE active = 'Yes' ORDER BY category";
$db->setsql($getCat);
$resCat = $db->selectquery();

if (!$resCat)
	echo "No categories found.\n";
else
{
?>
    <select name="category" class="input-style"> 
<?php
	for ($loopy = 0; $loopy < $db->numberrows; $loopy++)
	{
		if ($db->result[$loopy]['id'] == $resAsset['category'])
			$selected = "selected"; // Pre-select entry in drop down list
		else
			$selected = "";
			
		print "      <option value=\"" . $db->result[$loopy]['id'] . "\" " . $selected . ">" . $db->result[$loopy]['category'] . "</option>\n";
	}
*/
//}
?>
    </td>
</tr>

<tr>
	<td>Description</td>
	<td colspan="3">
		<input type="text" name="description" class="input-style" value="<?= $resAsset['description']?>" size="80" >
	</td>

</tr>

<tr>
    <td>Location</td>
    <td>
        <input type="text" name="original_location" size="20" class="input-style" value="<?= $resAsset['original_location']?>" >
        <input type="text" name="network_port" size="6" class="input-style-protected" onFocus='javascript:blur();' value="<?= $resAsset['network_port']?>" >
        <a href="javascript:DisplayFloorLayout(document.asset.original_location.value, document.asset.network_port.value,document.asset.computer_user.value);">[View]</a>
        <a href="javascript:EditFloorLayout();">&nbsp;[Edit]</a>
    </td>

    <td><abbr title="Type of software license where applicable, for example: Multi Volume, OEM, GPL">Software license type</abbr></td>
    <td>
      <select name="license_type" class="input-style">
	<option value="0">Not Selected</option>
<?php
/*
 * Retrieve software licenses
 */
    foreach( $license as $lic ) {
        if ( $lic['id'] == $resAsset['license'] ) {
            $selected = "selected"; // Pre-select entry in drop down list
        } else {
            $selected = "";
        }
        echo "        <option value=\"" . $lic['id'] . "\" " . $selected . ">" . $lic['license'] . "</option>\n";
    }
?>
      </select>
		<!-- <input type="text" name="license_type" size="20" class="input-style" value="" > -->
	</td>
</tr>
<tr>
    <td>Parent computer</td>
    <td>
        <!-- <input type="hidden" name="parent_computer" value="UK-224" > -->
        <input type="text" name="parent_computer" size="20" class="input-style" value="<?= $resAsset['parent_id']?>" >
        <a href="javascript:ViewCompDetails(document.asset.parent_computer.value);">[View]</a>&nbsp;
        <a href="javascript:ListComputers(0);">[List computers]</a>
    </td>

    <td><abbr title="License key (mainly for OEM licensed software)">License key (if applicable)</abbr></td>
	<td>
<?php
if ($permission == "write" || $auth == "Admin" || $auth == "SuperUser")
{
?>
		<input type="text" name="license_key" size="30" class="input-style" value="<?= $resAsset['license_key']?>" >
<?php
}
else
{
?>
        <input type="text" name="license_key" size="30" class="input-style-protected" value="" <?php echo $WriteProt ?>>
<?php
}
?>
	</td>
</tr>

<tr>
    <td><abbr title="Name of the principal user, if applicable">User</abbr></td>
    <td>
        <input type="hidden" name="computer_user" value="<?= $resAsset['computer_user']?>">
<?php
// If a user name is stored with the asset, let's retreive the full name of the user
if (isset($resAsset['computer_user']))
{
	$getUserDetails = "SELECT first_name, surname FROM user WHERE user_name = '" . $resAsset['computer_user'] . "' LIMIT 1";
	$db->setsql($getUserDetails);
	if (! $db->selectquery())
		$user = $resAsset['computer_user'];	// In case we can't retreive details
	else
		$user = $db->result[0]['first_name'] . " " . $db->result[0]['surname'];
}
?>
        <input type="text" name="computer_user_full_name" size="20" class="input-style-protected" value="<?= $user?>" onFocus="blur();">
        <a href="javascript:ListUsers(0);">[List users]</a>
    </td>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><abbr title="Each asset will be in a STATUS level, eg Active, Missing, Disposed">Status</abbr></td>
    <td>
        <select name="status" class="input-style">

<?php 
/*
 * Retrieve status
 */
    foreach( $status as $stat ) {
        if ( $stat['id'] == $resAsset['status'] ) {
            $selected = "selected"; // Pre-select entry in drop down list
        } else {
            $selected = "";
        }
        echo "        <option value=\"" . $stat['id'] . "\" " . $selected . ">" . $stat['status'] . "</option>\n";
    }
?>

        </select>
    </td>

    <td>&nbsp;</td>
    <td>
        <!-- <input type="text" name="status_change" value="<?= $resAsset['status_change']?>" > -->
        &nbsp;
    </td>
</tr>

<tr>
    <td><abbr title="Owner of the asset">Owner</abbr></td>
    <td>
<?php
// Retrieve list of clients/owners
$getClients = "SELECT cid, client FROM clients WHERE active = 'Yes' ORDER BY client";
$db->setsql($getClients);
$resClients = $db->selectquery();

if (!$resClients)
	echo "No clients found.\n";
else
{
?>
    <select name="client" class="input-style"> 
<?php
	for ($loopy = 0; $loopy < $db->numberrows; $loopy++)
	{
		if ($db->result[$loopy]['cid'] == $resAsset['client'])
			$selected = "selected"; // Pre-select entry in drop down list
		else
			$selected = "";
			
		print "      <option value=\"" . $db->result[$loopy]['cid'] . "\" " . $selected . ">" . $db->result[$loopy]['client'] . "</option>\n";
	}

?>
    </select>
<?php
}
?>
  </td>
  <TD>Active</TD>
  <TD>
    <SELECT NAME="active" CLASS="input-style"> 
      <OPTION VALUE="Yes" SELECTED>Yes
      <OPTION VALUE="No">No
    </SELECT>
  </TD>
</TR>

<tr>
  <td><abbr title="Name of the person within AQE who is responsible for the asset.">Contact</abbr></td>
  <td>
<?php
// Retrieve list of users
$getUsers = "SELECT id, first_name, surname FROM user WHERE priv != 'Disabled' ORDER BY first_name";
$db->setsql($getUsers);
$resUser = $db->selectquery();

if (!$resUser)
	echo "No users found.\n";
else
{
?>
    <select name="owner" class="input-style"> 
<?php
	for ($loopy = 0; $loopy < $db->numberrows; $loopy++)
	{
		if ($db->result[$loopy]['id'] == $resAsset['owner_name'])
			$selected = "selected"; // Pre-select entry in drop down list
		else
			$selected = "";
			
		print "      <option value=\"" . $db->result[$loopy]['id'] . "\" " . $selected . ">" . $db->result[$loopy]['first_name'] . " " . $db->result[$loopy]['surname'] . "</option>\n";
	}

?>
    </select>
<?php
}
?>
  </td>

  <td><abbr title="AQ department the asset is assigned to.">Department</abbr></td>
  <td>
<?php
// Retrieve list of departments
$getDep = "SELECT dep_id, dep_name FROM aq_department WHERE active = 'Yes' ORDER BY dep_name";
$db->setsql($getDep);
$resDep = $db->selectquery();

if (!$resDep)
	echo "No departments found.\n";
else
{
?>
    <select name="dep_id" class="input-style"> 
<?php
	for ($loopy = 0; $loopy < $db->numberrows; $loopy++)
	{
		if ($db->result[$loopy]['dep_id'] == $resAsset['owner_dep'])
			$selected = "selected"; // Pre-select entry in drop down list
		else
			$selected = "";
			
		print "      <option value=\"" . $db->result[$loopy]['dep_id'] . "\" " . $selected . ">" . $db->result[$loopy]['dep_name'] . "</option>\n";
	}

?>
    </select>
<?php
}
?>
  </td>
</tr>

<TR>
	<TD>P.O. number</TD>
	<TD>
<?php
// Check for any recorded purchase orders
$getPO = "SELECT COUNT(*) FROM purchase_orders WHERE asset = " . $asset;
$db->setsql($getPO);
if ($permission == "full-read" || $permission == "write" || $auth == "Admin" || $auth == "SuperUser")
{
?>
		<INPUT TYPE="text" NAME="po_number" SIZE="20" CLASS="input-style" value="<?= $resAsset['po_number']?>">
		<a href="javascript:DisplayPurchaseOrder(document.asset.po_number.value);">[View PO items]</a>
<?php
}
else
{
?>
        <INPUT TYPE="text" NAME="po_number" SIZE="20" CLASS="input-style-protected" value="" <?php echo $WriteProt ?>>
<?php
}
?>
	</TD>
	<td><abbr title="The suppliers invoice number">Suppliers invoice</abbr></td>

	<TD>
		<INPUT TYPE="text" NAME="manuf_invoice" CLASS="input-style" VALUE="<?= $resAsset['manuf_invoice']?>">
	</TD>
</TR>
<tr>
	<td><abbr title="The suppliers product code/article number">Product code</abbr></td>
	<td>
<?php
if ($permission == "full-read" || $permission == "write" || $auth == "Admin" || $auth == "SuperUser")
{
?>
		<input type="text" name="product_code" size="40" class="input-style" value="<?= $resAsset['product_code']?>">
<?php
}
else
{
?>
        <input type="text" name="product_code" size="40" class="input-style-protected" value="" <?php echo $WriteProt ?> >
<?php
}
?>
	</td>

	<td><abbr title="Name of the supplier">Supplier</abbr></td>
	<td>
		<input type="text" name="supplier" size="20" class="input-style-protected" value="<?= $resAsset['supplier']?>" onfocus='javascript:blur();'><a href="javascript:ListSuppliers(0);">&nbsp;[List suppliers]</a>
	</td>
</tr>


<!-- Manufacturers product code -->
<tr>
    <td><abbr title="The manufacturers product code/article number">Manufacturers artno</abbr></td>
    <td>
<?php
if ($permission == "full-read" || $permission == "write" || $auth == "Admin" || $auth == "SuperUser")
{
?>
        <input type="text" name="manuf_artno" size="20" class="input-style" value="<?= $resAsset['manuf_artno']?>">
<?php
}
else
{
?>
        <input type="text" name="manuf_artno" size="20" class="input-style-protected" value="" <?php echo $WriteProt ?> >
<?php
}
?>
    </td>
    <td><abbr title="The suppliers article number/product code">Suppliers artno</abbr></td>
    <td>
<?php
if ($permission == "full-read" || $permission == "write" || $auth == "Admin" || $auth == "SuperUser")
{
?>
        <input type="text" name="supplier_artno" size="20" class="input-style" value="<?= $resAsset['supplier_artno']?>">
<?php
}
else
{
?>
        <input type="text" name="supplier_artno" size="20" class="input-style-protected" value="" <?php echo $WriteProt ?> >
<?php
}
?>

    </td>
</tr>

<!-- Barcode -->
<tr>
    <td><abbr title="The manufacturers barcode">Barcode</abbr></td>
    <td>
<?php
if ($permission == "full-read" || $permission == "write" || $auth == "Admin" || $auth == "SuperUser")
{
?>
        <input type="text" name="barcode" size="20" class="input-style" value="<?= $resAsset['barcode']?>">
<?php
}
else
{
?>
        <input type="text" name="barcode" size="20" class="input-style-protected" value="" <?php echo $WriteProt ?> >
<?php
}
?>
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<!-- End barcode -->

<tr>
	<td valign="top">Comments</td>
	<td colspan=3>
		<textarea rows="8" cols="100" name="comment" class="input-style"><?= $resAsset['comment']?></textarea>
	</td>
</tr>
<!-- Display info about the user who created/modified the asset entry -->
<tr>
	<td>Entry created by</td>
	<td>
		<input type="text" name="" size="30" class="input-style-protected" onFocus='javascript:blur();' value="<?= $resAsset['asset_entry_created_by'];?>" >
	</td>

	<td>Modified by</td>
	<td>
		<input type="text" name="" size="30" class="input-style-protected" onFocus='javascript:blur();' value="<?= $resAsset['asset_modified_by'];?>" >
	</td>
</tr>
<tr>
	<td>Creation time</td>
	<td>

		<input type="text" name="" size="22" class="input-style-protected" onFocus='javascript:blur();' value="<?= $resAsset['asset_entry_created'];?>" >
	</td>

	<td>Modification time</td>
	<td>
		<input type="text" name="" size="22" class="input-style-protected" onFocus='javascript:blur();' value="<?= $resAsset['asset_modified'];?>" >
	</td>
</tr>

<!-- List the history of any changes made to the asset. -->
<?php
if (($permission == "full-read" || $permission == "write" || $auth == "Admin" || $auth == "SuperUser") && $action == "edit")
{
?>
<tr>
	<td colspan="4" height="2" bgcolor="#000000"></td>
</tr>
<tr>
	<td colspan="4">Asset modification history:</td>
</tr>
<?php
if (isset($asset) && $asset > 0)
{
	// Get the modification history of the asset, if applicable
	$getHist = "SELECT hid, comment, updated_by, updated_time FROM asset_history WHERE asset=" . $asset . " ORDER BY updated_time 	DESC";
	$db->setsql($getHist);
	$resHist = $db->selectquery();

	$numHistRows = $db->numberrows;
}
else
{
	$numHistRows = 0;
}

if ($numHistRows == 0)
{
?>
<tr>
	<td colspan="4"><i>No previous history exists for this asset.</i></td>
</tr>
<?php
}
else
{
?>
<tr>
	<td colspan="4">
	<select name="asset_history" size="4" class="history_comment" onDblClick="javascript:DisplayHistory(this);">
<?php
if ($numHistRows > 0)
{
	for ($histLoop = 0; $histLoop < $numHistRows; $histLoop++)
	{
	    print "\t\t<option value=\"" . $db->result[$histLoop]['hid'] . "\">";
	    printf("%-'_66s|%-'_8s|%-20s\n", substr($db->result[$histLoop]['comment'], 0, 66), $db->result[$histLoop]['updated_by'], $db->result[$histLoop]['updated_time']);
	}
}
?>
	</select>
	</td>
</tr>
<?php
}
}
?>

<!-------------------------------------------------------------------------
 *
 * Display any existing images
 *
-------------------------------------------------------------------------!>
<?php
if (isset($asset) && $asset > 0)
{
    // Retrieve a list of images if there happen to be any
    $getImg = "SELECT * FROM images WHERE asset = " . $asset;
    echo "<!-- $getImg -->\n";
    $db->setsql($getImg);
    $resImg = $db->selectquery();
    $numImgRows = $db->numberrows;

    if ($numImgRows > 0) {
        echo "<tr>\n    <td colspan=\"4\"><h4>Images</h4>\n</tr>\n";
        for ($imgLoop = 0; $imgLoop < $numImgRows; $imgLoop++) {
?>
  <tr>
    <td colspan="4"><img src="<?php echo $db->result[$imgLoop]['path'] . '/' . $db->result[$imgLoop]['image'];?>" /></td>
  </tr>
<?php
        }
    }
}
?>

</table>
</form>
<br/>

<?php
} // EOF asset_form()

//****************************************************************************
// Function			default_page($db)
// Arguments		$db - database connection
// Return value		none
// Description		Print out the default asset page
// Created by		Daniel Ruus
// Modified			2005-10-25
// Version			0.1
//****************************************************************************
function default_page($db)
{
?>
<form method="post" action="<?= $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="cmd" value="edit">
Enter asset number&nbsp;
<input type="text" name="asset" value="" class="input-style">
<input type="submit" value="Search">
</form>
<?php
} // EOF default()

//****************************************************************************
// Function		search_form($db)
// Arguments		$db - database connection
// Return value		none
// Description		Provide user with a form to enter search criteria
// Created by		Daniel Ruus
// Modified		2005-10-25
// Version		0.1
//****************************************************************************
function search_form($db)
{
	// Allow user to search only via asset number
	default_page($db);
	
	print "<p>And here some other search criterias can be entered...</p>\n";
	
	// Allow searching by category/categories
?>
<form method="POST" action="<?= $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="cmd" value="search_criteria">
<table>
<tr>
	<!-- Search on category -->
	<td>
	Select category<br/>
<?php
// Retreive list of categories, present in a multiple-select list
$selCategory = "SELECT id, category FROM category WHERE active='Yes' ORDER BY category";
$db->setsql($selCategory);
$resCategory = $db->selectquery();

if (!$resCategory)
{
	print_error("Unable to retreive list of categories!<br/>Database error message: " . mysql_error(), "error");
}
else
{
	if ($db->numberrows == 0)
		print "No categories found\n";
	else
	{
?>
		<select name="search_category[]" class="input-style" multiple="true" size="8">
<?php
		for ($loop = 0; $loop < $db->numberrows; $loop++)
			print "\t\t\t<option value=\"" . $db->result[$loop]['id'] . "\">" . $db->result[$loop]['category'] . "</option>\n";
?>

		</select>
		
<?php
	}
}
?>
	</td>
	<!-- Search on department -->
	<td>
	Select department<br/>
<?php
// Retreive list of departments, present in a multiple-select list
$selDepartment = "SELECT dep_id, dep_name FROM aq_department WHERE active='Yes' ORDER BY dep_name";
$db->setsql($selDepartment);
$resDepartment = $db->selectquery();

if (!$resDepartment)
{
	print_error("Unable to retreive list of departments!<br/>Database error message: " . mysql_error(), "error");
}
else
{
	if ($db->numberrows == 0)
		print "No departments found\n";
	else
	{
?>
		<select name="search_department[]" class="input-style" multiple="true" size="8">
<?php
		for ($loop = 0; $loop < $db->numberrows; $loop++)
			print "\t\t\t<option value=\"" . $db->result[$loop]['dep_id'] . "\">" . $db->result[$loop]['dep_name'] . "</option>\n";
?>

		</select>
		
<?php
	}
}
?>
	</td>
	<!-- Search on owner -->
	<td>
	Select asset owner/client<br/>
<?php
// Retreive list of owners (clients), present in a multiple-select list
$selOwner = "SELECT cid, client FROM clients WHERE active='Yes' ORDER BY client";
$db->setsql($selOwner);
$resOwner = $db->selectquery();

if (!$resOwner)
{
	print_error("Unable to retreive list of clients!<br/>Database error message: " . mysql_error(), "error");
}
else
{
	if ($db->numberrows == 0)
		print "No clients found\n";
	else
	{
?>
		<select name="search_client[]" class="input-style" multiple="true" size="8">
<?php
		for ($loop = 0; $loop < $db->numberrows; $loop++)
			print "\t\t\t<option value=\"" . $db->result[$loop]['cid'] . "\">" . $db->result[$loop]['client'] . "</option>\n";
?>

		</select>
		
<?php
	}
}
?>
	</td>

    <!-- Search on manufacturer -->
    <td>
    Select manufacturer
<?php
// Retreive list of owners (clients), present in a multiple-select list
$selManuf= "SELECT DISTINCT(manufacturer) FROM asset ORDER BY manufacturer";
$db->setsql($selManuf);
$resManuf= $db->selectquery();

if (!$resManuf )
{
	print_error("Unable to retreive list of manufacturers!<br/>Database error message: " . mysql_error(), "error");
}
else
{
	if ($db->numberrows == 0)
		print "<br/>No manufacturers found\n";
	else
	{
        echo " (Found " . $db->numberrows . " unique entries)<br/>\n";
?>
		<select name="search_manuf[]" class="input-style" multiple="true" size="8">
<?php
		for ($loop = 0; $loop < $db->numberrows; $loop++)
			print "\t\t\t<option value=\"" . $db->result[$loop]['manufacturer'] . "\">" . $db->result[$loop]['manufacturer'] . "</option>\n";
?>

		</select>
		
<?php
	}
}
?>

    </td>
</tr>
<tr>
	<td colspan="3">
	Enter additional search text:&nbsp;
	<input type="text" name="search_text" class="input-style" size="40">
	</td>
<tr>
	<td colspan="2" align="center"><input type="submit" value="Search"></td>
</tr>
</form>
</table>
<?php
} // EOF search_form()

//****************************************************************************
// Function         search_assets($db, $search_category, $search_department, $search_client, $list_order)
// Arguments        $db - database connection
//                  $searchsting - search criteria used to find an asset
// Return value     none
// Description      Search the asset table
// Created by       Daniel Ruus
// Modified         2006-10-13
// Version          1.2
//****************************************************************************
function search_assets($db, $search_category, $search_department, $search_client, $search_manuf, $search_text, $list_order = "asset")
{
	
    // Let's get the user authentication value
    $auth = authenticate();

	// Let's search for all assets that corresponds to the selected 
	$searchAsset =<<< EOQ
SELECT DISTINCT(a.asset), a.manufacturer, a.model, a.description, c.category, d.dep_name AS department, a.original_location, a.network_port, a.computer_user 
FROM asset a, category c, aq_department d, clients o, asset_history h 
WHERE a.category = c.id 
AND a.owner_dep = d.dep_id 
AND a.client = o.cid 
AND a.asset = h.asset
EOQ;
	
	if (isset($search_category))
		$searchAsset .= " AND c.id IN (" . implode(",", $search_category) . ") ";
		
	if (isset($search_department))
		$searchAsset .= " AND d.dep_id IN (" . implode(",", $search_department) . ") ";
		
	if (isset($search_client))
		$searchAsset .= " AND a.client IN (" . implode(",", $search_client) . ") ";

    if (isset($search_manuf))
        $searchAsset .= " AND a.manufacturer IN ('" . implode("','", $search_manuf) . "') ";

	if (isset($search_text))
	    $searchAsset .= " AND (a.asset LIKE '%$search_text%' OR manufacturer LIKE '%$search_text%' OR model LIKE '%$search_text%' OR serial LIKE '%$search_text%' OR a.description LIKE '%$search_text%' OR a.comment LIKE '%$search_text%' OR computer_user LIKE '%$search_text%' OR parent_id LIKE '%$search_text%' OR h.comment LIKE '%$search_text%') ";
	    
	$searchAsset .= "ORDER BY " . $list_order;
	
	print "<!-- Query: " . $searchAsset . " -->\n";
	
	$db->setsql($searchAsset);
	$searchRes = $db->selectquery();
	
	if (!$searchRes)
		print_error("Unable to execute query:<br/>" . $searchAsset . "<br/><br/>Database error message: " . mysql_error(), "error");
	else
	{
		if ($db->numberrows == 0)
			print_error("No rows found for search criteria", "info");
		else if ($db->numberrows == 1)
		{
			// If only one asset found, go straight to the asset form and load the
			// data from there.
			asset_form($db, $db->result[0]['asset']);
		}
		else
		{
			// Set up two different background colours to make a multi-asset list
			// a bit easier to read!
			$bgCol1 = "#D8D8D8";
			$bgCol2 = "#C9C9C9";
			
			print "<table border=\"0\" width=\"95%\">\n";
			print "<tr>\n\n  <td colspan=\"6\">Found <b>" . $db->numberrows . "</b> entries matching the search criteria.</td>\n</tr>\n";
			
			print "<tr bgcolor=\"#FF0000\">\n";
			print "  <td><b>Asset</b></td>\n";
			print "  <td><b>Manufacturer</b></td>\n";
			print "  <td><b>Model</b></td>\n";
			print "  <td><b>Description</b></td>\n";
			print "  <td><b>Category</b></td>\n";
			print "  <td><b>Department</b></td>\n";
			print "  <td><b>Used by</b></td>\n";
			print "  <td><b>Location</b></td>\n";
			print "</tr>\n";
			
			for ($loop = 0; $loop < $db->numberrows; $loop++)
			{
				if ($loop % 2 == 0)
				    $bgCol = $bgCol1;
				else
				    $bgCol = $bgCol2;
				    
                // Let's get the user pemission for the given asset
                $permission = check_permissions($db, $db->result[$loop]['asset']);

                // Only display certain parts for authorised users
                if ( $permission == "read" || 
                     $permission == "part-read" || 
                     $permission == "full-read" || 
                     $permission == "write" ||
                     $auth == "Admin" ||
                     $auth == "SuperUser" ) 
                {
                    $allow = true;
                }
                else {
                    $allow = false;
                }


				print "<tr bgcolor=\"" . $bgCol . "\" onmouseover=\"javascript:style.backgroundColor='#C1D2EE'\" onmouseout=\"javascript:style.backgroundColor='" . $bgCol . "'\">\n";

                if ( $allow == true ) {
				    print "  <td align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?cmd=edit&amp;asset=" . $db->result[$loop]['asset'] . "\">" . $db->result[$loop]['asset'] . "</a></td>\n";
                }
                else {
                    print "  <td align=\"center\">" . $db->result[$loop]['asset'] . "</td>\n";
                }

				print "  <td nowrap>" . $db->result[$loop]['manufacturer'] . "</td>\n";
				print "  <td nowrap>" . $db->result[$loop]['model'] . "</td>\n";
				print "  <td nowrap>" . substr($db->result[$loop]['description'], 0, 40) . "</td>\n";

                // Only display these for authorised users
                if ( $allow == true ) {
    				print "  <td nowrap>" . $db->result[$loop]['category'] . "</td>\n";
	    			print "  <td nowrap>" . $db->result[$loop]['department'] . "</td>\n";
		    		print "  <td nowrap>" . $db->result[$loop]['computer_user'] . "</td>\n";
			    	print "  <td nowrap>" . $db->result[$loop]['original_location'] . " " . $db->result[$loop]['network_port'] . "</td>\n";
                }
                else {
                    print "  <td nowrap>-</td>\n";
                    print "  <td nowrap>-</td>\n";
                    print "  <td nowrap>-</td>\n";
                    print "  <td nowrap>-</td>\n";
                }

				print "</tr>\n";
			}
			print "<tr>\n\n  <td colspan=\"6\">Found <b>" . $db->numberrows . "</b> entries matching the search criteria.</td>\n</tr>\n";
			print "</table>\n";
		}
	}
} // EOF search_asset()


//****************************************************************************
// Function		create_asset_entry($db, $values)
// Arguments		$db - database connection ID
// 			$values - all information entered in the asset form
// Return value		none
// Description		Add the information for a new asset entry into the DB
// Created by		Daniel Ruus
// Modified		2005-11-22
// Version		0.1
//****************************************************************************
function create_asset_entry($db, $values)
{
	global $ENABLE_COMMENT;
	
	// First of all, ensure certain fields contain any useful data. If not,
	// complain and let the customer go back to the asset form to re-enter
	// what is missing.
	if (isset($values['asset']))
	{
		print_error("Error in function call create_asset_entry().<br/>Error description: No asset number should be supplied, creating NEW entry.", "warn");
	}
	else if ((strlen($values['manufacturer']) < 2) || (strlen($values['model']) < 2) || (strlen($values['description']) < 2))
	{
		print_error("Error in function call create_asset_entry().<br/>Error description: No valid entries in fields Manufacturer, Model and/or Description.", "info");
	}
	else
	{
		// OK, by now we should be able to create a new asset entry for the information
		// provided in the asset form.
		$insert_query = "INSERT INTO asset ";
		$insert_query .= "(manufacturer, model, serial, description, category, original_location, ";
		$insert_query .= "current_location, network_port, computer_user, client, parent_id, active, introduced, reference, ";
		$insert_query .= "status, owner_name, owner_dep, po_number, supplier, manuf_invoice, barcode, product_code, ";
		$insert_query .= "license_type, license_key, ";
		$insert_query .= "comment, asset_entry_created_by, asset_created_host_ip, asset_created_server_ip, asset_created_session_id, ";
		$insert_query .= "asset_entry_created, time_stamp) ";

		$insert_query .= "VALUES(";
		$insert_query .= "'" . $values['manufacturer'] . "', ";
		$insert_query .= "'" . $values['model'] . "', ";
		$insert_query .= "'" . $values['serial'] . "', ";
		$insert_query .= "'" . $values['description'] . "', ";
		$insert_query .= "'" . $values['category'] . "', ";
		$insert_query .= "'" . $values['original_location'] . "', ";
		$insert_query .= "'" . $values['current_location'] . "', ";
		$insert_query .= "'" . $values['network_port'] . "', ";
		$insert_query .= "'" . $values['computer_user'] . "', ";
		$insert_query .= "'" . $values['client'] . "', ";
		$insert_query .= "'" . $values['parent_computer'] . "', ";
		$insert_query .= "'" . $values['active'] . "', ";
		$insert_query .= "'" . $values['introduced'] . "', ";
		$insert_query .= "'" . $values['reference'] . "', ";
		$insert_query .= "'" . $values['status'] . "', ";
		$insert_query .= "'" . $values['owner'] . "', ";
		$insert_query .= "'" . $values['dep_id'] . "', ";
		$insert_query .= "'" . $values['po_number'] . "', ";
		$insert_query .= "'" . $values['supplier'] . "', ";
		$insert_query .= "'" . $values['manuf_invoice'] . "', ";
		$insert_query .= "'" . $values['barcode'] . "', ";
		$insert_query .= "'" . $values['product_code'] . "', ";
		$insert_query .= "'" . $values['license_type'] . "', ";
		$insert_query .= "'" . $values['license_key'] . "', ";
		$insert_query .= "'" . $values['comment'] . "', ";
		$insert_query .= "'" . $_SESSION['username'] . "', ";
    	$insert_query .= "'" . $_SERVER['REMOTE_ADDR'] . "', ";
		$insert_query .= "'" . $_SERVER['SERVER_ADDR'] . "', ";
		$insert_query .= "'" . $_REQUEST['PHPSESSID'] . "', ";
		$insert_query .= "NOW(), ";
		$insert_query .= "NOW() )";
		
		$db->setsql($insert_query);
		$insRes = $db->insertquery();
		
		if (! $insRes)
		    print_error("Error in function call create_asset_entry().<br/>Error description: <b>Unable to insert new asset entry.</b><br/>Database error message: " . mysql_error(), "error");
		else
		{
			// Read back the last inserted asset ID.
			$lastQuery = "SELECT LAST_INSERT_ID(asset) AS asset FROM asset ORDER BY asset DESC LIMIT 1";
			//$lastResult = mysql_query($lastQuery);
			$db->setsql($lastQuery);
			//$lastFetch = mysql_fetch_row($lastResult);
			$lastFetch = $db->selectquery();
			
			if (! $lastFetch)
			    print_error("Error in function call create_asset_entry().<br/>Error description: <b>Unable to retreive last asset ID for newly created asset entry.</b>", "error");
			else
			{
				print_error("New asset " . $db->result[0]['asset'] . " inserted successfully.", "info");
				
				//
				// **** APPEND HISTORY COMMENT ****
				// If the variable ENABLE_COMMENT is set to "yes", automatically insert
				// a record into the asset_history table indicating that this asset
				// has just been created.
				if ($ENABLE_COMMENT == "yes")
				{
					$histInsert = "INSERT INTO asset_history ";
					$histInsert .= "(asset, comment, updated_by, updated_time) ";
					$histInsert .= "VALUES ('" . $db->result[0]['asset'] . "', ";
					$histInsert .= "'Asset created', ";
					$histInsert .= "'" . $_SESSION['username'] . "', NOW())";

					$db->setsql($histInsert);

					if (! $db->selectquery())
					{
						print_error("Unable to insert asset history entry. Non-critical problem, so is ignored.", "info");
					}
					else
					{
						print "<br/>Inserted entry into asset_history using insert statement:<br/>";
						print "<pre>" . $histInsert . "</pre>\n";
					}
				}
	        }
			
			print_error("Successfully inserted new asset ID '" . $db->result[0]['asset'] . "' into the database.<br/>", "info");
			echo "Click <A HREF=\"" . $_SERVER['$PHP_SELF'] . "?cmd=Edit&amp;asset=" . $db->result[0]['asset'] . "\">here</A> to continue...<BR>\n";
		}
	}
} // EOF create_asset_entry()


//****************************************************************************
// Function		update_asset_entry($db, $values)
// Arguments		$db - database connection ID
// 			$values - all information entered in the asset form
// Return value		none
// Description		Update an existing asset entry
// Created by		Daniel Ruus
// Modified		2010-01-05
// Version		0.2
//****************************************************************************
function update_asset_entry($db, $values)
{
	global $ENABLE_COMMENT;
	
	// Create a suitable update statement to let an existing asset entry be amended
	$query = "UPDATE asset ";
	$query .= "SET manufacturer='" . $values['manufacturer'] . "', ";
	$query .= "model='" . $values['model'] . "', ";
	$query .= "serial='" . $values['serial'] . "', ";
	$query .= "description='" . $values['description'] . "', ";
	$query .= "client='" . $values['client'] . "', ";
	$query .= "active='" . $values['active'] . "', ";
	$query .= "comment=\"" . $values['comment'] . "\", ";
	$query .= "owner_name='" . $values['owner'] . "', ";
	$query .= "status='" . $values['status'] . "', ";
	$query .= "status_change='" . $values['status_change'] . "', ";
	$query .= "introduced='" . $values['introduced'] . "', ";
	$query .= "original_location='" . $values['original_location'] . "', ";
	$query .= "current_location='" . $values['current_location'] . "', ";
	$query .= "parent_id='" . $values['parent_computer'] . "', ";
	$query .= "network_port='" . $values['network_port'] . "', ";
	$query .= "computer_user='" . $values['computer_user'] . "', ";
	$query .= "owner_dep='" . $values['dep_id'] . "', ";
	$query .= "license_type='" . $values['license_type'] . "', ";
	$query .= "license_key='" . $values['license_key'] . "', ";
	$query .= "po_number='" . $values['po_number'] . "', ";
	$query .= "supplier='" . $values['supplier'] . "', ";
	$query .= "supplier_artno='" . $values['supplier_artno'] . "', ";
	$query .= "reference='" . $values['reference'] . "', ";
	$query .= "manuf_invoice='" . $values['manuf_invoice'] . "', ";
	$query .= "barcode='" . $values['barcode'] . "', ";
	$query .= "product_code='" . $values['product_code'] . "', ";
	$query .= "category='" . $values['category'] . "', ";
	$query .= "asset_modified_by='" . $_SESSION['username'] . "', ";
	$query .= "asset_modified=NOW(), ";
	$query .= "asset_modified_host_ip='" . $_SERVER['REMOTE_ADDR'] . "', ";
	$query .= "asset_modified_server_ip='" . $_SERVER['SERVER_ADDR'] . "' ";

	$query .= "WHERE asset='" . $values['asset'] . "' ";

	//$query_result = mysql_query($query, $db) or die("Unable to update database using query<BR>:$query.<BR><BR>REASON: " . mysql_error());
	$db->setsql($query);
	if (! $db->insertquery())
	{
		print_error("Error in function update_asset_entry().<br/>Error description: <b>Unable to update asset entry.</b><br/>Database error message: <b>" . mysql_error() . "</b><br/>Using SQL statement: " . $db->getsql(), "error");
	}
	else
	{
		print_error("Asset " . $values['asset']. " updated successfully.");

		//print "<H1><FONT COLOR=\"blue\">Asset " . $values['asset'] . " updated</FONT></H1>\n";
		//print "<BR><A HREF=\"" . $_SERVER['PHP_SELF'] . "?cmd=Edit&asset=" . $values['asset'] . "\">Click to go back</A> or wait for a couple of seconds...\n";

		//
		// **** APPEND HISTORY COMMENT ****
		// If the variable ENABLE_COMMENT is set to "yes", give the user the option
		// of adding a comment for historical purposes to why the asset entry was
		// modified.
		if ($ENABLE_COMMENT == "yes")
		{
			// Present the user with a text input box for entering a reason for
			// updating the asset entry. By clicking Cancel no history comment
			// will be recorded, otherwise if Save is clicked, take the comment
			// and store it into the asset_history table.
?>
		<form action="<?= $_SERVER['PHP_SELF']?>" method="post">
		<input type="hidden" name="cmd" value="InsertComment">
		<input type="hidden" name="name" value="Asset">
		<input type="hidden" name="searchtype" value="asset">
		<input type="hidden" name="searchstring" value="<?= $values['asset']?>">

		<h4>Add comment</h4>
		<p>Specify a reason for updating the details for asset <?= $values['asset']?></p>
		<p>Enter a comment in the below text input box, and click Save to store the
		information. To skip adding a comment, click on Cancel.</p>

		<p>
		<input type="text" name="comment" size="80" maxlength="254"/><br/>
		<input type="submit" value="Save"/>&nbsp;
		<input type="button" value="Cancel" onClick="javascript:location='<?= $_SERVER['PHP_SELF']?>?name=Asset&cmd=Edit&asset=<?= $values['asset']?>'"/>
		</form>

<?php
 		}
 		else
		{
			// Ensure we reload the previous page...
			print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $_SERVER['PHP_SELF'] . "?name=Asset&cmd=Edit&asset=" . $values['asset'] . "\">\n";
		}
	}

} // EOF update_asset_entry()


//****************************************************************************
// Function			update_asset_entry($db, $values)
// Arguments		$db - database connection ID
// 					$values - all information entered in the asset form
// Return value		none
// Description		Update an existing asset entry
// Created by		Daniel Ruus
// Modified			2005-11-22
// Version			0.1
//****************************************************************************
function duplicate_asset($db, $asset)
{
	global $ENABLE_COMMENT;
	
    // First of all, read all the fields from the given asset.
	$select = "SELECT * FROM asset WHERE asset = '" . $asset . "' LIMIT 1";
	//$result = mysql_query($select) or die("Unable to execute query:<br/>" . $select);
	//$rows = mysql_num_rows($result);
	$db->setsql($select);
	
	if (! $db->selectquery())
	{
	    print_error("Error in function duplicate_asset().<br/>Error Description: <b>Unable to retrieve data for asset " . $asset . ".</b><br/>Database error message: <b>" . mysql_error() . "</b>", "error");
	}
	else
	{
		if ($db->numberrows == 0)
		{
			print_error("No matching rows found when searching for asset " . $asset . ".", "info");
		}
		else if ($db->numberrows > 1)
		{
			print_error("Too many rows returned when searching for asset " . $asset . ". Expected one row, found " . $db->numberrows . " rows.", "info");
		}
		else
		{
			// Retrieve the data for the given asset
			//$retreiveData = $db->result[0];
			$retreiveData = $db->result;
			
			// Now create a SQL statement that simply creates a new asset, and provide
			// a new asset numbers for it.

			// Issue an INSERT statement with the given asset number
			$insert_query = "INSERT INTO asset ";
			$insert_query .= "(manufacturer, model, serial, description, category, original_location, ";
			$insert_query .= "current_location, network_port, computer_user, client, parent_id, active, introduced, reference, ";
			$insert_query .= "status, owner_name, owner_dep, po_number, supplier, manuf_invoice, product_code, barcode, ";
			$insert_query .= "comment, asset_entry_created_by, asset_created_host_ip, asset_created_server_ip, asset_created_session_id, ";
			$insert_query .= "asset_entry_created, time_stamp) ";

			$insert_query .= "VALUES(";
			$insert_query .= "'" . $db->result[0]['manufacturer'] . "', ";
			$insert_query .= "'" . $db->result[0]['model'] . "', ";
			$insert_query .= "'" . $db->result[0]['serial'] . "', ";
			$insert_query .= "'" . $db->result[0]['description'] . "', ";
			$insert_query .= "'" . $db->result[0]['category'] . "', ";
			$insert_query .= "'" . $db->result[0]['original_location'] . "', ";
			$insert_query .= "'" . $db->result[0]['current_location'] . "', ";
			$insert_query .= "'" . $db->result[0]['network_port'] . "', ";
			$insert_query .= "'" . $db->result[0]['computer_user'] . "', ";
			$insert_query .= "'" . $db->result[0]['client'] . "', ";
			$insert_query .= "'" . $db->result[0]['parent_computer'] . "', ";
			$insert_query .= "'" . $db->result[0]['active'] . "', ";
			$insert_query .= "'" . $db->result[0]['introduced'] . "', ";
			$insert_query .= "'" . $db->result[0]['reference'] . "', ";
			$insert_query .= "'" . $db->result[0]['status'] . "', ";
			$insert_query .= "'" . $db->result[0]['owner_name'] . "', ";
			$insert_query .= "'" . $db->result[0]['owner_dep'] . "', ";
			$insert_query .= "'" . $db->result[0]['po_number'] . "', ";
			$insert_query .= "'" . $db->result[0]['supplier'] . "', ";
			$insert_query .= "'" . $db->result[0]['manuf_invoice'] . "', ";
			$insert_query .= "'" . $db->result[0]['product_code'] . "', ";
			$insert_query .= "'" . $db->result[0]['barcode'] . "', ";
			$insert_query .= "'" . $db->result[0]['comment'] . "', ";
			$insert_query .= "'" . $_SESSION['username'] . "', ";
			$insert_query .= "'" . $_SERVER['REMOTE_ADDR'] . "', ";
			$insert_query .= "'" . $_SERVER['SERVER_ADDR'] . "', ";
			$insert_query .= "'" . $_REQUEST['PHPSESSID'] . "', ";
			$insert_query .= "NOW(), ";
			$insert_query .= "NOW() )";

			$db->setsql($insert_query);

			//print $insert_query; die();
			if (! $db->insertquery())
			{
				print_error("Error in function duplicate_asset(). <br/>Error Description: <b>Unable to duplicate asset ID " . $asset . ".</b><br/>Database error message: " . mysql_error(), "error");
			}
			else
			{
				print_error("Successfully duplicated asset " . $asset . ".", "info");

				// Read back the last inserted asset ID.
				$lastQuery = "SELECT LAST_INSERT_ID(asset) AS asset FROM asset ORDER BY asset DESC LIMIT 1";
				$db->setsql($lastQuery);

				if (! $db->selectquery())
			    	print_error("Error in function duplicate_asset().<br/>Error Description: <b>Unable to retrieve last created asset number.</b><br/>Database error message: " . mysql_error(), "warn");
				else
				{
					// We should have been able to retreive an asset number here....
					$newasset = $db->result[0]['asset'];
					echo "Successfully inserted new asset ID '" . $newasset . "' into the database.<br/>\n";
					echo "Click <A HREF=\"" . $_SERVER['$PHP_SELF'] . "?cmd=Edit&amp;asset=" . $newasset . "\">here</A> to continue...<BR>\n";


					//
					// **** APPEND HISTORY COMMENT ****
					// If the variable ENABLE_COMMENT is set to 1, automatically insert
					// a record into the asset_history table indicating that this asset
					// was created by duplicating an existing one.
					if ($ENABLE_COMMENT == "yes")
					{
						$histInsert = "INSERT INTO asset_history ";
						$histInsert .= "(asset, comment, updated_by, updated_time) ";
						$histInsert .= "VALUES ('" . $newasset . "', ";
						$histInsert .= "'Asset created by duplicating asset " . $asset . "', ";
						$histInsert .= "'" . $_SESSION['username'] . "', NOW())";

						$db->setsql($histInsert);
						$db->insertquery();

						print "<br/>Inserted entry into asset_history using insert statement:<br/>";
						print "<pre>" . $histInsert . "</pre>\n";
					}
					else
					{
						print "<br/>No entry inserted into asset_history!!!<br/>";
	  				}

					// Try and automatically reload the main asset page
					print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"10;url=" . $_SERVER['PHP_SELF'] . "?name=Asset&cmd=Edit&asset=" . $newasset . "\">\n";
				}

			}
		}
	}
} // EOF duplicate_asset()

//****************************************************************************
// Function			update_asset_entry($db, $values)
// Arguments		$db - database connection ID
// 					$values - all information entered in the asset form
// Return value		none
// Description		Update an existing asset entry
// Created by		Daniel Ruus
// Modified			2005-11-22
// Version			0.1
//****************************************************************************
function insert_history_comment($db, $values)
{
    // Only insert anything if the comment field is not empty!
	if (isset($values['comment']))
	{
		// Create an insert statement and load the comment into the history table
		$insComment = "INSERT INTO asset_history ";
		$insComment .= "(asset, comment, updated_by, updated_time) ";
		$insComment .= "VALUES (";
		$insComment .= "'" . $values['searchstring'] . "', ";
		$insComment .= "'" . $values['comment'] . "', ";
		$insComment .= "'" . $_SESSION['username']. "', NOW())";

		$db->setsql($insComment);
		if (! $db->insertquery())
		{
			print_error("Error in funcion insert_history_comment().<br/>Error Description: <b>Unable to insert a comment for the modification of an asset.</b><br/>Database error message: " . mysql_error() . "<br/>SQL statement used: <b>" . $db->getsql() . "</b>", "warn");
		}
		else
		{
			print_error("Successfully inserted comment into the database.", "info");

			// Reload the screen to the main asset page
			// Ensure we reload the previous page...
			print "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=" . $_SERVER['PHP_SELF'] . "?name=Asset&cmd=Edit&asset=" . $values['searchstring'] . "\">\n";
		}
	}
	
} // EOF insert_history_comment()

?>
