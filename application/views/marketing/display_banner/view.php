<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/style.css">
<script language="javascript" src="<?=base_url()?>/js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script language="javascript">
<!--
function showEditLytebox(val)
{
    if(val == "L")
    {
        //document.getElementById("lb_content").style.display = "";
        //document.getElementById("lb_image").style.display = "";
        document.getElementById("url").style.display = "none";
    }
    else
    {
        //document.getElementById("lb_content").style.display = "none";
        //document.getElementById("lb_image").style.display = "none";
        document.getElementById("url").style.display = "";
    }
}
function changeUpdate(position_id, slide_id, index)
{
    document.getElementById("position_id").value = position_id;
    //document.getElementById('typeval').innerHTML = position_id;
    document.getElementById('height').value = height[position_id];
    document.getElementById('width').value = width[position_id];
    document.fm.elements["link_type"].value = link_type[position_id][slide_id];
    document.fm.elements["priority_val"].value = priority_val[position_id][slide_id];
    document.fm.elements["link"].value = link[position_id][slide_id];
    document.fm.elements["time"].value = time[position_id];
    document.fm.elements["status"].value = status[position_id][slide_id];
    document.fm.slide_id.value = slide_id;
    document.getElementById('slideval').innerHTML = parseInt(slide_id) + 1;
    if(!index)
    {
        document.getElementById('banner_type').value = banner_type[position_id];
    }
}

