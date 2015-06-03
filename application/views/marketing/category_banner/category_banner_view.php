<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="imagetoolbar" content="no">
<link rel="stylesheet" type="text/css" href="<?=base_url().'css/style.css'?>">
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script language="javascript" src="<?=base_url()?>/js/checkform.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script language="javascript">
jQuery(function(){
	$('#btn-up').bind('click', function() {
		$('#select_to option:selected').each( function() {
			var newPos = $('#select_to option').index(this) - 1;
			if (newPos > -1) {
				$('#select_to option').eq(newPos).before("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
				$(this).remove();
			}
		});
	});
	$('#btn-down').bind('click', function() {
		var countOptions = $('#select_to option').size();
		$('#select_to option:selected').each( function() {
			var newPos = $('#select_to option').index(this) + 1;
			if (newPos < countOptions) {
				$('#select_to option').eq(newPos).after("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
				$(this).remove();
			}
		});
	});
});
function openWin(src)
{
	window.open('<?=base_url()?>' + src);
}
function SaveChange(el)
{
	el.form.submit();
}
function submitform()
{
	for (var i = 0; i < document.fm.select_to.length; i++)
	{
		document.fm.select_to[i].selected = true;
	}
	document.fm.submit();
}
function checkName(name,id)
{
	document.fm.sub_cat_id.value = id;
	document.getElementById('cat_name').innerHTML = name + ' Banner';
	//alert(id);
}
function checkvalid()
{
	j = 0;
	for (var i = 0; i < document.fm.select_to.length; i++)
	{
		if(document.fm.select_to[i].selected == true)
		{
			j++;
		}
	}
	if(j != 1)
	alert("Only one category can be selected.");
}
</script>
</head>
<body topmargin="0" leftmargin="0">
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td height="30" class="title"><b style="font-size:16px;color:#000000"><?=$lang["banner_setup"]?></b></td>
</tr>
</table>
<form name="fm" method="POST" onSubmit="return CheckForm(this)" enctype="multipart/form-data">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="page_header">
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;">Please Select Language</td>
	<td width="20%" class="value" >&nbsp;&nbsp;
		<select onChange='SaveChange(this);gotoPage("<?=base_url()."marketing/category_banner/index/"?>",this.value)' >
			<option value="" style="padding-right:50px;"> -- Please Select -- </option>
			<?php
				foreach($language_list as $obj)
				{
					?><option value="<?=$obj->get_id()?>"<?=($obj->get_id()==$language_id?"SELECTED":"")?>><?=$obj->get_name()?></option><?php
				}
			?>
		</select>
	</td>
	<?if($language_id)
	{
	?>
	<td width="15%" class="field" align="right" style="padding-right:8px;" size='5'>Please Select Country</td>
	<td width="20%" class="value">&nbsp;&nbsp;
		<select name="country_list[]" multiple>
			<?php
				foreach($country_list_w_lang as $obj)
				{
					?><option value="<?=$obj->get_id()?>"SELECTED><?=$obj->get_name()?></option><?php
				}
			?>
		</select>
	</td>
	<td width="15%" class="field" align="right" style="padding-right:8px;">Sub Category Order</td>
	<td width="15%" class="value">&nbsp;&nbsp;
		<select name="sub_cat_list[]" id="select_to" onchange="checkvalid()" multiple >
			<?php
				$i = 0;
				foreach($cat_ban_list as $obj)
				{
					?><option value="<?=$obj->get_id()?>" onclick="checkName('<?=$obj->get_name()?>', <?=$obj->get_id()?>)"><?=$obj->get_name()?></option><?php
				}
			?>
		</select><br>
		&nbsp;&nbsp;&nbsp;<input type="button" value="Up" id="btn-up">
		&nbsp;&nbsp;<input type="button" value="Down" id="btn-down">
	</td>
	<?php
	}
	?>
</tr>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="1" class="tb_list">
<?if($language_id)
{
?>
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;" rowspan='2'><b id='cat_name'>Category Banner</b><br>Dimension: 90px(W) X 87px(H)<br> Format: jpg, jpeg, gif, png<br></td>
	<td width="20%" class="value" rowspan='2'>
		<input type="file" name="image">
	</td>
	<td width="15%" class="field special" align="right">
		<?if($updated_cat_ban){?>Updated Category<?}?><br>
		<?if($updated_language){?>Updated Language<?}?><br>
		<?if($updated_country){?>Updated Countries<?}?><br>
	<td width="50%" class="field special" colspan='3' align="left">
		<?if($updated_cat_ban){echo $updated_cat_ban->get_name();}?><br>
		<?if($updated_language){echo $updated_language->get_name();}?><br>
		<?if($updated_country){?><?foreach($updated_country AS $obj){echo $obj->get_name();$i++;if($i % 5 == 0){?><br><?}}}?></td>
</tr>
<tr>
	<td colspan='4' class="value">
<?php
	if($updated_banner)
{
	$image_file = CAT_PH.$updated_banner.".jpg";
	if (file_exists($image_file))
	{
?>
	<img src='<?=base_url().$image_file?>'>
	<?}
}?>
	</td>
</tr>

</table>
<table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
<tr>
	<td align="right" style="padding-right:8px">&nbsp;&nbsp;<input type="button" value="<?=$lang["update_banner"]?>" onClick="submitform()"></td>
</tr>
</table>
<?php
}
?>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="sub_cat_id" value="">
<input type="hidden" name="template" value="<?=$template_type?>">
<!-- <input type="hidden" name="type" id="type" value="">-->
</form>
</div>
<?=$notice["js"]?>
<script language="javascript" src="<?=base_url()?>js/check_change.js"></script>
</body>
</html>