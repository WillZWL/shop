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
                                            $order_type = $order_type_obj->get_id();
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
                                            $pmgw_id = $pmgw_obj->get_id();
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
                                        foreach ($currency_list as $currency_id => $currency_name) {
                                            ?>
                                            <option name="currency_id" id="currency_id"
                                                    value="<?= htmlspecialchars($currency_id) ?>" <?= "$select_currency" == "$currency_id" ? " selected='selected'" : '' ?>><?= $currency_id ?></option>

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
            if ($current_so != $orders[$i]->get_so_no()) {
                print "<td>" . $order_number . ".</td>";
                $order_number++;
            } else
                print "<td>&nbsp;</td>";

            print "<td>" . $orders[$i]->get_so_no() . "</td>";
            print "<td>" . $orders[$i]->get_hold_reason() . "</td>";
            print "<td>" . $orders[$i]->get_hold_date() . "</td>";
            print "<td>" . $orders[$i]->get_hold_time() . "</td>";
            print "<td>" . $orders[$i]->get_hold_staff() . "</td>";
            print "<td>" . $orders[$i]->get_empty_field() . "</td>";
            print "<td>" . $orders[$i]->get_empty_field() . "</td>";
            print "<td>" . $orders[$i]->get_empty_field() . "</td>";
            print "<td>" . $orders[$i]->get_order_create_date() . "</td>";
            print "<td>" . $orders[$i]->get_order_create_time() . "</td>";
            print "<td>" . $orders[$i]->get_payment_transaction_id() . "</td>";
            print "<td>" . $orders[$i]->get_empty_field() . "</td>";
            print "<td>" . $orders[$i]->get_payment_gateway_id() . "</td>";
            print "<td>" . $orders[$i]->get_product_name() . "</td>";
            print "<td>" . $orders[$i]->get_category_name() . "</td>";
            print "<td>" . $orders[$i]->get_currency() . "</td>";
            print "<td>" . $orders[$i]->get_item_value() . "</td>";
            print "<td>" . $orders[$i]->get_item_quantity() . "</td>";
            print "<td>" . $orders[$i]->get_order_quantity() . "</td>";
            print "<td>" . $orders[$i]->get_order_value() . "</td>";
            print "<td>" . $orders[$i]->get_paid() . "</td>";
            print "<td>" . $orders[$i]->get_mb_status() . "</td>";
            print "<td>" . $orders[$i]->get_client_forename() . "</td>";
            print "<td>" . $orders[$i]->get_client_surname() . "</td>";
            print "<td>" . $orders[$i]->get_client_id() . "</td>";
            print "<td>" . $orders[$i]->get_email() . "</td>";
            print "<td>" . $orders[$i]->get_bill_forename() . "</td>";
            print "<td>" . $orders[$i]->get_bill_surname() . "</td>";
            print "<td>" . $orders[$i]->get_bill_company() . "</td>";
            print "<td>" . $orders[$i]->get_bill_address1() . "</td>";
            print "<td>" . $orders[$i]->get_bill_address2() . "</td>";
            print "<td>" . $orders[$i]->get_bill_address3() . "</td>";
            print "<td>" . $orders[$i]->get_bill_city() . "</td>";
            print "<td>" . $orders[$i]->get_bill_state() . "</td>";
            print "<td>" . $orders[$i]->get_bill_postcode() . "</td>";
            print "<td>" . $orders[$i]->get_bill_country_id() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_name() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_company() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_address1() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_address2() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_address3() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_city() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_state() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_postcode() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_country_id() . "</td>";
            print "<td>" . $orders[$i]->get_password() . "</td>";
            print "<td>" . $orders[$i]->get_tel() . "</td>";
            print "<td>" . $orders[$i]->get_mobile() . "</td>";
            print "<td>" . $orders[$i]->get_order_type() . "</td>";
            print "<td>" . $orders[$i]->get_ship_service_level() . "</td>";
            print "<td>" . $orders[$i]->get_delivery_cost() . "</td>";
            print "<td>" . $orders[$i]->get_promotion_code() . "</td>";
            print "<td>" . $orders[$i]->get_payment_type() . "</td>";
            print "<td>" . $orders[$i]->get_card_type() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var1() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var2() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var3() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var4() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var5() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var6() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var7() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var8() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var9() . "</td>";
            print "<td>" . $orders[$i]->get_risk_var10() . "</td>";
            print "<td>" . $orders[$i]->get_card_bin() . "</td>";
            print "<td>" . $orders[$i]->get_verification_level() . "</td>";
            print "<td>" . $orders[$i]->get_fraud_result() . "</td>";
            print "<td>" . $orders[$i]->get_avs_result() . "</td>";
            print "<td>" . $orders[$i]->get_protection_eligibility() . "</td>";
            print "<td>" . $orders[$i]->get_protection_eligibilityType() . "</td>";
            print "<td>" . $orders[$i]->get_address_status() . "</td>";
            print "<td>" . $orders[$i]->get_payer_status() . "</td>";
            print "<td>" . $orders[$i]->get_ip_address() . "</td>";
            print "<td>" . $orders[$i]->get_order_status() . "</td>";
            print "<td>" . $orders[$i]->get_dispatch_date() . "</td>";
            print "<td>" . $orders[$i]->get_refund_status() . "</td>";
            print "<td>" . $orders[$i]->get_refund_date() . "</td>";
            print "<td>" . $orders[$i]->get_refund_reason() . "</td>";
            print "</tr>";
            $current_so = $orders[$i]->get_so_no();
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