function Init(banner_type)
{
    var total = <?=$pv_num_of_banner?>;
    for (var i=1; i<=total;i++)
    {
        if(document.getElementById("bannerimage["+i+"][0]"))
        {
            document.getElementById("bannerimage["+i+"][0]").style.display = '';
        }
        document.getElementById("rowingpagination["+i+"]").style.display="none";
    }
    changeUpdate(1, 0);
    showBannerStyle(document.fm.banner_type.value, 1, 0);

}
function showBannerStyle(banner_type, position_id, slide_id)
{
    var total = <?=$pv_num_of_banner?>;
    document.fm.slide_id.value = slide_id;
    document.getElementById('slideval').innerHTML = parseInt(slide_id) + 1;
    document.getElementById("flash").style.display = 'none';
    document.getElementById("image").style.display = 'none';
    document.getElementById("time_interval").style.display = 'none';
    document.getElementById("priority").style.display = 'none';
    document.getElementById("slide_id").style.display = 'none';

    for (var i = 1; i<=total;i++)
    {
        document.getElementById("border["+i+"]").style.border="";
        document.getElementById("rowingpagination["+i+"]").style.display="none";
    }

    for (var j = 0; j < 7; j++)
    {
        if(document.getElementById("bannerimage["+position_id+"]["+j+"]"))
        {
            document.getElementById("bannerimage["+position_id+"]["+j+"]").style.display = 'none';
        }
    }

    if(document.getElementById("link_type").value == 'L')
    {
        document.getElementById("url").style.display = 'none';
        //document.getElementById("lb_image").style.display = '';
        //document.getElementById("lb_content").style.display = '';
    }
    else
    {
        document.getElementById("url").style.display = '';
        //document.getElementById("lb_image").style.display = 'none';
        //document.getElementById("lb_content").style.display = 'none';
    }

    if(document.getElementById('banner_type').value == 'I')
    {
        document.getElementById("image").style.display = '';
        <?if($show_lightbox[$display_id]){?>document.getElementById("select_lb").style.display = '';<?}?>
    }
    if(document.getElementById('banner_type').value == 'F')
    {
        document.getElementById("flash").style.display = '';
        document.getElementById("image").style.display = '';
        <?if($show_lightbox[$display_id]){?>document.getElementById("select_lb").style.display = 'none';<?}?>
    }
    if(document.getElementById('banner_type').value == 'R')
    {
        document.getElementById("time_interval").style.display = '';
        document.getElementById("priority").style.display = '';
        document.getElementById("slide_id").style.display = '';
        document.getElementById("image").style.display = '';
        document.getElementById("rowingpagination["+position_id+"]").style.display="";
        <?if($show_lightbox[$display_id]){?>document.getElementById("select_lb").style.display = 'none';<?}?>
    }
    if(document.getElementById("bannerimage["+position_id+"]["+slide_id+"]"))
    {
        document.getElementById("bannerimage["+position_id+"]["+slide_id+"]").style.display = '';
    }
    document.getElementById("border["+position_id+"]").style.border="thick solid #ff0000";
    document.getElementById('banner_type').value = banner_type;
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
    <td width="100%" height="40" style="padding-left:8px;" colspan="5"><b style="font-size:14px"><?=$lang["banner_setup"]." - ".$disp_obj->get_display_name()?><?if($cat_obj){echo " (".$cat_obj->get_name().")";}?></b></td>
</tr>
<tr>
    <td class="value" width="30%" align="right"><?=$lang["select_language"]?></td>
    <td class="value" width="15%">&nbsp;&nbsp;
        <select onChange='gotoPage("<?=base_url()."marketing/display_banner/view/".$display_id."/ALL/"?>",this.value+"<?="?catid=$catid"?>")' ><option value=""> -- <?=$lang["please_select"]?> -- </option><?php
            foreach($lang_list as $lang_obj)
            {
            ?><option value="<?=$lang_obj->get_id()?>" <?=($lang_obj->get_id()==$lang_id?"SELECTED":"")?>><?=$lang_obj->get_name()?></option><?php
            }
            ?>
        </select>
    </td>
    <td class="value" width="2%"><?=$lang["or"]?></td>
    <td class="value" width="10%" align="right"><?=$lang["select_country"]?></td>
    <td class="value" width="45%">&nbsp;&nbsp;
        <select onChange='gotoPage("<?=base_url()."marketing/display_banner/view/".$display_id."/"?>",this.value+"<?="?catid=$catid"?>")' ><option value=""> -- <?=$lang["please_select"]?> -- </option><?php
            foreach($country_list as $country_obj)
            {
            ?><option value="<?=$country_obj->get_id()?>" <?=($country_obj->get_id()==$country_id?"SELECTED":"")?>><?=$country_obj->get_name()?></option><?php
            }
            ?>
        </select>
    </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td height="30" align="left" style="padding-left:10px;"><b><?=$lang["publish"]?></b></td>
</tr>
<?php
if($different_country_list)
{
?>
<tr>
    <td class="value" align="center"><?=$lang["disable_word"]?></td>
</tr>
<tr>
    <td class="value" align="center">
<?php
    foreach($different_country_list AS $no=>$country)
    {
        $id = $country['id'];
?>
        &nbsp;&nbsp;<input type="button" value="<?=$lang["disable_button"].$country['name']?>" onclick="return SaveChange();">
<?php
    }
?>
    </td>
</tr>
<?php
}
if($country_id)
{
?>
<tr>
    <td width="100%" align="center">
    <table border="0" cellpadding="0" cellspacing="0" width="760">
        <tr>
            <td align="center"><?=$template_type?></td>
        </tr>
        <tr>
            <td height="10">&nbsp;&nbsp;</td>
        </tr>
<?php
    if($publish_banner)
    {
        $no = 0;
        foreach($publish_banner AS $banner)
        {
        ?>
            <tr>
                <td align="center">
                <?php
                    include APPPATH."views/marketing/display_banner/banner_publish/publish_".$banner["publish_key"].".php";
                ?>
                </td>
            </tr>
            <tr>
                <td height="10">&nbsp;&nbsp;</td>
            </tr>
        <?php
        $no++;
        }
    }
    /*
        for($j = 1; $j <= $pb_num_of_banner; $j++)
        {
            $graphic = $pb_redirect_link = $pb_link_type = $pb_time_interval = $pb_graphic = array();
            if($pb_db_obj[$j]["config"])
            {
                $pb_banner_height = $pb_db_obj[$j]["config"]->get_height();
                $pb_banner_width = $pb_db_obj[$j]["config"]->get_width();
                $pb_banner_type = $pb_db_obj[$j]["config"]->get_banner_type();
                if($pb_db_obj[$j]["details"][0])
                {
                    $num = 0;
                    $backup_link = $backup_link_type = $backup_graphic = "";
                    if($pb_db_obj[$j]["details"])
                    {
                        foreach($pb_db_obj[$j]["details"] AS $pb_obj)
                        {
                            $pb_redirect_link[$num] = $pb_obj->get_link();
                            $pb_link_type[$num] = $pb_obj->get_link_type();
                            $pb_time_interval = (int)$pb_obj->get_time_interval() * 1000;
                            $pb_graphic[$num] = base_url().GRAPHIC_PH.$pb_obj->get_graphic_location().$pb_obj->get_graphic_file();
                            if($pb_banner_type == "F")
                            {
                                if($pb_db_obj[$j]["backup_image"])
                                {
                                    $backup_link = $pb_db_obj[$j]["backup_image"]->get_link();
                                    $backup_link_type = $pb_db_obj[$j]["backup_image"]->get_link_type();
                                    $backup_graphic = base_url().GRAPHIC_PH.$pb_db_obj[$j]["backup_image"]->get_graphic_location().$pb_db_obj[$j]["backup_image"]->get_graphic_file();
                                }
                                $file = explode('.', $pb_obj->get_graphic_file());
                                $pb_graphic[$num] = base_url().GRAPHIC_PH.$pb_obj->get_graphic_location().$file[0];
                            }
                            $num++;
                        }
                    }
                }
            }
            if($pb_db_obj[$j]["details"])
            {
            ?>
        <tr>
            <td align="center">
            <?php
                include APPPATH."views/marketing/display_banner/publish_".$display_id."_".$j.".php";
            ?>
            </td>
        </tr>
        <tr>
            <td height="10">&nbsp;&nbsp;</td>
        </tr>
            <?php
            }
        }
        */
?>

        </table>
    </td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="page_header">
<?php
    $link = array();
    for($i = 1; $i <= $pv_num_of_banner; $i++)
    {
    $link[$i] = array();
        if($pv_db_obj[$i])
        {
            $banner_type[$i] = $pv_db_obj[$i]["config"]->get_banner_type()?$pv_db_obj[$i]["config"]->get_banner_type():"I";
            $height[$i] = $pv_db_obj[$i]["config"]->get_height();
            $width[$i] = $pv_db_obj[$i]["config"]->get_width();
            $dbc_id[$i] = $pv_db_obj[$i]["config"]->get_id();
            if($pv_db_obj[$i]["details"])
            {
                $obj = $pv_db_obj[$i]["details"];

                $slide_id = $obj[0]->get_slide_id();
                //image
                if($pv_db_obj[$i]["config"]->get_banner_type() != "F" && $obj[0]->get_image_id() != "" && file_exists(GRAPHIC_PH.$obj[0]->get_graphic_location().$obj[0]->get_graphic_file()))
                {
                    $image_html = '';
                    $num = 0;
                    //foreach slide
                    for($num = 0; $num <7; $num++)
                    {
                        if($obj[$num])
                        {
                            $slide_id = $obj[$num]->get_slide_id();

                            $link[$i][$slide_id] = $obj[$num]->get_link();

                            if($obj[$num]->get_time_interval())
                            {
                                $time[$i] = $obj[$num]->get_time_interval();
                            }
                            $priority_val[$i][$slide_id] = $obj[$num]->get_priority();
                            $link_type[$i][$slide_id] = $obj[$num]->get_link_type();
                            $status[$i][$slide_id] = $obj[$num]->get_status();
                            $image_html .=
                                        "
        <a href=\"javascript:changeUpdate('".$i."','".$obj[$num]->get_slide_id()."'); showBannerStyle('".$banner_type[$i]."','".$i."','".$obj[$num]->get_slide_id()."');\">
            <img id=\"bannerimage[$i][$num]\" style=\"display:none;\"src=\"".base_url().GRAPHIC_PH.$obj[$num]->get_graphic_location().$obj[$num]->get_graphic_file()."?".$obj[$num]->get_modify_on()."\" border=\"0\" height=\"".$height[$i]."\" width=\"".$width[$i]."\">
        </a>";
                        }
                        else
                        {
                            $link[$i][$num] = "";
                            if($time[0])
                            {
                                $time[$i] = $time[0];
                            }
                            else
                            {
                                $time[$i] = "";
                            }
                            $priority_val[$i][$num] = "";
                            $link_type[$i][$num] = "";
                            $status[$i][$num] = "1";
                            $image_html .=
                                        "
        <a href=\"javascript:changeUpdate('".$i."','".$num."'); showBannerStyle('".$banner_type[$i]."','".$i."','".$num."');\">
            <img id=\"bannerimage[$i][$num]\" style=\"display:none;\"src=\"".base_url()."images/adbanner/preview/blank_".$display_id."_".$i.".jpg\" border=\"0\" height=\"".$pv_db_obj[$i]["config"]->get_height()."\" width=\"".$pv_db_obj[$i]["config"]->get_width()."\">
        </a>";
                        }
                    }
                    ${"content".$i} =
                    "
<tr>
    <td align=\"center\">
    <div id=\"border[$i]\" style=\"width:".$width[$i]."px;height:".$height[$i]."px\" >".$image_html."
    </div>
        <div class=\"pagination\" id=\"rowingpagination[$i]\" style=\"text-align:right;width:".$pv_db_obj[$i]["config"]->get_width()."\">
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','0');changeUpdate('".$i."','0', '1');\">1</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','1');changeUpdate('".$i."','1', '1');\">2</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','2');changeUpdate('".$i."','2', '1');\">3</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','3');changeUpdate('".$i."','3', '1');\">4</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','4');changeUpdate('".$i."','4', '1');\">5</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','5');changeUpdate('".$i."','5', '1');\">6</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','6');changeUpdate('".$i."','6', '1');\">7</a></b>
        </div>
    </td>
</tr>
                    ";
                }
                elseif($pv_db_obj[$i]["config"]->get_banner_type() == "F" && $obj[0]->get_flash_id() != "" && file_exists(GRAPHIC_PH.$obj[0]->get_graphic_location().$obj[0]->get_graphic_file()))
                {
                    $priority_val[$i][$slide_id] = $obj[0]->get_priority();
                    $link_type[$i][$slide_id] = $obj[0]->get_link_type();
                    $status[$i][$slide_id] = $obj[0]->get_status();
                    ${"content".$i} = "
<tr>
    <td align=\"center\">
        <div  id=\"border[$i]\" style=\"width:".$obj[0]->get_width()."px;height:".$obj[0]->get_height()."px\" >
            <button id = \"bannerimage".$i.$slide_id."\"class='flashbutton' onclick=\"javascript:changeUpdate('".$i."','".$slide_id."'); showBannerStyle('".$banner_type[$i]."','".$i."','".$slide_id."');\" style='width:".$obj[0]->get_width().";height:".$obj[0]->get_height()."'>
                <object width='".$obj[0]->get_width()."' height='".$obj[0]->get_height()."' classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0'>
                <param name='movie' value='".base_url().GRAPHIC_PH.$obj[0]->get_graphic_location().$obj[0]->get_graphic_file()."?".$obj[0]->get_modify_on()."'>
                <param name='wmode' value='opaque'>
                    <embed src='".base_url().GRAPHIC_PH.$obj[0]->get_graphic_location().$obj[0]->get_graphic_file()."?".$obj[0]->get_modify_on()."' width='".$obj[0]->get_width()."' height='".$obj[0]->get_height()."' wmode='opaque' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'>
                    </embed>
                </object>
            </button>
        </div>
        <div class=\"pagination\" id=\"rowingpagination[$i]\" style=\"text-align:right;width:".$obj[0]->get_width()."\">
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','0');changeUpdate('".$i."','0', '1');\">1</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','1');changeUpdate('".$i."','1', '1');\">2</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','2');changeUpdate('".$i."','2', '1');\">3</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','3');changeUpdate('".$i."','3', '1');\">4</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','4');changeUpdate('".$i."','4', '1');\">5</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','5');changeUpdate('".$i."','5', '1');\">6</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','6');changeUpdate('".$i."','6', '1');\">7</a></b>
        </div>
    </td>
</tr>
                    ";
                }
                else
                {
                    $image_html = '';
                    $num = 0;
                    //foreach slide
                    for($num = 0; $num <4; $num++)
                    {
                            $image_html .=
                                        "
        <a href=\"javascript:changeUpdate('".$i."','".$num."'); showBannerStyle('".$banner_type[$i]."','".$i."','".$num."');\">
            <img id=\"bannerimage[$i][$num]\" style=\"display:none;\"  src=\"".base_url()."images/adbanner/preview/blank_".$display_id."_".$i.".jpg\" border=\"0\" height=\"".$pv_db_obj[$i]["config"]->get_height()."\" width=\"".$pv_db_obj[$i]["config"]->get_width()."\">
        </a>";
                    }

                    ${"content".$i} =
                    "
<tr>
    <td align=\"center\">
        <div id=\"border[$i]\" style=\"width:".$obj[0]->get_width()."px;height:".$obj[0]->get_height()."px\" >".$image_html."
        </div>
        <div class=\"pagination\" id=\"rowingpagination[$i]\" style=\"text-align:right;width:".$obj[0]->get_width()."\">
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','0');changeUpdate('".$i."','0', '1');\">1</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','1');changeUpdate('".$i."','1', '1');\">2</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','2');changeUpdate('".$i."','2', '1');\">3</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','3');changeUpdate('".$i."','3', '1');\">4</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','4');changeUpdate('".$i."','4', '1');\">5</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','5');changeUpdate('".$i."','5', '1');\">6</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','6');changeUpdate('".$i."','6', '1');\">7</a></b>
        </div>
    </td>
</tr>
                    ";
                }
                $link[$i][$obj[0]->get_slide_id()] = $obj[0]->get_link();
                $time[$i] = $obj[0]->get_time_interval();
                $priority_val[$i][$obj[0]->get_slide_id()] = $obj[0]->get_priority();
                $link_type[$i][$obj[0]->get_slide_id()] = $obj[0]->get_link_type();
            }
            else
            {

                    $image_html = '';
                    $num = 0;
                    //foreach slide
                    for($num = 0; $num <4; $num++)
                    {
                        $link[$i][$num] = "";
                        $link_type[$i][$num] = "E";
                        $status[$i][$num] = "1";
                            $image_html .=
                                        "
        <a href=\"javascript:changeUpdate('".$i."','".$num."'); showBannerStyle('".$banner_type[$i]."','".$i."','".$num."');\">
            <img id=\"bannerimage[$i][$num]\" style=\"display:none;\" src=\"".base_url()."images/adbanner/preview/blank_".$display_id."_".$i.".jpg\" border=\"0\" height=\"".$pv_db_obj[$i]["config"]->get_height()."\" width=\"".$pv_db_obj[$i]["config"]->get_width()."\">
        </a>";
                    }
                ${"content".$i} =
                    "
<tr>
    <td align=\"center\">
        <div  id=\"border[$i]\" style=\"width:".$pv_db_obj[$i]["config"]->get_width()."px;height:".$pv_db_obj[$i]["config"]->get_height()."px\" >".$image_html."
        </div>
        <div class=\"pagination\" id=\"rowingpagination[$i]\" style=\"text-align:right;width:".$pv_db_obj[$i]["config"]->get_width()."\">
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','0', '1');\">1</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','1', '1');\">2</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','2', '1');\">3</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','3', '1');\">4</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','4', '1');\">5</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','5', '1');\">6</a></b>
            <b style=\"color:#000000;\"><a href=\"javascript:showBannerStyle('R','".$i."','6', '1');\">7</a></b>
        </div>
    </td>
</tr>
                    ";
            }
        }
