<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>supply/supplier_helper/js_supplist"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/profit_var_helper/js_platformlist"></script>
<!--<script type="text/javascript" src="<?=base_url()?>mastercfg/region_helper/js_sourcing_region"></script>-->
<script type="text/javascript" src="<?=base_url()?>mastercfg/exchange_rate_helper/js_xratelist/<?=$default_curr?>"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/freight_helper/js_freight_cat"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/lytebox.js"></script>
<script>
    ar_creditor = ["<?=$lang["no"]?>", "<?=$lang["yes"]?>"];
</script>
</head>
<body style="width:auto;">
<?php //var_dump($lang); ?>
<div id="main" style="width:auto;">
<?=$notice["img"]?>
<?php
    $ars_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"], "P" => $lang["pre-order"]);
?>
<?$ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"], "P" => $lang["pre-order"]);?>
<table class="page_header" border="0" cellpadding="0" cellspacing="0" height="70" width="100%">
    <tr>
        <td height="70" style="padding-left:8px">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td height="70" style="padding-left:8px;line-height:17px;">
                        <div style="float:left"><img src='<?=get_image_file($prod["low_profit"]->get_image(), 's', $prod["low_profit"]->get_sku())?>'> &nbsp;</div>
                        <b style="font-size:14px"><?=$prod["low_profit"]->get_prod_name()?><?=$prod["low_profit"]->get_clearance()?" <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>":""?></b>
                        <br>
                        <?=$lang["sku"]?>: <b><?=$prod["low_profit"]->get_sku()?></b> &nbsp; &nbsp; &nbsp;
                        <?=$lang["master_sku"]?>: <b><?=$master_sku?></b> &nbsp; &nbsp; &nbsp;
                        <?=$lang["freight_category"]?>: <b>
                        <?php
                            if ($prod["low_profit"]->get_freight_cat_id())
                            {
                        ?>
                            <script>w(fcatlist[<?=$prod["low_profit"]->get_freight_cat_id()?>][0])</script>
                        <?php
                            }
                        ?>
                        </b>
                        <br>
                        <?=$lang["ean"]?>: <b><?=$prod["low_profit"]->get_ean()?></b> &nbsp; &nbsp; &nbsp;
                        <?=$lang["mpn"]?>: <b><?=$prod["low_profit"]->get_mpn()?></b> &nbsp; &nbsp; &nbsp;
                        <?=$lang["upc"]?>: <b><?=$prod["low_profit"]->get_upc()?></b>
                        <br>
                        <?php
                            if ($prod["low_profit"]->get_price())
                            {
                        ?>
                        <?=$lang["selling_price"]?>: <b><?=$default_curr?> <script>w((exc('<?=$prod["low_profit"]->get_platform_currency_id()?>', '<?=$default_curr?>', <?=$prod["low_profit"]->get_price()?>)*1).toFixed(2))</script></b> &nbsp; &nbsp; &nbsp;
                        <?=$lang["stock_qty"]?>: <b><?=$inventory?$inventory->get_inventory():"0"?></b> &nbsp; &nbsp; &nbsp;
                        <?=$lang["profit_margin"]?>: <b id="b_margin"><?=$prod["low_profit"]->get_margin()?>%</b> <span class="remark">(<?=$lang["lowest_profit_base_on"]?> <?=$lang["platform"]?>: <script>w(platformlist['<?=$prod["low_profit"]->get_platform_id()?>'])</script> &nbsp;&nbsp;)</span>
                        <?php
                            }
                            else
                            {
                        ?>
                        <span class="warn"><b><?=$lang["selling_price_not_set"]?></b></span>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
<!--        <td width="200" valign="top" align="right" style="padding-right:8px"><br><?=$lang["suppliers_found"]?>: <b><?=count($purchaser)?></b><br><br>

        </td>-->
    </tr>
</table>
<?php
    if (method_exists($brand, "get_regions") && $brand->get_regions())
    {
?>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
    <tr class="marked warn"><td style="padding-left:8px;"><?=$lang["restricted_region"]?>: <?=$brand->get_regions()?></td></tr>
</table>
<?php
    }
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
<form name="fm_search" method="get">
        <tr class="header" valign="top">
        <td style="line-height:14px;"><?=$lang["last_update"]?></td>
        <td width="40"></td>
        <td><?=$lang["supplier_name"]?></td>
        <td style="line-height:14px;"><?=$lang["origin_country"]?></td>
        <td><?=$lang["creditor"]?></td>
        <td><?=$lang["status"]?></td>
        <td width="50"><?=$lang["moq"]?></td>
        <td width="85"><?=$lang["freight_cost"]?></td>
        <td width="85"><?=$lang["local_cost"]?></td>
        <td width="85"><?=$lang["total_cost"]?></td>
        <td width="20"></td>
    </tr>
    <input type="hidden" name="freight_region" value="">
</form>
<form name="fm" method="post" onSubmit="return CheckForm(this)">
<input type="hidden" name="order_default">
<input type="hidden" name="region_default">
<?php
    $i=0;
    if ($purchaser)
    {
        foreach ($purchaser as $obj)
        {
            $tc_name = "sp[{$obj->get_supplier_id()}][total_cost]";
            if ($obj->get_order_default())
            {
?>
                <script>document.fm.order_default.value=<?=$obj->get_supplier_id()?>;</script>
<?php
            }

            if ($this->input->get("sourcing_reg") == 0 || $obj->get_sourcing_reg() == $this->input->get("sourcing_reg"))
            {
?>
    <tr class="row<?=$i%2?>" onMouseOver="AddClassName(this, 'highlight')" onMouseOut="RemoveClassName(this, 'highlight')">
        <td style="line-height:14px;"><?=substr($obj->get_modify_on(), 0, 10)?></td>
        <td>
            <?php
                if ($obj->get_order_default())
                {
            ?>
                <span class="block">
                <?php
                    if($this->input->get("sourcing_reg") != 0 && $obj->get_region_default() != $this->input->get("sourcing_reg"))
                    {
                ?>
                    <input name="o_default" type="radio" value="<?=$obj->get_supplier_id()?>" onClick="ChangeDefault(this);">
                <?php
                    }
                ?>
                </span>
                <img src='/images/tick.gif'>
            <?php
                }
                /*
                elseif ($obj->get_region_default())
                {
            ?>
                <span class="block">
                <?php
                    if($obj->get_region_default() != $this->input->get("sourcing_reg"))
                    {
                ?>
                    <input name="o_default" type="radio" value="<?=$obj->get_supplier_id()?>" onClick="ChangeDefault(this);">
                <?php
                    }
                ?>
                </span>
                <img src='/images/tick.gif'>
            <?php
                }
                */
                else
                {
            ?>
                <span class="block">
                <!--<input name="o_default" type="radio" value="<?=$obj->get_supplier_id()?>" onClick="ChangeDefault(this);">-->
                </span>
            <?php
                }
            ?>
        </td>
        <td style="line-height:14px;"><a href="<?=base_url()?>supply/supplier/view/<?=$obj->get_supplier_id()?>/1" rel="lyteframe[supplier_note]" rev="width: 800px; height: 275px; scrolling: auto;" title="<?=$lang["supplier_note"]?> - <?=$obj->get_supplier_name()?>"><?=$obj->get_supplier_name()?></a>
        </td>
        <td nowrap style='white-space: nowrap'><?=$obj->get_origin_country()?></td>
        <td><script>w(ar_creditor[supplist[<?=$obj->get_supplier_id()?>][3]])</script></td>
        <td>
            <?php
                $ss_selected = array();
                $ss_selected[$obj->get_supplier_status()] = "SELECTED";
            ?>
            <select name="sp[<?=$obj->get_supplier_id()?>][supplier_status]" onChange="if (ChkChg(this, '<?=$obj->get_supplier_status()?>')) this.form.elements['check[<?=$obj->get_supplier_id()?>]'].checked=true; else this.form.elements['check[<?=$obj->get_supplier_id()?>]'].checked=false;">
                <option value="A" <?=$ss_selected["A"]?>><?=$ars_status["A"]?>
                <option value="C" <?=$ss_selected["C"]?>><?=$ars_status["C"]?>
                <option value="O" <?=$ss_selected["O"]?>><?=$ars_status["O"]?>
                <option value="L" <?=$ss_selected["L"]?>><?=$ars_status["L"]?>
                <option value="D" <?=$ss_selected["D"]?>><?=$ars_status["D"]?>
                <option value="P" <?=$ss_selected["P"]?>><?=$ars_status["P"]?>
            </select>
        </td>
        <td><input class="input" name="sp[<?=$obj->get_supplier_id()?>][moq]" value="<?=$obj->get_moq()?>" notEmpty isInteger min="0" onKeyUp="if (ChkChg(this, <?=$obj->get_moq()?>)) this.form.elements['check[<?=$obj->get_supplier_id()?>]'].checked=true; else this.form.elements['check[<?=$obj->get_supplier_id()?>]'].checked=false;"></td>
        <td>
            <?php
                if($prod["low_profit"]->get_freight_cost()) {
            ?>
            <?=$default_curr?>
            <script>w(exc('<?=$prod["low_profit"]->get_platform_currency_id()?>', '<?=$default_curr?>', <?=$prod["low_profit"]->get_freight_cost()?>))</script>
            <?php
                }
            ?>
        </td>
        <td><?=$obj->get_currency_id()?> <input class="int_input" name="sp[<?=$obj->get_supplier_id()?>][cost]" value="<?=$obj->get_cost()?>" onKeyUp="if (ChkChg(this, <?=$obj->get_cost()?>)){ this.form.elements['sp[<?=$obj->get_supplier_id()?>][supplier_status]'].value='A'; this.form.elements['check[<?=$obj->get_supplier_id()?>]'].checked=true;} else{ this.form.elements['sp[<?=$obj->get_supplier_id()?>][supplier_status]'].value='<?=$obj->get_supplier_status()?>'; this.form.elements['check[<?=$obj->get_supplier_id()?>]'].checked=false} exc('<?=$obj->get_currency_id()?>', '<?=$default_curr?>', this.value, document.fm.elements['<?=$tc_name?>']);<?if ($obj->get_order_default()){?>CalcProfit(this.value)<?}?>" notEmpty isNumber min="0"></td>
        <td><?=$default_curr?> <input name="<?=$tc_name?>" class="int_input read" style="text-align:left;" value="<?=number_format($obj->get_total_cost(), 2, ".", "")?>" READONLY></td>
        <td>
                <input type="checkbox" name="check[<?=$obj->get_supplier_id()?>]" value="<?=$obj->get_supplier_id()?>" onClick='Marked(this);'>
        </td>
    </tr>
<?php
            $i++;
            }
        }
    }
?>
    <tr>
        <td colspan="11" align="right" class="bg_row" style="padding-right:8px">
            <input type="submit" value="<?=$lang["update_button"]?>" class="button"> &nbsp; &nbsp; &nbsp;
            <!--<input type="button" value="<?=$lang["delete_button"]?>" class="button" onClick="Delete(this.form)">-->
        </td>
    </tr>
</table>
<input type="hidden" name="sku" value='<?=$sku?>'>
<input type="hidden" name="sourcing_reg" value='<?=$this->input->get("sourcing_reg")?>'>
<input type="hidden" name="cmd" value='edit'>
<input type="hidden" name="posted" value='1'>
</form>
<!--
<form name="fm_add" action="<?=base_url()?>supply/purchaser/add" method="post" onSubmit="return CheckForm(this)">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <tr class="header">
        <td height="20" colspan="4"><?=strtoupper($lang["add_new_supplier"])?></td>
    </tr>
    <tr class="bg_row">
        <td width="400">
            <select name="supplier_id" class="input" onChange="ChgSupp()" notEmpty>
            <option value="">
            </select>
        </td>
        <td width="100">
            <?=$lang["moq"]?>:
            <input name="moq" value="<?=htmlspecialchars($supp_prod->get_moq())?>" class="int_input" notEmpty isInteger min="0">
        </td>
        <td>
            <?=$lang["cost"]?>:
            <input name="cost" value="<?=htmlspecialchars($supp_prod->get_cost())?>" class="int_input" notEmpty isNumber min="0">
            <input name="currency_id" value="<?=htmlspecialchars($supp_prod->get_currency_id())?>" size="3" class="read" READONLY notEmpty>
        </td>
        <td align="right" style="padding-right:8px">
            <input type="submit" value="<?=$lang["add_new_supplier"]?>" class="button">
        </td>
    </tr>
</table>
<input type="hidden" name="prod_sku" value='<?=$sku?>'>
<input type="hidden" name="posted" value='1'>
<input type="hidden" name="cmd" value='add'>
</form>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <tr class="header">
        <td height="20" colspan="2"><?=strtoupper($lang["product_notes"])?></td>
    </tr>
    <tr>
        <td width="150" class="field"><?=$lang["max_cost"]?><br>
            <form name="fm_fr">
                <select name="freight_region" onChange="document.fm_search.freight_region.value=this.value;document.fm_search.submit()">
                </select>
            </form>
        </td>
        <td class="value">
            <?php
                if ($prod["max_cost"])
                {
                    krsort($prod["max_cost"]);
                    foreach ($prod["max_cost"] as $plat_mc)
                    {
            ?>
                        <b><?=$default_curr?> <script>w(exc('<?=$plat_mc->get_platform_currency_id()?>', '<?=$default_curr?>', <?=$plat_mc->get_supplier_cost()?>))</script></b> <span class="remark">(<?=$lang["platform"]?>: <script>w(platformlist['<?=$plat_mc->get_platform_id()?>'])</script> &nbsp;&nbsp; <?=$lang["ship_type"]?>: <?=$plat_mc->get_shiptype_name()?> &nbsp;&nbsp; <?=$lang["selling_price"]?>: <?=$default_curr?> <?if (!is_null($plat_mc->get_price())) { ?><script>w(exc('<?=$plat_mc->get_platform_currency_id()?>', '<?=$default_curr?>', <?=$plat_mc->get_price()?>))</script><?}?>)</span>
                        <br>
            <?php
                    }
                }
            ?>
        </td>
    </tr>
    <tr>
        <td class="field"><?=$lang["sourcing_status"]?></td>
        <td class="value">
                <form name="fm_status" action="<?=base_url()?>supply/purchaser/update_status/<?=$sku?>" method="post">
                    <?php
                        $selected_src[$prod["low_profit"]->get_sourcing_status()] = "SELECTED";
                    ?>
                    <select name="sourcing_status" onChange="this.form.submit();">
                    <?php
                        foreach ($ar_src_status as $rskey=>$rsvalue)
                        {
                    ?>
                        <option value="<?=$rskey?>" <?=$selected_src[$rskey]?>><?=$rsvalue?>
                    <?php
                        }
                    ?>
                    </select>
                    <input type="hidden" name="posted" value='1'>
                </form>
        </td>
    </tr>
    <tr>
        <td class="field" rowspan="2"><?=$lang["sourcing_notes"]?></td>
        <td class="value">
            <?php
                if ($note_objlist)
                {
                    foreach ($note_objlist as $note_obj)
                    {
            ?>
                        <p class="normal_p"><?=nl2br($note_obj->get_note())?></p><p class="normal_p comment"><?=$lang["create_by"]?>: <?=$note_obj->get_username()?> &nbsp; &nbsp; <?=$lang["create_on"]?>: <?=$note_obj->get_create_on()?><br><br></p>
            <?php
                    }
                }
            ?>
        </td>
    </tr>
    <tr>
        <td class="value" valign="top">
            <form name="fm_note" action="<?=base_url()?>supply/purchaser/add_note/<?=$sku?>" method="post">
                <table cellspacing=0 cellpadding=0 width=100%>
                    <tr>
                        <td style="border:0px;" width="95%">
                            <textarea rows="4" name="note" style="height:80px; width:100%"></textarea>
                        </td>
                        <td style="border:0px;">
                            <input type="submit" style="height:80px;width:20px;" value="+">
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="posted" value='1'>
            </form>
        </td>
    </tr>
</table>
InitSupp(document.fm_add.supplier_id);
document.fm_add.supplier_id.value = '<?=$supp_prod->get_supplier_id()?>';
ChangeSupp(document.fm_add.supplier_id.value, document.fm_add.currency_id);

function ChgSupp()
{
    if (document.fm_add.supplier_id.value != "" && document.fm_add.moq.value == "")
    {
        document.fm_add.moq.value = 1;
    }
    ChangeSupp(document.fm_add.supplier_id.value, document.fm_add.currency_id);
}

-->
<script>
function ChangeDefault(el)
{
    if (confirm("<?=$lang["change_default"]?>"))
    {
        el.form.<?=$this->input->get("sourcing_reg")?"region_default":"order_default"?>.value = el.value;
        el.form.submit();
    }
    else
    {
        el.checked = false;
    }
}
function Delete(f)
{
    if (confirm("<?=$lang["delete_supplier"]?>"))
    {
        f.action = "<?=base_url()?>supply/purchaser/delete";
        check_eles = getEle(f, "input", "name", 'check');
        for (i=0; i<check_eles.length; i++)
        {
            check_ele = check_eles[i];
            if (check_ele.checked && f.order_default.value == check_ele.value)
            {
                alert("<?=$lang["cannot_del_default_supplier"]?>");
                check_ele.focus();
                return false;
            }
        }
        f.submit();
    }
}

function CalcProfit(supplier_cost)
{
/*
    var price = <?=$prod["low_profit"]->get_price()*1?>;
    var declared = <?=$prod["low_profit"]->get_declared_pcent()*1?> * price / 100;
    declared = declared.toFixed(2)*1;
    var freight_cost = <?=$prod["low_profit"]->get_freight_cost()*1?>;
    var vat_pcent = <?=$prod["low_profit"]->get_vat_percent()*1?>;
    var vat = (declared + freight_cost) * vat_pcent  / 100;
    vat = vat.toFixed(2)*1;
    var duty = <?=$prod["low_profit"]->get_duty_pcent()*1?> / 100 * declared;
    duty = duty.toFixed(2)*1;
    var payment = <?=$prod["low_profit"]->get_payment_charge_percent()*1?> / 100 * price;
    payment = payment.toFixed(2)*1;
    var admin_fee = <?=$prod["low_profit"]->get_admin_fee()*1?>;
    var delivery_cost = <?=$prod["low_profit"]->get_delivery_cost()*1?>;
    var fdl = <?=$prod["low_profit"]->get_free_delivery_limit()*1?>;
    var ddc = <?=$prod["low_profit"]->get_default_delivery_charge()*1?>;
    var delivery_charge = (fdl > 0 && price > fdl?0:ddc);
    delivery_charge = delivery_charge.toFixed(2)*1;

    var total = price + delivery_charge;
    total = total;
    var cost =  vat*1 + duty*1 + payment*1 + admin_fee*1 + freight_cost*1 + delivery_cost*1 + supplier_cost*1;
    cost = cost.toFixed(2);
    var profit = price*1 + delivery_charge*1 - vat*1 - duty*1 - payment*1 - admin_fee*1 - freight_cost*1 - delivery_cost*1 - supplier_cost*1;
    profit = profit.toFixed(2);
    var margin = profit / price * 100;
    margin = margin.toFixed(2);

    document.getElementById('b_margin').innerHTML = margin+'%';
*/
}
/*
InitSrcReg(document.fm_search.sourcing_reg);
document.fm_search.sourcing_reg.value = '<?=$this->input->get("sourcing_reg")?>';

InitSrcReg(document.fm_fr.freight_region);
document.fm_fr.freight_region.value = '<?=$freight_region?>';
*/
</script>
</div>
<?=$notice["js"]?>
</body>
</html>