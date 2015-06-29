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
<?php $status_arr = array("A"=>$lang["active"],"I"=>$lang["inactive"]);?>
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title"><input type="button" value="<?=$lang["show_all_cc"]?>" class="button" onclick="Redirect('<?=site_url('mastercfg/version/')?>')"></td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="tb_list" width="100%">
    <col width="30"><col width="100"><col><col width="100"><col width="300">
    <tr class="add_header">
        <td>&nbsp;</td>
        <td><?=$lang["code"]?></td>
        <td><?=$lang["name"]?></td>
        <td><?=$lang["status"]?></td>
        <td>&nbsp;</td>
    </tr>
    <form name="fm_add" method="post" onSubmit="return CheckForm(this)">
    <tr class="add_row">
        <td>&nbsp;</td>
        <td><input name="id" class="input" notEmpty maxLen=2 maxlength="2"></td>
        <td><input name="desc" class="input" notEmpty></td>
        <td><select name="status" class="input">
        <?php
            foreach($status_arr as $key=>$value)
            {
        ?><option value="<?=$key?>"><?=$value?></option><?php
            }
        ?></select></td>
        <td align="center"><input type="submit" value="Add"></td>
    </tr>
    <tr class="empty_row">
        <td colspan="6"><hr></hr></td>
    </tr>
    <input type="hidden" name="posted" value="1">
    <input type="hidden" name="action" value="add">
    </form>
    <form name="fm" method="get">
    <tr class="header">
        <td height="20"><img src="<?=base_url().'/images/expand.png'?>" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td><a href="#" onClick="SortCol(document.fm, 'id', '<?=$xsort["id"]?>')"><?=$lang["code"]?></a> <?=$sortimg["id"]?></td>
        <td><a href="#" onClick="SortCol(document.fm, 'desc', '<?=$xsort["desc"]?>')"><?=$lang["name"]?></a> <?=$sortimg["desc"]?></td>
        <td><a href="#" onClick="SortCol(document.fm, 'status', '<?=$xsort["status"]?>')"><?=$lang["status"]?></a> <?=$sortimg["status"]?></td>
        <td></td>
    </tr>
    <tr class="search" id="tr_search" >
        <td></td>
        <td><input name="id" class="input" value="<?=$this->input->get("id")?>" maxlength="2"></td>
        <td><input name="desc" class="input" value="<?=$this->input->get("desc")?>"></td>
        <td><select name="status" class="input"><option value=""></option>
        <?php
            foreach($status_arr as $key=>$value)
            {
        ?><option value="<?=$key?>" <?= $key === $this->input->get("status")?"SELECTED":""?>><?=$value?></option><?php
            }
        ?></select></td>
        <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()."images/find.gif"?>') no-repeat;"></td>
    </tr>
    <input type="hidden" name="sort" value="<?=$this->input->get("sort")?>">
    <input type="hidden" name="order" value="<?=$this->input->get("order")?>">
    </form>
<?php
    $i=0;
    if($vlist)
    {
        foreach($vlist as $obj)
        {
            if($obj->get_id() != $eid || $edit != 1)
            {
?>
    <tr class="row<?=$i++%2?> pointer" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')" onClick='Redirect("<?=base_url()."mastercfg/version/index/1/".$obj->get_id()."/?".$_SERVER["QUERY_STRING"]?>");'>
        <td><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
        <td><?=$obj->get_id()?></td>
        <td><?=$obj->get_desc()?></td>
        <td><?=$status_arr[$obj->get_status()]?></td>
        <td></td>
    </tr>
<?php
            }
            else
            {
?>
    <form name="fm_edit" action="<?=base_url()."mastercfg/version/?".$_SERVER["QUERY_STRING"]?>"method="post" onClick="checkForm(this)";>
    <tr class="row<?=$i++%2?>">
        <td><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
        <td><input name="id" type="text" value="<?=$obj->get_id()?>" class="input"></td>
        <td><input name="desc" type="text" value="<?=$obj->get_desc()?>" class="input"></td>
        <td><select name="status" class="input"><?php
        foreach($status_arr as $key=>$value)
        {
        ?><option value="<?=$key?>" <?=$key == $obj->get_status()?"SELECTED":""?>><?=$value?></option><?php
        }
        ?></select></td>
        <td><input name="posted" type="hidden" value="1"><input name="action" type="hidden" value="edit"><input type="button" value="<?=$lang["update"]?>" onClick="if(CheckForm(this.form)) document.fm_edit.submit();" class="button">&nbsp;&nbsp;<input type="button" value="<?=$lang["back"]?>" onClick='Redirect("<?=base_url()."mastercfg/version/?".$_SERVER["QUERY_STRING"]?>");' class="button"></td>
    </tr>
    </form>
<?php
            }
        }
    }

?>
</table>
<?=$this->pagination_service->create_links_with_style()?>
</div>
<?=$notice["js"]?>
</body>
</html>