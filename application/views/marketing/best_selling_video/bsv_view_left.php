<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
<form name="fm" method="get" action="<?=base_url()?>marketing/best_selling_video/view_left/">
<input type="hidden" name="cat" value="<?=$this->input->get('cat')?>">
<input type="hidden" name="level" value="<?=$this->input->get('level')?>">
<input type="hidden" name="platform" value="<?=$this->input->get('platform')?>">
<input type="hidden" name="type" value="<?=$this->input->get('type')?>">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<col width="5"><col width="40"><col width="120"><col width="35">
<tr>
    <td>&nbsp;</td>
    <td colspan="3" height="40" valign="middle" align="left"><b style="font-size:14px; color:#000000;"><?=$lang["product_search"]?></b></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td align="left"><?=$lang["sku"]?></td>
    <td><input type="text" name="sku" value="<?=$this->input->get('sku')?>" class="input"></td>
    <td rowspan="2"><input type="submit" style="background: rgb(204, 204, 204) url('<?=base_url()?>/images/find.gif') no-repeat scroll center center; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; width: 30px; height: 25px;" class="search_button" value=""/></td>
</tr>
</table>
<?php
    if($search)
    {
?>
<script language="javascript">
<!--
function addToRight(sku,name,ref,lang)
{
    parent.frames['right'].addRow(sku,name,ref,lang);

}
-->
</script>

<hr>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <col width="30%"><col width="70%">
    <tr class="header">
        <td><a href="#" onClick="SortCol(document.fm, 'sku', '<?=$xsort["sku"]?>')"><?=$lang["sku"]?> <?=$sortimg["sku"]?></a></td>
        <td><?=$lang["video"]?></td>
    </tr>
<?php
    $i=0;
    if ($objlist)
    {
        foreach ($objlist as $obj)
        {
?>

    <tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick="addToRight('<?=$obj->get_sku()?>','<?=$obj->get_prod_name()?>','<?=$obj->get_ref_id()?>','<?=$obj->get_lang_id()?>')">
        <td nowrap style="white-space:nowrap;"><?=$obj->get_sku()?></td>
        <td >
            <object width="195" height="135"><param name="movie" value="http://www.youtube.com/v/<?=$obj->get_ref_id()?>&amp;hl=en_US&amp;fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/<?=$obj->get_ref_id()?>&amp;hl=en_US&amp;fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="195" height="135"></embed></object>
        </td>
    </tr>
<?php
            $i++;
        }
    }
?>
</table>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
<?=$this->pagination_service->create_links_with_style()?>
<?php
    }
?>
</form>
<br><br>
<div style="padding-top:10px; padding-left:5px; width:100%; text-align:left;">
<?=$lang["notes"]?>
</div>
</div>
</body>
</html>
