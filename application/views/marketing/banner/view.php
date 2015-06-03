<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/style.css">
<script language="javascript" src="<?=base_url()?>/js/checkform.js"></script>
<script language="javascript">
<!--
function changeUpdate(value)
{
	document.getElementById("type").value = value;
	document.getElementById('typeval').innerHTML = value;
	document.getElementById('link').value = link[value];
	document.fm.elements["link_type"].value = link_type[value];
}


var reload = "<?=$refresh?>";
if(reload == "y")
{
    var h = parent.document.getElementById('plist').src;
    parent.document.getElementById('plist').src = h;
}

-->
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
	<td width="100%" height="40" style="padding-left:8px;"><b style="font-size:14px"><?=$lang["banner_setup"]." - ".$catobj->get_name()?></b></td>
</tr>
</table>
<?php

	if($template_type == "1")
	{
		$limit = 7;
	}
	else
	{
		$limit = 3;
	}

	for($i = 1; $i <= $limit; $i++)
	{
		$obj = $image_list[$i];

		if(!empty($obj))
		{
				${"content".$i} = "<a href=\"javascript:changeUpdate('".$i."');\"><img src=\"".base_url()."images/adbanner/preview/blank_".$template_type."_".$i.".gif\" border=\"0\" height=\"".$this->config->item('banner_height_'.$template_type.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$template_type.'_'.$i)."\"></a>";
				if($obj->get_image_file() != "" && file_exists($this->config->item('banner_local_path')."preview/".$obj->get_image_file()))
				{
					${"content".$i} = "<a href=\"javascript:changeUpdate('".$i."');\"><img src=\"".base_url()."images/adbanner/preview/".$obj->get_image_file()."?".$obj->get_modify_on()."\" border=\"0\" height=\"".$this->config->item('banner_height_'.$template_type.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$template_type.'_'.$i)."\"></a>";
				}
				if($obj->get_flash_file() != "" && file_exists($this->config->item('banner_local_path')."preview/".$obj->get_image_file()))
				{
					${"content".$i} = "<button class='flashbutton' onclick=\"javascript:changeUpdate('".$i."');\" style='width:".$this->config->item('banner_width_'.$template_type.'_'.$i).";height:".$this->config->item('banner_height_'.$template_type.'_'.$i)."'><object width='".$this->config->item('banner_width_'.$template_type.'_'.$i)."' height='".$this->config->item('banner_height_'.$template_type.'_'.$i)."' classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0'><param name='movie' value='".base_url()."images/adbanner/preview/".$obj->get_flash_file()."?".$obj->get_modify_on()."'><param name='wmode' value='opaque'><embed src='".base_url()."images/adbanner/preview/".$obj->get_flash_file()."?".$obj->get_modify_on()."' width='".$this->config->item('banner_width_'.$template_type.'_'.$i)."' height='".$this->config->item('banner_height_'.$template_type.'_'.$i)."' wmode='opaque' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed></object></button>";
				}
				$link[$i] = $obj->get_link();
				$link_type[$i] = "E";
		}
		else
		{
				${"content".$i} = "<a href=\"javascript:changeUpdate('".$i."');\"><img src=\"".base_url()."images/adbanner/preview/blank_".$template_type."_".$i.".gif\" border=\"0\" height=\"".$this->config->item('banner_height_'.$template_type.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$template_type.'_'.$i)."\"></a>";
				$link[$i] = "";
				$link_type[$i] = "E";
		}

		$pobj = $pimage_list[$i];

		if(!empty($pobj))
		{
				${"pcontent".$i} = "<img src=\"".base_url()."images/adbanner/preview/blank_".$template_type."_".$i.".gif\" border=\"0\" height=\"".$this->config->item('banner_height_'.$template_type.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$template_type.'_'.$i)."\">";
				if($pobj->get_image_file() != "" && file_exists($this->config->item('banner_publish_path').$pobj->get_image_file()))
				{
					${"pcontent".$i} = "<img src=\"".$this->config->item('banner_public_path').$pobj->get_image_file()."?".$obj->get_modify_on()."\" border=\"0\" height=\"".$this->config->item('banner_height_'.$template_type.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$template_type.'_'.$i)."\">";

					if($pobj->get_link() != "")
					{

						${"pcontent".$i} = "<a href=\"".$pobj->get_link()."\" target=\"blank\">".${"pcontent".$i}."</a>";
					}
				}
				if($pobj->get_flash_file() != "" && file_exists($this->config->item('banner_publish_path').$pobj->get_image_file()))
				{
					${"pcontent".$i} = "<button class='flashbutton' onclick=\"javascript:changeUpdate('".$i."');\" style='width:".$this->config->item('banner_width_'.$template_type.'_'.$i).";height:".$this->config->item('banner_height_'.$template_type.'_'.$i)."'><object width='".$this->config->item('banner_width_'.$template_type.'_'.$i)."' height='".$this->config->item('banner_height_'.$template_type.'_'.$i)."' classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0'><param name='movie' value='".$this->config->item('banner_public_path').$pobj->get_flash_file()."?".$pobj->get_modify_on()."'><param name='wmode' value='opaque'><embed src='".$this->config->item('banner_public_path').$pobj->get_flash_file()."?".$obj->get_modify_on()."' width='".$this->config->item('banner_width_'.$template_type.'_'.$i)."' height='".$this->config->item('banner_height_'.$template_type.'_'.$i)."' wmode='opaque' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed></object></button>";
				}
				$link[$i] = $obj->get_link();
				$link_type[$i] = $obj->get_link_type();

		}
		else
		{
				${"pcontent".$i} = "<img src=\"".base_url()."images/adbanner/preview/blank_".$template_type."_".$i.".gif\" border=\"0\" height=\"".$this->config->item('banner_height_'.$template_type.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$template_type.'_'.$i)."\">";
				$link[$i] = "";
				$link_type[$i] = "E";
		}
	}

	include_once APPPATH."views/marketing/banner/publish_".$template_type.".php";

	if($template_type == 1)
	{
		include_once APPPATH."views/marketing/banner/template".$template_type."_upper.php";
		include_once APPPATH."views/marketing/banner/template".$template_type."_lower.php";
	}
	else
	{
		include_once APPPATH."views/marketing/banner/template".$template_type.".php";
	}

	if($this->input->post('type') == "")
	{
		$type = 1;
	}
	else
	{
		$type = $this->input->post('type');
	}
