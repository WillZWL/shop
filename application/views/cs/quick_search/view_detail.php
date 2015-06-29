<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/calendar.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/calendar.css" type="text/css" media="all"/>
</head>
<style>
.bp{padding-bottom:30px;}
#maintb td{height:20px;}
#maintb .header1{background-color:#FFCCCC;}
#maintb .header2{background-color:#A4FFA4;}
#maintb .header3{background-color:#3399FF;}
#maintb .header4{background-color:#6A006A;}
#maintb .header5{background-color:#B6B6DA;}
#maintb .header6{background-color:#CC6600;}
#maintb .field1{background-color:#FFFF77; padding-left:8px;}
#maintb .field2{background-color:#CFFF9F; padding-left:8px;}
#maintb .field3{background-color:#84D7FF; padding-left:8px;}
#maintb .field4{background-color:#C99CC0; padding-left:8px;}
#maintb .field6{background-color:#FFCC33; padding-left:3px;}
#maintb .value1{background-color:#FFFFCC; padding-left:8px;}
#maintb .value2{background-color:#DDFFDD; padding-left:8px;}
#maintb .value3{background-color:#C1FFFF; padding-left:8px;}
#maintb .value4{background-color:#D8CBDE; padding-left:8px;}
#maintb .value6{background-color:#FFFF66; padding-left:3px;}
#maintb .refund_header{background-color:#ffafd6;}
#maintb .refund_header td{padding-left:4px;}
#maintb .refund_row0{background-color:#ffcdfe; }
#maintb .refund_row0 td{padding-left:4px;}
#maintb .refund_row1{background-color:#ffddfe;}
#maintb .refund_row1 td{padding-left:4px;}
#maintb #ordernote {height: 150px;}
#maintb #itemlist {height: 80px;}

</style>
<body>
<?php
function display_risk($title, $results)
{
    if (!is_null($results) && (!empty($results)) && (!$results == ""))
    {
        print "<td><table border='1' cellpadding='0' cellspacing='0' width='100%'>";
        $tdSet = "";
        $tdSet .= "<tr>";
        for ($i=0;$i<sizeof($results);$i++)
        {
            if ($results[$i] == null)
                $tdSet .= "<td>&nbsp;</td>";
            else
                $tdSet .= "<td class='risk_" . $results[$i]['style'] . "'>" . $results[$i]['value'] . "</td>";
        }
        $tdSet .= "</tr>";
        print "<tr><td align='center' colspan='" . $i . "'>" . $title . "</td></tr>";
        print $tdSet;
        print "</table></td>";
    }
}
?>
<div id="main" <?=$viewtype==""?"":"style='width:1004px;'"?> class="bp">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="25" class="title"><?=$lang["subtitle"]?></td>
        <td align="right" style="padding-right:10px;" class="title">
        <form method='POST'>
        <?php if (check_app_feature_access_right($app_id, "ORD000403_print_invoice")) {
            // #sbf 4145 - custom invoice in HKD ?>
            <input type="button" value="<?=$lang["print_custom_invoice"]?> (HKD)" onClick="this.form.action='<?=base_url()?>order/integrated_order_fulfillment/custom_invoice/hkd';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">&nbsp;&nbsp;&nbsp;
            <input type="button" value="<?=$lang["print_custom_invoice"]?>" onClick="this.form.action='<?=base_url()?>order/integrated_order_fulfillment/custom_invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">&nbsp;&nbsp;&nbsp;
            <input type="button" value="<?=$lang["print_selected_invoice"]?>" onClick="this.form.action='<?=base_url()?>order/integrated_order_fulfillment/invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';"><input type="hidden" name="check[<?=$so_obj->get_so_no()?>]" value="<?=$so_obj->get_so_no()?>">
        <?php }?>
            <input type="hidden" name="posted" value="1">
            </form>
        </td>
    </tr>
    <tr>
        <td height="2" colspan="2" bgcolor="#000033"></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" bgcolor="#BBBBFF" width="100%" id="maintb">
<tr><td style="padding-left:8px" class="header1"><b style="font-size:11px;color:#000000"><?=$lang["customer_detail"]?></b></td></tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <tr>
        <td width="15%" align="left" class="field1"><?=strtoupper($lang["client_id"])?></td>
        <td width="35%" align="left" class="value1"><?=$client_obj->get_id()?></td>
        <td width="15%" align="left" class="field1"><?=strtoupper($lang["company_name"])?></td>
        <td width="35%" align="left" class="value1"><?=$client_obj->get_companyname()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field1"><?=strtoupper($lang["client_name"])?></td>
        <?if($client_obj->get_client_id_no() == "")
        { ?>
        <td width="35%" align="left" class="value1"><?=$client_obj->get_forename()." ".$client_obj->get_surname()?></td>
        <?php }
        else
        { ?>
        <td width="35%" align="left" class="value1"><?=$client_obj->get_forename()." ".$client_obj->get_surname()?> / ID: <?=$client_obj->get_client_id_no()?></td>
        <?php } ?>
        <td width="15%" align="left" class="field1"><?= (check_app_feature_access_right($app_id, "CS000100_password"))?strtoupper($lang["password"]):""?></td>
        <td width="35%" align="left" class="value1"><?= (check_app_feature_access_right($app_id, "CS000100_password"))?$this->encrypt->decode($client_obj->get_password()):""?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field1"><?=strtoupper($lang["email"])?></td>
        <td width="35%" align="left" class="value1"><?=$client_obj->get_email()?></td>
        <td width="15%" align="left" class="field1"><?=strtoupper($lang["client_status"])?></td>
        <td width="35%" align="left" class="value1">
            <?php if (check_app_feature_access_right($app_id, 'CS000102_deactivate_client') === TRUE && $client_obj->get_status() == 1): ?>
                <form action="<?php echo current_url(); ?>" method="POST" name="deactivate_client">
                    <input type="hidden" name="action" value="deactivate_client" />
                    <span id="client_status"><?php echo $lang['active']; ?><a onclick="document.deactivate_client.submit();" href="#" style="color:#000;display:inline-block;float:right;margin-right:10px">[<?php echo $lang['deactivate_account']; ?>]</a></span>
                </form>
            <?php else: ?>
                <?php echo ($client_obj->get_status() == 1) ? $lang['active'] : $lang['inactive']; ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field1"><?=strtoupper($lang["tel"])?></td>
        <td width="35%" align="left" class="value1"><?=trim($client_obj->get_tel_1()." ".$client_obj->get_tel_2()." ".$client_obj->get_tel_3())." / ".$client_obj->get_mobile()?></td>
        <td width="15%" align="left" class="field1"><?=strtoupper($lang["ip_address"])?></td>
        <td width="35%" align="left" class="value1"><?=$so_obj->get_create_at()?></td>
    </tr>
    </table>
    </td>
</tr>
<tr><td style="padding-left:8px" class="header2"><b style="font-size:11px;color:#000000"><?=$lang["order_billaddr"]?></b></td></tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
<?php
    $bill_address = explode("|", $so_obj->get_bill_address());
?>
    <tr>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["bill_name"])?></td>
        <td width="35%" align="left" class="value2"><?=$so_obj->get_bill_name()?></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["bill_company_name"])?></td>
        <td width="35%" align="left" class="value2"><?=$so_obj->get_bill_company()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["bill_addr_l1"])?></td>
        <td width="35%" align="left" class="value2"><?=$bill_address[0]?></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["postcode"])?></td>
        <td width="35%" align="left" class="value2"><?=$so_obj->get_bill_postcode()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["bill_addr_l2"])?></td>
        <td width="35%" align="left" class="value2"><?=$bill_address[1]?></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["city"])?></td>
        <td width="35%" align="left" class="value2"><?=$so_obj->get_bill_city()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["bill_addr_l3"])?></td>
        <td width="35%" align="left" class="value2"><?=$bill_address[2]?></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["state"])?></td>
        <td width="35%" align="left" class="value2"><?=$so_obj->get_bill_state()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field2"></td>
        <td width="35%" align="left" class="value2"></td>
        <td width="15%" align="left" class="field2"><?=strtoupper($lang["country"])?></td>
        <td width="35%" align="left" class="value2"><?=$so_obj->get_bill_country_id()?></td>
    </tr>
    </table>
    </td>
