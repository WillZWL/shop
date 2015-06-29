<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="">
<title><?=$lang["title"]?></title>
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url().'css/style.css'?>">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript" src="<?=base_url().'js/picklist.js'?>"></script>
</head>

<body topmargin="0" leftmargin="0">
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>

<td class="title"><b style="font-size:16px;color:#000000"><?=$lang["title"]?></b></td>
</tr>
<tr>
<td height="2" bgcolor="#000033"></td>
</tr>
</table>
<form action="<?=$_SERVER["PHP_SELF"]?>" name="editform" method="post" style="padding:0; margin:0" onSubmit="return CheckForm(this);">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
<tr>
<td height="70" style="padding-left:8px">
<b style="font-size:14px"><?=$lang["header"]?></b><br>
<?=$lang["header_message"]?><br>
</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" height="20" class="tb_list" width="100%">
<tr class="header">
<td height="20" width="250">&nbsp;&nbsp;<?=$lang["region_prop"]?></td>
<td >&nbsp;&nbsp;<?=$lang["assoc_value"]?></td>
</tr>
<tr>
    <td height="20" width="250" class="field">&nbsp;&nbsp;<?=$lang["region_name"]?></td>
    <td height="20" align="left" class="value">&nbsp;&nbsp;<input type="text" name="region_name" class="input" value="<?=$region_obj->get_region_name()?>" notEmpty></td>
</tr>
<tr>
    <td height="20" width="250" class="field">&nbsp;&nbsp;<?=$lang["region_type"]?></td>
    <td height="20"  class="value">&nbsp;&nbsp;
        <select name="region_type" style="width:200px;" >
            <option value="C"><?=$lang["courier"]?></option>
            <option value="S"><?=$lang["sourcing"]?></option>
        </select>
    </td>
</tr>
<tr>
    <td height="20" width="250" class="field" >&nbsp;&nbsp;<?=$lang["country_in_region"]?></td>
    <td height="20" align="left" class="value" style="padding:6px;">
        <table border="0" cellpadding="0" cellspacing="1" class="tb_list">
        <tr class="header">
            <td align="center"><?=$lang["not_in_region"]?></th>
            <td align="center">&nbsp;</th>
            <td align="center"><?=$lang["in_region"]?></th>
        </tr>
        <tr>
        <td><select name="countrylist" id='left' style='width:150px; height:300px;' multiple='multiple'><?php
        foreach($country_ex as $key=>$value)    
        {
            echo '<option value=\''.$key.'\'>'.$value.'</option>';
        }
?></select></td>
        <td align="centre" valign="middle"><input type="button" value=">" onclick="AddOne(document.getElementById('left'),document.getElementById('right'));" class="button"><br><br><input type="button" value=">>" onclick="AddAll(document.getElementById('left'),document.getElementById('right'));" class="button"><br><br><br><input type="button" value="<" onclick="DelOne(document.getElementById('left'),document.getElementById('right'));" class="button"><br><br><input type="button" value="<<" onclick="DelAll(document.getElementById('left'),document.getElementById('right'));" class="button"></td>
        <td><select name="country[]" id='right' style='width:150px; height:300px;' multiple='multiple'></select>        
        </td>
    </tr>
    </table></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header"  width="100%">
<tr>
<td align="left" style="padding-left:8px;"><input type="button" value="<?=$lang["back_to_main"]?>" onClick="Redirect('<?=base_url()."mastercfg/region/"?>')"></td>
<td align="right" style="padding-right:8px"><input type="button" value="<?=$lang["add_region"]?>" onclick="SelectAllItems(document.editform.elements['country[]']); if(CheckForm(this.form)) this.form.submit();" style="font-size:11px"></td>
</tr>
</table>
<input type="hidden" name="posted" value="1">
</form>
</div>
<?=$notice["js"]?>
</body>
</html>