<html>
<head>
<title><?=$lang["title"]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="<?=base_url()?>css/style.css" type="text/css" media="all"/>
<script type="text/javascript" src="<?=base_url()?>js/common.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/checkform.js"></script>
<script type="text/javascript" src="<?=base_url()?>mastercfg/profit_var_helper/js_platformlist"></script>
<script type="text/javascript" language="javascript" src="<?=base_url()?>js/lytebox.js"></script>
<link rel="stylesheet" href="<?=base_url()?>css/lytebox.css" type="text/css" media="screen" />
</head>
<body>
<div id="main">
<?=$notice["img"]?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td height="30" class="title"><?=$lang["title"]?></td>
        <td width="400" align="right" class="title"><input type="button" value="<?=$lang["list_button"]?>" class="button" onClick="Redirect('<?=site_url('order/order_fulfilment/')?>')">&nbsp;<input type="button" value="<?=$lang["ship_button"]?>" class="button" onClick="Redirect('<?=site_url('order/order_fulfilment/to_ship')?>')">&nbsp;<input type="button" value="<?=$lang["dispatch_button"]?>" class="button" onClick="Redirect('<?=site_url('order/order_fulfilment/dispatch')?>')"></td>
    </tr>
    <tr>
        <td height="2" class="line"></td>
        <td height="2" class="line"></td>
    </tr>
</table>
<form name="fm" method="get" onSubmit="return CheckForm(this)">
<table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
    <tr>
        <td style="padding-left:8px"><b style="font-size:14px"><?=$lang["header"]?></b><br><?=$lang["header_message"]?></td>
    </tr>
    <tr>
        <td align="right" style="padding-right:8px;">
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <col width="20"><col width="82"><col width="65"><col width="80"><col width="75"><col width="65"><col width="52"><col width="60"><col><col width="30"><col width="60"><col width="80"><col width="30"><col width="30"><col width="50"><col width="30"><col width="60"><col width="80"><col width="80"><col width="26">
    <tr class="header">
        <td height="20">
                <img src="<?=base_url()?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
        </td>
        <td title="<?=$lang["platform"]?>"><a href="#" onClick="SortCol(document.fm, 'platform_id', '<?=$xsort["platform_id"]?>')"><?=$lang["platform"]?> <?=$sortimg["platform_id"]?></a></td>
        <td title="<?=$lang["order_id"]?>"><a href="#" onClick="SortCol(document.fm, 'so_no', '<?=$xsort["so_no"]?>')"><?=$lang["order_id"]?> <?=$sortimg["so_no"]?></a></td>
<!--        <td title="<?=$lang["platform_order_id"]?>"><a href="#" onClick="SortCol(document.fm, 'platform_order_id', '<?=$xsort["platform_order_id"]?>')"><?=$lang["p_order_id"]?> <?=$sortimg["platform_order_id"]?></a></td> -->
        <td title="<?=$lang["order_date"]?>"><a href="#" onClick="SortCol(document.fm, 'order_create_date', '<?=$xsort["order_create_date"]?>')"><?=$lang["order_date"]?> <?=$sortimg["order_create_date"]?></a></td>
<!--        <td title="<?=$lang["edd"]?>"><a href="#" onClick="SortCol(document.fm, 'expect_delivery_date', '<?=$xsort["expect_delivery_date"]?>')"><?=$lang["edd"]?> <?=$sortimg["expect_delivery_date"]?></a></td> -->
        <td title="<?=$lang["multiple_items"]?>"><a href="#" onClick="SortCol(document.fm, 'multiple', '<?=$xsort["multiple"]?>')"><?=$lang["mult"]?> <?=$sortimg["multiple"]?></a></td>
        <td title="<?=$lang["sku"]?>"><a href="#" onClick="SortCol(document.fm, 'item_sku', '<?=$xsort["item_sku"]?>')"><?=$lang["sku"]?> <?=$sortimg["item_sku"]?></a></td>
        <td title="<?=$lang["product"]?>"><?=$lang["product"]?></td>
        <td title="<?=$lang["qty"]?>"><?=$lang["qty"]?></td>
        <td title="<?=$lang["amount"]?>"><a href="#" onClick="SortCol(document.fm, 'amount', '<?=$xsort["amount"]?>')"><?=$lang["amount"]?> <?=$sortimg["amount"]?></a></td>
        <td title="<?=$lang["client_name"]?>"><a href="#" onClick="SortCol(document.fm, 'delivery_name', '<?=$xsort["delivery_name"]?>')"><?=$lang["client_name"]?> <?=$sortimg["delivery_name"]?></a></td>
        <td title="<?=$lang["postcode"]?>"><a href="#" onClick="SortCol(document.fm, 'delivery_postcode', '<?=$xsort["delivery_postcode"]?>')"><?=$lang["pc"]?> <?=$sortimg["delivery_postcode"]?></a></td>
        <td title="<?=$lang["country_code"]?>"><a href="#" onClick="SortCol(document.fm, 'delivery_country_id', '<?=$xsort["delivery_country_id"]?>')"><?=$lang["cc"]?> <?=$sortimg["delivery_country_id"]?></a></td>
