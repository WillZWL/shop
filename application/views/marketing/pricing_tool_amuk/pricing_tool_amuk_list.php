<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript">
<!--
function showView(sku)
{
    var x ='<?=base_url()?>marketing/pricing_tool_amuk/view/'+sku;
    parent.document.getElementById('pview').src=x;
}
-->
</script>
</head>
<body class="frame_left" style="width:auto;">
<div id="main" style="width:auto;">
<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <col width="50"><col>
    <tr class="header">
        <td><a href="#" onClick="SortCol(document.fm, 'sku', '<?=$xsort["sku"]?>')"><?=$lang["sku"]?> <?=$sortimg["sku"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'name', '<?=$xsort["name"]?>')"><?=$lang["name"]?> <?=$sortimg["name"]?></a></td>
    </tr>
<?php
    $i=0;
    if ($objlist)
    {
        foreach ($objlist as $obj)
        {
?>

    <tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick="showView('<?=urlencode($obj->get_sku())?>')">
        <td nowrap style="white-space:nowrap;"><?=$obj->get_sku()?></td>
        <td><?=$obj->get_name()?></td>
    </tr>
<?php
            $i++;
        }
    }
?>
</table>
<input type="hidden" name="sku" value='<?=htmlspecialchars($this->input->get("sku"))?>'>
<input type="hidden" name="name" value='<?=htmlspecialchars($this->input->get("name"))?>'>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?=$this->pagination_service->create_links_with_style()?>
</div>
</body>
</html>