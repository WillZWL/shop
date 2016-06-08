<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <!-- <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script> -->
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <script>
        function isValidDate(inputDate) {
            var result = inputDate.search(/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])/);
            return (result >= 0) ? true : false;
        }

        function checkFormInput() {
            var start_date = document.getElementById("start_date").value;
            var end_date = document.getElementById("end_date").value;
            var so_number = document.getElementById("so_number").value;
            var message = "";

            if ((so_number == "")
                && ((start_date == "") || (end_date == "")))
                message += "You need to put a order number or a date range!\n";
            else if (so_number == "") {
                if (!isValidDate(start_date))
                    message += "Not a valid start date!\n";
                if (!isValidDate(end_date))
                    message += "Not a valid end date!\n";
            }
            if (message != "") {
                alert(message);
                return false;
            }
            else
                return true;
        }

    </script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
    <style type="text/css">
        .contentRow {
            height: 20px;
            background-color: #FFCCFF;
        }

        .tableField {
            background-color: #666666;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
            <td align="left" class="title" height="30"><b
                    style="font-size: 16px; color: rgb(0, 0, 0);"><?= $lang["title"] ?></b></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
        <tr height="70">
            <td align="left" style="padding-left:8px;">
                <b style="font-size: 14px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br>
            </td>
            <td align="right">
                <form name="fm" action="<?= base_url() . "order/all_orders/index" ?>" method="post"
                      onsubmit="return checkFormInput()">
                    <table border="0" cellpadding="3" cellspacing="0" width="600" style="line-height:8px;">
                        <col width="60">
                        <col width="80">
                        <col width="60">
                        <col width="80">
                        <col width="30">
                        <tr>
                            <td align='right'><b><?= $lang["order_type"] ?></b></td>
                            <td>
                                <select name="order_type">
                                    <option></option>
                                    <?php
                                    if ($order_type_list) {
                                        foreach ($order_type_list as $order_type_obj) {
                                            $order_type = $order_type_obj->getSellingPlatformId();
                                            ?>
                                            <option name="order_type" id="order_type"
                                                    value="<?= htmlspecialchars($order_type) ?>" <?= $select_order_type == $order_type ? " selected='selected'" : '' ?>><?= $order_type ?></option>

                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>

                            <td align='right'><b><?= $lang["start_date"] ?></b></td>
                            <td><input name="start_date" id="start_date"
                                       value='<?= htmlspecialchars($start_date) ?>'><img src="/images/cal_icon.gif"
                                                                                         class="pointer"
                                                                                         onclick="showcalendar(event, document.fm.start_date, false, false, false, '2010-01-01')"
                                                                                         align="absmiddle"></td>
                            <td rowspan="2" align="center"><input type="submit" value="" class="search_button"
                                                                  name="searchSubmit"
                                                                  style="background: url('<?= base_url() ?>/images/find.gif') #CCCCCC no-repeat center; width: 30px; height: 25px;"><input
                                    type="hidden" name="post" value="1"> &nbsp; </td>
                        </tr>
                        <tr>
                            <td align='right'><b><?= $lang["psp_gateway"] ?></b></td>
                            <td>
                                <select name="psp_gateway">
                                    <option></option>
                                    <?php
                                    if ($pmgw_list) {
                                        foreach ($pmgw_list as $pmgw_obj) {
                                            $pmgw_id = $pmgw_obj->getPaymentGatewayId();
                                            ?>
                                            <option name="pmgw_id" id="pmgw_id"
                                                    value="<?= htmlspecialchars($pmgw_id) ?>" <?= $select_psp_gateway == $pmgw_id ? " selected='selected'" : '' ?>><?= $pmgw_id ?></option>

                                        <?php
                                        }
                                    }
                                    ?>
                                </select>

                            </td>
                            <td align='right'><b><?= $lang["end_date"] ?></b></td>
                            <td><input name="end_date" id="end_date" value='<?= htmlspecialchars($end_date) ?>'><img
                                    src="/images/cal_icon.gif" class="pointer"
                                    onclick="showcalendar(event, document.fm.end_date, false, false, false, '2010-01-01')"
                                    align="absmiddle"></td>
                        </tr>
                        <tr>
                            <td align='right'><b><?= $lang["hold_reason"] ?></b></td>
                            <td>
                                <select name="hold_reason">
                                    <option></option>
                                    <?php
                                    if ($so_hold_reason_list) {
                                        foreach ($so_hold_reason_list as $key => $value) {
                                            $hold_reason = $key;
                                            ?>
                                            <option name="hold_reason" id="hold_reason"
                                                    value="<?= htmlspecialchars($hold_reason) ?>" <?= "$select_hold_reason" == "$hold_reason" ? " selected='selected'" : '' ?>><?= $hold_reason ?></option>

                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td align='right'><b><?= $lang["so_number"] ?></b></td>
                            <td><input name="so_number" id="so_number" value='<?= htmlspecialchars($so_number) ?>'></td>
                        </tr>
                        <tr>
                            <td align='right'><b><?= $lang["currency"] ?></b></td>
                            <td>
                                <select name="currency">
                                    <option></option>
                                    <?php
                                    if ($currency_list) {
                                        foreach ($currency_list as $curr) {
                                            $currId = $curr->getCurrencyId();
                                            ?>
                                            <option name="currency_id" id="currency_id"
                                                    value="<?= htmlspecialchars($currId) ?>" <?= "$select_currency" == "$currId" ? " selected='selected'" : '' ?>><?= $currId ?></option>

                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>

                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align='center'>
                                <input type="submit" name="exportSubmit" value="Generate CSV report">
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </form>

            </td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033" colspan="3"></td>
        </tr>
    </table>
    <?= $notice["js"] ?>
    <table cellpadding='2' cellspacing='2' border='0' width="6000">
        <?php
        $total_number_of_records = "";
        //print heading
        print "<tr bgcolor='#000000'>";
        print "<td height='20' width='20' class='tableField'>&nbsp;</td>";
        for ($i = 0; $i < sizeof($heading); $i++) {
            print "<td class='tableField' align='center'>" . $heading[$i] . "</td>";
        }
        print "</tr>";
        //content
        $order_number = 1;
        $current_so = "";
        for ($i = 0; $i < sizeof($orders); $i++) {
            print "<tr class='contentRow'>";
            if ($current_so != $orders[$i]->getSoNo()) {
                print "<td>" . $order_number . ".</td>";
                $order_number++;
            } else
                print "<td>&nbsp;</td>";

            print "<td>" . $orders[$i]->getSoNo() . "</td>";
            print "<td>" . $orders[$i]->getHoldReason() . "</td>";
            print "<td>" . $orders[$i]->getHoldDate() . "</td>";
            print "<td>" . $orders[$i]->getHoldTime() . "</td>";
            print "<td>" . $orders[$i]->getHoldStaff() . "</td>";
            print "<td>" . $orders[$i]->getEmptyField() . "</td>";
            print "<td>" . $orders[$i]->getEmptyField() . "</td>";
            print "<td>" . $orders[$i]->getEmptyField() . "</td>";
            print "<td>" . $orders[$i]->getOrderCreateDate() . "</td>";
            print "<td>" . $orders[$i]->getOrderCreateTime() . "</td>";
            print "<td>" . $orders[$i]->getPaymentTransactionId() . "</td>";
            print "<td>" . $orders[$i]->getEmptyField() . "</td>";
            print "<td>" . $orders[$i]->getPaymentGatewayId() . "</td>";
            print "<td>" . $orders[$i]->getProductName() . "</td>";
            print "<td>" . $orders[$i]->getCategoryName() . "</td>";
            print "<td>" . $orders[$i]->getCurrency() . "</td>";
            print "<td>" . $orders[$i]->getItemValue() . "</td>";
            print "<td>" . $orders[$i]->getItemQuantity() . "</td>";
            print "<td>" . $orders[$i]->getOrderQuantity() . "</td>";
            print "<td>" . $orders[$i]->getOrderValue() . "</td>";
            print "<td>" . $orders[$i]->getPaid() . "</td>";
            print "<td>" . $orders[$i]->getMbStatus() . "</td>";
            print "<td>" . $orders[$i]->getClientForename() . "</td>";
            print "<td>" . $orders[$i]->getClientSurname() . "</td>";
            print "<td>" . $orders[$i]->getClientId() . "</td>";
            print "<td>" . $orders[$i]->getEmail() . "</td>";
            print "<td>" . $orders[$i]->getBillForename() . "</td>";
            print "<td>" . $orders[$i]->getBillSurname() . "</td>";
            print "<td>" . $orders[$i]->getBillCompany() . "</td>";
            print "<td>" . $orders[$i]->getBillAddress1() . "</td>";
            print "<td>" . $orders[$i]->getBillAddress2() . "</td>";
            print "<td>" . $orders[$i]->getBillAddress3() . "</td>";
            print "<td>" . $orders[$i]->getBillCity() . "</td>";
            print "<td>" . $orders[$i]->getBillState() . "</td>";
            print "<td>" . $orders[$i]->getBillPostcode() . "</td>";
            print "<td>" . $orders[$i]->getBillCountryId() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryName() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryCompany() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryAddress1() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryAddress2() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryAddress3() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryCity() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryState() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryPostcode() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryCountryId() . "</td>";
            print "<td>" . $orders[$i]->getPassword() . "</td>";
            print "<td>" . $orders[$i]->getTel() . "</td>";
            print "<td>" . $orders[$i]->getMobile() . "</td>";
            print "<td>" . $orders[$i]->getOrderType() . "</td>";
            print "<td>" . $orders[$i]->getShipServiceLevel() . "</td>";
            print "<td>" . $orders[$i]->getDeliveryCost() . "</td>";
            print "<td>" . $orders[$i]->getPromotionCode() . "</td>";
            print "<td>" . $orders[$i]->getPaymentType() . "</td>";
            print "<td>" . $orders[$i]->getCardType() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar1() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar2() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar3() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar4() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar5() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar6() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar7() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar8() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar9() . "</td>";
            print "<td>" . $orders[$i]->getRiskVar10() . "</td>";
            print "<td>" . $orders[$i]->getCardBin() . "</td>";
            print "<td>" . $orders[$i]->getVerificationLevel() . "</td>";
            print "<td>" . $orders[$i]->getFraudResult() . "</td>";
            print "<td>" . $orders[$i]->getAvsResult() . "</td>";
            print "<td>" . $orders[$i]->getProtectionEligibility() . "</td>";
            print "<td>" . $orders[$i]->getProtectionEligibilityType() . "</td>";
            print "<td>" . $orders[$i]->getAddressStatus() . "</td>";
            print "<td>" . $orders[$i]->getPayerStatus() . "</td>";
            print "<td>" . $orders[$i]->getIpAddress() . "</td>";
            print "<td>" . $orders[$i]->getOrderStatus() . "</td>";
            print "<td>" . $orders[$i]->getDispatchDate() . "</td>";
            print "<td>" . $orders[$i]->getRefundStatus() . "</td>";
            print "<td>" . $orders[$i]->getRefundDate() . "</td>";
            print "<td>" . $orders[$i]->getRefundReason() . "</td>";
            print "</tr>";
            $current_so = $orders[$i]->getSoNo();
            $total_number_of_records = $order_number;
        }
        //print heading
        print "<tr bgcolor='#000000'>";
        print "<td height='20' width='20' class='tableField'>&nbsp;</td>";
        for ($i = 0; $i < sizeof($heading); $i++) {
            print "<td class='tableField' align='center'>" . $heading[$i] . "</td>";
        }
        print "</tr>";
        ?>
    </table>
    <?php
    print $lang["total"] . (($total_number_of_records == "") ? $total_number_of_records : ($total_number_of_records - 1));
    ?>
</div>
</body>
</html>
