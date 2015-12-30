{#
 # File: asset.tpl
 # Desc: Main form for assets
#}

{% include 'header.tpl' %}

{% include 'navbar_top.tpl' %}
{% include 'navbar_left.tpl' %}

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">



<!--**************************************************************************
**
** ASSET FORM
**
***************************************************************************-->
<form name="asset" action="" method="post">
<input type="hidden" name="name" value="Asset">
<input type="hidden" name="cmd" value="CreateNewAsset">

<table width="95%" align="center" border="0" bgcolor="#f3f3f3">
<tr>
    <td colspan="4" align="center" valign="middle"><h2>{{ formTitle }}</h2></td>
</tr>

<tr>
    <td colspan="4">
    <table width="98%" cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td bgcolor="#CCCCCC" width="32" align="center">
          <abbr title="New">
          <a href="/~daniel/oscar_v3/index.php?cmd=new" onMouseOver="document.img_new.src='images/New_Active.gif';" onMouseOut="document.img_new.src='images/New_Inactive.gif';">
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
          <a href="/~daniel/oscar_v3/index.php?cmd=DuplicateAsset&amp;asset=" onClick="return confirmSubmit('Are you sure you want to duplicate this asset?');" onMouseOver="document.img_duplicate.src='images/Duplicate_Active.gif';" onMouseOut="document.img_duplicate.src='images/Duplicate_Inactive.gif';">
          <img src="images/Duplicate_Inactive.gif" border="0" name="img_duplicate" /></a>
          </abbr>
      </td>

      <td bgcolor="#CCCCCC" >
        &nbsp;<abbr title="Edit permissions"><a href="javascript:EditPermissions(0);">Permissions</a></abbr>
      </td>
    </tr>
    </table>
</tr>

<TR>
	<TD>Asset</TD>
	<TD>
		<INPUT TYPE="text" SIZE="6" NAME="searchstring" CLASS="input-style" value="{{ asset.asset }}">
		<!-- &nbsp;&nbsp;&nbsp;&nbsp;Productcode&nbsp;<input type="text" size="6" name="productcode" class="input-style" value=""> -->
	</TD>

	<td>Introduction</td>
	<td>
		<input type="text" name="introduced" class="input-style" value="{{ asset.introduced }}"  >
	</td>
</TR>

<tr>
    <td><abbr title="Manufacturer of the product.">Make</abbr></td>
    <td>
        <INPUT TYPE="text" NAME="manufacturer" SIZE="40" CLASS="input-style" VALUE="{{ asset.manufacturer }}" >
    </td>

	<TD>Model</TD>
	<TD>
		<INPUT TYPE="text" NAME="model" SIZE="40" CLASS="input-style" VALUE="{{ asset.model }}" >
	</TD>
</TR>

<tr>
    <td><abbr title="Serial number of the asset, if applicable.">Serial No</abbr></td>
    <td>
        <input type="text" name="serial" class="input-style" value="{{ asset.serial }}" >
    </td>
    <td><abbr title="Each asset should belong to a specified category, indicating roughly what the asset is, eg CD-ROM disk, computer hardware or similar.">Category</abbr></td>
    <td>
      <select name="category" class="input-style">
      <option value="0">No category</option>
      {% for category in categoryList %}
      <option value="{{ category.id }}" {{ category.id == asset.category  ? "selected" : "" }}>{{ category.category }}</option>
      {% endfor %}
      </select>

    </td>
</tr>

<tr>
	<td>Description</td>
	<td colspan="3">
		<input type="text" name="description" class="input-style" value="{{ asset.description }}" size="80" >
	</td>

</tr>

<!-- Disabled 2015-12-29 / DR 
<tr>
    <td>Location</td>
    <td>
        <input type="text" name="original_location" size="20" class="input-style" value="" >
        <input type="text" name="network_port" size="6" class="input-style-protected" onFocus='javascript:blur();' value="" >
        <a href="javascript:DisplayFloorLayout(document.asset.original_location.value, document.asset.network_port.value,document.asset.computer_user.value);">[View]</a>
        <a href="javascript:EditFloorLayout();">&nbsp;[Edit]</a>
    </td>

    <td><abbr title="Type of software license where applicable, for example: Multi Volume, OEM, GPL">Software license type</abbr></td>
    <td>
      <select name="license_type" class="input-style">
	<option value="0">Not Selected</option>
        <option value="10" >Commercial</option>
        <option value="2" >GNU / General Public License</option>
        <option value="6" >Mac OS X</option>
        <option value="5" >Microsoft OSL</option>
        <option value="3" >Open Source License v2.1</option>
        <option value="8" >Windows Vista Business</option>
        <option value="7" >Windows Vista HP OEM</option>
        <option value="9" >Windows Vista Ultimate</option>
        <option value="1" >Windows XP EULA</option>
        <option value="4" >Windows XP OEM</option>
      </select>
	</td>
</tr>
<tr>
    <td>Parent computer</td>
    <td>
        <input type="text" name="parent_computer" size="20" class="input-style" value="" >
        <a href="javascript:ViewCompDetails(document.asset.parent_computer.value);">[View]</a>&nbsp;
        <a href="javascript:ListComputers(0);">[List computers]</a>
    </td>

    <td><abbr title="License key (mainly for OEM licensed software)">License key (if applicable)</abbr></td>
	<td>
		<input type="text" name="license_key" size="30" class="input-style" value="" >
	</td>
</tr>
-->

<tr>
    <td><abbr title="Name of the principal user, if applicable">User</abbr></td>
    <td>
        <input type="hidden" name="computer_user" value="">
        <input type="text" name="computer_user_full_name" size="20" class="input-style-protected" value="" onFocus="blur();">
        <a href="javascript:ListUsers(0);">[List users]</a>
    </td>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><abbr title="Each asset will be in a STATUS level, eg Active, Missing, Disposed">Status</abbr></td>
    <td>
        <select name="status" class="input-style">
        {% for status in statusList %}
        <option value="{{ status.id }}" {% if asset.status == status.id %} {{"selected"}} {% endif %}>{{ status.status }}</option>
        {% endfor %}
        </select>
    </td>

    <td>&nbsp;</td>
    <td>
        <!-- <input type="text" name="status_change" value="" > -->
        &nbsp;
    </td>
</tr>

<tr>
    <td><abbr title="Owner of the asset (such as the company director or head of department)">Owner</abbr></td>
    <td>
    <select name="client" class="input-style"> 
      <option value="12" >Annette McCarthy</option>
      <option value="10" >Daniel Ruus</option>
    </select>
  </td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
<!-- Disabled 2015-12-29 / DR
  <TD>Active</TD>
  <TD>
    <SELECT NAME="active" CLASS="input-style"> 
      <OPTION VALUE="Yes" SELECTED>Yes
      <OPTION VALUE="No">No
    </SELECT>
  </TD>
-->
</tr>

<tr>
  <td><abbr title="Name of the person who is responsible for the asset.">Contact</abbr></td>
  <td>
    <select name="owner" class="input-style"> 
    {% for user in userList %}
    <option value="{{ user.id }}">{{ user.name }}</option>
    {% endfor %}
    </select>
  </td>

  <td><abbr title="Department the asset is assigned to.">Department</abbr></td>
  <td>
    <select name="dep_id" class="input-style"> 
      <option value="6" >Home</option>
      <option value="7" >Home Office</option>
      <option value="1" >IS</option>
    </select>
  </td>
</tr>

<!-- Disabled 2015-12-30 / DR
<tr>
	<td><abbr title="If a purchase order exists for the asset, enter the number here.">P.O. number</abbr></td>
	<td>
		<input type="text" name="po_number" size="20" class="input-style" value="">
		<a href="javascript:DisplayPurchaseOrder(document.asset.po_number.value);">[View PO items]</a>
	</td>
	<td><abbr title="The suppliers invoice number">Suppliers invoice</abbr></td>

	<td>
		<input type="text" name="manuf_invoice" class="input-style" value="">
	</td>
</tr>
-->
  
<tr>
	<td><abbr title="The suppliers product code/article number">Product code</abbr></td>
	<td>
		<input type="text" name="product_code" size="40" class="input-style" value="">
	</td>

	<td><abbr title="Name of the supplier">Supplier</abbr></td>
	<td>
		<input type="text" name="supplier" size="20" class="input-style-protected" value="" onfocus='javascript:blur();'><a href="javascript:ListSuppliers(0);">&nbsp;[List suppliers]</a>
	</td>
</tr>

<!-- Manufacturers product code -->
<tr>
    <td><abbr title="The manufacturers product code/article number">Manufacturers artno</abbr></td>
    <td>
        <input type="text" name="manuf_artno" size="20" class="input-style" value="{{ asset.manuf_artno }}">
    </td>
    <td><abbr title="The suppliers product code/article number">Suppliers artno</abbr></td>
    <td>
        <input type="text" name="supplier_artno" size="20" class="input-style" value="{{ asset.supplier_artno }}">

    </td>
</tr>

<!-- Barcode -->
<!-- Disabled 2015-12-30 / DR
<tr>
    <td><abbr title="The manufacturers barcode">Barcode</abbr></td>
    <td>
        <input type="text" name="barcode" size="20" class="input-style" value="">
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
-->
<!-- End barcode -->

<tr>
	<td valign="top">Comments</td>
	<td colspan=3>
		<textarea rows="8" cols="100" name="comment" class="input-style">{{ asset.comment }}</textarea>
	</td>
</tr>
<!-- Display info about the user who created/modified the asset entry -->
<tr>
	<td>Entry created by</td>
	<td>
		<input type="text" name="" size="30" class="input-style-protected" onFocus='javascript:blur();' value="" >
	</td>

	<td>Modified by</td>
	<td>
		<input type="text" name="" size="30" class="input-style-protected" onFocus='javascript:blur();' value="" >
	</td>
</tr>
<tr>
	<td>Creation time</td>
	<td>

		<input type="text" name="" size="22" class="input-style-protected" onFocus='javascript:blur();' value="{{ asset.asset_entry_created }}" >
	</td>

	<td>Modification time</td>
	<td>
		<input type="text" name="" size="22" class="input-style-protected" onFocus='javascript:blur();' value="{{ asset.asset_modified }}" >
	</td>
</tr>

<!-- List the history of any changes made to the asset. -->

<!-------------------------------------------------------------------------
 *
 * Display any existing images
 *
-------------------------------------------------------------------------!>

</table>
</form>

<!------------------------------------------------------------------------
 *
 * End of asset form
 *
------------------------------------------------------------------------->

{% include 'footer.tpl' %}