</tr>
<?php
    $address = explode("|", $so_obj->get_delivery_address());
    if($this->input->get('caddr') == "")
    {
?>
<tr><td style="padding-left:8px" class="header3"><b style="font-size:11px;color:#000000"><?=$lang["order_deladdr"]?></b>&nbsp;&nbsp;
<?php
        if (($order_obj->get_status() < 6) && (check_app_feature_access_right($app_id, "CS000102_change_delivery_addr")))
        {
?>
    <input type="button" value="<?=$lang["change_delivery_addr"]?>" onClick="document.location.href='<?=base_url()."cs/quick_search/view/".$order_obj->get_so_no()."/".$viewtype."?caddr=1"?>';">
<?php
        }
?>
    </td></tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_name"])?></td>
        <td width="35%" align="left" class="value3"><?=$so_obj->get_delivery_name();?></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_company_name"])?></td>
        <td width="35%" align="left" class="value3"><?=$so_obj->get_delivery_company()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_addr_l1"])?></td>
        <td width="35%" align="left" class="value3"><?=$address[0]?></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["postcode"])?></td>
        <td width="35%" align="left" class="value3"><?=$so_obj->get_delivery_postcode()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_addr_l2"])?></td>
        <td width="35%" align="left" class="value3"><?=$address[1]?></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["city"])?></td>
        <td width="35%" align="left" class="value3"><?=$so_obj->get_delivery_city()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_addr_l3"])?></td>
        <td width="35%" align="left" class="value3"><?=$address[2]?></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["state"])?></td>
        <td width="35%" align="left" class="value3"><?=$so_obj->get_delivery_state()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["tel"])?></td>
        <td width="35%" align="left" class="value3"><?=trim($client_obj->get_del_tel_1()." ".$client_obj->get_del_tel_2()." ".$client_obj->get_del_tel_3())." / ".$client_obj->get_del_mobile()?></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["country"])?></td>
        <td width="35%" align="left" class="value3"><?=$so_obj->get_delivery_country_id()?></td>
    </tr>
    </table>
    </td>
</tr>
<?php
    }
    else
    {
?>
<form name="changeaddr" onSubmit="return CheckForm(this);" method="POST" action='<?=base_url()."cs/quick_search/view/".$order_obj->get_so_no()?>'>
<input type="hidden" name="ca" value="1">
<tr><td style="padding-left:8px" class="header3"><b style="font-size:11px;color:#000000"><?=$lang["order_deladdr"]?></b>&nbsp;&nbsp;<input type="button" onClick="if(CheckForm(this.form)) document.changeaddr.submit();" value="<?=$lang["update_addr"]?>"></td></tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_name"])?></td>
        <td width="35%" align="left" class="value3"><input type="text" name="dname" value="<?=$so_obj->get_delivery_name();?>" notEmpty class="input"></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_company_name"])?></td>
        <td width="35%" align="left" class="value3"><input type="text" name="dcomp" value="<?=$so_obj->get_delivery_company()?>" class="input"></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_addr_l1"])?></td>
        <td width="35%" align="left" class="value3"><input type="text" name="daddr1" value="<?=$address[0]?>" notEmpty class="input"></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["postcode"])?></td>
        <td width="35%" align="left" class="value3"><input type="text" name="dpostcode" value="<?=$so_obj->get_delivery_postcode()?>" class="input"></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_addr_l2"])?></td>
        <td width="35%" align="left" class="value3"><input type="text" name="daddr2" value="<?=$address[1]?>" class="input"></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["city"])?></td>
        <td width="35%" align="left" class="value3"><input type="text" name="dcity" value="<?=$so_obj->get_delivery_city()?>" notEmpty class="input"></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["del_addr_l3"])?></td>
        <td width="35%" align="left" class="value3"><input type="text" name="daddr3" value="<?=$address[2]?>" class="input"></td>
        <td width="15%" align="left" class="field3"><?=strtoupper($lang["state"])?></td>
        <td width="35%" align="left" class="value3"><input type="text" name="dstate" value="<?=$so_obj->get_delivery_state()?>" class="input"></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field3"></td>
        <td width="35%" align="left" class="value3"></td>
        <td width="15%" align="left" class="field3"></td>
        <td width="35%" align="left" class="value3"><select type="text" name="dcountry" class="input">
<?php
            foreach($country_list as $country_obj)
            {
?>
        <option value="<?=$country_obj->get_id()?>" <?=($so_obj->get_delivery_country_id() == $country_obj->get_id()?"SELECTED":"")?>><?=$country_obj->get_id()." - ".$country_obj->get_name()?></option>
<?php
            }
?>
        </select></td>
    </table>
    </td>
</tr>
</form>
<?php
    }
?>

<tr><td style="padding-left:8px" class="header4"><b style="font-size:11px;color:#ffffff;"><?=$lang["order_detail"]?></b>
<div style="float:right">
    <a href="/order/on_hold_admin?so_no=<?=$order_obj->get_so_no()?>&search=1"><button>HOLD</button></a>
    <?php if ($allow_release)
        {
    ?>
        <a href="/order/on_hold_admin/oc_index/?so_no=<?=$order_obj->get_so_no()?>"><button>RELEASE</button></a>
    <?php
        }
    ?>
</div>
</td>
</tr>
<tr>
<?php
        # SBF #4484 - Allow users to mark fulfill = Y on so_extend for marketplaces
        $fulfilled = $fulfillment_html = $mark_as_close = "";
        if(strpos($so_obj->get_platform_id(), "WEB") === FALSE)
        {
            $base_url = base_url();
            $so_no = $so_obj->get_so_no();
            $fulfilled = $order_obj->get_fulfilled();

            if(empty($fulfilled))
                $fulfilled = "N";

            if($fulfilled == "N")
            {
                $mark_as_close = <<<html
                    <input type="button" value="Mark as closed" onClick="this.form.submit()">
html;
            }
            $fulfillment_html = <<<html
                <form action="{$base_url}cs/quick_search/set_fulfill/{$so_no}" method="POST">
                    <div>
                        Fulfillment updated on marketplace? <b>$fulfilled</b>
                        <br>$mark_as_close
                    </div>
                </form>
html;
        }
        $childlink_html = "";
        if($child)
        {
            foreach ($child as $child_so_no => $v)
            {
                $childlink_html .= "<a href='#$child_so_no'>>> $child_so_no</a><br>";
            }

        }
?>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["order_number"])?></td>
        <td width="35%" align="left" class="value4">
            <?=$order_obj->get_so_no().($order_obj->get_so_no() != $order_obj->get_platform_order_id()?" (".$lang["platform_order_number"].": ".$order_obj->get_platform_order_id().")":"").($order_obj->get_txn_id() != ""?" (".$lang["pm_txn_id"]." : ".$order_obj->get_txn_id().")":"")?>
            <br><?=$childlink_html?>
        </td>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["order_type"])?></td>
        <td width="35%" align="left" class="value4"><?=$order_obj->get_platform_id()." - ".$order_obj->get_biz_type()?><br><?=$fulfillment_html?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["website_status"])?></td>
        <td width="35%" align="left" class="value4"><?=$website_status?></td>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["expected_delivery_date"])?></td>
        <td width="35%" align="left" class="value4">
        <form name="edd_form" id="edd_form" method="post" action="<?=base_url()."cs/quick_search/update_edd/" . $so_obj->get_so_no() . "/"?>">
<?php
        if($so_obj->get_hold_status() == 15)
            print "Update on Split Child below";
        else
        {
            print "<input size=\"10\" name='expect_delivery_date' id='expect_delivery_date' value='" . $order_obj->get_expect_delivery_date() . "'>";
            print "<img src='/images/cal_icon.gif' class='pointer' onclick=\"showcalendar(event, document.getElementById('expect_delivery_date'), false, false, false, '2010-01-01')\" align='absmiddle'>";
            print "<input type='button' value='" . $lang["update"] . "' onclick=\"this.form.submit();\">";
        }
?>
        </form>
        </td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["cost_wo_del"])?></td>
        <td width="35%" align="left" class="value4"><?=$order_obj->get_currency_id()." ".number_format($order_obj->get_amount() - $order_obj->get_delivery_charge(),2)?></td>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["del_cost"])?></td>
        <td width="35%" align="left" class="value4"><?=$order_obj->get_currency_id()." ".$order_obj->get_delivery_charge()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["total_paid"])?></td>

<?php
    if($order_obj->get_payment_gateway_id() == 'w_bank_transfer')
    {
        #sbf #3546
        $bt_total_received = number_format($order_obj->get_bt_total_received() ,2);
        $bt_total_bank_charge = number_format($order_obj->get_bt_total_bank_charge() ,2);
        $net_received = $bt_total_received - $bt_total_bank_charge;
        $net_unpaid = number_format((number_format($order_obj->get_amount() ,2) - $net_received),2);

?>
        <td width="35%" align="left" class="value4">
            Total received: <?=$order_obj->get_currency_id()." ".$bt_total_received?><br>
            Total bank charges: <?=$order_obj->get_currency_id()." ".$bt_total_bank_charge?><br>
            <b>TOTAL PAID: <?=$order_obj->get_currency_id()." ".$net_received?> </b>|| Net Unpaid: <?=$order_obj->get_currency_id()." ".$net_unpaid?>
        </td>
<?php
    }
    else
    {
?>

        <td width="35%" align="left" class="value4"><?=$order_obj->get_currency_id()." ".number_format($order_obj->get_amount() ,2)?></td>
<?php
    }
