<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/profitVarHelper/jsPlatformlist"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["list_button"] ?>" class="button" onclick="Redirect('<?= site_url('order/integrated_order_fulfillment/') ?>')">&nbsp;
                <input type="button" value="<?= $lang["ship_button"] ?>" class="button" onclick="Redirect('<?= site_url('order/integrated_order_fulfillment/to_ship') ?>')">&nbsp;
                <input type="button" value="<?= $lang["dispatch_button"] ?>" class="button" onclick="Redirect('<?= site_url('order/integrated_order_fulfillment/dispatch') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <form name="fm" method="get" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <tr>
                <td style="padding-left:8px">
                    <b style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?>
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right:8px;">
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="82">
            <col width="65">
            <col width="80">
            <col width="75">
            <col width="65">
            <col width="52">
            <col width="60">
            <col>
            <col width="30">
            <col width="60">
            <col width="80">
            <col width="30">
            <col width="30">
            <col width="50">
            <col width="30">
            <col width="60">
            <col width="80">
            <col width="80">
            <col width="26">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
                </td>
                <td title="<?= $lang["platform"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'platform_id', '<?= $xsort["platform_id"] ?>')"><?= $lang["platform"] ?> <?= $sortimg["platform_id"] ?></a>
                </td>
                <td title="<?= $lang["order_id"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'so_no', '<?= $xsort["so_no"] ?>')"><?= $lang["order_id"] ?> <?= $sortimg["so_no"] ?></a>
                </td>
                <td title="<?= $lang["platform_order_id"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'platform_order_id', '<?= $xsort["platform_order_id"] ?>')"><?= $lang["p_order_id"] ?> <?= $sortimg["platform_order_id"] ?></a>
                </td>
                <td title="<?= $lang["order_date"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'order_create_date', '<?= $xsort["order_create_date"] ?>')"><?= $lang["order_date"] ?> <?= $sortimg["order_create_date"] ?></a>
                </td>
                <td title="<?= $lang["packing_date"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'packing_date', '<?= $xsort["packing_date"] ?>')"><?= $lang["packing_date"] ?> <?= $sortimg["packing_date"] ?></a>
                </td>
                <td title="<?= $lang["edd"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'expect_delivery_date', '<?= $xsort["expect_delivery_date"] ?>')"><?= $lang["edd"] ?> <?= $sortimg["expect_delivery_date"] ?></a>
                </td>
                <td title="<?= $lang["multiple_items"] ?>"><?= $lang["mult"] ?></td>
                <td title="<?= $lang["master_sku"] ?>"><?= $lang["master_sku"] ?></td>
                <td title="<?= $lang["sku"] ?>"><?= $lang["sku"] ?></td>
                <td title="<?= $lang["product"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'product_name_ref', '<?php echo @$xsort['product_name_ref']; ?>')"><?php echo $lang['product']; ?> <?php echo @$sortimg['product_name_ref']; ?></a>
                </td>
                <td title="<?= $lang["qty"] ?>"><?= $lang["qty"] ?></td>
                <td title="<?= $lang["amount"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'amount', '<?= $xsort["amount"] ?>')"><?= $lang["amount"] ?> <?= $sortimg["amount"] ?></a>
                </td>
                <td title="<?= $lang["client_name"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'delivery_name', '<?= $xsort["delivery_name"] ?>')"><?= $lang["client_name"] ?> <?= $sortimg["delivery_name"] ?></a>
                </td>
                <td title="<?= $lang["postcode"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'delivery_postcode', '<?= $xsort["delivery_postcode"] ?>')"><?= $lang["pc"] ?> <?= $sortimg["delivery_postcode"] ?></a>
                </td>
                <td title="<?= $lang["country_code"] ?>">
                    <a href="#" onClick="SortCol(document.fm, 'delivery_country_id', '<?= $xsort["delivery_country_id"] ?>')"><?= $lang["cc"] ?> <?= $sortimg["delivery_country_id"] ?></a>
                </td>
                <td title="<?= $lang["express_delivery"] ?>"><?= $lang["exp"] ?></td>
                <td title="<?= $lang["warehouse"] ?>"><?= $lang["wh"] ?></td>
                <td title="<?= $lang["shipment_no"] ?>"><?= $lang["ship_no"] ?></td>
                <td title="<?= $lang["tracking_no"] ?>"><?= $lang["tracking_no"] ?></td>
                <td title="REC_COURIER">REC. COURIER</td>
                <td title="<?= $lang["notes"] ?>"><?= $lang["notes"] ?></td>
                <td title="SPLIT SO GROUP">SPLIT SO GROUP</td>
                <td title="<?= $lang["check_all"] ?>"><input type="checkbox" name="chkall" value="1"
                                                             onClick="checkall(document.fm_edit, this, 1);"></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td>
                    <select name="platform_id" class="input">
                        <option value="">
                    </select>
                </td>
                <td><input name="so_no" class="input" value="<?= htmlspecialchars($this->input->get("so_no")) ?>"></td>
                <td><input name="platform_order_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("platform_order_id")) ?>"></td>
                <td><input name="order_create_date" class="input"
                           value="<?= htmlspecialchars($this->input->get("order_create_date")) ?>"></td>
                <td><input name="packing_date" class="input"
                           value="<?= htmlspecialchars($this->input->get("packing_date")) ?>"></td>
                <td><input name="expect_delivery_date" class="input"
                           value="<?= htmlspecialchars($this->input->get("expect_delivery_date")) ?>"></td>
                <td>
                    <?php $this->input->get("multiple") === "0" ? $m_select["0"] = " Selected" : $this->input->get("multiple") === "1" ? $m_select["1"] = " Selected" : "" ?>
                    <select name="multiple" class="input">
                        <option value="">
                        <option value="1"<?= $m_select["1"] ?>><?= $lang["yes"] ?>
                        <option value="0"<?= $m_select["0"] ?>><?= $lang["no"] ?>
                    </select>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><input name="amount" class="input" value="<?= htmlspecialchars($this->input->get("amount")) ?>">
                </td>
                <td><input name="delivery_name" class="input"
                           value="<?= htmlspecialchars($this->input->get("delivery_name")) ?>"></td>
                <td><input name="delivery_postcode" class="input"
                           value="<?= htmlspecialchars($this->input->get("delivery_postcode")) ?>"></td>
                <td><input name="delivery_country_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("delivery_country_id")) ?>"></td>
                <td>
                    <?php $e_select[$this->input->get("express")] = " SELECTED"; ?>
                    <select name="express" class="input">
                        <option value="">
                        <option value="Y"<?= $e_select["Y"] ?>><?= $lang["yes"] ?>
                        <option value="N"<?= $e_select["N"] ?>><?= $lang["no"] ?>
                    </select>
                </td>
                <td>
                    <select name="warehouse_id" class="input">
                        <option value="">
                            <?php
                            $wh_selected[$this->input->get("warehouse_id")] = " SELECTED";
                            foreach ($whlist as $obj) :
                            ?>
                        <option value="<?= $obj->getWarehouseId() ?>"<?= $wh_selected[$obj->getWarehouseId()] ?>><?= $obj->getWarehouseId() ?>
                            <?php
                            endforeach;
                            ?>
                    </select>
                </td>
                <td><input name="sh_no" class="input" value="<?= htmlspecialchars($this->input->get("sh_no")) ?>"></td>
                <td><input name="tracking_no" class="input"
                           value="<?= htmlspecialchars($this->input->get("tracking_no")) ?>"></td>
                <td>
                    <select name="rec_courier">
                        <option value=""></option>
                        <?php
                        $html_courier = "";
                        foreach ($courier_list as $key => $value) :
                            $selected_courier = "";
                            if ($this->input->get("rec_courier") == $value) :
                                $selected_courier = " SELECTED";
                            endif;
                            $html_courier .= <<<html
                        <option value="$value" $selected_courier>$value</option>
