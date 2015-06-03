<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/style.css">
<script language="javascript" src="<?=base_url()?>/js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script language="javascript">
function openWin(src)
{
	window.open('<?=base_url()?>images/display/' + src);
}

function SaveChange(el)
{
	el.form.submit();
}

function changeValue(position_id, slide_id)
{
	var src = document.getElementById("status[" + position_id + "][" + slide_id + "]");

	if (src.value == 1)
	{
		if (position_id == 0)
		{
			for (var i = 1; i <= 6; i++)
			{
				var target = document.getElementById("status[1][" + i + "]");
				target.value = 0;
			}
		}
		else if (position_id == 1)
		{
			var target = document.getElementById("status[0][1]");
			target.value = 0;
		}
	}

	if (typeof has_change == 'function')
	{
		has_change();
	}
}
</script>
<style>
.flashbutton
{
	padding:0px;
	border: 0px;
}
</style>
</head>
<body class="frame_left">
<div id="main" style="width:auto;">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="page_header">
<tr>
	<td width="100%" height="40" style="padding-left:8px;"><b style="font-size:14px">Display Banner<?//=$lang["banner_setup"]." - ".$catobj->get_name()?></b></td>
</tr>
</table>

<form name="fm" action="/marketing/display_banner/edit_home" method="POST" onSubmit="return CheckForm(this)" enctype="multipart/form-data">
<table width="100%" cellpadding="0" cellspacing="1" class="tb_list">

<tr>
	<td width="20%" class="field" align="right" style="padding-right:8px;">Please Select Language</td>
	<td width="20%" class="value" >&nbsp;&nbsp;
		<select onChange='SaveChange(this);gotoPage("<?=base_url()."marketing/display_banner/view_home/"?>",this.value)' >
			<option value="" style="padding-right:50px;"> -- Please Select -- </option>
			<?php
				foreach($language_list as $obj)
				{
					?><option value="<?=$obj->get_id()?>"<?=($obj->get_id()==$language_id?"SELECTED":"")?>><?=$obj->get_name()?></option><?php
				}
			?>
		</select>
	</td>
	<td width="20%" class="field" align="right" style="padding-right:8px;" size='5'>Please Select Country</td>
	<td width="20%" class="value">&nbsp;&nbsp;
		<select name="country[]" multiple>
			<?php
				foreach($country_list_w_lang as $obj)
				{
					?><option value="<?=$obj->get_id()?>"SELECTED><?=$obj->get_name()?></option><?php
				}
			?>
		</select>
	</td>
	<td width="20%" class="value"></td>