?>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["ship_service_level"])?></td>
        <td width="35%" align="left" class="value4"><?=$del_opt_list[$order_obj->get_delivery_mode()]->get_display_name()?></td>
    </tr>
<?php

    $courier = "<i>".$lang["not_yet_shipped"]."</i>";
    $tracking_no =  "<i>".$lang["not_yet_shipped"]."</i>";
    $packed_on = "<i>".$lang["not_yet_shipped"]."</i>";
    $shipped_on = "<i>".$lang["not_yet_shipped"]."</i>";
    if($order_obj->get_dispatch_date() != "")
    {
        $shipped_on = $order_obj->get_dispatch_date();
    }
    if($order_obj->get_tracking_no() != "")
    {
        $tmp = explode("||",$order_obj->get_tracking_no());
        $tmp2 = explode("::", $tmp[0]);


        $courier = $tmp2[3];
        if(count($tmp2) == 6)
        {
            $tracking_no = $tmp2[4];
            $packed_on = $tmp2[5];
        }
        else
        {
            $tracking_no = "N/A";
            $packed_on = $tmp2[4];
        }
    }
/*
    if($order_obj->get_biz_type() == "OFFLINE" || $order_obj->get_biz_type() == "SPECIAL")
    {
        if($so_extend_obj->get_offline_fee() < 0)
        {
            $promotion = ucwords($lang["discount"])." : ".$order_obj->get_currency_id()." ".number_format(($so_extend_obj->get_offline_fee() * -1),2);
        }
        else{
            //Promotional Code (To be implemented
            $promotion = "";
        }
    }
*/
?>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["payment_mode"])?></td>
        <td width="35%" align="left" class="value4">
            <?=ucwords($order_obj->get_payment_gateway_id())?>
            <?php
                if ($order_obj->get_payment_gateway_id() != "paypal" && $pmgw_card_obj)
                {
                    echo " (<span style='color:#004000'>{$lang["payment_type"]}: <font color='#000080'>{$pmgw_card_obj->get_card_name()}</font></span>)";
                    if ($socc_obj)
                        echo ", " . $socc_obj->get_card_type();
                }

                switch ($order_obj->get_payment_gateway_id())
                {
                    case "paypal":
                    case "paypal_cash":
            ?>
                        <br>
                        <span style="color:#004040">
                        PP Acct: <font color="#FF2200"><?=$sops_obj->get_pay_to_account()?></font><br>
                        PP Payer Email: <font color="#FF2200"><?=$sops_obj->get_payer_email()?></font><br>
                        PROTECTIONELIGIBILITY: <font color="#0000FF"><?=$sops_obj->get_risk_ref1()?></font><br>
                        PROTECTIONELIGIBILITYTYPE: <font color="#0000FF"><?=$sops_obj->get_risk_ref2()?></font><br>
                        ADDRESSSTATUS: <font color="#0000FF"><?=$sops_obj->get_risk_ref3()?></font><br>
                        PAYERSTATUS: <font color="#0000FF"><?=$sops_obj->get_risk_ref4()?></font>
                        </span>
            <?php
                        break;
                    case "global_collect":
            ?>
                        <br>
                        <span style="color:#004040">
                        ECI: <font color="#0000FF"><?=$sops_obj->get_risk_ref4()?></font>
                        FRAUDRESULT: <font color="#0000FF"><?=$sops_obj->get_risk_ref2()?></font>
                        </span>
            <?php
                        break;
                    case "altapay":
            ?>
                        <br>
                        <span style="color:#004040">
                        3D Result: <font color="#FF2200"><?=$sops_obj->get_risk_ref2()?></font><br>
                        CVV Result: <font color="#0000FF"><?=$sops_obj->get_risk_ref3()?></font><br>
                        Card Status: <font color="#0000FF"><?=$sops_obj->get_risk_ref4()?></font><br>
                        </span>
            <?php
                        break;
                    case "moneybookers":
            ?>
                        <br>
                        <span style="color:#004040">
                        VERIFICATIONLEVEL: <font color="#0000FF"><?=$sops_obj->get_risk_ref1()?></font>
                        </span>
            <?php
                        break;
                    case "worldpay":
            ?>
                        <br>
                        <span style="color:#004040">
                        3D result: <font color="#0000FF"><?=$sops_obj->get_risk_ref2()?></font>
                        </span>
            <?php
                        break;
                    case "inpendium_ctpe":
            ?>
                        <br>
                        <span style="color:#004040">
                        Risk Score: <font color="#0000FF"><?=$sops_obj->get_risk_ref1()?></font>
                        ECI: <font color="#0000FF"><?=$sops_obj->get_risk_ref4()?></font>
<!--                        result: <font color="#0000FF"><?=$inpendium_result['result']?></font> -->
                        3D result: <font color="#0000FF"><?=$inpendium_result['3d']?></font>
                        </span>
            <?php
                        break;

                    case "adyen":
                        if(strpos($sops_obj->get_risk_ref1(), '||'))
                        {
                            list($resp_3doffered, $resp_3dauth, $resp_liabilityshift) = explode("||", $sops_obj->get_risk_ref1());
                            $arr_3doffered = explode(":", $resp_3doffered);
                            $arr_3dauth = explode(":", $resp_3dauth);
                            $arr_liabilityshift = explode(":", $resp_liabilityshift);

                            $riskref1_html = <<<html
                                3D Enrolled: <font color="#0000FF">{$arr_3doffered[1]}</font><br>
                                3D Authentication Result: <font color="#0000FF">{$arr_3dauth[1]}</font><br>
                                Liability Shift: <font color="#0000FF">{$arr_liabilityshift[1]}</font><br>
html;
                        }
                        else
                        {
                            $riskref1_html = <<<html
                                3D Authentication Result: <font color="#0000FF">{$sops_obj->get_risk_ref1()}</font><br>
html;
                        }
            ?>
                        <br>
                        <span style="color:#004040">
                        <?=$riskref1_html?>
                        Total Fraud Score: <font color="#0000FF"><?=$sops_obj->get_risk_ref2()?></font><br>
                        6 Digit Authorisation Code: <font color="#0000FF"><?=$sops_obj->get_risk_ref3()?></font><br>
                        Refusal Reason: <font color="#0000FF"><?=$sops_obj->get_risk_ref4()?></font><br>
                        </span>
            <?php
                        break;
                }

                $ship_text = $del_text = "";
                if($ship_days = $order_obj->get_expect_ship_days())
                {
                    $min_ship_day = trim(substr($ship_days, 0, strpos($ship_days, '-')-1));
                    $max_ship_day = trim(substr($ship_days, strpos($ship_days, '-')+1));

                    if(ctype_digit($max_ship_day))
                    {
                        $max_ship_date = date('Y-m-d', (strtotime($order_obj->get_order_create_date()) + $max_ship_day*24*60*60));
                        $ship_text = "Ship time frame: $min_ship_day - $max_ship_day days (latest $max_ship_date)";
                    }
                }
                if($del_days = $order_obj->get_expect_del_days())
                {
                    $min_del_day = trim(substr($del_days, 0, strpos($del_days, '-')-1));
                    $max_del_day = trim(substr($del_days, strpos($del_days, '-')+1));

                    if(ctype_digit($max_del_day))
                    {
                        $max_ship_date = date('Y-m-d', (strtotime($order_obj->get_order_create_date()) + $max_del_day*24*60*60));
                        $del_text = "Del time frame: $min_del_day - $max_del_day days (latest $max_ship_date)";
                    }
                }
            ?>
        </td>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["promotion_code"])?></td>
        <td width="35%" align="left" class="value4"><?=$so_obj->get_promotion_code()?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["order_create_date"])?></td>
        <td width="35%" align="left" class="value4"><?=$order_obj->get_order_create_date()?></td>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["packed_on"])?></td>
        <td width="35%" align="left" class="value4"><?=$packed_on?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["status"])?></td>
        <td width="35%" align="left" class="value4"><?=$lang["status_name"][$order_obj->get_status()]?></td>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["shipped_on"])?></td>
        <td width="35%" align="left" class="value4"><?=$shipped_on?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["customer_status"])?></td>
        <td width="35%" align="left" class="value4"><b><?=$status["status"]?></b><br /><?=$status["desc"]?></td>
        <td width="15%" align="left" class="field4">DELIVERY FRAMES SHOWN TO CUSTOMER</td>
        <td width="35%" align="left" class="value4"><?="$ship_text<br>$del_text"?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["courier"])?></td>
        <td width="35%" align="left" class="value4"><?=$courier?></td>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["tracking_no"])?></td>
        <td width="35%" align="left" class="value4"><?=$tracking_no?></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["chasing_order"])?></td>
        <td width="35%" align="left" class="value4">
        <form name="chasing_order_form" id="chasing_order_form" method="post" action="<?=base_url()."cs/quick_search/update_cs_order_query/" . $so_obj->get_so_no() . "/"?>">
