<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
</head>
<body>
<div id="main">
<?$ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]);?>
<?=$notice["img"]?>
<script>
function Proc(sov, sv, hold)
{
    var f = document.fm_proc;
    f.so_no.value = sov;
    f.status.value = sv;
    f.hold_status.value = hold;
    payment_gateway = document.getElementById("payment_gateway['" + sov+ "']");
    if (payment_gateway)
    {
        f.payment_gateway.value = document.getElementById("payment_gateway['" + sov+ "']").value;
        f.pay_to_account.value = document.getElementById("pay_to_account['" + sov+ "']").value;
        f.txn_id.value = document.getElementById("txn_id['" + sov+ "']").value;
    }
    f.submit();
}
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="650" align="right" class="title"><input type="button" value="<?=$lang["add_button"]?>" class="button" onClick="Redirect('<?=site_url('order/special_order')?>')"> &nbsp; <input type="button" value="<?=$lang["on_hold_button"]?>" class="button" onClick="Redirect('<?=site_url('order/special_order/on_hold')?>')"> &nbsp;
<?if (check_app_feature_access_right($app_id, "ORD001101_aps_payment_order_page"))
{
?>
        <input type="button" style="width:220px" value="<?=$lang["sale_aps_button"]?>" class="button" onClick="Redirect('<?=site_url('order/special_order/on_hold/aps_payment')?>')"> &nbsp;
<?php
}
?>
        <input type="button" value="<?=$lang["pending_button"]?>" class="button" onClick="Redirect('<?=site_url('order/special_order/pending')?>')"></td>
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

<form name="fm" method="get">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <col width="20"><col width="80"><col width="100"><col><col><col>
<?php
    if ($is_aps_payment_page)
    {
        print "<col width='120'>";
    }
?>
    <col width="150"><col width="26">
    <tr class="header">
        <td height="20"><img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'so_no', '<?=$xsort["so_no"]?>')"><?=$lang["order_id"]?> <?=$sortimg["so_no"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'platform_id', '<?=$xsort["platform_id"]?>')"><?=$lang["platform_id"]?> <?=$sortimg["platform_id"]?></a></td>
        <td style="white-space:nowrap"><a href="#" onClick="SortCol(document.fm, 'email', '<?=$xsort["email"]?>')"><?=$lang["client_email"]?> <?=$sortimg["email"]?></a></td>
        <td style="white-space:nowrap"><?=$lang["order_detail"]?></td>
        <td style="white-space:nowrap"><?=$lang["order_amount"]?></a></td>
<?php
    if ($is_aps_payment_page)
    {
        print "<td style='white-space:nowrap'>" . $lang["payment_mode"] . "</a></td>";
    }
?>
        <td></td>
        <td title="<?=$lang["check_all"]?>">
<?php if (check_app_feature_access_right($app_id, "ORD001101_print_invoice")) {?>
            <input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this, 1);">
<?php } ?>
        </td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td><input name="so_no" class="input" value="<?=htmlspecialchars($this->input->get("so_no"))?>"></td>
        <td><input name="platform_id" class="input" value="<?=htmlspecialchars($this->input->get("platform_id"))?>"></td>
        <td><input name="email" class="input" value="<?=htmlspecialchars($this->input->get("email"))?>"></td>
        <td></td>
<?php
    if ($is_aps_payment_page)
    {
        print "<td></td>";
    }
?>
        <td><!--<input name="amount" class="input" value="<?=htmlspecialchars($this->input->get("amount"))?>">--></td>
        <td align="center" colspan="2"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
    <input type="hidden" name="showall" value='<?=$this->input->get("showall")?>'>
    <input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
    <input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
    </form>
