
<form method="post" action="">
<input type="hidden" name="cmd" value="asset">
Enter asset number&nbsp;
<input type="text" name="asset" value="" class="input-style">
<input type="submit" value="Search">
</form>
<p>And here some other search criterias can be entered...</p>
<form method="post" action="">
<input type="hidden" name="cmd" value="search_criteria">
<table>
<tr>
  <!-- Search on category -->
  <td>
    Select category<br/>
    <select name="search_category[]" class="input-style" multiple="true" size="8">
    {% for category in categories %}
      <option value="{{ category.id }}">{{ category.category }}</option>
    {% endfor %}
    </select>
		
  </td>

  <!-- Search on department -->
  <td>
    Select department<br/>
    <select name="search_department[]" class="input-style" multiple="true" size="8">
    {% for department in departments %}
     <option value="{{ department.id }}">{{ department.department }}</option>
    {% endfor %}
    </select>
		
  </td>

  <!-- Search on owner -->
  <td>
  Select asset owner/client<br/>
    <select name="search_client[]" class="input-style" multiple="true" size="8">
			<option value="12">Annette McCarthy</option>
			<option value="10">Daniel Ruus</option>

    </select>
		
  </td>

  <!-- Search on manufacturer -->
  <td>
    Select manufacturer
 (Found 41 unique entries)<br/>
    <select name="search_manuf[]" class="input-style" multiple="true" size="8">
    {% for manufacturer in manufacturers %}
      <option value="{{ manufacturer.manufacturer }}">{{ manufacturer.manufacturer }}</option>
    {% endfor %}
    </select>
		

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

<h3>List existing assets</h3>
<table>
<tr>
  <td>Asset</td>
  <td>Manufacturer</td>
  <td>Model</td>
</tr>

{% for asset in assetList %}
<tr>
  <td><a href="index.php?cmd=asset&amp;asset={{ asset.asset }}">{{ asset.asset }}</a></td>
  <td>{{ asset.manufacturer }}</td>
  <td>{{ asset.model }}</td>
</tr>
{% endfor %}

</div>