<?php
        if($so_obj->get_hold_status() == 15)
        {
            print "Update on Split Child below.";
        }
        else
        {
            if ($order_obj->get_cs_customer_query() & 1)
            {
                print $lang["chasing_message"];
    //          print "<input type='hidden' name=\"chasing_order\" id=\"chasing_order\" value=\"1\">";
            }
            else
            {
?>
            <input type="checkbox" name="chasing_order" id="chasing_order" value="1">
            <input type="button" value="<?=$lang["update"]?>" onClick="this.form.submit();">
<?php
            }
        }
?>

        </form>
        </td>
        <td width="15%" align="left" class="field4">&nbsp;</td>
        <td width="35%" align="left" class="value4">&nbsp;</td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["status_history"])?></td>
        <td colspan="3" width="85%" align="left" class="value4"><div style="width:100%; height:100px; overflow-y:scroll">
<?php
        foreach($history_obj as $hobj)
        {
            $name = $hobj->get_username() != ""?$hobj->get_username():"System";
?>
        <div><?=$lang["change_to"]?><b><?=$lang["status_name"][$hobj->get_status()]?></b>&nbsp;&nbsp;<i><?=$lang["change_username"]." ".$name." ".$lang["change_create_on"]." ".$hobj->get_create_on()?></i><br><br></div>
<?php

        }
?>      </div></td>
    </tr>
<?php
    if(count($hold_history))
    {
?>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["order_hold_history"])?></td>
        <td colspan="3" width="85%" align="left" class="value4">
        <div style="height:70px; width:100%; overflow-y:scroll;">
<?php
        foreach($hold_history as $hsobj)
        {
            echo "<b>".$hsobj->get_reason()."</b>&nbsp;&nbsp;<i>".$lang["change_username"]." ".$hsobj->get_username()." ".$lang["change_create_on"]." ".$hsobj->get_create_on()."</i><br><br>";
        }
?>
        </div>
        </td>
    </tr>
<?php
    }
?>

<?php
    if(count($release_history))
    {
?>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["order_release_history"])?></td>
        <td colspan="3" width="85%" align="left" class="value4">
        <div style="height:70px; width:100%; overflow-y:scroll;">
<?php
        foreach($release_history as $hsobj)
        {
            echo "<b>".$hsobj->get_release_reason()."</b>&nbsp;&nbsp;<i>".$lang["change_username"]." ".$hsobj->get_create_by()." ".$lang["change_create_on"]." ".$hsobj->get_create_on()."</i><br><br>";
        }
?>
        </div>
        </td>
    </tr>
<?php
    }
?>

    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["payment_result_remark"])?>1</td>
        <td colspan="3" width="85%" align="left" class="value4"><div style="width:100%; height:45px; overflow-y:scroll">
<?php
        if($sops_obj)
        {
?>
            <?=nl2br($result_remark)?>
<?php
        }
?>      </div></td>
    </tr>
<?php if ($sor_obj)
{
?>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["payment_result_remark"])?>2</td>
        <td colspan="3" width="85%" align="left" class="value4">
            <div style="width:100%; height:100px; overflow-y:scroll">
    <table cellpadding='0' cellspacing='0' border='0'>
        <tr>
<?php
        display_risk("Decision", $risk1);
        display_risk("AVS", $risk2);
        display_risk("CVN", $risk3);
        display_risk("Factor", $risk4);
        display_risk("Score", $risk5);
        display_risk("Suspicious", $risk6);
        display_risk("Velocity", $risk7);
        display_risk("Internet", $risk8);
        display_risk("SmartID", $risk9);
?>
        </tr>
    </table>
<?php
    print str_replace("||", ", ", str_replace("&&" , "<br>", $sor_obj->get_risk_var10()));
?>
            </div>
        </td>
    </tr>
<?php
}
?>
    <tr>
        <td width="15%" id='ordernote' align="left" class="field4"><?=strtoupper($lang["order_notes"])?></td>
        <td colspan="3" width="85%" align="left" class="value4"><form action="<?=$_SERVER["PHP_SELF"]?>" method="POST"><div style="width:100%; height:100px; overflow-y:scroll">
<?php

        if(count($order_note))
        {
            foreach($order_note as $obj)
            {
?>
        <div><b><?=$obj->get_note()?></b><br><i><?=$lang["username"]." ".$obj->get_username()." ".$lang["create_on"]." ".$obj->get_create_on()?></i><br><br></div>
<?php
            }
        }
        else
        {
?>
        <div><b><i><?=$lang["no_order_note"]?></i></b><br><br></div>
<?php
        }
?>
        </div>
<?php
        if($so_obj->get_hold_status() == 15)
        {
            $addnote_html = "<div>Update on Split Child below.</div>";
        }
        else
        {
            $addnote_html = <<<html
                <div>
                    {$lang["create_note"]}<br>
                    <input type="text" name="note" class="input"><input type="hidden" name="addnote" value="1" ><br><br><input type="button" value="{$lang["add_note"]}" onClick="this.form.submit();">
                </div>
html;

        }
        print $addnote_html;
?>

        </form></td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["order_reason"])?></td>
        <td colspan="3" width="85%" align="left" class="value4">
        <?=$so_extend_obj->get_reason()?>
        </td>
    </tr>
    <tr>
        <td width="15%" align="left" class="field4"><?=strtoupper($lang["order_score"])?></td>
        <td colspan="1" width="35%" align="left" class="value4">
            <form action="<?=base_url()."cs/quick_search/update_priority_score/".$so_obj->get_so_no()."/"?>" method="POST">
                <div style="width:100%; height:100px; overflow-y:scroll">
                    <b><?=$lang["now_score"]?><?=$priority_score?></b><br>

                        <div>
                            <table border="0" padding-bottom="0" cellspacing="3">
    <?php
    #sbf #2250 add order score history
        if ($sops_history_obj_list)
        {
            foreach ($sops_history_obj_list as $key => $row)
            {
                echo    "<tr><td>" . 'Score ' . $row->get_score() .
                        "</td><td>" . ' Modified by: ' . "</td><td>" . $row->get_modify_by() .
                        "</td><td>" . ' Modified on: ' . "</td><td>" . $row->get_modify_on() . "</td></tr>";
                // var_dump($row->get_score());
            }
        }

    ?>
                            </table>
                        </div>

<?php
        $editscore_html = "";
        if($so_obj->get_hold_status() == 15)
        {
            $editscore_html = "Update on Split Child below.";
        }
        else
        {
            if(check_app_feature_access_right($app_id, "CS000102_priority_score_edit"))
            {
                $editscore_html = <<<html
                    <div style="width:100%;">
                        <input type="hidden" name="update" value="1" >
                        <input name="biz_type" class="input" value="{$order_obj->get_biz_type()}" type='hidden'>
                        <input name="priority_score" class="input" value="{$priority_score}" notEmpty isNumber min=0>
                        <input type="button" value="{$lang["edit"]}" onClick="this.form.submit();">
                    </div>
html;
                if($sops_history_obj)
                {
                    $editscore_html .= "
                        <div>{$lang["last_score"]}{$sops_history_obj->get_score()} <i>{$lang["last_modify"]} {$sops_last_modify->get_modify_by()} {$lang["change_create_on"]} {$sops_last_modify->get_modify_on()}</i><br><br>
                        </div>";
                }

            }
        }
        print $editscore_html;
?>
                </div>
            </form>
        </td>
<?#sbf #2607 add order refund score?>

