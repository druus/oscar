{% include 'header.tmpl' %}

{% include 'navbar_top.tmpl' %}
{% include 'navbar_left.tmpl' %}

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">



<!-- Query: SELECT DISTINCT(a.asset), a.manufacturer, a.model, a.description, c.category, d.dep_name AS department, a.original_location, a.network_port, a.computer_user 
FROM asset a, category c, aq_department d, clients o, asset_history h 
WHERE a.category = c.id 
AND a.owner_dep = d.dep_id 
AND a.client = o.cid 
AND a.asset = h.asset AND a.client IN (10)  AND (a.asset LIKE '%%' OR manufacturer LIKE '%%' OR model LIKE '%%' OR serial LIKE '%%' OR a.description LIKE '%%' OR a.comment LIKE '%%' OR computer_user LIKE '%%' OR parent_id LIKE '%%' OR h.comment LIKE '%%') ORDER BY asset -->
<table border="0" width="95%">
<tr>

  <td colspan="6">Found <b>50</b> entries matching the search criteria.</td>
</tr>
<tr bgcolor="#FF0000">
  <td><b>Asset</b></td>
  <td><b>Manufacturer</b></td>
  <td><b>Model</b></td>
  <td><b>Description</b></td>
  <td><b>Category</b></td>
  <td><b>Department</b></td>
  <td><b>Used by</b></td>
  <td><b>Location</b></td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=455">455</a></td>
  <td nowrap>Atmel</td>
  <td nowrap>AT90USBKey</td>
  <td nowrap>AT90USBKey development board</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=456">456</a></td>
  <td nowrap>Atmel</td>
  <td nowrap>AVRISP mk II</td>
  <td nowrap>USB programmer for Atmel AVR devices</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=457">457</a></td>
  <td nowrap>QNAP</td>
  <td nowrap>TS-439 Pro II+</td>
  <td nowrap>NAS - Networked Attached Storage</td>
  <td nowrap>Storage</td>
  <td nowrap>Home Office</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=458">458</a></td>
  <td nowrap>Western Digital</td>
  <td nowrap>WD10EALX</td>
  <td nowrap>Hard Drive 1TB</td>
  <td nowrap>Storage</td>
  <td nowrap>IS</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=459">459</a></td>
  <td nowrap>Datalogic</td>
  <td nowrap>Gryphon D230</td>
  <td nowrap>Barcode scanner USB</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=460">460</a></td>
  <td nowrap>Raspberry Pi Foundation</td>
  <td nowrap>Model B</td>
  <td nowrap>Raspberry  Pi Model B SBC</td>
  <td nowrap>Computer</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=461">461</a></td>
  <td nowrap>SanDisk</td>
  <td nowrap>SDCZ36-O16G-B35</td>
  <td nowrap>SanDisk Cruzer 16GB USB2.0 memory stick</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=462">462</a></td>
  <td nowrap>Clas Ohlson</td>
  <td nowrap>RJ45 coupler</td>
  <td nowrap>RJ45 network coupler female to female, U</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=463">463</a></td>
  <td nowrap>Hewlett-Packard</td>
  <td nowrap>ML350 G5</td>
  <td nowrap>HP ML350 G5 server, 146GB HDD SAS, 6GB R</td>
  <td nowrap>Server</td>
  <td nowrap>Home Office</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=464">464</a></td>
  <td nowrap>Dymo</td>
  <td nowrap>LabelManager 120P</td>
  <td nowrap>Labelprinter Dymo LabelManager 120P</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=465">465</a></td>
  <td nowrap>Hewlett-Packard</td>
  <td nowrap>dv7</td>
  <td nowrap>Our laptop</td>
  <td nowrap>Laptop</td>
  <td nowrap>IS</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=466">466</a></td>
  <td nowrap>Hewlett-Packard</td>
  <td nowrap>LaserJet 1200</td>
  <td nowrap>HP LaserJet 1200 laserprinter</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=467">467</a></td>
  <td nowrap>Hewlett-Packard</td>
  <td nowrap>6730b</td>
  <td nowrap>HP 6730b laptop</td>
  <td nowrap>Laptop</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=468">468</a></td>
  <td nowrap>Unknown</td>
  <td nowrap>Unknown</td>
  <td nowrap>Tool to form component legs</td>
  <td nowrap>Not applicable</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=470">470</a></td>
  <td nowrap>D-Link</td>
  <td nowrap>DIR-825</td>
  <td nowrap>Broadband router wireless N quadband</td>
  <td nowrap>Network</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=476">476</a></td>
  <td nowrap>Guan Mau</td>
  <td nowrap>ZH2528-B</td>
  <td nowrap>External enclosure 2.inch USB 3.0 SATA</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=477">477</a></td>
  <td nowrap>Universal</td>
  <td nowrap>DVD</td>
  <td nowrap>Public Enemies</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=478">478</a></td>
  <td nowrap>Colombia Pictures</td>
  <td nowrap>DVD</td>
  <td nowrap>Men in Black</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=479">479</a></td>
  <td nowrap>Svensk Filmindustri</td>
  <td nowrap>DVD</td>
  <td nowrap>Jönssonligan & Dynamit Harry</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=480">480</a></td>
  <td nowrap>Paramount</td>
  <td nowrap>DVD</td>
  <td nowrap>Blades of Glory</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=481">481</a></td>
  <td nowrap>Warner Bros</td>
  <td nowrap>DVD</td>
  <td nowrap>A Clockwork Orange</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=482">482</a></td>
  <td nowrap>BBC</td>
  <td nowrap>DVD</td>
  <td nowrap>Anne Frank - A story of the diary</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=483">483</a></td>
  <td nowrap>BBC</td>
  <td nowrap>DVD</td>
  <td nowrap>Top Gear - The Challenges</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=484">484</a></td>
  <td nowrap>HBO</td>
  <td nowrap>DVD</td>
  <td nowrap>Sex and the City - The complete series</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=485">485</a></td>
  <td nowrap>Fox</td>
  <td nowrap>DVD</td>
  <td nowrap>The Simpsons Movie</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=486">486</a></td>
  <td nowrap>United Artists</td>
  <td nowrap>DVD</td>
  <td nowrap>Valkyria</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=487">487</a></td>
  <td nowrap>Sky Pictures</td>
  <td nowrap>DVD</td>
  <td nowrap>Saving Grace</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=488">488</a></td>
  <td nowrap>BBC</td>
  <td nowrap>DVD</td>
  <td nowrap>Young James Herriot</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=489">489</a></td>
  <td nowrap>United Artists</td>
  <td nowrap>DVD</td>
  <td nowrap>Blake Edwards The pink Panther film coll</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=490">490</a></td>
  <td nowrap>Warner Bros</td>
  <td nowrap>DVD</td>
  <td nowrap>The Whole Nine Yards</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=491">491</a></td>
  <td nowrap>Fox</td>
  <td nowrap>DVD</td>
  <td nowrap>The Thin Red Line</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=492">492</a></td>
  <td nowrap>HBO</td>
  <td nowrap>DVD</td>
  <td nowrap>Sex and the City - The Movie</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=493">493</a></td>
  <td nowrap>HBO</td>
  <td nowrap>DVD</td>
  <td nowrap>Sex and the City 2</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=494">494</a></td>
  <td nowrap>Universal</td>
  <td nowrap>DVD</td>
  <td nowrap>The Break Up</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=495">495</a></td>
  <td nowrap>MGM</td>
  <td nowrap>DVD</td>
  <td nowrap>Casino Royale</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=496">496</a></td>
  <td nowrap>Noble Entertainment</td>
  <td nowrap>DVD</td>
  <td nowrap>Superhero Movie</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=497">497</a></td>
  <td nowrap>Warner Bros</td>
  <td nowrap>DVD</td>
  <td nowrap>Deep Blue Sea</td>
  <td nowrap>Movie</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=498">498</a></td>
  <td nowrap>Fasett</td>
  <td nowrap>Floor Fan 40cm</td>
  <td nowrap>Electric fan on adjustable floor stand</td>
  <td nowrap>Not applicable</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=499">499</a></td>
  <td nowrap>Lycom</td>
  <td nowrap>PE-103</td>
  <td nowrap>PCI-Express SATA II internal interface, </td>
  <td nowrap>Peripheral</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=500">500</a></td>
  <td nowrap>Rusta AB</td>
  <td nowrap>3075-1074</td>
  <td nowrap>LED torch</td>
  <td nowrap>Not applicable</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=501">501</a></td>
  <td nowrap>Rusta AB</td>
  <td nowrap>3075-1076</td>
  <td nowrap>LED torch 13 LEDs</td>
  <td nowrap>Not applicable</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap>Car </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=502">502</a></td>
  <td nowrap>Philips</td>
  <td nowrap>QT4050</td>
  <td nowrap>Beard trimmer, battery operated</td>
  <td nowrap>Not applicable</td>
  <td nowrap>Home</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=503">503</a></td>
  <td nowrap>Benefit</td>
  <td nowrap>Gym Ball</td>
  <td nowrap>Benefit Gym Ball</td>
  <td nowrap>Not applicable</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=504">504</a></td>
  <td nowrap>Trust</td>
  <td nowrap>FRIO</td>
  <td nowrap>Trust FRIO laptop cooling stand</td>
  <td nowrap>Peripheral</td>
  <td nowrap>Home</td>
  <td nowrap></td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=505">505</a></td>
  <td nowrap>Lenovo</td>
  <td nowrap>Y50-70</td>
  <td nowrap>Lenovo Y50-70 laptop</td>
  <td nowrap>Laptop</td>
  <td nowrap>Home Office</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=506">506</a></td>
  <td nowrap>Hewlett Packard</td>
  <td nowrap>ProLiant MicroServer</td>
  <td nowrap>HP ProLiant MicroServer</td>
  <td nowrap>Server</td>
  <td nowrap>Home Office</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=507">507</a></td>
  <td nowrap>Crucial</td>
  <td nowrap>200 744179 Crucial DDR3 Ballis</td>
  <td nowrap>200 744179 Crucial DDR3 BallistiX Sport </td>
  <td nowrap>Peripheral</td>
  <td nowrap>Home Office</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=508">508</a></td>
  <td nowrap>Kingston</td>
  <td nowrap>Datatraveler 101 G2 8GB</td>
  <td nowrap>Kingston Datatraveler 101 G2 8GB USB-mem</td>
  <td nowrap>Storage</td>
  <td nowrap>Home Office</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#D8D8D8" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#D8D8D8'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=509">509</a></td>
  <td nowrap>Raspberry Pi Foundation</td>
  <td nowrap>2 Model B</td>
  <td nowrap>Raspberry  Pi 2 Model B SBC</td>
  <td nowrap>Computer</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr bgcolor="#C9C9C9" onmouseover="javascript:style.backgroundColor='#C1D2EE'" onmouseout="javascript:style.backgroundColor='#C9C9C9'">
  <td align="center"><a href="/~daniel/oscar_v3/index.php?cmd=edit&amp;asset=510">510</a></td>
  <td nowrap>SanDisk</td>
  <td nowrap>SDSDQUAN-016G-G4A</td>
  <td nowrap>SanDisk microSHDC Ultra 16GB memory card</td>
  <td nowrap>Peripheral</td>
  <td nowrap>IS</td>
  <td nowrap>druus</td>
  <td nowrap> </td>
</tr>
<tr>

  <td colspan="6">Found <b>50</b> entries matching the search criteria.</td>
</tr>
</table>




</div>

{% include 'footer.tmpl' %}