<!--        <td title="<?=$lang["express_delivery"]?>"><a href="#" onClick="SortCol(document.fm, 'express', '<?=$xsort["express"]?>')"><?=$lang["exp"]?> <?=$sortimg["express"]?></a></td>-->
        <td title="<?=$lang["warehouse"]?>"><a href="#" onClick="SortCol(document.fm, 'warehouse_id', '<?=$xsort["warehouse_id"]?>')"><?=$lang["wh"]?> <?=$sortimg["warehouse_id"]?></a></td>
<!--        <td title="<?=$lang["shipment_no"]?>"><a href="#" onClick="SortCol(document.fm, 'warehouse_id', '<?=$xsort["sh_no"]?>')"><?=$lang["ship_no"]?> <?=$sortimg["sh_no"]?></a></td> -->
        <td title="<?=$lang["tracking_no"]?>"><a href="#" onClick="SortCol(document.fm, 'tracking_no', '<?=$xsort["tracking_no"]?>')"><?=$lang["tracking_no"]?> <?=$sortimg["tracking_no"]?></a></td>
        <td title="<?=$lang["notes"]?>"><a href="#" onClick="SortCol(document.fm, 'notes', '<?=$xsort["notes"]?>')"><?=$lang["notes"]?> <?=$sortimg["notes"]?></a></td>
        <td title="<?=$lang["check_all"]?>"><input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this, 1);"></td>
    </tr>
    <tr class="search" id="tr_search" <?=$searchdisplay?>>
        <td></td>
        <td>
            <select name="platform_id" class="input">
                <option value="">
            </select>
        </td>
        <td><input name="so_no" class="input" value="<?=htmlspecialchars($this->input->get("so_no"))?>"></td>
<!--        <td><input name="platform_order_id" class="input" value="<?=htmlspecialchars($this->input->get("platform_order_id"))?>"></td> -->
        <td><input name="order_create_date" class="input" value="<?=htmlspecialchars($this->input->get("order_create_date"))?>"></td>
<!--        <td><input name="expect_delivery_date" class="input" value="<?=htmlspecialchars($this->input->get("expect_delivery_date"))?>"></td> -->
        <td>
            <?$m_select[$this->input->get("multiple")] = " SELECTED";?>
            <select name="multiple" class="input">
                <option value="">
                <option value="Y"<?=$m_select["Y"]?>><?=$lang["yes"]?>
                <option value="N"<?=$m_select["N"]?>><?=$lang["no"]?>
            </select>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td><input name="amount" class="input" value="<?=htmlspecialchars($this->input->get("amount"))?>"></td>
        <td><input name="delivery_name" class="input" value="<?=htmlspecialchars($this->input->get("delivery_name"))?>"></td>
        <td><input name="delivery_postcode" class="input" value="<?=htmlspecialchars($this->input->get("delivery_postcode"))?>"></td>
        <td><input name="delivery_country_id" class="input" value="<?=htmlspecialchars($this->input->get("delivery_country_id"))?>"></td>
<!--
        <td>
            <?$e_select[$this->input->get("express")] = " SELECTED";?>
            <select name="express" class="input">
                <option value="">
                <option value="Y"<?=$e_select["Y"]?>><?=$lang["yes"]?>
                <option value="N"<?=$e_select["N"]?>><?=$lang["no"]?>
            </select>
        </td>
-->
        <td>
            <select name="warehouse_id" class="input">
                <option value="">
            <?php
                $wh_selected[$this->input->get("warehouse_id")]=" SELECTED";
                foreach ($whlist as $wh)
                {
                    $ar_wh[$wh["id"]] = $wh["name"];
            ?>
                <option value="<?=$wh["id"]?>"<?=$wh_selected[$wh["id"]]?>><?=$wh["id"]?>
            <?php
                }
            ?>
            </select>
        </td>
