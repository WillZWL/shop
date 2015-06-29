<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script language="javascript">
<!--
shiptype = new Array();
<?php
    if(!empty($shiptype))
    {
        $i = 0;
        foreach($shiptype as $obj)
        {
?>shiptype[<?=$i++?>] = "<?=$obj->get_id()?>";<?php
            echo "\n";

        }
    }
?>

function lockqty(value)
{
    if(value == 'O')
    {
        document.list.webqty.readOnly = true;
    }
    else
    {
        document.list.webqty.readOnly = false;
    }
}

function showHide(platform)
{
    if(platform)
    {
        var target = 'prow_'+platform;
        var sign = 'sign_'+platform;
        var sp = 'sp_'+platform;
        var tobj = document.getElementById(target);
        var sobj = document.getElementById(sign);
        var spobj = document.getElementById(sp);
        if(tobj && sobj && spobj)
        {
            if(tobj.style.display == 'block')
            {
                tobj.style.display = 'none';
                sobj.innerHTML = '+';
                spobj.style.display = 'block';
            }
            else if(tobj.style.display == 'none')
            {
                tobj.style.display = 'block';
                sobj.innerHTML = '-';
                spobj.style.display = 'none';
            }
            else
            {
                return;
            }
        }
    }
}
-->
</script>
<script type="text/javascript" src="<?=base_url().$this->tool_path?>/get_js/"></script>
</head>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" class="frame_left">
<?php

    $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
    $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
    $ar_l_status = array("L"=>$lang["listed"], "N"=>$lang["not_listed"], "NC"=>$lang["no_content"], "NS"=>$lang['unsuitable']);
