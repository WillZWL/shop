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
}
-->
</script>
</head>
<body class="frame_left">
<div id="main" style="width:auto;">
<?=$notice["img"]?>
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
                    ${"content".$i} = "<button class='flashbutton' onclick=\"javascript:changeUpdate('".$i."');\" style='width:".$this->config->item('banner_width_1_'.$i).";height:".$this->config->item('banner_height_1_'.$i)."'><object width='".$this->config->item('banner_width_1_'.$i)."' height='".$this->config->item('banner_height_1_'.$i)."' classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0'><param name='movie' value='".base_url()."images/adbanner/preview/".$obj->get_flash_file()."?".$obj->get_modify_on()."'><param name='wmode' value='opaque'><embed src='".base_url()."images/adbanner/preview/".$obj->get_flash_file()."?".$obj->get_modify_on()."' width='".$this->config->item('banner_width_1_'.$i)."' height='".$this->config->item('banner_height_1_'.$i)."' wmode='opaque' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed></object></button>";
                }
        }
        else
        {
                ${"content".$i} = "<a href=\"javascript:changeUpdate('".$i."');\"><img src=\"".base_url()."images/adbanner/preview/blank_".$template_type."_".$i.".gif\" border=\"0\" height=\"".$this->config->item('banner_height_'.$template_type.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$template_type.'_'.$i)."\"></a>";
        }
    }

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
<div style="padding-top:20px; padding-bottom:20px;"><b style="font-size:16px;"><?=$lang["update_image"]?> #<span id="typeval" style="font-size:16px; "><?=$type?></span></b></div>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST" onSubmit="return CheckForm(this)" enctype="multipart/form-data">
<table width="100%" cellpadding="0" cellspacing="1" bgcolor="#dddddd">
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["banner_image"]?></td><td width="85%" class="value">&nbsp;&nbsp;<input type="file" name="image" class="input"><br><i></i></td>
</tr>
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["banner_flash"]?></td><td width="85%" class="value">&nbsp;&nbsp;<input type="file" name="flash" class="input">&nbsp;&nbsp;<input type="checkbox" name="removeflash" value="1">&nbsp;<?=$lang["remove"]?><br><?=$lang["size_limit"]?></td>
</tr>
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["link"]?></td><td width="85%" class="value">&nbsp;&nbsp;<input type="text" name="link" class="input"></td>
</tr>
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["status"]?></td><td width="85%" class="value">&nbsp;&nbsp;<select name="status"><option value="A"><?=$lang["active"]?></option><option value="I"><?=$lang["inactive"]?></option></select></td>
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
    document.getElementById("type").value = "<?=$type?>";
}
-->
</script>
<?=$notice["js"]?>
</body>
</html>