<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/region_helper/js_sourcing_region"></script>
</head>
<?php
    $ar_fcid = array("US_FC"=>$lang["us_fc"],"UK_FC"=>$lang["uk_fc"],"HK_FC"=>$lang["hk_fc"]);
?>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onclick="Redirect('<?=site_url('mastercfg/country/')?>')"> &nbsp;</td>
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
<form name="fm" method="post" onSubmit="return CheckForm(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
    <col width="20%"><col width="30%"><col width="20%"><col width="30%">
    <tr class="header">
        <td height="20" colspan="4"><?=$lang["table_header"]?></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["id"]?></td>
        <td class="value" colspan="3"><?=$country_vo->get_id()?><input name="id" type="hidden" value="<?=$country_vo->get_id()?>" ><input type="hidden" name="posted" value="1"></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["id_3_digit"]?></td>
        <td class="value" colspan="3"><input name="id_3_digit" class="input" value="<?=htmlspecialchars($country_vo->get_id_3_digit())?>" maxlength="3"></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["name"]?></td>
        <td class="value" colspan="3">
        <div style="width:auto; height:200px; overflow-y:scroll;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tb_main">
        <tr class="header">
            <td height="20" width="30%"><?=$lang["language"]?></td>
            <td height="20" width="70%"><?=$lang["name_in_language"]?></td>
        </tr>
<?php
    foreach($name as $key=>$value)
    {
?>
        <tr>
            <td class="field"><?=$ar_lang[$key]?></td>
            <td class="value"><input name="langname[<?=$key?>]" type="text" value="<?=htmlspecialchars($value)?>"></td>
        </tr>
<?php
    }
?>
        </table>
        </div>
        </td>
    </tr>
<?php
    $select[$country_vo->get_status()] = "selected";
?>
    <tr>
        <td class="field"><?=$lang["status"]?></td>
        <td class="value" colspan="3"><select name="status" class="input"><option value="1" <?=$select[1]?>><?=$lang["active"]?></option><option value="0" <?=$select[0]?>><?=$lang["inactive"]?></option></select></td>
    </tr>
<?php
    unset($select);
    $select[$country_vo->get_currency_id()] = "selected";
?>
    <tr>
        <td class="field"><?=$lang["currency_id"]?></td>
        <td class="value" colspan="3"><select name="currency_id" class="input">
<?php
    foreach($ar_currency as $cur=>$cname)
    {
?>
<option value="<?=$cur?>" <?=$select[$cur]?>><?=$cname?></option>
<?php
    }
?>
        </select></td>
    </tr>
<?php
    unset($select);
    $select[$country_vo->get_language_id()] = "selected";
?>
    <tr>
        <td class="field"><?=$lang["language_id"]?></td>
        <td class="value" colspan="3"><select name="language_id" class="input">
<?php
    foreach($ar_lang as $langid=>$lname)
    {
?>
<option value="<?=$langid?>" <?=$select[$langid]?>><?=$lname?></option>
<?php
    }
?>
        </select></td>
    </tr>
<?php
    unset($select);
    $select[$country_vo->get_fc_id()] = "selected";
?>
<!--
    <tr>
        <td class="field"><?=$lang["fc_id"]?></td>
        <td class="value" colspan="3"><select name="fc_id" class="input"><option value=""></option>
<?php
    foreach($ar_fcid as $k=>$v)
    {
?>
<option value="<?=$k?>" <?=$select[$k]?>><?=$v?></option>
<?php
    }
?>
        </select></td>
    </tr>
-->
    <tr>
        <td class="field"><?=$lang["rma_fc"]?></td>
        <td class="value" colspan="3"><select name="rma_fc" class="input"><option value=""></option>
<?php
    unset($select);
    $select[$rma_fc_vo->get_rma_fc()] = "selected";
    foreach($ar_fcid as $k=>$v)
    {
?>
<option value="<?=$k?>" <?=$select[$k]?>><?=$v?></option>
<?php
    }
?>
    </select></td>
    </tr>
<?php
    unset($select);
    $select[$country_vo->get_allow_sell()] = "selected";
?>
    <tr>
        <td class="field"><?=$lang["allow_sell"]?></td>
        <td class="value" colspan="3"><select name="allow_sell" class="input"><option value="1" <?=$select[1]?>><?=$lang["allow_sell_to"]?></option><option value='0' <?=$select[0]?>><?=$lang["not_allow_sell"]?></option></select></td>
    </tr>
<?php
    unset($select);
    $select[$country_vo->get_url_enable()] = "selected";
?>
    <tr>
        <td class="field"><?=$lang["url_enable"]?></td>
        <td class="value" colspan="3"><select name="url_enable" class="input"><option value="1" <?=$select[1]?>><?=$lang["url_enable_to"]?></option><option value='0' <?=$select[0]?>><?=$lang["not_url_enable"]?></option></select></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["create_on"]?></td>
        <td class="value"><?=$country_vo->get_create_on()?></td>
        <td class="field"><?=$lang["modify_on"]?></td>
        <td class="value"><?=$country_vo->get_modify_on()?></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["create_at"]?></td>
        <td class="value"><?=$country_vo->get_create_at()?></td>
        <td class="field"><?=$lang["modify_at"]?></td>
        <td class="value"><?=$country_vo->get_modify_at()?></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["create_by"]?></td>
        <td class="value"><?=$country_vo->get_create_by()?></td>
        <td class="field"><?=$lang["modify_by"]?></td>
        <td class="value"><?=$country_vo->get_modify_by()?></td>
    </tr>

    <tr class="tb_detail">
        <td colspan="2" align="left" style="padding-left:10px;"><input type="button" value="<?=$lang["cancel"]?>" onClick="Redirect('<?=$_SESSION["clist_page"]?>');"></td>
        <td colspan="2" align="right" style="padding-right:10px;"><input type="button" value="<?=$lang["update"]?>" onClick="document.fm.submit();"></td>
    </tr>
</table>
</form>
</div>
<?=$notice["js"]?>
</body>
</html>