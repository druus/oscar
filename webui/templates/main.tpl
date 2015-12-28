{% include 'header.tpl' %}

{% include 'navbar_top.tpl' %}
{% include 'navbar_left.tpl' %}

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

<form method="post" action="/~daniel/oscar_v3/index.php">
<input type="hidden" name="cmd" value="edit">
Enter asset number&nbsp;
<input type="text" name="asset" value="" class="input-style">
<input type="submit" value="Search">
</form>

</div>

{% include 'footer.tpl' %}