<?if($refund_score !== NULL){?>
        <td class="field4" width="15%">
            <?=strtoupper($lang["refund_score"])?>
        </td>
        <td class="value4" width="35%" style="padding:0;">
            <form action="<?=base_url()."cs/quick_search/update_refund_score/".$so_obj->get_so_no()."/"?>" method="POST">
                <strong><?=$lang["current_refund_score"]?><?=$refund_score?></strong><br>
                <div style="width:100%;">
                    <input type="hidden" name="update" value="1" >
                    <?php
                        $selected_sorf = array();
                        $selected_sorf[$refund_score] = "selected";
                    ?>
                    <select name="new_refund_score" style="width:100px">
                        <option value="0" <?=$selected_sorf[0]?>>0</option>
                        <option value="1" <?=$selected_sorf[1]?>>1</option>
                        <option value="2" <?=$selected_sorf[2]?>>2</option>
                    </select>
<?php
                    $refundscore_html = "";
                    if($so_obj->get_hold_status() == 15)
                        $refundscore_html = "<br>Update on Split Child below";
                    else
                        $refundscore_html = "<input type=\"button\" value=\"{$lang["edit"]}\" onClick=\"this.form.submit();\">";

                    print $refundscore_html;
?>
                    <?php
                        if($sorf_history_last_obj)
                        {
                    ?>
                            <div><?=$lang["last_score"]?><?=$sorf_history_last_obj->get_score()?> <i><?=$lang["last_modify"]." ".$sorf_history_last_obj->get_modify_by()." ".$lang["change_create_on"]." ".$sorf_history_last_obj->get_modify_on()?></i><br><br></div>
                    <?php
                        }
                    ?>
                </div>


                <div style="overflow-y:scroll; height:70px; width:100%; border-top:1px solid #B6B6DA;"><table border="0" padding-bottom="0" cellspacing="3">
<?php
                if ($sorf_history_obj_list)
                {
                    foreach ($sorf_history_obj_list as $key => $row)
                    {
                        echo    "<tr><td>" . 'Score ' . $row->get_score() .
                                "</td><td>" . ' Modified by: ' . "</td><td>" . $row->get_modify_by() .
                                "</td><td>" . ' Modified on: ' . "</td><td>" . $row->get_modify_on() . "</td></tr>";
                        // var_dump($row->get_score());
                    }
                }
?>
                </div></table>
            </form>
        </td>
<?}?>

    </tr>
    <!-- stopp  -->
    </table>
    </td>
</tr>
<?php
    if(count($refund_history))
    {
?>
<tr><td style="padding-left:8px" class="header4"><b style="font-size:11px;color:#FFFFFF"><?=$lang["refund_history"]?></b>&nbsp;&nbsp;</td></tr>
<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
<?php
        $ri = 0;
        $rstatus_arr = array("1"=>$lang["cs_requested"],"2"=>$lang["logistics_approved"],"3"=>$lang["cs_manager_approved"],"4"=>$lang["account_refunded"]);
        foreach($refund_history as $key=>$robj)
        {
            $refund = $robj["content"];
            $refund_item = $robj["item"];
            $refund_history = $robj["history"];
            $refund_reason = $robj["reason"];
?>
    <tr>
        <td colspan="2">
        <table width="100%" cellpadding="0" cellspacing="1" border="0" class="tb_list">
        <col width="20%"><col width="30%"><col width="20%"><col width="30%">
        <tr class="refund_row0">
            <td><?=$lang["refund_id"]?></td>
            <td><?=$refund->get_id()?></td>
            <td><?=$lang["request_date"]?></td>
            <td><?=$refund->get_create_on()?></td>
        </tr>
        <tr class="refund_row0">
            <td><?=$lang["refund_reason"]?></td>
            <td><?=$lang["refund_category"][$refund_reason->get_reason_cat()]?>: <?=$refund_reason->get_description()?></td>
            <td><?=$lang["refund_amount"]?></td>
            <td><?=$order_obj->get_currency_id()." ".number_format($refund->get_total_refund_amount(),2)?></td>
        </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td width="60%">
        <div style="height:105px; overflow-y:scroll; width:100%;">
        <table border="0" cellpadding="0" cellspacing="1" bgcolor="#ffffff" width="100%" class="tb_list">
        <col width="3"><col width="120"><col width="120"><col><col width="100"><col width="80">
        <tr class="header">
            <td></td>
            <td><?=$lang["process_time"]?></td>
            <td><?=$lang["refund_stage"]?></td>
            <td><?=$lang["refund_comment"]?></td>
            <td><?=$lang["processed_by"]?></td>
            <td><?=$lang["approval_status"]?></td>
        </tr>
<?php
        foreach($refund_history as $hist)
        {
?>
        <tr class="refund_row1">
            <td></td>
            <td><?=$hist->get_create_on()?></td>
            <td><?=$lang["ristatus"][$hist->get_status()]?></td>
            <td><?=$hist->get_notes()?></td>
            <td><?=$hist->get_name()?></td>
            <td><?=$lang["app_status_arr"][$hist->get_app_status()]?></td>
        </tr>
<?php
        }
?>
        </table>
        </div>
        </td>
        <td width="40%">
        <div style="height:105px; overflow-y:scroll; width:100%;">
        <table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#ffffff" class="tb_list">
        <col width="3"><col><col><col><col>
        <tr class="header">
            <td></td>
            <td><?=$lang["item_sku"]?></td>
            <td><?=$lang["item_refund_qty"]?></td>
            <td><?=$lang["item_refund_status"]?></td>
            <td><?=$lang["refund_type"]?></td>
        </tr>
<?php
        foreach($refund_item as $item)
        {
?>
        <tr class="refund_row1">
            <td></td>
            <td><?=$item->get_item_sku()?></td>
            <td><?=($item->get_qty()?$item->get_qty():"N/A")?></td>
            <td><?=$lang["ristatus"][$item->get_status()]?></td>
            <td><?=$lang["refund_type_arr"][$item->get_refund_type()]?></td>
        </tr>
<?php
        }
?>
        </table>
        </div>
        </td>
    </tr>
<?php
        }
?>
    </table>
    </td>
</tr>
<?php
    }