/*
$pobj = $pimage_list[$i];

        if(!empty($pobj))
        {
                ${"pcontent".$i} = "<img src=\"".base_url()."images/adbanner/preview/blank_".$display_id."_".$i.".jpg\" border=\"0\" height=\"".$this->config->item('banner_height_'.$display_id.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$display_id.'_'.$i)."\">";
                if($pobj->get_image_file() != "" && file_exists($this->config->item('banner_publish_path').$pobj->get_image_file()))
                {
                    ${"pcontent".$i} = "<img src=\"".$this->config->item('banner_public_path').$pobj->get_image_file()."?".$obj->get_modify_on()."\" border=\"0\" height=\"".$this->config->item('banner_height_'.$display_id.'_'.$i)."\" width=\"".$this->config->item('banner_width_'.$display_id.'_'.$i)."\">";

                    if($pobj->get_link() != "")
                    {

                        ${"pcontent".$i} = "<a href=\"".$pobj->get_link()."\" target=\"blank\">".${"pcontent".$i}."</a>";
                    }
                }
                if($pobj->get_flash_file() != "" && file_exists($this->config->item('banner_publish_path').$pobj->get_image_file()))
                {
                    ${"pcontent".$i} = "<button class='flashbutton' onclick=\"javascript:changeUpdate('".$i."');\" style='width:".$this->config->item('banner_width_'.$display_id.'_'.$i).";height:".$this->config->item('banner_height_'.$display_id.'_'.$i)."'><object width='".$this->config->item('banner_width_'.$display_id.'_'.$i)."' height='".$this->config->item('banner_height_'.$display_id.'_'.$i)."' classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0'><param name='movie' value='".$this->config->item('banner_public_path').$pobj->get_flash_file()."?".$pobj->get_modify_on()."'><param name='wmode' value='opaque'><embed src='".$this->config->item('banner_public_path').$pobj->get_flash_file()."?".$obj->get_modify_on()."' width='".$this->config->item('banner_width_'.$display_id.'_'.$i)."' height='".$this->config->item('banner_height_'.$display_id.'_'.$i)."' wmode='opaque' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed></object></button>";
                }
                $link[$i] = $obj->get_link();
                $link_type[$i] = $obj->get_link_type();
                $height[$i] = $dbc_obj[$i]->get_height();
                $width[$i] = $dbc_obj[$i]->get_width();
        }
        else
        {
                ${"pcontent".$i} = "<img src=\"".base_url()."images/adbanner/preview/blank_".$display_id."_".$i.".jpg\" border=\"0\" height=\"".$height[$i] = $pv_db_obj[$i]["config"]->get_height()."\" width=\"".$height[$i] = $pv_db_obj[$i]["config"]->get_width()."\">";
                $link[$i] = "";
                $link_type[$i] = "E";
                $banner_type[$i] = $pv_db_obj[$i]["config"]->get_banner_type()?$pv_db_obj[$i]["config"]->get_banner_type():"I";
                $height[$i] = $pv_db_obj[$i]["config"]->get_height();
                $width[$i] = $pv_db_obj[$i]["config"]->get_width();
        }
        */
    }

    include_once APPPATH."views/marketing/display_banner/template_".$display_id.".php";

    if($this->input->post('position_id') == "")
    {
        $position_id = 1;
        $slide_id = 0;
    }
    else
    {
        $position_id = $this->input->post('position_id');
    }
