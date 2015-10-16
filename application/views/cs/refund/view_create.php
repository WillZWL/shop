<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script language="javascript">
        <!--
        function changeRowStatus(row) {
            if (document.fm.elements["refund[" + row + "]"].checked) {
                document.fm.elements["rqty[" + row + "]"].disabled = false;
                document.fm.elements["ramount[" + row + "]"].disabled = false;
                document.fm.elements["rsku[" + row + "]"].disabled = false;

                userQtyInput = document.fm.elements["rqty[" + row + "]"].value;
                defaultQty = document.fm.elements["pqty[" + row + "]"].value;
                if ((userQtyInput == defaultQty) || (userQtyInput == 0))
                    document.fm.elements["rqty[" + row + "]"].value = defaultQty;
                if (document.fm.elements["rqty[" + row + "]"].value <= defaultQty)
                    discountedUnitPrice = document.fm.elements["price[" + row + "]"].value / document.fm.elements["pqty[" + row + "]"].value;
                else
                    discountedUnitPrice = 0;
                document.fm.elements["ramount[" + row + "]"].value = discountedUnitPrice * document.fm.elements["rqty[" + row + "]"].value;
                total++;
            }
            else {
                document.fm.elements["rqty[" + row + "]"].disabled = true;
                document.fm.elements["rqty[" + row + "]"].value = 0;
                document.fm.elements["ramount[" + row + "]"].disabled = true;
                document.fm.elements["ramount[" + row + "]"].value = 0.00;
                document.fm.elements["rsku[" + row + "]"].disabled = true;
                total--;
            }
            document.fm.elements["cashback"].disabled = (total > 0 ? true : false);
            if (total > 0) {
                document.fm.elements["cashback"].value = 0;
            }
            updateTotal();
        }

        function updateTotal() {
            if (document.getElementsByName("ramount[]")) {
                var total = 0;

                for (var i = 0; i < itemcnt; i++) {
                    val = document.fm.elements["ramount[" + i + "]"].value * 1;
                    document.getElementById("rt[" + i + "]").innerHTML = val.toFixed(2);
                    total = total + val;
                }

                total = total + document.fm.elements["cashback"].value * 1;

                document.fm.elements["total"].value = total;
            }
        }

        itemcnt = <?=$itemcnt?>;
        total = 0;
        -->
    </script>
