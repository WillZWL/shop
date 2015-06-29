<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/lytebox.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<?php
    $ar_status = array("N" => $lang["new"], "R" => $lang["ready_update_to_master"], "S" => $lang["success"], "F" => $lang["failed"], "I" => $lang["investigated"]);
    $ar_color = array("N" => "#000000", "R" => "#0000CC", "S" => "#009900", "F" => "#CC0000", "I" => "#440088");
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title">
            <!--
            <input type="button" value="<?=$lang["check_record"]?>" class="button" onclick="Redirect('<?=site_url('account/payment_gateway/check_record')?>')">&nbsp&nbsp
            -->
            <input type="button" value="<?=$lang["process_report"]?>" class="button" onclick="Redirect('<?=site_url('account/payment_gateway')?>')">&nbsp&nbsp
            <input type="button" value="<?=$lang["edit_failed_record"]?>" class="button" onclick="Redirect('<?=site_url('account/payment_gateway/pmgw_failed_record')?>')">&nbsp&nbsp
        </td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td height="70" style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?><br>
            <?=$lang["pmgw"]?> :
            <select name="country_id" onChange="Redirect('<?=base_url()?>account/payment_gateway/index/'+this.value)">
                <option value=""></option>
                <option value="worldpay" <?=$pmgw == "worldpay"?"SELECTED":""?>><?=$lang["worldpay"]?></option>
                <option value="worldpay_moto" <?=$pmgw == "worldpay_moto"?"SELECTED":""?>><?=$lang["worldpay_moto"]?></option>
                <option value="paypal_uk" <?=$pmgw == "paypal_uk"?"SELECTED":""?>><?=$lang["paypal_uk"]?></option>
                <option value="paypal_au" <?=$pmgw == "paypal_au"?"SELECTED":""?>><?=$lang["paypal_au"]?></option>
                <!--<option value="fnac" <?=$pmgw == "fnac"?"SELECTED":""?>><?=$lang["fnac"]?></option>-->
            </select>
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <col width="20"><col><col width="100">
    <?php
        if($file_list)
        {
    ?>
    <tr class="header">
        <td height="20"></td>
        <td><a href="#"><?=$lang["file_in_ftp"]?></a></td>
        <td></td>
    </tr>
    <?php
            foreach($file_list AS $i=>$file_name)
            {
    ?>
    <tr class="row<?=$i%2?>">
        <td height="20"><?=++$i?></td>
        <td height="20" colspan=2><?=$file_name?></td>

    </tr>
        <?php
            }
    ?>
</table>
    <form name="fm" method="post">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
            <tr align="right" style="padding-right:8px;">
                <td>
                    <input type="submit" value="<?=$lang["process_all"]?>">
                    <input type="hidden" name="posted" value="1">
                </td>
            </tr>
        </table>
    </form>
    <?php
        }
        else
        {
    ?>
    <tr class="row<?=$i%2?>">
        <td colspan="3">Please Choose the payment gateway</td>
    </tr>
</table>
    <?php
        }
    ?>
<?=$notice["js"]?>
</div>
</body>
</html>