?>
<script language="javascript">
<!--
link = new Array();
link_type = new Array();
banner_type = new Array();
height = new Array();
width = new Array();
time = new Array();
priority_val = new Array();
status = new Array();
<?php
    foreach($link as $key=>$val)
    {
?>
        link[<?=$key?>] = new Array();
        status[<?=$key?>] = new Array();
        link_type[<?=$key?>] = new Array();
        priority_val[<?=$key?>] = new Array();
<?php
        if ($val)
        {
            foreach ($val as $slide_id=>$data)
            {
?>
                link[<?=$key?>][<?=$slide_id?>] = "<?=$data?>";
                status[<?=$key?>][<?=$slide_id?>] = "<?=$status[$key][$slide_id]?>";
                link_type[<?=$key?>][<?=$slide_id?>] = "<?=$link_type[$key][$slide_id]?>";
                priority_val[<?=$key?>][<?=$slide_id?>] = "<?=$priority_val[$key][$slide_id]?>";
<?php
            }
        }
?>
    time[<?=$key?>] = "<?=$time[$key]?>";
    banner_type[<?=$key?>] = "<?=$banner_type[$key]?>";
    height[<?=$key?>] = "<?=$height[$key]?>";
    width[<?=$key?>] = "<?=$width[$key]?>";
<?php
    }
