<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manifest Label</title>
<!-- onLoad="if (parent.frames['printframe']){parent.frames['printframe'].focus();parent.frames['printframe'].print();}else{print();}"-->
<style type="text/css">
.pb{ page-break-after : always;}
body {margin:0;}
h3{margin:0px; margin:0;font-size: 12px}
* {font-size:13pt;}
table.border_table
{	
    border-width: 0 0 1px 1px;
    border-spacing: 0;
    border-collapse: collapse;
    border-style: solid;
    border-color: #000;
}
table.border_table td
{
    margin: 0;
    border-width: 1px 1px 0 0;
    border-style: solid;
    height: 50px;
    border-color: #000;
}
table.border_table td span
{
padding-left:10px;
}
p{margin:0;padding:0;}
@media screen {
    tfoot{display: block;}
}
@media print {
 	/*@page{ size: 4.5in 3.0in;}*/
    tfoot{display: table-footer-group;}
    table.border_table  {width: 400px}
}
</style>
</head>
<body topmargin="5" leftmargin="5" rightmargin="5"  onLoad="if (parent.frames['printframe']){parent.frames['printframe'].focus();parent.frames['printframe'].print();}else{print();}" style="overflow:none;">

<?php if($manifestBags){
	foreach($manifestBags as $bagNo => $manifestBag){
?>
<table border="0" class="border_table" cellpadding="0" cellspacing="0" width="450px">
	<tbody>
	<tr>
		<td width="50%"><span>PPI:<?=$asendiaPpi?></span></td>
		<td width="50%"><span>Country:<?=$manifestBag->CountryCode;?></span></td>
	</tr>
	<tr>
		<td width="50%"><span>Service Type:<?=$manifestBag->ServiceType;?></span></td>
		<td width="50%"></td>
	</tr>
		<tr>
		<td colspan="2" align="center"><?php print '<img style="margin:5px" src=" data:image/png;base64,'.base64_encode($barcode[$bagNo]).'" >'; ?></td>
	</tr>
	</tbody>
</table>
<p class="pb"></p>

<?php } 

} ?>

</body>
</html>