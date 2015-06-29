<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
    $ar_status = array($lang["inactive"], $lang["active"]);
    $ar_warranty = array($lang["no"], $lang["yes"]);
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="100" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/upselling/')?>')"></td>
        <td width="100" align="right" class="title"><input type="button" value="<?=$lang["group_list_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/upselling/group_list')?>')"></td>
        <td width="100" align="right" class="title"><input type="button" value="<?=$lang["add_group_button"]?>" class="button" onclick="Redirect('<?=site_url('marketing/upselling/add_group')?>')"></td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>

<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
    </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <col width="20"><col><col width="70"><col width="80">
    <tr class="header">
        <td height="20">
            <img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
        </td>
        <td><a href="#" onClick="SortCol(document.fm, 'group_name', '<?=$xsort["group_name"]?>')"><?=$lang["group_name"]?> <?=$sortimg["group_name"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'warranty', '<?=$xsort["warranty"]?>')"><?=$lang["warranty"]?> <?=$sortimg["warranty"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'status', '<?=$xsort["status"]?>')"><?=$lang["status"]?> <?=$sortimg["status"]?></a></td>
        <td></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td><input name="name" class="input" value="<?=htmlspecialchars($this->input->get("name"))?>"></td>
        <td>
            <?php
            if ($this->input->get("warranty") != "")
            {
                $warranty_selected[$this->input->get("warranty")] = "SELECTED";
            }
            ?>
            <select name="warranty" class="input">
                <option value="">
                <option value="0" <?=$warranty_selected[0]?>><?=$ar_warranty[0]?></option>
                <option value="1" <?=$warranty_selected[1]?>><?=$ar_warranty[1]?></option>
            </select>
        </td>
        <td>
            <?php
                if ($this->input->get("status") != "")
                {
                    $selected[$this->input->get("status")] = "SELECTED";
                }
            ?>
            <select name="status" class="input">
                <option value="">
                <option value="0" <?=$selected[0]?>><?=$ar_status[0]?>
                <option value="1" <?=$selected[1]?>><?=$ar_status[1]?>
            </select>
        </td>
        <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
<?php
    $i=0;
    if ($objlist)
    {
        foreach ($objlist as $obj)
        {
?>

    <tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick="Redirect('<?=site_url('marketing/upselling/view_group/' . $obj->get_group_id())?>')">
        <td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
        <td><?=$obj->get_group_name()?></td>
        <td><?=$ar_warranty[$obj->get_warranty()]?></td>
        <td><?=$ar_status[$obj->get_status()]?></td>
        <td></td>
    </tr>

<?php
            $i++;
        }
    }
?>
</table>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>