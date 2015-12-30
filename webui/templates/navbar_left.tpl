
<div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li {{ cfgData.menuHighlight == "home" ? "class='active'" : "" }}><a href="index.php">Home<span class="sr-only">(current)</span></a></li>
            <li {{ cfgData.menuHighlight == "new_asset" ? "class='active'" : "" }}><a href="?cmd=new">New Asset</a></li>
          </ul>

<!-- Disabled as the feautre does not yet exist / 2015-12-29/DR
        <ul class="nav nav-sidebar">
          <li><a href="user_settings.php?cmd=chngpwd">Change password</a></li>
        </ul>
-->

{# Make the below block visible for administrators #}
{% if cfgData.userRole == "Admin" %}
<!-- Disabled as the features does not yet exist / 2015-12-29/DR
        <ul class="nav nav-sidebar">
            <li><a href="?cmd=admin&amp;module=categories">Categories</a></li>
            <li><a href="?cmd=admin&amp;module=clients">Clients</a></li>
            <li><a href="?cmd=admin&amp;module=departments">Departments</a></li>
            <li><a href="?cmd=admin&amp;module=licenses">Software Licenses</a></li>
            <li><a href="?cmd=admin&amp;module=status">Status Levels</a></li>
            <li><a href="?cmd=admin&amp;module=suppliers">Suppliers</a></li>
            <li><a href="?cmd=admin&amp;module=invoices">Invoices</a></li>
            <li><a href="?cmd=admin&amp;module=users">Users</a></li>
        </ul>
{% endif %}
-->
        <ul class="nav nav-sidebar">
            <li><a href="javascript:about();">About...</a></li>
        </ul>

{% if cfgData.userRole == false %}
    <ul class="nav nav-sidebar">
        <li><a href="index.php?cmd=login">Login</a></li>
    </ul>
{% else %}
        <ul class="nav nav-sidebar">
            <li><a href="index.php?cmd=logout">Logout</a></li>
        </ul>
{% endif %}
</div>