?>
<?php
    // ############################################ CONSTRUCT EACH CHILD SPLIT ORDER ##################################################################################
    if($child)
    {
        foreach ($child as $child_so_no => $v)
        {
            $child_so_obj = $v["so_obj"];
            $child_order_obj = $v["order_obj"];
            $child_so_no = $child_order_obj->get_so_no();
            $courier = $v["courier"];
            $tracking_no = $v["tracking_no"];
            $child_refund_history = $v["refund_history"];
            $release_html = "";
            $base_url = base_url();

            // EDD form
            $eddform_html = "<input size=\"10\" name='expect_delivery_date' id='expect_delivery_date' value='" . $child_order_obj->get_expect_delivery_date() . "'>";
            $eddform_html .= "<img src='/images/cal_icon.gif' class='pointer' onclick=\"showcalendar(event, document.getElementById('expect_delivery_date'), false, false, false, '2010-01-01')\" align='absmiddle'>";
            $eddform_html .= "<input type='button' value='" . $lang["update"] . "' onclick=\"this.form.submit();\">";

            $childitemdetail_html = "";
            $item = $child_order_obj->get_items();
            $childitemarr = explode("||",$item);
            foreach($childitemarr as $childitemobj)
            {
                $item_detail = explode("::",$childitemobj);
                $childitemdetail_html .= <<<html
                        {$item_detail[0]} - {$item_detail[1]} <font color="red">({$item_detail[2]})</font> x {$item_detail[3]}<br>
html;
            }

            // PRINT INVOICE HTML
            $printinvoice_html = "&nbsp;";
            if (check_app_feature_access_right($app_id, "ORD000403_print_invoice"))
            {
                // #sbf 4145 - custom invoice in HKD
                $printinvoice_html = <<<html
                    <form method='POST'>
                        <input type="button" value="{$lang["print_custom_invoice"]} (HKD)" onClick="this.form.action='{$base_url}order/integrated_order_fulfillment/custom_invoice/hkd';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">&nbsp;&nbsp;&nbsp;
                        <input type="button" value="{$lang["print_custom_invoice"]}" onClick="this.form.action='{$base_url}order/integrated_order_fulfillment/custom_invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">&nbsp;&nbsp;&nbsp;
                        <input type="button" value="{$lang["print_selected_invoice"]}" onClick="this.form.action='{$base_url}order/integrated_order_fulfillment/invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';"><input type="hidden" name="check[{$child_so_no}]" value="{$child_so_no}">
                        <input type="hidden" name="posted" value="1">
                    </form>
html;
            }

            // HOLD/RELEASE button depending on access rights
            if($allow_release)
                $release_html = '<a href="/order/on_hold_admin/oc_index/?so_no='.$child_so_no.'"><button>RELEASE</button></a>';

            // See if customer chasing order
            if($child_order_obj->get_cs_customer_query() & 1)
                $chasingorder_html = $lang["chasing_message"];
            else
                $chasingorder_html = '<input type="checkbox" name="chasing_order" id="chasing_order" value="1">
                                        <input type="button" value="Update" onClick="this.form.submit();">';

            // Order status history
            foreach ($v["history_obj"] as $childhobj)
            {
                $name = $childhobj->get_username() != ""?$childhobj->get_username():"System";
                $hist_status = $childhobj->get_status();
                $hist_createon = $childhobj->get_create_on();
                $orderstatushist_html = <<<html
                    <div><b>{$lang["status_name"][$hist_status]}</b>&nbsp;&nbsp;<i>{$lang["change_username"]} $name {$lang["change_create_on"]} $hist_createon</i><br><br></div>
html;
            }

            // Split time/user info
            $splitcreateby = $child_so_obj->get_split_create_by();
            if(!$splitcreateby) $splitcreateby = "system";
            $splitstatus_html = "<i>Create by {$splitcreateby} On {$child_so_obj->get_split_create_on()}</i>";

            // Hold history
            $holdhistory_row_html = "<td class=\"field6\">&nbsp;</td><td class=\"value6\" colspan='3'>&nbsp;</td>";
            $holdhistorydetail_html = "";
            if(count($v["hold_history"]))
            {
                foreach($v["hold_history"] as $childhsobj)
                {
                    $holdhistorydetail_html .= "<b>{$childhsobj->get_reason()}</b>&nbsp;&nbsp;<i> Set by {$childhsobj->get_username()} On {$childhsobj->get_create_on()}</i><br><br>";
                }
                $holdhistory_row_html = <<<html
                        <td width="15%" align="left" class="field6">Order Hold History</td>
                        <td colspan="3" width="85%" align="left" class="value6">
                            <div style="height:70px; width:100%; overflow-y:scroll;">
                                $holdhistorydetail_html
                            </div>
                        </td>
html;
            }

            // Order notes
            $ordernote_html = "";
            if(count($v["order_note"]))
            {
                foreach($v["order_note"] as $childordernoteobj)
                {
                        $ordernote_html .= "<div><b>{$childordernoteobj->get_note()}</b><br><i>Create by: {$childordernoteobj->get_username()} Created on: {$childordernoteobj->get_create_on()}</i><br><br></div>";
                }
            }
            else
            {
                $ordernote_html = "<div><b><i>No order note.</i></b><br><br></div>";
            }

            // Priority Score history
            $soprioritys_hist_list_html = "";
            if ($v["sops_history_obj_list"])
            {
                foreach ($v["sops_history_obj_list"] as $key => $row)
                {
                    $soprioritys_hist_list_html =
                            "<tr>
                                <td>Score {$row->get_score()}</td>
                                <td>Modified by: </td>
                                <td>{$row->get_modify_by()}</td>
                                <td>Modified on: </td>
                                <td>{$row->get_modify_on()}</td>
                            </tr>";
                }
            }

            // Edit priority score depending on access rights
            $allow_prioritys_edit_html = $soprioritysinfo_html = "";
            if(check_app_feature_access_right($app_id, "CS000102_priority_score_edit"))
            {
                $allow_prioritys_edit_html = <<<html
                        <div style="width:100%;">
                            <input type="hidden" name="update" value="1" >
                            <input name="biz_type" class="input" value="{$child_order_obj->get_biz_type()}" type='hidden'>
                            <input name="priority_score" class="input" value="{$v["priority_score"]}"" notEmpty isNumber min=0>
                            <input type="button" value="Edit" onClick="this.form.submit();">
                        </div>
html;

                // so priority score history
                if($v["sops_history_obj"])
                {
                    $child_sops_score = $v["sops_history_obj"]->get_score();
                    $child_sops_modifyby = $v["sops_history_obj"]->get_modify_by();
                    $child_sops_modifyon = $v["sops_history_obj"]->get_modify_on();
                    $soprioritysinfo_html = "<div>Last Score: $child_sops_score <i>Last Modify : $child_sops_modifyby On: $child_sops_modifyby</i><br><br></div>";
                }
            }

            // Refund score history
            $child_sorf_info_html = $child_sorf_history_obj_list_html = "";
            $refundscore_html = "<td class=\"value6\" colspan='2'>&nbsp;</td>";
            if($v["refund_score"] !== NULL)
            {
                $refund_score = $v["refund_score"];
                $selected_sorf = array();
                $selected_sorf[$refund_score] = "selected";

                // last refund score
                if($v["sorf_history_last_obj"])
                {
                    $child_sorf_score = $v["sorf_history_last_obj"]->get_score();
                    $child_sorf_modify_by = $v["sorf_history_last_obj"]->get_modify_by();
                    $child_sorf_modiyfy_on = $v["sorf_history_last_obj"]->get_modify_on();
                    $child_sorf_info_html = "<div>Last Score : $child_sorf_score <i>Last Modify : $child_sorf_modify_by On : $child_sorf_create_on</i><br><br></div>";
                }

                // refund history list
                if ($v["sorf_history_obj_list"])
                {
                    foreach ($v["sorf_history_obj_list"] as $key => $row)
                    {
                        $child_sorf_history_obj_list_html =  "
                            <tr>
                                <td> Score {$row->get_score()}</td>
                                <td> Modified by: </td>
                                <td>{$row->get_modify_by()}</td>
                                <td> Modified on: </td>
                                <td>{$row->get_modify_on()}</td>
                            </tr>";
                    }
                }

                $refundscore_html = <<<html
                    <td class="field6" width="15%">
                        REFUND SCORE
                    </td>
                    <td class="value6" width="35%" style="padding:0;">
                        <form action="{$base_url}cs/quick_search/update_refund_score/{$child_so_no}" method="POST">
                            <strong>Current Score : $refund_score</strong><br>
                            <div style="width:100%;">
                                <input type="hidden" name="update" value="1" >
                                <select name="new_refund_score" style="width:100px">
                                    <option value="0" $selected_sorf[0]>0</option>
                                    <option value="1" $selected_sorf[1]>1</option>
                                    <option value="2" $selected_sorf[2]>2</option>
                                </select>
                                <input type="button" value="Edit" onClick="this.form.submit();">
                                $child_sorf_info_html
                            </div>

                            <div style="overflow-y:scroll; height:70px; width:100%; border-top:1px solid #B6B6DA;">
                                <table border="0" padding-bottom="0" cellspacing="3">
                                    $child_sorf_history_obj_list_html
                                </table>
                            </div>
                        </form>
                    </td>
html;

            }

            // row of refund history
            $childrefundhistory_html = "";
            if(count($child_refund_history))
            {
                $childrefundhistory_html = <<<html
                    <tr><td style="padding-left:8px" class="header4"><b style="font-size:11px;color:#FFFFFF">{$lang["refund_history"]} - $child_so_no</b>&nbsp;&nbsp;</td></tr>
                    <tr>
                        <td>
                        <table width="100%" cellpadding="0" cellspacing="1" border="0">
html;
                $ri = 0;

                foreach($child_refund_history as $key=>$robj)
                {
                    $refund = $robj["content"];
                    $refund_item = $robj["item"];
                    $refund_history = $robj["history"];
                    $refund_reason = $robj["reason"];

                    $childrefundid = $refund->get_id();
                    $childrefundcreateon = $refund->get_create_on();
                    $childrefundreasoncat = $refund_reason->get_reason_cat();
                    $childrefunddesc = $refund_reason->get_description();
                    $childrefundtotal = number_format($refund->get_total_refund_amount(),2);

                    $refund_id = $lang["refund_id"];
                    $request_date = $lang["request_date"];
                    $refund_reason = $lang["refund_reason"];
                    $refund_category = $lang["refund_category"];
                    $refund_category = $lang["refund_category"];
                    $refund_amount = $lang["refund_amount"];

                    $childrefundhistory_html .= <<<html
                            <tr>
                                <td colspan="2">
                                <table width="100%" cellpadding="0" cellspacing="1" border="0" class="tb_list">
                                <col width="20%"><col width="30%"><col width="20%"><col width="30%">
                                <tr class="refund_row0">
                                    <td>$refund_id</td>
                                    <td>$childrefundid</td>
                                    <td>$request_date</td>
                                    <td>$childrefundcreateon</td>
                                </tr>
                                <tr class="refund_row0">
                                    <td>$refund_reason</td>
                                    <td>$refund_categoryhildrefundreasoncat: $childrefunddesc</td>
                                    <td>$refund_amount</td>
                                    <td>$currid $childrefundtotal</td>
                                </tr>
                                </table>
                                </td>
                            </tr>
                            <tr>
                                <td width="60%">
                                    <div style="height:105px; overflow-y:scroll; width:100%;">
                                    <table border="0" cellpadding="0" cellspacing="1" bgcolor="#ffffff" width="100%" class="tb_list">
                                    <col width="3"><col width="120"><col width="120"><col><col width="100"><col width="80">
                                    <tr class="header">
                                        <td></td>
                                        <td>Process Time</td>
                                        <td>Refund Stage</td>
                                        <td>Refund Comment</td>
                                        <td>Processed by</td>
                                        <td>Approval Status</td>
                                    </tr>
html;
                    foreach($refund_history as $hist)
                    {
                        $create_on = $hist->get_create_on();
                        $status = $hist->get_status();
                        $notes = $hist->get_notes();
                        $name = $hist->get_name();
                        $app_status = $hist->get_app_status();
                        $rihiststatus = $lang["ristatus"][$status];

                        // Process Time, Refund stage
                        $childrefundhistory_html .= <<<html
                                    <tr class="refund_row1">
                                        <td></td>
                                        <td>$create_on</td>
                                        <td>$rihiststatus</td>
                                        <td>$notes</td>
                                        <td>$name</td>
                                        <td>{$lang["app_status_ar"][$app_status]}</td>
                                    </tr>
html;
                    }

                    $childrefundhistory_html .= <<<html
                                    </table>
                                    </div>
                                </td>
                                <td width="40%">
                                    <div style="height:105px; overflow-y:scroll; width:100%;">
                                    <table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#ffffff" class="tb_list">
                                    <col width="3"><col><col><col><col>
                                    <tr class="header">
                                        <td></td>
                                        <td>item_sku</td>
                                        <td>item_refund_qty</td>
                                        <td>item_refund_status</td>
                                        <td>refund_type</td>
                                    </tr>
html;

                    foreach($refund_item as $item)
                    {
                        $itemsku = $item->get_item_sku();
                        $itemqty = $item->get_qty()?$item->get_qty():"N/A";
                        $ristatus = $lang["ristatus"][$item->get_status()];
                        $refundtype = $lang["refund_type_arr"][$item->get_refund_type()];

                        // refund items
                        $childrefundhistory_html .= <<<html
                                    <tr class="refund_row1">
                                        <td></td>
                                        <td>$itemsku</td>
                                        <td>$itemqty</td>
                                        <td>$ristatus</td>
                                        <td>$refundtype</td>
                                    </tr>
html;
                    }

                    $childrefundhistory_html .= <<<html
                                    </table>
                                    </div>
                                </td>
                            </tr>
html;


                }

                $childrefundhistory_html .= <<<html
                        </table>
                        </td>
                    </tr>
html;

            }

?>
<tr>
    <!-- CHILD SPLIT HTML HERE -->
    <td style="padding-left:8px;padding-top:5px" class="header6"><b style="font-size:11px;color:#ffffff;"><a name="<?=$child_so_no?>">CHILD <?=$lang["order_detail"]?> - <?=$child_so_no?></a></b>
    <div style="float:right">
        <a href="/order/on_hold_admin?so_no=<?=$child_so_no?>&search=1"><button>HOLD</button></a>
            <?=$release_html?>
    </div>
    </td>
</tr>
<tr style="padding-left:8px" class="header6">
    <td><div style="float:right"><?=$printinvoice_html?></div></td>
</tr>
<tr>
    <td>
        <table width="100%" cellpadding="0" cellspacing="1" border="0">
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["order_number"])?></td>
                <td width="35%" align="left" class="value6"><?=$child_so_no?></td>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["order_type"])?></td>
                <td width="35%" align="left" class="value6"><?=$order_obj->get_platform_id()." - ".$order_obj->get_biz_type()?><br></td>
            </tr>
            <tr>
                <td width="15%" id="itemlist" align="left" class="field6">ITEM LIST<br><button onClick="window.open('<?=$base_split_url?><?=$child_so_no?>');">Split Order</button></td>
                <td colspan="3" width="85%" align="left" class="value6">
                    <div style="width:100%; height:80px; overflow-y:scroll">
                        <?=$childitemdetail_html?>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6">SPLIT INFO</td>
                <td width="35%" align="left" class="value6"><?=$splitstatus_html?></td>
                <td width="15%" align="left" class="field6">&nbsp;</td>
                <td width="35%" align="left" class="value6">&nbsp;</td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["website_status"])?></td>
                <td width="35%" align="left" class="value6"><?=$website_status?></td>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["expected_delivery_date"])?></td>
                <td width="35%" align="left" class="value6">
                <form name="edd_form" id="edd_form" method="post" action="<?=base_url()."cs/quick_search/update_edd/" . $child_so_no . "/"?>">
                    <?=$eddform_html?>
                </form>
                </td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["cost_wo_del"])?></td>
                <td width="35%" align="left" class="value6"><?=$child_order_obj->get_currency_id()." ".number_format($child_order_obj->get_amount() - $child_order_obj->get_delivery_charge(),2)?></td>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["del_cost"])?></td>
                <td width="35%" align="left" class="value6"><?=$child_order_obj->get_currency_id()." ".$child_order_obj->get_delivery_charge()?></td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["total_paid"])?></td>
                <td width="35%" align="left" class="value6"><?=$child_order_obj->get_currency_id()." ".number_format($child_order_obj->get_amount() ,2)?></td>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["ship_service_level"])?></td>
                <td width="35%" align="left" class="value6"><?=$del_opt_list[$child_order_obj->get_delivery_mode()]->get_display_name()?></td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"></td>
                <td width="35%" align="left" class="value6"></td>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["promotion_code"])?></td>
                <td width="35%" align="left" class="value6"><?=$child_so_obj->get_promotion_code()?></td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["order_create_date"])?></td>
                <td width="35%" align="left" class="value6"><?=$child_so_obj->get_order_create_date()?></td>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["packed_on"])?></td>
                <td width="35%" align="left" class="value6"><?=$packed_on?></td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["status"])?></td>
                <td width="35%" align="left" class="value6"><?=$lang["status_name"][$child_so_obj->get_status()]?></td>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["shipped_on"])?></td>
                <td width="35%" align="left" class="value6"><?=$shipped_on?></td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["customer_status"])?></td>
                <td width="35%" align="left" class="value6"><b><?=$v["status"]["status"]?></b><br /><?=$v["status"]["desc"]?></td>
                <!-- DELIVERY FRAME SHOULD BE SAME AS PARENT'S -->
                <td width="15%" align="left" class="field6">DELIVERY FRAMES SHOWN TO CUSTOMER</td>
                <td width="35%" align="left" class="value6"><?="$ship_text<br>$del_text"?></td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["courier"])?></td>
                <td width="35%" align="left" class="value6"><?=$courier?></td>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["tracking_no"])?></td>
                <td width="35%" align="left" class="value6"><?=$tracking_no?></td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["chasing_order"])?></td>
                <td width="35%" align="left" class="value6">
                    <form name="chasing_order_form" id="chasing_order_form" method="post" action="<?=base_url()."cs/quick_search/update_cs_order_query/" . $child_so_no . "/"?>">
                        <?=$chasingorder_html?>
                    </form>
                </td>
                <td width="15%" align="left" class="field6">&nbsp;</td>
                <td width="35%" align="left" class="value6">&nbsp;</td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["status_history"])?></td>
                <td colspan="3" width="85%" align="left" class="value6">
                    <div style="width:100%; height:70px; overflow-y:scroll">
                         <?=$orderstatushist_html?>
                    </div>
                </td>
            </tr>
            <tr>
                <?=$holdhistory_row_html?>
            </tr>
            <tr>
                <td width="15%" id='ordernote' align="left" class="field6"><?=strtoupper($lang["order_notes"])?></td>
                <td colspan="3" width="85%" align="left" class="value6">
                    <form action="<?=base_url()."cs/quick_search/view/$child_so_no"?>" method="POST">
                        <div style="width:100%; height:100px; overflow-y:scroll">
                            <?=$ordernote_html?>
                        </div>
                        <div>
                            <?=$lang["create_note"]?><br><input type="text" name="note" class="input"><input type="hidden" name="addnote" value="1" ><br><br>
                            <input type="button" value="<?=$lang["add_note"]?>" onClick="this.form.submit();">
                        </div>
                    </form>
                </td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["order_reason"])?></td>
                <td colspan="3" width="85%" align="left" class="value6">
                <?=$v["so_extend_obj"]->get_reason()?>
                </td>
            </tr>
            <tr>
                <td width="15%" align="left" class="field6"><?=strtoupper($lang["order_score"])?></td>
                <td colspan="1" width="35%" align="left" class="value6">
                    <form action="<?=base_url()."cs/quick_search/update_priority_score/{$child_so_no}/"?>" method="POST">
                        <div style="width:100%; height:100px; overflow-y:scroll">
                            <b><?=$lang["now_score"]?><?=$v["priority_score"]?></b><br>

                                <div>
                                    <table border="0" padding-bottom="0" cellspacing="3">
                                        <?=$soprioritys_hist_list_html?>
                                    </table>
                                </div>
                                <!-- appears if can edit proority score -->
                                <?=$allow_prioritys_edit_html?>
                                <?=$soprioritysinfo_html?>
                        </div>
                    </form>
                </td>

                <!-- Appears if have refund score -->
                <?=$refundscore_html?>

            </tr>
        </table>
    </td>