?>
<script language="javascript">
<!--
link = new Array();
link_type = new Array();
<?php
	foreach($link as $key=>$val)
	{
?>
	link[<?=$key?>] = "<?=$val?>";
	link_type[<?=$key?>] = "<?=$link_type[$key]?>";
<?php
	}
?>
-->
</script>
<form name="fm" action="<?=$_SERVER["PHP_SELF"]?>" method="POST" onSubmit="return CheckForm(this)" enctype="multipart/form-data">
<table width="100%" cellpadding="0" cellspacing="1" class="tb_list">
<tr>
	<td width="15%" class="field" align="right" height="20" style="padding-right:8px;"><?=$lang["banner_number"]?></td>
	<td width="85%" class="value">&nbsp;&nbsp;<span id="typeval"><?=$type?></span></td>
</tr>
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["banner_image"]?></td><td width="85%" class="value">&nbsp;&nbsp;<input type="file" name="image" class="input"><br>&nbsp;&nbsp;<?=$lang["image_limit"]."  ".$this->config->item("banner_height_".$template_type."_".$type)."(H) x".$this->config->item("banner_width_".$template_type."_".$type)."(W)"?></td>
</tr>
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["banner_flash"]?></td><td width="85%" class="value">&nbsp;&nbsp;<input type="file" name="flash" class="input"><br>&nbsp;&nbsp;<input type="checkbox" name="removeflash" value="1">&nbsp;&nbsp;<?=$lang["remove"]?><br>&nbsp;&nbsp;<?=$lang["size_limit"]?></td>
</tr>
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["link"]?></td><td width="85%" class="value">&nbsp;&nbsp;<input type="text" name="link" class="input" id="link" value=""></td>
</tr>
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["link_type"]?></td>
	<td width="85%" class="value">&nbsp;&nbsp;<select name="link_type" ><option value="E"><?=$lang["external"]?></option><option value="I"><?=$lang["internal"]?></option></select></td>
</tr>
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["status"]?></td><td width=8"5%" class="value">&nbsp;&nbsp;<select name="status"><option value="A"><?=$lang["active"]?></option><option value="I"><?=$lang["inactive"]?></option></select></td>
</tr>
<tr>
	<td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["publish"]?></td><td width="85%" class="value">&nbsp;&nbsp;<input type="checkbox" name="publish" value="1"></td>
</tr>
<tr>
	<td width="15%" class="field">&nbsp;</td><td width="85%" class="value">&nbsp;&nbsp;<input type="button" value="<?=$lang["update_banner"]?>" onClick="if(CheckForm(this.form)) this.form.submit()"></td>
</tr>
</table>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="template" value="<?=$template_type?>">
<input type="hidden" name="type" id="type" value="">
</form>
</div>
<script language="javascript">
<!--
if(document.getElementById("type").value == "")
{
	changeUpdate("<?=$type?>");
}
-->
</script>
<?=$notice["js"]?>
</body>
</html>