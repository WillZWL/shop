<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>marketing/product/js_catlist"></script>
<link rel="stylesheet" href="<?=base_url()?>css/colorbox.css" />
<script type="text/javascript" src="<?=base_url()?>js/jquery-colorbox.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>/marketing/complementary_acc/complementary_acc_js"></script>
<script language="javascript">

<!--

function showHide(country)
{
	if(country)
	{
		var ctry = 'c_'+country;
		var target = 'caprod_row_'+country;
		var sign = 'sign_'+country;
		var targetobj = document.getElementById(target);
		var ctry_header = document.getElementById(ctry);
		var signobj = document.getElementById(sign);
		if(targetobj && signobj)
		{
			if(targetobj.style.display == 'block')
			{
				targetobj.style.display = 'none';
				signobj.innerHTML = '+';
				ctry_header.style.display = 'block';
			}
			else if(targetobj.style.display == 'none')
			{
				targetobj.style.display = 'block';
				signobj.innerHTML = '-';
				ctry_header.style.display = 'none';
			}
			else
			{
				return;
			}
		}
	}
}

-->
</script>

</head>

<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" class="frame_left">
<script type="text/javascript">
	$(document).ready
	(
		function()
		{
			$(".iframe").colorbox({iframe:true, width:"40%", height:"80%"});
		}
	);
</script>

<?php
	$base_url = base_url();
	$ca_status = array(0=>"inactive", 1=>"active");
?>
<div id="main">
<?=$notice["img"]?>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td align="left" class="title" height="30"><b style="font-size: 16px; color: rgb(0, 0, 0);">
		<?=$lang["title"]?></b>
	</td>
	<td align="right" class="title" height="30">
		<input type="button" value="<?=$lang["map_by_sku_button"]?>" class="button" onclick="Redirect('<?=base_url()?>marketing/complementary_acc/')">
		<input type="button" value="<?=$lang["map_by_cat_button"]?>" class="button" onclick="Redirect('<?=base_url()?>marketing/complementary_acc/map_cat')">
	</td>
</tr>
<tr>
	<td height="2" bgcolor="#000033" colspan="2"></td>
</tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
<tr>
	<td height="70" style="padding-left:8px;">

	<div style="float:left"><img src=''> &nbsp;</div>
	<b style="font-size: 12px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message_mapcat"]?><b></b>
	</td>
</tr>
</table>
<form name="fm_mapcat" id="fm_mapcat" action="<?=base_url()?>marketing/complementary_acc/map_cat/<?=$country?>" method="POST" onSubmit="return CheckForm(this)">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
<col width="20%"><col width="30%"><col width="20%"><col width="30%">
<tr class="header">
	<td colspan="4"><?=$lang["subheader_message_mapcat"]?></td>
</tr>
<tr>
	<td class="field">Destination Country: </td>
	<td class="value" colspan="3">
<?php
if($country_list)
{
	$ctry_select = "<select name='ctry' id='ctry' onChange=\"Redirect('$base_url/marketing/complementary_acc/map_cat/'+this.value)\"><option></option>";
	foreach ($country_list as $ctryobj)
	{
		$selected="";
		if($ctryobj->get_id() == $country)
			$selected = " selected";

		$ctry_select .= <<<HTML
						<option value="{$ctryobj->get_id()}" $selected>{$ctryobj->get_id()} - {$ctryobj->get_name()}</option>
HTML;
	}
	$ctry_select .= "</select>";
	echo $ctry_select;

?>
	</td>
</tr>
<?php
	// only show after destination country selected
	if($country)
	{
?>
<tr>
	<td class="field"><?=$lang["category"]?></td>
	<td class="value" colspan="3">
		<select name="cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)" notEmpty>
			<option value="">
		</select>
	</td>
</tr>
<tr>
	<td class="field"><?=$lang["sub_cat"]?></td>
	<td class="value" colspan="3">
		<select name="sub_cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)" >
			<option value="">
		</select>
	</td>
</tr>
<tr>
	<td class="field"><?=$lang["sub_sub_cat"]?></td>
	<td class="value" colspan="3">
		<select name="sub_sub_cat_id" class="input">
			<option value="">
		</select>
	</td>
</tr>
<tr>
	<td class="field"><?=$lang["accessory"]?><br>(<?=$lang["accessory_message"]?>)</td>
	<td class="value" colspan="3">
		<input type="text" size = "90" name="new_acc_sku[<?=$country?>]" id="new_acc_sku[<?=$country?>]" onkeyup="showData('txtHint[<?=$country?>]', this.value, '<?=$country?>')" notEmpty>
		<div id="txtHint[<?=$country?>]" name="txtHint[<?=$country?>]" style="display:none;background-color:#0F192A;overflow-y:scroll;max-height:300px;width:560px;"></div>
		<input type="hidden" name="posted" id="posted" value="1">
	</td>
</tr>
<tr>
	<td class="field" colspan="4" align="right"><input type="submit" value="Submit"></td>
</tr>
<?php
	}
}
?>
	</td>
</tr>
<tr>
	<td class="field" colspan="4"></td>
</tr>
</table>
</form>
</div>
<?=$notice["js"]?>
<script language="javascript">
	ChangeCat('0', document.fm_mapcat.cat_id);
</script>
<?php
if($prompt_notice)
{
?><script language="javascript">alert('<?=$lang["update_notice"]?>')</script>
<?php
}
?>
<script language="javascript">

</script>
</body>
</html>