?>
-->
</script>
<form name="fm" action="<?=$_SERVER["PHP_SELF"]?>?catid=<?=$catid?>" method="POST" onSubmit="return CheckForm(this)" enctype="multipart/form-data">
<table width="100%" cellpadding="0" cellspacing="1" class="tb_list">
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["banner_type"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;
        <select name="banner_type" id="banner_type" onChange="showBannerStyle(this.value, document.getElementById('position_id').value,0);">
            <option value="I"><?=$lang["image"]?></option>
            <option value="R"><?=$lang["rowing_banner"]?></option>
        </select>
    </td>
</tr>
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["height"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp<input type="text" name="height" id="height" value="" class="read" readonly></td>
</tr>
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["width"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;<input type="text" name="width" id="width" value="" class="read" readonly></td>
</tr>
<!--
<tr>
    <td width="15%" class="field" align="right" height="20" style="padding-right:8px;"><?=$lang["banner_number"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;<span id="typeval"><?=$position_id?></span></td>
</tr>
-->
<tr id="slide_id">
    <td width="15%" class="field" align="right" height="20" style="padding-right:8px;"><?=$lang["slide_id"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;<span id="slideval"><?=$slide_id?></span></td>
</tr>
<tr id="flash">
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["banner_flash"]?><br><?=$lang["size_limit"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;<input type="file" name="flash" class="input" accept="swf" onChange="checkAccept(this);"></td>
</tr>
<tr id="image">
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["banner_image"]?><br><?=$lang["image_limit"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;<input type="file" name="image" class="input" accept="gif,jpg,png" onChange="checkAccept(this);"></td>
</tr>
<tr id="priority">
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["priority"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp<input type="text" name="priority" id="priority_val" value=""></td>
</tr>
<tr id="time_interval">
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["time_interval"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp<input type="text" name="time_interval" id="time" value=""></td>
</tr>
<tr id="url">
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["link"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;<input type="text" name="link" class="input" id="link" value=""><input type="hidden" name="slide_id" value="0"></td>
</tr>
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["link_type"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;
        <select onchange="showEditLytebox(this.value)" id="link_type" name="link_type" >
            <option value="E"><?=$lang["external"]?></option>
            <option value="I"><?=$lang["internal"]?></option>
            <?php
            if($show_lightbox[$display_id])
            {
            ?>
                <option id="select_lb" value="L"><?=$lang["lightbox"]?></option>
            <?php
            }
            ?>
        </select>
    </td>
</tr>
<!--
<tr id="lb_image">
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["lytebox_image"]?><br><?=$lang["image_limit"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;<input type="file" name="lb_image" class="input" accept="gif,jpg,png" onChange="checkAccept(this);"></td>
</tr>
<tr id="lb_content">
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["content"]?></td>
    <td><textarea class="input_format" name="content" rows="20" style="width:100%; resize:none;"><?='<a herf="www.google.com"><img src="'.base_url().'/images/skype_email_free_min_offer.png" /></a>'?></textarea></td>
</tr>
-->
<tr>
    <td width="15%" class="field" align="right" style="padding-right:8px;"><?=$lang["status"]?></td>
    <td width="85%" class="value">&nbsp;&nbsp;
        <select name="status">
            <option value="0"><?=$lang["inactive"]?></option>
            <option value="1"><?=$lang["active"]?></option>
        </select>
    </td>
</tr>
<tr>
    <td width="15%" class="field">&nbsp;</td>
    <td width="85%" class="value">
        &nbsp;&nbsp;<input type="button" value="<?=$lang["update_banner"]?>" onClick="if(CheckForm(this.form)) this.form.submit()">
        &nbsp;&nbsp;<input type="button" value="<?=$lang["copy_button"]?>" onclick="Redirect('<?=site_url('marketing/display_banner/to_publish/'.$catid.'/'.'?'.current_url())?>')">
    </td>
</tr>
</table>
<?php
}
?>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="template" value="<?=$display_id?>">
<input type="hidden" name="position_id" id="position_id" value="">
</form>
</div>
<script language="javascript">
<!--
if(document.getElementById("position_id").value == "")
{
    changeUpdate("<?=$position_id?>", '0');
}
-->
Init(document.fm.elements["banner_type"].value);
function SaveChange()
{
    if ((confirm("<?=$lang["save_change"]?>")))
    {
        gotoPage('<?=site_url('marketing/display_banner/disable/'.$id.'/?'.current_url())?>');
        return true;
    }
    return false;
}
</script>
<?=$notice["js"]?>
</body>
</html>