<!--        <td><input name="sh_no" class="input" value="<?=htmlspecialchars($this->input->get("sh_no"))?>"></td> -->
        <td><input name="tracking_no" class="input" value="<?=htmlspecialchars($this->input->get("tracking_no"))?>"></td>
        <td><input name="note" class="input" value="<?=htmlspecialchars($this->input->get("note"))?>"></td>
        <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?=base_url()?>images/find.gif') no-repeat;"></td>
    </tr>
<input type="hidden" name="sort" value='<?=$this->input->get("sort")?>'>
<input type="hidden" name="order" value='<?=$this->input->get("order")?>'>
</form>
<form name="fm_edit" method="post">
<?php
    if ($objlist)
    {
        $i=0;
        foreach ($objlist as $obj)
        {
            $row_style = "row".$i%2;
            if ($obj->get_refund_status() || $obj->get_hold_status())
            {
                $row_style .= " notallow";
            }
            $item_list = explode("||", $obj->get_items());
            $row_span = ($rows=count($item_list))>1?" rowspan='{$rows}'":"";
            list($cur_sku, $cur_prod, $cur_o_qty) = explode("::", $item_list[0]);
?>
    <tr name="row<?=$i?>" class="<?=$row_style?>"<?=$row_span?> onMouseOver="AddGroupClassName('row<?=$i?>', 'highlight')" onMouseOut="RemoveGroupClassName('row<?=$i?>', 'highlight')" valign="top">
        <td<?=$row_span?>></td>
        <td<?=$row_span?>><script>w(platformlist['<?=$obj->get_platform_id()?>'])</script></td>
        <td<?=$row_span?>><?=$obj->get_so_no()?></td>
        <td<?=$row_span?>><?=substr($obj->get_order_create_date(),0,10)?></td>
        <td<?=$row_span?> align="center"><?=$obj->get_multiple()=="Y"?"<image src='/images/tick.gif'>":""?></td>
        <td><?=$cur_sku?></td>
        <td><?=$cur_prod?></td>
        <td><?=$cur_o_qty?></td>
        <td<?=$row_span?>><?=$currsign[$obj->get_currency_id()]?><?=number_format($obj->get_amount(), 2)?></td>
        <td<?=$row_span?>><?=$obj->get_delivery_name()?></td>
        <td<?=$row_span?>><?=$obj->get_delivery_postcode()?></td>
        <td<?=$row_span?>><?=$obj->get_delivery_country_id()?></td>
        <td<?=$row_span?>><?=$obj->get_warehouse_id()?></td>
        <td<?=$row_span?> nowrap style="white-space:nowrap;"><?=$obj->get_courier_id()?> -><input size="6" style="width:50px" name="courier[<?=$obj->get_sh_no()?>]" value="<?=htmlspecialchars($_POST["courier"][$obj->get_sh_no()])?>"><br><input name="tracking[<?=$obj->get_sh_no()?>]" class="input" value="<?=htmlspecialchars($obj->get_tracking_no())?>"></td>
        <td<?=$row_span?>><div id="note_<?=$i?>" class="normal_p"><?=nl2br($obj->get_note())?></div><div align="right"><a href="<?=base_url()?>order/order_fulfilment/add_note/<?=$obj->get_so_no()?>/<?=$i?>" rel="lyteframe[note]" rev="width: 400px; height: 400px; scrolling: auto;" title="<?=$lang["add_note"]?>: <?=$obj->get_so_no()?>"><?=$lang["add_note"]?></a></div></td>
        <td<?=$row_span?> align="center"><input type="checkbox" name="check[<?=$obj->get_sh_no()?>]" value="<?=$obj->get_so_no()?>"></td>
    </tr>
        <?php
            for ($j=1; $j<count($item_list); $j++)
            {
                list($cur_sku, $cur_prod, $cur_o_qty) = explode("::", $item_list[$j]);
        ?>
    <tr name="row<?=$i?>" class="<?=$row_style?>" onMouseOver="AddGroupClassName('row<?=$i?>', 'highlight')" onMouseOut="RemoveGroupClassName('row<?=$i?>', 'highlight')" valign="top">
        <td><?=$cur_sku?></td>
        <td><?=$cur_prod?></td>
        <td><?=$cur_o_qty?></td>
    </tr>
<?php
            }
            $i++;
        }
    }