html;
                        endforeach;
                        echo $html_courier;
                        ?>
                    </select>
                </td>
                <td><input name="note" class="input" value="<?= htmlspecialchars($this->input->get("note")) ?>"></td>
                <td>&nbsp;</td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <form name="fm_edit" method="post">
        <?php
        if ($objlist) :
            $allrowspan = array();
            foreach ($objlist as $obj) :
                $current_split_so_group = $obj->getSplitSoGroup();
                $rowspan = $obj->getOrderTotalSku();

                if (isset($current_split_so_group)) :
                    if (empty($last_split_so_group) || $current_split_so_group != $last_split_so_group) :
                        $splitrowspan = 1;
                    else :
                        $splitrowspan++;
                    endif;

                    $so_list[$current_split_so_group] .= $obj->getSoNo() . ",";
                    $splitsogrp_html[$current_split_so_group] = "<td rowspan = '$splitrowspan'>$current_split_so_group</td>";

                else :
                    $splitrowspan = "";
                endif;

                $last_split_so_group = $current_split_so_group;

                if (isset($allrowspan[$obj->getSoNo()]))
                    $allrowspan[$obj->getSoNo()]++;
                else
                    $allrowspan[$obj->getSoNo()] = 1;
            endforeach;

            $i = 0;
            $n = 0;
            $last_so_no = "";
            foreach ($objlist as $obj) :
                $skip_this_column = false;
                $current_so_no = $obj->getSoNo();
                $order_total_sku = $obj->getOrderTotalSku();
                // $row_span = "rowspan=".$order_total_sku;
                $row_span = "rowspan=" . $allrowspan[$current_so_no];     # use loop instead of order_total_sku because those with missing master_sku does not match order_total_sku

                if ($splitsogrp_html[$obj->getSplitSoGroup()] != "") :
                    // on the first line of split_so_group
                    $splitgrouprow = $splitsogrp_html[$obj->getSplitSoGroup()];

                    // we then set to empty string so that next row will not get pushed by rowspan
                    $splitsogrp_html[$obj->getSplitSoGroup()] = "";
                else :
                    if ($obj->getSplitSoGroup()) :
                        $splitgrouprow = $splitsogrp_html[$obj->getSplitSoGroup()];
                    else :
                        $splitgrouprow = "<td $row_span>&nbsp;</td>"; # rows without split_so_group
                    endif;
                endif;

                $check_html = $courier_html = "";

                if ($current_so_no == $last_so_no) :
                    $skip_this_column = true;
                else :
                    $n++;
                endif;

                $last_so_no = $current_so_no;

                $row_style = "row" . $n % 2;
                if ($obj->getRefundStatus() || $obj->getHoldStatus()) :
                    $row_style .= " notallow";
                endif;

                $td_style = $obj->getOutstandingQty() > $obj->getInventory() ? "row" . $n % 2 : "xrow0";

                if ($skip_this_column) :
                    ?>
                    <tr name="row<?= $n ?>" class="<?= $row_style ?>"
                        onMouseOver="AddGroupClassName('row<?= $n ?>', 'highlight')"
                        onMouseOut="RemoveGroupClassName('row<?= $n ?>', 'highlight')">
                        <td name="row<?= $n ?>"><?= $obj->getMasterSku() ?></td>
                        <td name="row<?= $n ?>"><?= $obj->getSku() ?></td>
                        <td name="row<?= $n ?>"><?= $obj->getProductName() ?></td>
                        <td name="row<?= $n ?>"><?= $obj->getQty() ?></td>
                    </tr>


            <?php else : ?>
                    <tr name="row<?= $n ?>" class="<?= $row_style ?>" <?= $row_span ?>
                        onMouseOver="AddGroupClassName('row<?= $n ?>', 'highlight')"
                        onMouseOut="RemoveGroupClassName('row<?= $n ?>', 'highlight')" valign="top">
                        <td <?= $row_span ?>></td>
                        <td <?= $row_span ?>>
                            <script>w(platformlist['<?=$obj->getPlatformId()?>'])</script>
                        </td>
                        <td <?= $row_span ?>><?= $obj->getSoNo() ?></td>
                        <td <?= $row_span ?>><?= $obj->getPlatformOrderId() ?></td>
                        <td <?= $row_span ?>><?= substr($obj->getOrderCreateDate(), 0, 10) ?></td>
                        <td <?= $row_span ?>><?= substr($obj->getPackingDate(), 0, 10) ?></td>
                        <td <?= $row_span ?>><?= substr($obj->getExpectDeliveryDate(), 0, 10) ?></td>
                        <td <?= $row_span ?>
                            align="center"><?= $obj->getOrderTotalSku() > 1 ? "<image src='/images/tick.gif'>" : "" ?></td>
                        <td><?= $obj->getMasterSku() ?></td>
                        <td><?= $obj->getSku() ?></td>
                        <td><?= $obj->getProductName() ?></td>
                        <td> <?= $obj->getQty() ?></td>
                        <td <?= $row_span ?>><?= $currsign[$obj->getCurrencyId()] ?><?= number_format($obj->getAmount(), 2) ?></td>
                        <td <?= $row_span ?>><?= $obj->getDeliveryName() ?></td>
                        <td <?= $row_span ?>><?= $obj->getDeliveryPostcode() ?></td>
                        <td <?= $row_span ?>><?= $obj->getDeliveryCountryId() ?></td>
                        <td <?= $row_span ?>
                            align="center"><?= $obj->getDeliveryTypeId() != $default_delivery ? "<image src='/images/tick.gif'>" : "" ?></td>
                        <td <?= $row_span ?>><?= $obj->getWarehouseId() ?></td>
                        <td <?= $row_span ?>><?= $obj->getShNo() ?></td>
                        <td <?= $row_span ?> nowrap style="white-space:nowrap;"><?= $obj->getCourierId() ?> -><input
                                size="6" style="width:50px" name="courier[<?= $obj->getShNo() ?>]" value="<?= htmlspecialchars($_POST["courier"][$obj->getShNo()]) ?>"><br><input
                                name="tracking[<?= $obj->getShNo() ?>]" class="input" value="<?= htmlspecialchars($obj->getTrackingNo()) ?>"></td>
                        <td <?= $row_span ?>><?= $obj->getRecCourier() ?></td>
                        <td <?= $row_span ?>>
                            <div id="note_<?= $i ?>" class="normal_p"><?= nl2br($obj->getNote()) ?></div>
                            <div align="right">
                                <a href="<?= base_url() ?>order/integrated_order_fulfillment/add_note/<?= $obj->getSoNo() ?>/<?= $i ?>" rel="lyteframe[note]" rev="width: 400px; height: 400px; scrolling: auto;" title="<?= $lang["add_note"] ?>: <?= $obj->getSoNo() ?>"><?= $lang["add_note"] ?></a>
                            </div>
                        </td>
                        <?= $splitgrouprow ?>
                        <td <?= $row_span ?> align="center">
                            <input type="checkbox" name="check[<?= $obj->getShNo() ?>]" id="check[<?= $obj->getSoNo() ?>]" value="<?= $obj->getSoNo() ?>"<?= $check_html ?>>
                        </td>
                    </tr>
                    <?php

                    $i++;
                endif;
            endforeach;
        endif;
        ?>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
            <tr>
                <td align="right" style="padding-right:8px;">
                    <input type="button" value="<?= $lang['print_order_packing_slip_selected'] ?>" onClick="this.form.action='<?= base_url() ?>order/integrated_order_fulfillment/order_packing_slip';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">
                    &nbsp;|&nbsp
                    <input type="button" value="<?= $lang['import_trackingfile'] ?>" onClick="document.getElementById('a_check').onclick();">
                    <a id="a_check" href="<?= base_url() ?>order/integrated_order_fulfillment/import_trackingfile" rel="lyteframe" rev="width: 700px; height: 500; scrolling: auto;" title="<?= $lang['import_trackingfile'] ?>"></a>
                    &nbsp;|&nbsp
                    <input type="button" value="<?= $lang['print_note_selected'] ?>" onClick="this.form.action='<?= base_url() ?>order/integrated_order_fulfillment/delivery_note';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">
                    &nbsp;|&nbsp
                    <input type="button" value="<?= $lang['print_custom_selected'] ?> (HKD)" onClick="this.form.action='<?= base_url() ?>order/integrated_order_fulfillment/custom_invoice/hkd';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">
                    &nbsp;|&nbsp
                    <input type="button" value="<?= $lang['print_custom_selected'] ?>" onClick="this.form.action='<?= base_url() ?>order/integrated_order_fulfillment/custom_invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">
                    &nbsp;|&nbsp
                    <input type="button" value="<?= $lang['print_selected'] ?>" onClick="this.form.action='<?= base_url() ?>order/integrated_order_fulfillment/invoice';this.form.target='_blank';this.form.submit();this.form.target='';this.form.action='';">
                    &nbsp;|&nbsp
                    <input type="button" value="<?= $lang['return_selected'] ?>" onClick="document.fm_edit.dispatch_type.value='r';this.form.submit();"> &nbsp;|&nbsp
                    <select name="courier_id" id="courier_id">
                        <?php
                        $html_courier_bottom = "";
                        foreach ($courier_list as $key => $value) :
                            $html_courier_bottom .= <<<html
                        <option value="$value">$value</option>