<form name="fm_edit" method="post">
<?php
    $i=0;
    if ($objlist)
    {
        foreach ($objlist as $obj)
        {
?>

    <tr class="row<?=$i%2?>">
        <td height="20"><img src="<?=base_url()?>images/info.gif" title='<?=$lang["create_on"]?>:<?=$obj->get_create_on()?>&#13;<?=$lang["create_at"]?>:<?=$obj->get_create_at()?>&#13;<?=$lang["create_by"]?>:<?=$obj->get_create_by()?>&#13;<?=$lang["modify_on"]?>:<?=$obj->get_modify_on()?>&#13;<?=$lang["modify_at"]?>:<?=$obj->get_modify_at()?>&#13;<?=$lang["modify_by"]?>:<?=$obj->get_modify_by()?>'></td>
        <td><a href="<?=base_url()."cs/quick_search/view/".$obj->get_so_no()?>" target="_blank"><?=$obj->get_so_no()?></a></td>
        <td><?=$obj->get_platform_id()?></td>
        <td><?=$obj->get_email()?></td>
        <td>
            <?php
                if ($obj->get_reason() || $obj->get_note())
                {
                    if ($obj->get_reason())
                    {
                        echo $obj->get_reason()." : ";
                    }
                    if ($obj->get_note())
                    {
                     echo $obj->get_note();
                    }
                    echo "<br>";
                }
                if ($obj->get_items())
                {
                    $items = explode("||", $obj->get_items());
                    foreach ($items as $item)
                    {
                        list($sku, $name, $qty, $u_p, $amount) = @explode("::", $item);
            ?>
                        <p class="normal_p">[<?=$sku?>] <?=$name?> x<?=$qty?> @<?=$u_p?> = <?=$amount?></p>
            <?php
                    }
                }
            ?>
                <p class="normal_p"><?="creator: ".$obj->get_create_by()?></p>

        </td>
        <td><?=$obj->get_currency_id()?> <?=$obj->get_amount()?></td>
<?php
    if ($is_aps_payment_page)
    {
?>
        <td>
            <select name="payment_gateway['<?=$obj->get_so_no()?>']" id="payment_gateway['<?=$obj->get_so_no()?>']" dname="Payment Mode" class="text" notEmpty>
                <option value="paypal">Paypal</option>
                <option value="paypal_cash">Paypal+Cash</option>
                <option value="inpendium_ctpe">Inpendium</option>
                <option value="inpendium_cash">Inpendium+Cash</option>
                <option value="m_bank_transfer">Bank Transfer</option>
                <option value="global_collect_moto">GlobalCollect Moto</option>
            </select>
            Transaction Ref:
            <input name="txn_id['<?=$obj->get_so_no()?>']" id="txn_id['<?=$obj->get_so_no()?>']" class="input" value="">
            Pay to account:
            <input name="pay_to_account['<?=$obj->get_so_no()?>']" id="pay_to_account['<?=$obj->get_so_no()?>']" class="input" value="">
        </td>
<?php
    }
?>
        <td><input type="button" value="<?=$lang["delete"]?>" onClick="if (confirm('<?=$lang["delete_confirm"]?>')){Proc('<?=$obj->get_so_no()?>', 0, 0);}"> &nbsp; &nbsp; <input type="button" value="<?=$lang["approve"]?>" onClick="if(confirm('<?=$lang["move_to_pending"]?>')) Proc('<?=$obj->get_so_no()?>', <?=$next_level_order_status[$obj->get_so_no()]?>, <?=$hold_status[$obj->get_so_no()]?>);"></td>
        <td>
<?php if (check_app_feature_access_right($app_id, "ORD001101_print_invoice")) {?>
            <input type="checkbox" name="check[<?=$obj->get_so_no()?>]" value="<?=$obj->get_so_no()?>">
<?php }?>
        </td>
    </tr>
<?php
            $i++;
        }
    }
?>
</table>
<?php if (check_app_feature_access_right($app_id, "ORD001101_print_invoice")) {?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
    <tr>
        <td align="right" style="padding-right:8px;">
            <input type="button" value="<?=$lang['print_custom_selected']?>" onClick="this.form.action='<?=base_url()?>order/order_fulfilment/custom_invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';"> &nbsp;|&nbsp
            <input type="button" value="<?=$lang['print_selected']?>" onClick="this.form.action='<?=base_url()?>order/order_fulfilment/invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">
        </td>
    </tr>
</table>
<?php }?>
<input type="hidden" name="posted" value="1">
</form>
<form name="fm_proc" method="post">
    <input type="hidden" name="posted" value="1">
    <input type="hidden" name="so_no" value="">
    <input type="hidden" name="status" value="0">
    <input type="hidden" name="hold_status" value="0">
    <input type="hidden" name="payment_gateway" value="">
    <input type="hidden" name="pay_to_account" value="">
    <input type="hidden" name="txn_id" value="">
</form>
<?=$this->pagination_service->create_links_with_style()?>
</div>
<?=$notice["js"]?>
</body>
</html>