?>
<div id="main" style="width:auto">
<?=$notice["img"]?>
<?php
    if(!$valid_supplier)
    {
    ?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="60" align="left" style="padding-left:8px;"><b>No Valid Supplier Cost</b></td>
        </tr>
        </table>
    <?php
    }
    if($value != "")
    {
        if($canedit)
        {
?>
<form name="list" action="<?=base_url().$this->tool_path?>/view/<?=$prod_obj->get_sku().($this->input->get('target') == ""?"":"?target=".$this->input->get('target'))?>" method="POST" onSubmit="return CheckForm(this)">
<input type="hidden" name="sku" value="<?=$value?>">
<input type="hidden" name="posted" value="1">
<input type="hidden" name="formtype" value="<?=$action?>">
<input type="hidden" name="target" value="<?=$target?>">
<?php
        }
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td height="60" align="left" style="padding-left:8px;">
    <div style="float:left"><img src='<?=get_image_file($prod_obj->get_image(),'m',$prod_obj->get_sku())?>'> &nbsp;</div>
    <b style="font-size: 12px; color: rgb(0, 0, 0);"><?=$lang["header"]?></b><br><?=$lang["header_message"]." - "?><b><a href="<?=$website_link."mainproduct/view/".$prod_obj->get_sku()?>" target="_blank"><font style="text-decoration:none; color:#000000; font-size:14px;"><?=$prod_obj->get_sku()." - ".$prod_obj->get_name()?><?=$prod_obj->get_clearance()?" <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>":""?></font></a></b><br><?=$lang["master_sku"]." ".$master_sku?></td>
</tr>
<?php
        if ($objcount)
        {
            foreach($pdata as $platform_id=>$value)
            {
                $trow = $value["pdata"]["content"];
                $platform = $platform_id;
                $price_obj = $price_list[$platform_id];
                $price_ext_obj = $price_ext[$platform_id];
                $pobj = $value["pdata"]["dst"];
?>
<tr class="header">
    <td height="20" align="left" style="padding-left:8px;"><b style="font-size: 12px; color: rgb(255, 255, 255);"><a href="javascript:showHide('<?=$platform?>');"><span style="padding-right:15px;" id='sign_<?=$platform?>'>+</span></a><?=$platform_id." - ".$value["obj"]->get_platform_name()." | ".$value["obj"]->get_platform_currency_id()." | "?>
            <?php
                if ($pobj->get_current_platform_price()*1)
                {
                    echo $pobj->get_price()." | ".$pobj->get_shiptype_name();
                }
                else
                {
                    echo $lang["price_not_set"]." <span class='converted'>({$this->default_platform_id} ";
                    if ($pobj->get_default_platform_converted_price()*1)
                    {
                        echo "{$lang["converted"]}: {$pobj->get_default_platform_converted_price()}";
                    }
                    else
                    {
                        echo $lang["price_not_set"];
                    }
                    echo ")</span>";
                }
            ?>
             |
            <?=$ar_l_status[$pobj->get_listing_status()]." | ".($pobj->get_margin()>0?'<span style="color:#88ff88;">'.$pobj->get_margin().'%</span>':'<span style="color:#ff8888;">'.$pobj->get_margin().'%</span>')?>
            <?php
                if(trim($price_list[$platform_id]->get_platform_code()))
                {
                    echo " | <a href='".($amazon_landpage_url_list[$platform]->get_value()).$price_list[$platform_id]->get_platform_code()."' target='_blank'>".($amazon_landpage_url_list[$platform]->get_value().$price_list[$platform_id]->get_platform_code())."</a>";
                }
            ?>
        </b>
    </td>
</tr>
<tr id="sp_<?=$platform?>" style="display:block;">
        <td height="5"></td>
</tr>
<tr id="prow_<?=$platform?>" style="display:none;">
    <td align="left">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="pst" class="tb_main">
<?php
    eval($value["pdata"]["header"]);
    echo $header;
    foreach($trow as $row)
    {
        echo $row;
    }
?>
    </table>
<?php
    if($price_obj->get_listing_status() == 'N' || $price_obj->get_listing_status() == 'NC' || $price_obj->get_listing_status() == 'NS')
    {
?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr height="20" bgcolor="ee3333">
        <td align="center"><font style="font-weight:bold; color:#dddddd;"><?=$lang["currrent_not_listed"]?></font></td>
    </tr>
    </table>
<?php
    }

?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb_main">
    <tr>
        <td class="field"><?=$lang["asin"]?></td>
        <td class="value"><input name="price[<?=$platform?>][platform_code]" value="<?=htmlspecialchars($price_obj->get_platform_code())?>"></td>
        <td width="20%" class="field"><?=$lang["fulfillment_centre_id"]?></td>
        <td width="30%" class="value">
            <select id="price_ext[<?=$platform?>][fulfillment_centre_id]" name="price_ext[<?=$platform?>][fulfillment_centre_id]" onchange="rePrice('<?=$platform?>', 4);">
        <?php
            foreach($fulfillment_centre_list[$platform] as $fc)
            {
                $selected = $price_ext_obj->get_fulfillment_centre_id()==$fc?"SELECTED":"";
        ?>
                <option value="<?=$fc?>" <?=$selected?>><?=$fc;?></option>
        <?php
            }
        ?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="20%" class="field"><?=$lang["autoprice"]?></td>
        <td width="30%" class="value"><select name="price[<?=$platform?>][auto_price]"><option value="N" <?=$price_obj->get_auto_price() == "N"?"SELECTED":""?>><?=$lang["disabled"]?></option><option value="Y" <?=$price_obj->get_auto_price() == "Y"?"SELECTED":""?>><?=$lang["enabled"]?></option></select></td>
        <td width="20%" class="field"><?=$lang["repricing_name"]?></td>
        <td width="30%" class="value">
            <select name="price_ext[<?=$platform?>][amazon_reprice_name]">
                <option value=""></option>
        <?php
            foreach($reprice_name_list[$platform] as $rule)
            {
                $selected = $price_ext_obj->get_amazon_reprice_name()==$rule->get_id()?"SELECTED":"";
        ?>
                <option value="<?=$rule->get_id();?>" <?=$selected?>><?=$rule->get_id();?></option>
        <?php
            }
        ?>
            </select>
            <input value="<?=$lang['edit_reprice']?>" onClick="window.open('/marketing/ixtens_reprice_rule/','ixtens_reprice_rule','width=1280,height=768')" type="button">
        </td>
    </tr>
    <tr>
        <td class="field"><?=$lang["latency_in_stock"]?><a href="/images/amazon_latency.jpg" target="_blink"><font color="blue" style="font-size:10px">&nbsp;&nbsp;Show Detail</font></a></td>
        <td class="value"><input name="price[<?=$platform?>][latency]" value="<?=htmlspecialchars($price_obj->get_latency())?>"></td>
        <td class="field"><?=$lang["latency_out_stock"]?><a href="/images/amazon_latency.jpg" target="_blink"><font color="blue" style="font-size:10px">&nbsp;&nbsp;Show Detail</font></a></td>
        <td class="value"><input name="price[<?=$platform?>][oos_latency]" value="<?=htmlspecialchars($price_obj->get_oos_latency())?>"></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["condition"]?></td>
        <td class="value">
            <select name="price_ext[<?=$platform?>][ext_condition]">
        <?php
            foreach($condition_list as $condition)
            {
                $selected = $price_ext_obj->get_ext_condition()==$condition->get_id()?"SELECTED":"";
        ?>
                <option value="<?=$condition->get_id()?>" <?=$selected?>><?=$condition->get_condition();?></option>
        <?php
            }
        ?>
            </select>
        </td>
        <td class="field"><?=$lang["max_order_qty"]?></td>
        <td class="value"><input name="price[<?=$platform?>][max_order_qty]" value="<?=htmlspecialchars($price_obj->get_max_order_qty())?>"></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["condition_note"]?></td>
        <td colspan="3" class="value"><input name="price_ext[<?=$platform?>][note]" value="<?=htmlspecialchars($price_ext_obj->get_note())?>" style="width:100%"></td>
    </tr>
    <tr>
        <td class="field"><?=$lang["listing_qty"]?></td>
        <td class="value"><input name="price_ext[<?=$platform?>][ext_qty]" value="<?=htmlspecialchars($price_ext_obj->get_ext_qty())?>"></td>
        <td class="field"><?=$lang["listing_status"]?></td>
        <td class="value">
            <select name="listing_status[<?=$platform?>]" style="width:130px;">
                <option value="N"><?=$lang["not_listed"]?></option>
                <option value="L" <?=($price_obj->get_listing_status() == "L"?"SELECTED":"")?>><?=$lang["listed"]?></option>
                <option value="NC" <?=($price_obj->get_listing_status() == "NC"?"SELECTED":"")?>><?=$lang["no_content"]?></option>
                <option value="NS" <?=($price_obj->get_listing_status() == "NS"?"SELECTED":"")?>><?=$lang["unsuitable"]?></option>
            </select>
            <input type="hidden" name="formtype[<?=$platform?>]" value="<?=$formtype[$platform]?>">
        </td>
    </tr>
    </table>
    </td>
</tr>
<?          }

?>

<tr>
    <td height="30" class="field">&nbsp;</td>
</tr>

<tr class="header">
    <td height="20" align="left" style="padding-left:8px;"><b style="font-size: 12px; color: rgb(255, 255, 255);"><?=$lang["selling_info"]?></b></td>
</tr>
<tr>
    <td width="100%">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="tb_main">
    <tr>
        <td width="20%" class="field"><?=$lang["prodname"]?></td>
        <td width="30%" class="value"><?=$prod_obj->get_name()?></td>
        <td width="20%" class="field"><?=$lang["sku"]?></td>
        <td width="30%" class="value"><?=$prod_obj->get_sku()?></td>
    </tr>
    <tr >
        <td width="20%" class="field"><?=$lang["status"]?></td>
        <td width="30%" class="value"><select name="status" style="width:130px;" onchange="lockqty(this.value)"><?php
                foreach( $ar_ws_status as $key=>$value)
                {
                    ?><option value="<?=$key?>" <?=($prod_obj->get_website_status() == $key?"SELECTED":"")?>><?=$value?></option><?php
                }
        ?></select></td>
        <td width="20%" class="field"><?=$lang["webqty"]?></td>
        <td width="30%" class="value"><input type="text" name="webqty" value="<?=$prod_obj->get_website_quantity()?>" notEmpty isNatural></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?=$lang["current_supplier"]?></td>
        <td width="30%" class="value"><?=$supplier["name"]?></td>
        <td width="20%" class="field"><?=$lang["freight_cat"]?></td>
        <td width="30%" class="value"><?=$freight_cat?></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?=$lang["supplier_status"]?></td>
        <td width="30%" class="value"><?=$ar_src_status[[$supplier['supplier_status']]?></td>
        <td width="20%" class="field"></td>
        <td width="30%" class="value"></td>
    </tr>

<?php
if(count($inv))
{
    $ttl_inv = $ttl_git = 0;
    $inventory .= "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
    $inventory .= "<tr><td>".$lang["warehouse"]."</td><td>".$lang["inventory"]."</td><td>".$lang["git"]."</td></tr>";
    foreach($inv as $iobj)
    {

        $inventory .= "<tr><td>".$iobj->get_warehouse_id()."</td><td>".$iobj->get_inventory()."</td><td>".$iobj->get_git()."</td></tr>";
        $ttl_inv += $iobj->get_inventory();
        $ttl_git += $iobj->get_git();
    }
    $inventory .= "<tr><td>".$lang["total"]."</td><td>".$ttl_inv."</td><td>".$ttl_git."</td></tr>";
    $inventory .= "</table>";
}
else
{
    $inventory = 0;
}
?>
    <tr >
        <td width="20%" class="field"><?=$lang["inventory"]?></td>
        <td width="30%" class="value"><?=$inventory?></td>
        <td width="20%" class="field"><?=$lang["qty_in_orders"]?></td>
        <td width="30%" class="value"><?=$qty_in_orders?></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?=$lang["on_clearance"]?></td>
        <td width="30%" class="value"><select name="clearance" class="input"><option value="0" <?=!$prod_obj->get_clearance()?"SELECTED":""?>><?=$lang["no"]?></option><option value="1" <?=$prod_obj->get_clearance()?"SELECTED":""?>><?=$lang["yes"]?></option></select></td>
        <td width="20%" class="field"><?=$lang["hitcount"]?></td>
        <td width="30%" class="value"><?=$hitcount?></td>
    </tr>
    <tr >
        <td width="20%" class="field" valign="top" style="padding-top:2px; padding-bottom:2px;"><?=$lang["marketing_notes"]?></td>
        <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;"><div><?=$lang["current_notes"]?></div><?php
            if(!empty($mkt_note_obj))
            {
                foreach($mkt_note_obj as $nobj)
                {
                    ?><div><?=$nobj->get_note()?><br><span style="font-size:9px; color:#888888; font-style:italic;"><?=$lang["create_by"].$nobj->get_username().$lang["on"].$nobj->get_create_on()?></span></div><?php
                }
            }
            else
            {
                echo "<div>".$lang["no_notes"]."</div>";
            }
        ?><div><?=$lang["create_note"]?></div><div><input name="m_note" maxlength="255" type="text"></div></td>
        <td width="20%" class="field" valign="top" style="padding-top:2px; padding-bottom:2px;"><?=$lang["sourcing_notes"]?></td>
        <td width="30%" class="value" valign="top" style="padding-top:2px; padding-bottom:2px;"><div><?=$lang["current_notes"]?></div><?php
            if(!empty($src_note_obj))
            {
                foreach($src_note_obj as $nobj)
                {
                    ?><div><?=$nobj->get_note()?><br><span style="font-size:9px; color:#888888; font-style:italic;"><?=$lang["create_by"].$nobj->get_username().$lang["on"].$nobj->get_create_on()?></span></div><?php
                }
            }
            else
            {
                echo "<div>".$lang["no_notes"]."</div>";
            }
        ?><div><?=$lang["create_note"]?></div><div><input name="s_note" maxlength="255" type="text"></div></td>
    </tr>
    <?="<!--"?>
    <?=$price_obj->get_listing_status()?>
    <?="-->"?>
    <tr >
        <td width="20%" class="field"><?=$lang["create_on"]?></td>
        <td width="30%" class="value"><?=$price_obj->get_create_on()?></td>
        <td width="20%" class="field"><?=$lang["modify_on"]?></td>
        <td width="30%" class="value"><?=$price_obj->get_modify_on()?></td>
    </tr>
    <tr >
        <td width="20%" class="field"><?=$lang["create_at"]?></td>
        <td width="30%" class="value"><?=$price_obj->get_create_at()?></td>
        <td width="20%" class="field"><?=$lang["modify_at"]?></td>
        <td width="30%" class="value"><?=$price_obj->get_modify_at()?></td>
    </tr>
    <tr >
        <td width="20%" class="field"><?=$lang["create_by"]?></td>
        <td width="30%" class="value"><?=$price_obj->get_create_by()?></td>
        <td width="20%" class="field"><?=$lang["modify_by"]?></td>
        <td width="30%" class="value"><?=$price_obj->get_modify_by()?></td>
    </tr>
    <tr >
        <td width="20%" class="field"><?=$lang["ean"]?></td>
        <td width="30%" class="value"><input type="text" name="ean" value="<?=$prod_obj->get_ean()?>"></td>
        <td width="20%" class="field"><?=$lang["mapping_code"]?></td>
        <td width="30%" class="value"><input type="text" name="ext_mapping_code" value="<?=$price_obj->get_ext_mapping_code()?>" style="width:130px;"></td>
    </tr>
    <tr>
        <td width="20%" class="field"><?=$lang["mpn"]?></td>
        <td width="30%" class="value"><input type="text" name="mpn" value="<?=$prod_obj->get_mpn()?>"></td>
        <td width="20%" class="field"><?=$lang["upc"]?></td>
        <td width="30%" class="value"><input type="text" name="upc" value="<?=$prod_obj->get_upc()?>" style="width:130px;"></td>
    </tr>

    </table>
    </td>
</tr>
</table>
<?php
            if($canedit)
            {
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
<tr>
    <td align="right" style="padding-right:8px;" height="30"><input type="button" value="Update Pricing Tool" class="button" onClick="if(CheckForm(this.form)) this.form.submit();"></td>
</tr>
</table>
</form>
<div style="margin-top:20px; margin-top:20px; text-align:left; padding-left:10px;">
Formula for this pricing tool:<br>
DECL. = SELLING PRICE * DECL.% <br>
VAT = (DECL. + FREIGHT TO WH + FREIGHT TO FC + POSTAGE) * VAT% <br>
DUTY = DECL. * DUTY% <br>
PAYPAL FEE = SELLING PRICE * PAYPAL FEE% + <!--<script>document.write(paypal_fee_adj)</script>--> <br>
TOTAL COST =  TOTAL COST = VAT + DUTY + EBAY LISTING FEE + EBAY COMMISSION + PAYPAL FEE + ADMIN FEE + FREIGHT TO WH + FREIGHT TO FC + POSTAGE + SUPP.COST; <br>
PROFIT = SELLING PRICE + DELIVERY - TOTAL COST; <br>
PROFIT MARGIN = (PROFIT)/(SELLING PRICE - VAT) * 100%; <br>
</div>
<?php
            }
        }
        else
        {
?>
        <tr>
            <td style="padding-left:8px;">
                <br>
                <b><a class="warn" href="<?=base_url()?>marketing/category/view_scpv/?subcat_id=<?=$prod_obj->get_sub_cat_id()?>&platform=WSGB"><?=$lang["sub_cat_no_set"]?></a></b>
            </td>
        </tr>
    </table>
<?php
        }
    }
?>
</div>
<?=$notice["js"]?>
<?php

    if($prompt_notice)
    {
?><script language="javascript">alert('<?=$lang["update_notice"]?>')</script><?php
    }
?>
<script language="javascript">
if(document.list)
{
    lockqty(document.list.status.value);
}
</script>
</body>
</html>