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
            <select name="country_id" onChange="Redirect('<?=base_url()?>mastercfg/custom_class/sub_cat/'+this.value)">
                <option value="">
            <?php
                if ($countrylist)
                {
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
    <col width="20"><col width="200"><col><col width="300"><col width="130">
<form name="fm" method="get" onSubmit="return CheckForm(this)">
    <tr class="header">
        <td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td><a href="#" onClick="SortCol(document.fm, 'cat_name', '<?=$xsort["cat_name"]?>')"><?=$lang["cat_name"]?> <?=$sortimg["cat_name"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'sub_cat_name', '<?=$xsort["sub_cat_name"]?>')"><?=$lang["sub_cat_name"]?> <?=$sortimg["sub_cat_name"]?></a></td>
        <td><a href="#" onClick="SortCol(document.fm, 'code', '<?=$xsort["code"]?>')"><?=$lang["mapping"]?> <?=$sortimg["code"]?></a></td>
        <!--<td><a href="#" onClick="SortCol(document.fm, 'description', '<?=$xsort["description"]?>')"><?=$lang["description"]?> <?=$sortimg["description"]?></a></td>-->
        <td></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td><input name="cat_name" class="input" value="<?=htmlspecialchars($this->input->get("cat_name"))?>"></td>
        <td><input name="sub_cat_name" class="input" value="<?=htmlspecialchars($this->input->get("sub_cat_name"))?>"></td>
        <td><!--<input name="code" class="input" value="<?=htmlspecialchars($this->input->get("code"))?>">--></td>
        <!--<td><input name="description" class="input" value="<?=htmlspecialchars($this->input->get("description"))?>"></td>-->
        <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<?php
    $i=0;
    if (!empty($ccmlist))
    {
        foreach ($ccmlist as $ccm_obj)
        {
            $is_edit = ($cmd == "edit" && $sub_cat_id == $ccm_obj->get_sub_cat_id());
            $cur_code = $ccm_obj->get_code();
?>

    <tr class="row<?=$i%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" <?if (!($is_edit)){?>onClick="Redirect('<?=site_url('mastercfg/custom_class/sub_cat/'.$country_id.'/'.$ccm_obj->get_sub_cat_id())?>/?<?=$_SERVER['QUERY_STRING']?>')"<?}?>>
        <td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$ccm_obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$ccm_obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$ccm_obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$ccm_obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$ccm_obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$ccm_obj->get_modify_by()?>'></td>
        <td><?=$ccm_obj->get_cat_name()?></td>
        <td><?=$ccm_obj->get_sub_cat_name()?></td>
        <?php
            if ($is_edit)
            {
        ?>
        <form name="fm_edit" action="<?=base_url()?>mastercfg/custom_class/edit_sub_cat/<?=$ccm_obj->get_sub_cat_id()?>/?<?=$_SERVER['QUERY_STRING']?>" method="post" onSubmit="return CheckForm(this)">
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="cmd" value="edit">
            <input type="hidden" name="sub_cat_id" value="<?=$ccm_obj->get_sub_cat_id()?>">
            <input type="hidden" name="country_id" value="<?=$country_id?>">
            <input type="hidden" id="custom_class_id" name="custom_class_id"  value="">
            <?php
                if ($this->input->post("posted"))
                {
            ?>
                <td><input name="code" class="input" value="<?=$this->input->post("code")?>" notEmpty maxLen=20></td>
                <!--<td><input name="description" class="input" value="<?=$this->input->post("description")?>" maxLen=255></td>-->
            <?php
                }
                else
                {
            ?>
                <td>
                    <select name="custom_class_id" dname="HS Code" notEmpty>
                        <option value="">
                    <?php
                        if ($custom_class_list)
                        {
                            $selected[$cur_code] = "SELECTED";
                            foreach ($custom_class_list as $cc)
                            {
                    ?>
                            <option value="<?=$cc->get_id()?>" <?=$selected[$cc->get_code()]?>><?=$cc->get_code() . " - " . $cc->get_description()?>
                    <?php
                            }
                        }
                    ?>
                    </select>
                </td>
                <!--<td><?=$ccm_obj->get_description()?></td>-->
            <?php
                }
            ?>
            <td align="center"><input type="submit" value="<?=$lang["update"]?>"> &nbsp; <input type="button" value="<?=$lang["back"]?>" onClick="Redirect('<?=site_url('mastercfg/custom_class/sub_cat/'.$country_id)?>?<?=$_SERVER['QUERY_STRING']?>')"></td>
        </form>
        <?php
            }
            else
            {
        ?>
        <td><?=$ccm_obj->get_code()?($ccm_obj->get_code() . " - " . $ccm_obj->get_description()):""?></td>
        <!--<td><?=$ccm_obj->get_description()?></td>-->
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