?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
    <tr>
        <td align="right" style="padding-right:8px;">
            <!--<select name="uploader"><option value='<?=base_url()?>/order/mpuploader'>MetaPack</option><option value='<?=base_url()?>/order/dpduploader'>DPD</option></select>-->
            <!-- <input type="button" value="<?=$lang['upload_tracking_no']?>" onClick="Pop(this.form.action='<?=base_url()?>integration/cron/cron_tracking_info');"> &nbsp;|&nbsp -->
            <!--<input type="button" value="<?=$lang['tracking_no_upload']?>" onClick="Pop(this.form.uploader.value, 'upload');"> &nbsp;|&nbsp -->
            <input type="button" value="<?=$lang['import_trackingfile']?>" onClick="document.getElementById('a_check').onclick();"> <a id="a_check" href="<?=base_url()?>order/order_fulfilment/import_trackingfile" rel="lyteframe" rev="width: 700px; height: 500; scrolling: auto;" title="<?=$lang['import_trackingfile']?>"></a> &nbsp;|&nbsp
            <input type="button" value="<?=$lang['print_note_selected']?>" onClick="this.form.action='<?=base_url()?>order/order_fulfilment/delivery_note';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';"> &nbsp;|&nbsp
            <input type="button" value="<?=$lang['print_custom_selected']?>" onClick="this.form.action='<?=base_url()?>order/order_fulfilment/custom_invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';"> &nbsp;|&nbsp
            <input type="button" value="<?=$lang['print_selected']?>" onClick="this.form.action='<?=base_url()?>order/order_fulfilment/invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';"> &nbsp;|&nbsp
            <input type="button" value="<?=$lang['return_selected']?>" onClick="document.fm_edit.dispatch_type.value='r';this.form.submit();"> &nbsp;|&nbsp
            <select name="courier_id" id="courier_id">
                <!--<option value="DHL">DHL Express</option><option value='RM1st'>RM First Class</option><option value='RM1stRec'>RM First Class Recorded</option><option value='RMSD'>RM Special Delivery</option><option value='RMAir'>RM Airmail</option><option value='RMInt'>RM International Signed For</option><option value='DPD'>DPD</option>-->
                <option value="DHL">DHL</option>
                <option value="DHLBBX">DHL-BBX</option>
                <option value="HK_Post">Global Mail</option>
                <option value="IM">IM</option>
                <option value="TOLL">Toll</option>
                <option value="DPD">DPD</option>
                <option value="ARAMEX">Aramex</option>
                <option value="ARAMEX_COD">Aramex COD</option>
                <option value="RMR">RMR</option>
                <option value="FEDEX">Fedex</option>
                <option value="FEDEX2">Fedex2</option>
            </select>
            <input type="button" value="<?=$lang['change_courier']?>" onClick="if(document.getElementById('courier_id').value == 'DHLBBX') { set_mawb();} document.fm_edit.dispatch_type.value='c';document.fm_edit.submit();"> &nbsp;|&nbsp
            <input type="button" value="<?=$lang['confirm_dispatch']?>" onClick="document.fm_edit.dispatch_type.value='d';this.form.submit();">
        </td>
    </tr>
</table>
<input type="hidden" name="posted" value="1">
<input type="hidden" name="dispatch_type" value="d">
<input type="hidden" name="db_time" value="<?=$db_time?>">
<input type="hidden" name="mawb" id="mawb" value="">
</form>
<div align="right" class="count_tag">&nbsp;Total order(s): <?=$total_order?> &nbsp; &nbsp;Total items(s): <?=$total_item?> &nbsp; </div><?=$this->pagination_service->create_links_with_style()?>
<?=$notice["js"]?>
</div>
<iframe name="printframe" src="" width="0" height="0" frameborder="0" scrolling="no"></iframe>
<script>
function set_mawb()
{
    if(mawb = prompt('Please enter MAWB# :',''))
    {
        document.getElementById('mawb').value = mawb;
    }
    else
    {
        exit;
    }
}

InitPlatform(document.fm.platform_id);
document.fm.platform_id.value = '<?=$this->input->get("platform_id")?>';
//document.fm.platform_id.value = 'AMUK';
<?php
    if ($_SESSION["courier_file"])
    {
?>
        top.frames["printframe"].window.location.href='<?=base_url()?>order/order_fulfilment/get_courier_file/<?=$_SESSION["courier_file"]?>';
<?php
    }
?>
</script>
</body>
</html>