</head>
<body>
<div id="main" bgcolor="#ddddff">
    <?= $notice["image"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>
    <!-- recent history -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
        <tr>
            <td align="left" height="20" style="padding-left:10px;"><font
                    style="font-size:12px; color:#ffffff; font-weight:bold;"><?= $lang["refund_history"] . " " . $orderid ?></font>
            </td>
        </tr>
    </table>
    <table width="100%" cellpadding="0" cellspacing="1" border="0" bgcolor="#cccccc" class="tb_list">
        <col width="20">
        <col width="150">
        <col width="100">
        <col width="150">
        <col width="">
        <col width="120">
        <col width="20">

        <?php
        if (count($history)) :
            ?>
            <tr class="header">
                <td height="20">&nbsp;</td>
                <td><?= $lang["date"] ?></td>
                <td><?= $lang["status"] ?></td>
                <td><?= $lang["reason"] ?></td>
                <td><?= $lang["notes"] ?></td>
                <td><?= $lang["process_by"] ?></td>
                <td><?= $lang["approval_status"] ?></td>
                <td>&nbsp;</td>
            </tr>
            <?php
            $i = 0;
            foreach ($history as $obj) :
                ?>
                <tr class="row<?= $i % 2 ?>">
                    <td>&nbsp;</td>
                    <td><?= $obj->getCreateOn() ?></td>
                    <td><?= $lang["ristatus"][$obj->getStatus()] ?></td>
                    <td><?= $lang["rcategory"][$obj->getReasonCat()] . " - " . $obj->getDescription() ?></td>
                    <td><?= $obj->getNotes() ?></td>
                    <td><?= $obj->getName() ?></td>
                    <td><?= $lang["app_status"][$obj->getAppStatus()] ?></td>
                    <td>&nbsp;</td>
                </tr>
                <?php
                $i++;
            endforeach;
        else :
            ?>
            <tr class="row0">
                <td align="center" colspan="7" height="20"><?= $lang["no_history"] ?></td>
            </tr>
        <?php
        endif;
        ?>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
        <tr height="20">
            <td>&nbsp;</td>
        </tr>
    </table>
    <!-- Order information -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
        <tr>
            <td align="left" height="20" style="padding-left:10px;"><font
                    style="font-size:12px; color:#ffffff; font-weight:bold;"><?= $lang["order_information"] ?></font>
            </td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
        <tr>
            <td width="15%" height="20" align="right" class="field"
                style="padding-right:10px;"><?= $lang["order_number"] ?></td>
            <td width="35%" align="left" class="value" style="padding-left:10px;"><?= $orderid ?></td>
            <td width="15%" height="20" align="right" class="field"
                style="padding-right:10px;"><?= $lang["order_status"] ?></td>
            <td width="35%" align="left" class="value"
                style="padding-left:10px;"><?= $lang["so_status"][$orderobj->getStatus()] ?></td>
        </tr>
        <tr>
            <td width="15%" height="20" align="right" class="field" style="padding-right:10px;">Refundable orders in
                same group:
            </td>
            <td width="35%" align="left" class="value" style="padding-left:10px;"><?= $split_child_list_html ?></td>
            <td width="15%" height="20" align="right" class="field" style="padding-right:10px;">&nbsp;</td>
            <td width="35%" align="left" class="value" style="padding-left:10px;">&nbsp;</td>
        </tr>
        <tr>
            <td width="15%" height="20" align="right" class="field"
                style="padding-right:10px;"><?= $lang["platform"] ?></td>
            <td width="35%" align="left" class="value"
                style="padding-left:10px;"><?= $lang["so_platform"][$orderobj->getPlatformId()] ?></td>
            <td width="15%" height="20" align="right" class="field"
                style="padding-right:10px;"><?= $lang["order_amount"] ?></td>
            <td width="35%" align="left" class="value"
                style="padding-left:10px;"><?= $orderobj->getCurrencyId() . " " . number_format(($orderobj->getAmount() - $orderobj->getDeliveryCharge()), 2) ?></td>
        </tr>
        <?php
        if (ereg('^WS', $orderobj->getPlatformId())) :
            ?>
            <tr>
                <td width="15%" height="20" align="right" class="field"
                    style="padding-right:10px;"><?= $lang["biztype"] ?></td>
                <td width="35%" align="left" class="value"
                    style="padding-left:10px;"><?= $orderobj->getBizType() ?></td>
                <td width="15%" height="20" align="right" class="field"
                    style="padding-right:10px;"><?= $lang["order_delivery_charge"] ?></td>
                <td width="35%" align="left" class="value"
                    style="padding-left:10px;"><?= $orderobj->getCurrencyId() . " " . number_format($orderobj->getDeliveryCharge(), 2) ?></td>
            </tr>
        <?php
        else :
            ?>
            <tr>
                <td width="15%" height="20" align="right" class="field"
                    style="padding-right:10px;"><?= $lang["platform_order_id"] ?></td>
                <td width="35%" align="left" class="value"
                    style="padding-left:10px;"><?= $orderobj->getPlatformOrderId() ?></td>
                <td width="15%" height="20" align="right" class="field"
                    style="padding-right:10px;"><?= $lang["order_delivery_charge"] ?></td>
                <td width="35%" align="left" class="value"
                    style="padding-left:10px;"><?= $orderobj->getCurrencyId() . " " . $orderobj->getDeliveryCharge() ?></td>
            </tr>
        <?php
        endif;
        ?>
        <tr>
            <td width="15%" height="20" align="right" class="field"
                style="padding-right:10px;"><?= $lang["client_id_and_name"] ?></td>
            <td width="35%" align="left" class="value"
                style="padding-left:10px;"><?= $orderobj->getClientId() . " - " . $orderobj->getBillName() ?></td>
            <td width="15%" height="20" align="right" class="field"
                style="padding-right:10px;"><?= $lang["order_total"] ?></td>
            <td width="35%" align="left" class="value"
                style="padding-left:10px;"><?= $orderobj->getCurrencyId() . " " . number_format(($orderobj->getAmount()), 2) ?></td>
        </tr>

    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
        <tr height="20">
            <td>&nbsp;</td>
        </tr>
    </table>
    <!-- setting up refund request -->
    <form name="fm" method="POST" onSubmit="CheckForm(this);">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000033">
            <tr>
                <td align="left" height="20" style="padding-left:10px;"><font
                        style="font-size:12px; color:#ffffff; font-weight:bold;"><?= $lang["request_refund"] ?></font>
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="1" bgcolor="#cccccc" width="100%">
            <tr class="header">
                <td height="20">&nbsp;</td>
                <td style="padding-left:4px;"><?= $lang["item_sku"] ?></td>
                <td style="padding-left:4px;"><?= $lang["prod_name"] ?></td>
                <td style="padding-left:4px;"><?= $lang["original_price"] ?></td>
                <td style="padding-left:4px;"><?= $lang["discounted_price"] ?></td>
                <td style="padding-left:4px;"><?= $lang["gst"] ?></td>
                <td style="padding-left:4px;"><?= $lang["qty"] ?></td>
                <td style="padding-left:4px;"><?= $lang["refund"] ?></td>
                <td style="padding-left:4px;"><?= $lang["refund_qty"] ?></td>
                <td style="padding-left:4px;"><?= $lang["suggested_refund_price_per_item"] ?></td>
                <td style="padding-left:4px;"><?= $lang["total_refund_amount"] ?></td>
                <td style="padding-left:4px;"><?= $lang["prod_deliver_date"] ?></td>
                <td>&nbsp;</td>
            </tr>
            <?php
            $i = 0;
            foreach ($itemlist as $obj) :
                ?>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"></td>
                    <td style="padding-left:4px;"><?= $obj->getItemSku() ?><input type="hidden" name="rsku[<?= $i ?>]"
                                                                                    value="<?= $obj->getItemSku() ?>"
                                                                                    DISABLED></td>
                    <td style="padding-left:4px;"><?= $obj->getName() ?></td>
                    <td style="padding-left:4px;"><?= $orderobj->getCurrencyId() . " " . number_format($obj->getUnitPrice(), 2) ?></td>
                    <td style="padding-left:4px;"><?= $orderobj->getCurrencyId() . " " . $obj->getAmount() ?><input
                            type="hidden" name="price[<?= $i ?>]"
                            value="<?= $obj->getAmount() + $obj->getGstTotal() ?>"></td>
                    <td style="padding-left:4px;"><?= $orderobj->getCurrencyId() . " " . $obj->getGstTotal() ?></td>
                    <td style="padding-left:4px;"><?= $obj->getQty() ?><input type="hidden" name="pqty[<?= $i ?>]"
                                                                               value="<?= $obj->getQty() ?>"></td>
                    <td style="padding-left:4px;">
                        <?php
                        if ($orderobj->getStatus() < 6) :
                            ?>
                            <input type="checkbox" name="refund[<?= $i ?>]" value="1"
                                   onClick="changeRowStatus('<?= $i ?>')">
                        <?php
                        endif;
                        ?>
                    </td>
                    <td style="padding-left:4px;"><input type="text" id="rqty[<?= $i ?>]" name="rqty[<?= $i ?>]"
                                                         value="<?= $obj->getQty() ?>" class="int_input" min=0
                                                         isInteger max=<?= $obj->getQty() ?> DISABLED
                                                         onChange="changeRowStatus('<?= $i ?>');"></td>
                    <td style="padding-left:4px;"><?= $orderobj->getCurrencyId() ?>&nbsp;<input type="text"
                                                                                                  id="ramount[<?= $i ?>]"
                                                                                                  name="ramount[<?= $i ?>]"
                                                                                                  value="0.00"
                                                                                                  class="int_input"
                                                                                                  min=0 isNumber
                                                                                                  DISABLED
                                                                                                  onChange="updateTotal();">
                    </td>
                    <td style="padding-left:4px;"><?= $orderobj->getCurrencyId() ?>&nbsp;<span
                            id="rt[<?= $i ?>]"><?= number_format(($obj->getAmount() + $obj->getGstTotal()), 2) ?></span>
                    </td>
                    <td style="padding-left:4px;"><?= $orderobj->getDispatchDate() ?></td>
                    <td></td>
                </tr>
                <?php
                $i++;
            endforeach;
            ?>
        </table>
        <table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="#cccccc">
            <tr>
                <td width="30%" height="20" align="right" style="padding-right:10px;"
                    class="field"><?= $lang["cashback_amount"] ?></td>
                <td width="70%" align="left" style="padding-left:10px;" class="value"><input name="cashback" type="text"
                                                                                             value="0.00"
                                                                                             class="int_input" isNumber
                                                                                             min=0
                                                                                             onChange="updateTotal();">
                </td>
            </tr>
            <tr>
                <td width="30%" height="20" align="right" style="padding-right:10px;"
                    class="field"><?= $lang["refund_reason"] ?></td>
                <td width="70%" align="left" style="padding-left:10px;" class="value"><select name="reason"
                                                                                              class="input">
                        <?php
                        foreach ($reason["reason_list"] as $robj) :

                            ?>
                            <option
                                value="<?= $robj->getId() ?>"><?= $lang["rcategory"][$robj->getReasonCat()] . " - " . $robj->getDescription() ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select></td>
            </tr>
            <tr>
                <td width="30%" height="20" align="right" style="padding-right:10px;"
                    class="field"><?= $lang["refund_notes"] ?></td>
                <td width="70%" align="left" style="padding-left:10px;" class="value"><textarea name="rnotes"
                                                                                                class="input"
                                                                                                rows="5"></textarea>
                </td>
            </tr>
            <tr>
                <td width="30%" height="20" align="right" style="padding-right:10px;" class="field">&nbsp;</td>

                <?php
                if ($orderobj->getBizType() == "SPECIAL") :
                    ?>
                    <td width="70%" align="left" style="padding-left:10px;" class="value">&nbsp;<input type="button"
                                                                                                       onClick="if(confirm('***ALERT*** \nYou are about to cancel a Special order. \nDo you wish to proceed?') && CheckForm(this.form)) document.fm.submit();"
                                                                                                       value="<?= $lang["submit_form"] ?>">&nbsp;&nbsp&nbsp;<input
                            type="reset" value="<?= $lang["reset_form"] ?>"></td>
                    <?php
                    ;
                else :
                    ?>
                    <td width="70%" align="left" style="padding-left:10px;" class="value">&nbsp;<input type="button"
                                                                                                       onClick="if(CheckForm(this.form)) document.fm.submit();"
                                                                                                       value="<?= $lang["submit_form"] ?>">&nbsp;&nbsp&nbsp;<input
                            type="reset" value="<?= $lang["reset_form"] ?>"></td>
                    <?php
                    ;
                endif;
                ?>


            </tr>
        </table>
        <input type="hidden" value="1" name="posted">
        <input type="hidden" value="0.00" name="total">
    </form>
    <table border="0" cellpadding="0" cellspacing="0" class="page_header" width="100%">
        <tr height="20" bgcolor="#000033">
            <td style="padding-left:10px;"><input type="button" value="<?= $lang["back_to_list"] ?>"
                                                  onClick="Redirect('<?= base_url() . "cs/refund/create/?" . $_SESSION["QUERY"] ?>')">
            </td>
        </tr>
        <tr height="20">
            <td>&nbsp;</td>
        </tr>
    </table>
</div>
<?= $notice["js"] ?>
</body>
</html>