</tr>
<?=$childrefundhistory_html?>
<!-- // ############################################ END CONSTRUCT EACH CHILD SPLIT ORDER ################################################################################## -->

<?php
        } // end of foreach child
    } //end of if child
?>

<tr>
    <td>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <col width="15%"><col width="85%">
    <td style="padding-left:8px" class="header5"><b style="font-size:11px;color:#000000"><?=$lang["order_detail"]?></b></td>
    <td style="padding-left:8px" class="header5"><b style="font-size:11px;color:#000000"><input type="button" value="Request Compensation" onClick="window.open('/cs/compensation_create/create_view/<?=$order_obj->get_so_no()?>/', 'compensation', 'width=1280 height=600,scrollbars=yes')"></b></td>
    </table>
</tr>
<tr>
<?php if ($priority_score_highlight == 1)
    {
        $detail_background = "#FF4800";
    }
    else if ($priority_score_highlight == 2)
    {
        $detail_background = "#FFA00C";
    }
    else
    {
        $detail_background = "#eeeeff";
    }
    if (check_app_feature_access_right($app_id, "CS000102_absolute_profit"))
        $colspan = 9;
    else
        $colspan = 7;

    if($so_obj->get_hold_status() == 15)
        $parentsplit_html = "<tr bgcolor='$detail_background' style='font-weight:bold;'><td colspan='$colspan' >This parent has been split.</td></tr>";
    else
    {
        $parentsplit_html = "";
        if($allow_split)
        {
            $parentsplit_html = "
                <tr bgcolor='$detail_background' style='font-weight:bold;'>
                    <td colspan='$colspan' style='padding-left:5px;border: 1px #CCCCCC solid;'><button onClick=\"window.open('{$base_split_url}{$so_obj->get_so_no()}');\">Split Order</button></td>
                </tr>";
        }
    }