</tr>
<?php
if($language_id)
{
?>

<tr><td colspan="7" class="field" align="left" style="padding-right:8px;"><b>Display Banner</b><br>Dimension: 1000px(W) X 240px(H)<br> Format: jpg, jpeg, gif, png, swf<br></td></tr>
<?php
if (empty($banner_list[0][1]))
{
	$link = '';
	$link_type = 'I';
	$status = 1;
	$image_file = '';
	$flash_file = '';
}
else
{
	$link = $banner_list[0][1]->get_link();
	$link_type = $banner_list[0][1]->get_link_type();
	$status = $banner_list[0][1]->get_status();
	$image_file = $banner_list[0][1]->get_image_file();
	$flash_file = $banner_list[0][1]->get_flash_file();
}
?>
<!--
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;">Main Banner (flash)</td>
	<td width="15%" class="value" colspan="1">
		<input type="file" name="flash[0][1]" class="input"><br>
		&nbsp;&nbsp;<input type="checkbox" name="removeflash[0][1]" value="1">&nbsp;&nbsp;<?=$lang["remove"]?><br>
		&nbsp;&nbsp;<?=$lang["size_limit"]?>
	</td>
	<td width="70%" class="value" colspan="5">
<?php
	if (!empty($flash_file))
	{
?>
		<a href="javascript:openWin('<?=$flash_file?>')"><?php echo $flash_file;?></a>
<?php
	}
?>
	</td>
</tr>
-->
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;" rowspan="2">Main Image (noFlash)</td>
	<td width="15%" class="value" colspan="1" rowspan="2">
		<input type="file" name="image[0][1]" class="input">
	</td>
	<td width="29%" class="value" colspan="2" rowspan="2">
<?php
	if (!empty($image_file))
	{
?>
		<a href="javascript:openWin('<?=$image_file?>')"><?php echo $image_file;?></a>
<?php
	}
?>
	</td>
	<td width="51%" class="field" align="left" style="padding-right:8px;" colspan="3">&nbsp;&nbsp;<b>Link</b></td>
</tr>
<tr>
	<td width="35%" class="value" align="left" style="padding-right:8px;">
		<input type="text" name="link[0][1]" id="link[0][1]" class="input" id="link" value="<?php echo $link;?>" onchange="has_change()">
	</td>
	<td width="8%" class="value">&nbsp;&nbsp;
		<select name="link_type[0][1]" onchange="has_change()">
			<option value="I"<?php echo (($link_type == 'I') ? ' selected' : '');?>><?=$lang["internal"]?></option>
			<option value="E"<?php echo (($link_type == 'E') ? ' selected' : '');?>><?=$lang["external"]?></option>
		</select>
	</td>
	<td width="8%" class="value">
		<select name="status[0][1]" id="status[0][1]" onchange="changeValue(0, 1)">
			<option value="1"<?php echo (($status == 1) ? ' selected' : '');?>><?=$lang["active"]?></option>
			<option value="0"<?php echo (($status == 0) ? ' selected' : '');?>><?=$lang["inactive"]?></option>
		</select>
	</td>
</tr>


<tr>
	<td colspan="3" class="field" align="left" style="padding-right:8px;"><b>Display Scrolling Banner</b><br>Dimension: 1000px(W) X 240px(H)<br> Format: jpg, jpeg, gif, png, swf</td>
	<td class="field" align="left" style="padding-right:8px;">&nbsp;&nbsp;<b>Time Interval<br>&nbsp;(sec)</b>&nbsp;&nbsp;</td>
	<td colspan="3" class="field" align="left" style="padding-right:8px;"?<b>&nbsp;&nbsp;Link&nbsp;&nbsp;</b></td>
</tr>
<?php
for($imge=1;$imge<=6;$imge++)
{
	if (empty($banner_list[1][$imge]))
	{
		$link = '';
		$link_type = 'I';
		$status = 1;
		$time_interval = 1;
		$image_file = '';
		$flash_file = '';
	}
	else
	{
		$link = $banner_list[1][$imge]->get_link();
		$link_type = $banner_list[1][$imge]->get_link_type();
		$status = $banner_list[1][$imge]->get_status();
		$time_interval = $banner_list[1][$imge]->get_time_interval();
		$image_file = $banner_list[1][$imge]->get_image_file();
		$flash_file = $banner_list[1][$imge]->get_flash_file();
	}
?>
	<tr>
		<td width="15%" class="field" align="right" style="padding-right:8px;">Main Image (noFlash)</td>
		<td width="16%" class="value">
			&nbsp;<input type="file" name="image[1][<?=$imge;?>]" class="input">
		</td>
		<td width="13%" class="value">
<?php
	if (!empty($image_file))
	{
?>
		<a href="javascript:openWin('<?=$image_file?>')"><?php echo $image_file;?></a>
<?php
	}
?>
		</td>
		<td width="5%" class="value">
			<input type="text" name="time_interval[1][<?=$imge;?>]" class="input" value="<?php echo $time_interval;?>" onchange="has_change()">
		</td>
		<td width="35%" class="value" align="left" style="padding-right:8px;">
			<input type="text" name="link[1][<?=$imge;?>]" class="input" id="link[1][<?=$imge;?>]" value="<?php echo $link;?>" onchange="has_change()">
		</td>
		<td width="8%" class="value">
			&nbsp;&nbsp;
			<select name="link_type[1][<?=$imge;?>]" onchange="has_change()">
				<option value="I"<?php echo (($link_type == 'I') ? ' selected' : '');?>><?=$lang["internal"]?></option>
				<option value="E"<?php echo (($link_type == 'E') ? ' selected' : '');?>><?=$lang["external"]?></option>
			</select></td>
		<td width="8%" class="value">
			<select name="status[1][<?=$imge;?>]" id="status[1][<?=$imge;?>]" onchange="changeValue(1,<?=$imge;?>)" onchange="has_change()">
				<option value="1"<?php echo (($status == 1) ? ' selected' : '');?>><?=$lang["active"]?></option>
				<option value="0"<?php echo (($status == 0) ? ' selected' : '');?>><?=$lang["inactive"]?></option>
			</select></td>
	</tr>
<?php
}
?>
<tr>
	<td colspan="4" class="field" align="left" style="padding-right:8px;"><b>Top Categories Banners</b></td>
	<td colspan="3" class="field" align="left" style="padding-right:8px;"><b>&nbsp;&nbsp;Link&nbsp;&nbsp;</b></td>
</tr>
<?php
for($imge=2;$imge<=5;$imge++)
{
	switch($imge)
	{
		case 2:
			$position = "Top Left";
			$width = '195';
			$height = '130';
			break;
		case 3:
			$position = "Top Right";
			$width = '240';
			$height = '130';
			break;
		case 4:
			$position = "Bottom Left";
			$width = '240';
			$height = '130';
			break;
		case 5:
			$position = "Bottom Right";
			$width = '195';
			$height = '130';
			break;
	}
	if (empty($banner_list[$imge][1]))
	{
		$link = '';
		$link_type = 'I';
		$status = 1;
		$image_file = '';
		$flash_file = '';
	}
	else
	{
		$link = $banner_list[$imge][1]->get_link();
		$link_type = $banner_list[$imge][1]->get_link_type();
		$status = $banner_list[$imge][1]->get_status();
		$image_file = $banner_list[$imge][1]->get_image_file();
		$flash_file = $banner_list[$imge][1]->get_flash_file();
	}

?>
	<tr>
		<td width="15%" class="field" align="right" style="padding-right:8px;">Position: <?=$position?><br>Dimension: <?=$width?>px(W) X <?=$height?>px(H)<br> Format: jpg, jpeg, gif, png</td>
		<td class="value"><input type="file" name="image[<?=$imge;?>][1]" class="input"></td>
		<td width="30%" class="value" colspan="2">
<?php
	if (!empty($image_file))
	{
?>
		<a href="javascript:openWin('<?=$image_file?>')"><?php echo $image_file;?></a>
<?php
	}
?>
		</td>
		<td width="35%" class="value" align="left" style="padding-right:8px;">
			<input type="text" name="link[<?=$imge;?>][1]" class="input" id="link[<?=$imge;?>][1]" value="<?php echo $link;?>" onchange="has_change()">
		</td>
		<td width="10%" class="value">
			&nbsp;&nbsp;
			<select name="link_type[<?=$imge;?>][1]" onchange="has_change()">
				<option value="I"<?php echo (($link_type == 'I') ? ' selected' : '');?>><?=$lang["internal"]?></option>
				<option value="E"<?php echo (($link_type == 'E') ? ' selected' : '');?>><?=$lang["external"]?></option>
			</select></td>
		<td width="10%" class="value">
			<select name="status[<?=$imge;?>][1]" onchange="has_change()">
				<option value="1"<?php echo (($status == 1) ? ' selected' : '');?>><?=$lang["active"]?></option>
				<option value="0"<?php echo (($status == 0) ? ' selected' : '');?>><?=$lang["inactive"]?></option>
			</select></td>
	</tr>
<?php } ?>

<!--
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["publish"]?></td><td colspan="4" width="85%" class="value">&nbsp;&nbsp;<input type="checkbox" name="publish" value="1"></td>
</tr>-->
<tr>
	<td width="15%" class="field">&nbsp;</td><td colspan="6" width="85%" class="value">&nbsp;&nbsp;<input type="button" value="<?=$lang["update_banner"]?>" onClick="if(CheckForm(this.form)) this.form.submit()"></td>
</tr>
<?php
}
?>
</table>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="language_id" value="<?=$language_id?>">
<input type="hidden" name="template" value="<?=$template_type?>">
<!-- <input type="hidden" name="type" id="type" value="">-->
</form>
</div>
<?=$notice["js"]?>
<script language="javascript" src="<?=base_url()?>js/check_change.js"></script>
</body>
</html>