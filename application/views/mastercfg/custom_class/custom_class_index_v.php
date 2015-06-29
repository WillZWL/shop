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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title"><input type="button" value="<?=$lang["default_mapping"]?>" class="button" onclick="Redirect('<?=site_url('mastercfg/custom_class/sub_cat/'.$country_id.'/')?>')"><input type="button" value="<?=$lang["edit_specific_sku"]?>" class="button" onclick="Redirect('<?=site_url('mastercfg/custom_class/sku/'.$country_id.'/')?>')"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('mastercfg/custom_class/index/'.$country_id.'/')?>')"></td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?><br>
            <?=$lang["country"]?>:
            <select name="country_id" onChange="Redirect('<?=base_url()?>mastercfg/custom_class/index/'+this.value)">
                <option value="">
            <?php
                if ($countrylist){
                    $selected[$country_id] = "SELECTED";
                    foreach ($countrylist as $country)
                    {
            ?>
                    <option value="<?=$country->get_id()?>" <?=$selected[$country->get_id()]?>><?=$country->get_name()?>
            <?php
                    }
                }
            ?>
            </select>
        </td>
    </tr>
</table>
<?php
    if ($country_id)
    {
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <col width="20"><col width="120"><col width="200"><col><col width="80"><col width="130">
    <tr class="add_header">
        <td height="20"></td>
        <td><?=$lang["id"]?></td>
        <td><?=$lang["code"]?></td>
        <td><?=$lang["description"]?></td>
        <td><?=$lang["duty_pcent"]?></td>
        <td></td>
    </tr>
<form name="fm_add" action="<?=base_url()?>mastercfg/custom_class/add/?<?=$_SERVER['QUERY_STRING']?>" method="post" onSubmit="return CheckForm(this)">
    <tr class="add_row">
        <td></td>
        <?php
            if ($cmd == "add")
            {
        ?>
        <td></td>
        <td><input name="code" class="input" value="<?=$this->input->post("code")?>" notEmpty maxLen=20></td>
        <td><input name="description" class="input" value="<?=$this->input->post("description")?>" maxLen=255></td>
        <td><input name="duty_pcent" class="input" value="<?=$this->input->post("duty_pcent")?>" notEmpty isNumber min=0></td>
        <?php
            }
            else
            {
        ?>
        <td></td>
        <td><input name="code" class="input" notEmpty maxLen=20></td>
        <td><input name="description" class="input" maxLen=255></td>
        <td><input name="duty_pcent" class="input" notEmpty isNumber min=0></td>
        <?php
            }
        ?>
        <td align="center"><input type="submit" value="<?=$lang["add"]?>"></td>
    </tr>
    <tr class="empty_row">
        <td colspan="7"><hr></hr></td>
    </tr>
    <input type="hidden" name="posted" value="1">
    <input type="hidden" name="cmd" value="add">
    <input type="hidden" name="country_id" value="<?=$country_id?>">
</form>
<form name="fm" method="get" onSubmit="return CheckForm(this)">
    <tr class="header">
        <td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td><a href="#" onClick="SortCol(document.fm, 'id', '<?=$xsort["id"]?>')"><?=$lang["id"]?> <?=$sortimg["id"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'code', '<?=$xsort["code"]?>')"><?=$lang["code"]?> <?=$sortimg["code"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'description', '<?=$xsort["description"]?>')"><?=$lang["description"]?> <?=$sortimg["description"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'duty_pcent', '<?=$xsort["duty_pcent"]?>')"><?=$lang["duty_pcent"]?> <?=$sortimg["duty_pcent"]?></a></td>
        <td></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td><input name="id" class="input" value="<?=htmlspecialchars($this->input->get("id"))?>"></td>
        <td><input name="code" class="input" value="<?=htmlspecialchars($this->input->get("code"))?>"></td>
        <td><input name="description" class="input" value="<?=htmlspecialchars($this->input->get("description"))?>"></td>
        <td><input name="duty_pcent" class="input" value="<?=htmlspecialchars($this->input->get("duty_pcent"))?>"></td>
        <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?php
    $i=0;
    if (!empty($cclist))
    {
        foreach ($cclist as $cc)
        {
            $is_edit = ($cmd == "edit" && $cc_id == $cc->get_id());
?>

    <tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" <?if (!($is_edit)){?>onClick="Redirect('<?=site_url('mastercfg/custom_class/index/'.$country_id.'/'.$cc->get_id())?>/?<?=$_SERVER['QUERY_STRING']?>')"<?}?>>
        <td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$cc->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$cc->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$cc->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$cc->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$cc->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$cc->get_modify_by()?>'></td>
        <td><?=$cc->get_id()?></td>
        <?php
            if ($is_edit)
            {
        ?>
        <form name="fm_edit" action="<?=base_url()?>mastercfg/custom_class/edit/<?=$cc->get_id()?>/?<?=$_SERVER['QUERY_STRING']?>" method="post" onSubmit="return CheckForm(this)">
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="cmd" value="edit">
            <input type="hidden" name="id" value="<?=$cc->get_id()?>">
            <input type="hidden" name="country_id" value="<?=$country_id?>">
            <?php
                if ($this->input->post("posted"))
                {
            ?>
                <td><input name="code" class="input" value="<?=$this->input->post("code")?>" notEmpty maxLen=20></td>
                <td><input name="description" class="input" value="<?=$this->input->post("description")?>" maxLen=255></td>
                <td><input name="duty_pcent" class="input" value="<?=$this->input->post("duty_pcent")?>" notEmpty isNumber min=0></td>
            <?php
                }
                else
                {
            ?>
                <td><input name="code" class="input" value="<?=$cc->get_code()?>" notEmpty maxLen=20></td>
                <td><input name="description" class="input" value="<?=$cc->get_description()?>" maxLen=255></td>
                <td><input name="duty_pcent" class="input" value="<?=$cc->get_duty_pcent()?>" notEmpty isNumber min=0></td>
            <?php
                }
            ?>
            <td align="center"><input type="submit" value="<?=$lang["update"]?>"> &nbsp; <input type="button" value="<?=$lang["back"]?>" onClick="Redirect('<?=site_url('mastercfg/custom_class/index/'.$country_id)?>?<?=$_SERVER['QUERY_STRING']?>')"></td>
        </form>
        <?php
            }
            else
            {
        ?>
        <td><?=$cc->get_code()?></td>
        <td><?=$cc->get_description()?></td>
        <td><?=$cc->get_duty_pcent()?></td>
        <td>&nbsp;</td>
        <?php
            }
        ?>
    </tr>
<?php
            $i++;
        }
    }
?>
<?php
    }
?>
</table>
<?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
</body>
</html>