?>

    <td style="background-color: <?=$detail_background?>;">
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
    <col width="100"><col><col width="100"><col width="100"><col width="100">
    <?=$parentsplit_html?>
    <tr bgcolor="<?=$detail_background?>" style="font-weight:bold;">
        <td style="border: 1px #CCCCCC solid;">&nbsp;</td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$lang["product_name"]?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$lang["warranty_in_month"]?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$lang["product_price"]?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$lang["gst_total"]?></td>
<?php if (check_app_feature_access_right($app_id, "CS000102_absolute_profit")) {?>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$lang["profit"]?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$lang["margin"]?></td>
<?  }?>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$lang["product_qty"]?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$lang["product_total"]?></td>
    </tr>
<?php
    $ve = 0;
    if($so_extend_obj)
    {
        $ve = $so_extend_obj->get_vatexempt();
    }
    $cost = 0;
    $total = 0;
    $item = $order_obj->get_items();
    $item_arr = explode("||",$item);
    foreach($item_arr as $obj)
    {
        $item_detail = explode("::",$obj);
?>
    <tr bgcolor="<?=$detail_background?>">
        <td align="center" style="border: 1px #CCCCCC solid;"><img src="<?=get_image_file($item_detail[7],'s',$item_detail[0])?>" border="0" style="margin:2px;"></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$item_detail[0]." - ".$item_detail[1]?> <font color="red">(<?=$item_detail[2]?>)</font></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$item_detail[8]?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$order_obj->get_currency_id()." ".($item_detail[4] - $ve * $item_detail[6] / $item_detail[3])?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$order_obj->get_currency_id()." ".$item_gst_total[$item_detail[0]]?></td>
<?php if (check_app_feature_access_right($app_id, "CS000102_absolute_profit")) {?>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$order_obj->get_currency_id()." ".$item_profit[$item_detail[0]]?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$item_margin[$item_detail[0]]?></td>
<?  }?>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$item_detail[3]?></td>
        <td style="padding-left:5px;border: 1px #CCCCCC solid;"><?=$order_obj->get_currency_id()." ".($item_detail[5] - $ve * $item_detail[6])?></td>
    </tr>
<?php
        $total += $item_detail[5] - $ve * $item_detail[6];
    }

    $type = "";
    if($so_extend_obj)
    {
        if($order_obj->get_biz_type() == "OFFLINE" )
        {
            $fee = $so_extend_obj->get_offline_fee();
        }

        $dc_message = "";

        if($fee < 0)
        {
            $dc_message = $lang["discount"]." : ".$order_obj->get_currency_id()." ".$fee;
        }

        if($fee > 0)
        {
            $dc_message = $lang["offline_fee"]." : ".$order_obj->get_currency_id()." ".$fee;
        }
    }
?>
    <tr bgcolor="<?=$detail_background?>">
        <td colspan="2" style="padding-left:10px; font-size: 14px; font-weight:bold; color:#bb0000;"><?=($so_extend_obj && $so_extend_obj->get_vatexempt() == 1?$lang["vatex_order"]:"&nbsp;")?></td>
        <td colspan="7" align="right">
        <table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#666666;">
        <col width="50%"><col width="50%">
        <tr bgcolor="#eeeeff">
            <td width="100%" colspan="2" align="right" style="padding-right:15px;">
                <?=$lang["total_gst"]." : ".$order_obj->get_currency_id()." ".number_format($total_gst,2)?><br/>
                <?=$lang["order_amount"]." : ".$order_obj->get_currency_id()." ".number_format($total,2)?><br/>
                <?=$lang["delivery_charge"]." : ".$order_obj->get_currency_id()." ".$order_obj->get_delivery_charge()?><br/>
                <?=$dc_message?>
            </td>
        </tr>
        <tr>
            <td align="right" style="background-color:#cccccc; padding-right:5px; font-weight:bold; font-size:11px;"><?=strtoupper($lang["total_cost"])?></td>
            <td align="right" style="background-color:#ff0000; padding-right:15px; color:#ffffff; font-weight:bold; font-size:11px;"><?=$order_obj->get_currency_id()." ".number_format($order_obj->get_amount() ,2)?></td>
        </tr>
<?php if (check_app_feature_access_right($app_id, "CS000102_absolute_profit")) {?>
        <tr>
            <td align="right" style="background-color:#cccccc; padding-right:5px; font-weight:bold; font-size:11px;"><?=strtoupper($lang["absolute_profit"])?></td>
            <td align="right" style="background-color:#ff0000; padding-right:15px; color:#ffffff; font-weight:bold; font-size:11px;"><?=$order_obj->get_currency_id()." ".number_format($total_profit, 2)?></td>
        </tr>
<?  }?>
        </table>
        </td>
    </tr>
    </table>
    </td>
</tr>
</table>
<?php
if ($so_list)
{
?>
    <div id='linkage_header'><?=$lang["linked_order"]?></div>
    <table id="linkage_table" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th><?=$lang["order_number"]?></th>
                <th><?=$lang["order_type"]?></th>
                <th width="50%"><?=$lang["additional_information"]?></th>
                <th><?=$lang["status"]?></th>
<!--                <th><?=$lang["hold_status"]?></th> -->
                <th><?=$lang["refund_status"]?></th>
            </tr>
<?php
    $i = 0;
    foreach($so_list as $so)
    {
        if ($i == 0)
            $styleName = "class='firstOrder'";
        else
            $styleName = "";
        if ($so_obj->get_so_no() == $so->get_so_no())
            $is_self = "(self)";
        else
            $is_self = "";
        print "<tr " . $styleName . ">";
            if ($is_self != "")
                print "<td>" . $so->get_so_no() . $is_self . "</td>";
            else
                print "<td><a style=\"text-decoration: underline\" target='_blank' href='/cs/quick_search/view/" . $so->get_so_no() . "'>" . $so->get_so_no() . "</a></td>";
            print "<td>" . $so->get_biz_type() . "</td>";
            if ($so->get_biz_type() != "SPECIAL")
                print "<td>" . $so->get_platform_id() . "</td>";
            else
            {
                if ($so->get_order_reason_note())
                    $notes = ", notes:" . $so->get_order_reason_note();
                else
                    $notes = "";
                print "<td>" . $so->get_reason_display_name() . $notes . "</td>";
            }
            if ($so->get_biz_type() == "SPECIAL")
                print "<td>" . $lang["special_order_status"][$so->get_status()] . "</td>";
            else
                print "<td>" . $lang["order_status"][$so->get_status()] . "</td>";
/*
            if ($so->get_hold_status() != 0)
                print "<td>" . $so->get_hold_reason() . "</td>";
            else
                print "<td>" . $lang["no"] . "</td>";
*/
            if ($so->get_refund_status() != 0)
                print "<td>" . $lang["refund_status_progress"][$so->get_refund_status_progress()] . "</td>";
            else
                print "<td>" . $lang["no"] . "</td>";
        print "</tr>";
        $i++;
    }
?>
        </tbody>
    </table>
<?php
}
?>
<iframe name="printframe" src="" width="0" height="0" frameborder="0" scrolling="no"></iframe>
</div>
<?=$notice["js"]?>
</body>
</html>