html;
                        endforeach;
                        echo $html_courier_bottom;
                        ?>
                    </select>
                    <input type="button" value="<?= $lang['change_courier'] ?>"
                           onClick="if(document.getElementById('courier_id').value == 'DHLBBX') { set_mawb();} document.fm_edit.dispatch_type.value='c';document.fm_edit.submit();">
                    &nbsp;|&nbsp
                    <input type="button" value="<?= $lang['confirm_dispatch'] ?>"
                           onClick="document.fm_edit.dispatch_type.value='d';this.form.submit();">
                </td>
            </tr>
        </table>
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="dispatch_type" value="d">
        <input type="hidden" name="db_time" value="<?= $db_time ?>">
        <input type="hidden" name="mawb" id="mawb" value="">
    </form>
    <div align="right" class="count_tag">&nbsp;Total order(s): <?= $total_order ?> &nbsp; &nbsp;Total
        items(s): <?= $total_item ?> &nbsp; </div><?= $links ?>
    <?= $notice["js"] ?>
</div>
<iframe name="printframe" src="" width="0" height="0" frameborder="0" scrolling="no"></iframe>
<script>
    function set_mawb() {
        if (mawb = prompt('Please enter MAWB# :', '')) {
            document.getElementById('mawb').value = mawb;
        }
        else {
            exit;
        }
    }

    function checkSplit(so_list, currentsono) {
        var listarr = so_list.split(',');
        var currentcheckval = document.getElementById('check[' + currentsono + ']').checked;

        for (var i = 0; i < listarr.length; i++) {
            var checkbox = document.getElementById('check[' + listarr[i] + ']');
            checkbox.checked = currentcheckval;
        }
        ;
    }

    InitPlatform(document.fm.platform_id);
    document.fm.platform_id.value = '<?=$this->input->get("platform_id")?>';
    //document.fm.platform_id.value = 'AMUK';
    <?php
        if ($_SESSION["courier_file"]) :
    ?>
    top.frames["printframe"].window.location.href = '<?=base_url()?>order/integrated_order_fulfillment/get_courier_file/<?=$_SESSION["courier_file"]?>';
    <?php
        endif;
    ?>
</script>
</body>
</html>
