<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/payment_gateway/js_pmgwlist"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
    <script>
        function cancel_check(e) {
            var elements = document.getElementsByName(e.name);
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].checked == true) {
                    elements[i].checked = false;
                    return false;
                }
            }
        }

        function save(number, type) {
            if (number) {
                var number = String(number);
                var select = document.getElementById('select[' + number + ']');
                select.value = 1;

                if (type == 'unknown') {
                    document.getElementById("fm3").submit();
                }
                else {
                    document.getElementById("fm2").submit();
                }
            }
        }

        function show_hide(ele_id, button_id) {
            var ele = document.getElementById(ele_id);
            var button_ele = document.getElementById(button_id);

            if (ele.style.display == 'table-row') {
                ele.style.display = 'none';
                button_ele.value = 'SHOW ROW'
            }
            else {
                ele.style.display = 'table-row';
                button_ele.value = 'HIDE ROW'
            }

        }

    </script>

</head>
<body>
<div id="main">

    <? $ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]); ?>
    <?php
    if ($pagetype == "all") {
        $net_diff_status = array(0 => "Unpaid", 1 => "Fully paid", 2 => "Underpaid <= 1%", 3 => "Underpaid > 1%", 4 => "Over paid");
        $hold_status_arr = array(0 => "Not On Hold", 2 => "On-Hold");
        $hold_reason_arr = array(0 => "All", 1 => "unpaid_web_bank_transfer", 2 => "unpaid_web_bank_transfer_aft_grace_period");
    } elseif ($pagetype == "not_full") {
        $net_diff_status = array(0 => "Unpaid", 2 => "Underpaid <= 1%", 3 => "Underpaid > 1%");
    }

    if ($pagetype == "unknown") {
        $unknown_field = 1;
        $unknown_disable = " disabled";
    } else {
        $unknown_field = 0;
        $unknown_disable = "";
    }
    ?>
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?>
                <?php
                if ($pagetype) {
                    ?>
                    - <?= $lang["list_" . $pagetype] ?>
                <?php
                }
                ?>
            </td>
            <td align="right" class="title">
                <input type="button" value="<?= $lang["list_not_full"] ?>"
                       onclick="Redirect('<?= site_url('order/website_bank_transfer/') ?>')"> &nbsp;
                <input type="button" class="button" value="<?= $lang["list_all"] ?>"
                       onclick="Redirect('<?= site_url('order/website_bank_transfer/index/all') ?>')"> &nbsp;
                <input type="button" class="button" value="<?= $lang["list_unknown"] ?>"
                       onclick="Redirect('<?= site_url('order/website_bank_transfer/index/unknown') ?>')"> &nbsp;
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="130">
            <col>
            <col>
            <col width="180">
            <col width="180">
            <col width="180">
            <col width="220">
            <col width="27">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>

                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'so_no', '<?= $xsort["so_no"] ?>')"><?= !$unknown_field ? $lang["order_id"] : "" ?> <?= !$unknown_field ? $sortimg["so_no"] : "" ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'platform_order_id', '<?= $xsort["platform_order_id"] ?>')"><?= !$unknown_field ? $lang["platform_order_id"] : "" ?> <?= !$unknown_field ? $sortimg["platform_order_id"] : "" ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'currency_id', '<?= $xsort["currency_id"] ?>')"><?= !$unknown_field ? $lang["currency_id"] : "" ?> <?= !$unknown_field ? $sortimg["currency_id"] : "" ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'amount', '<?= $xsort["amount"] ?>')"><?= !$unknown_field ? $lang["order_amount"] : "" ?> <?= !$unknown_field ? $sortimg["amount"] : "" ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'sbt.ext_ref_no', '<?= $xsort["sbt.ext_ref_no"] ?>')"><?= $lang["ext_ref_no"] ?> <?= $sortimg["sbt.ext_ref_no"] ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'sbt.net_diff_status', '<?= $xsort["sbt.net_diff_status"] ?>')"><?= !$unknown_field ? $lang["net_diff_status"] : "" ?> <?= !$unknown_field ? $sortimg["sbt.net_diff_status"] : "" ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'sbt.received_amt', '<?= $xsort["sbt.received_amt"] ?>')"><?= $lang["received_amt"] ?> <?= $sortimg["sbt.received_amt"] ?></a>
                </td>
                <td colspan="2" style="white-space:nowrap"></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="so_no" class="input"
                           value="<?= htmlspecialchars($this->input->get("so_no")) ?>" <?= $unknown_disable ?>></td>
                <td><input name="platform_order_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("platform_order_id")) ?>" <?= $unknown_disable ?>>
                </td>
                <td><input name="currency_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("currency_id")) ?>" <?= $unknown_disable ?>>
                </td>
                <td><input name="amount" class="input"
                           value="<?= htmlspecialchars($this->input->get("amount")) ?>" <?= $unknown_disable ?>></td>
                <td><input name="ext_ref_no" class="input"
                           value="<?= htmlspecialchars($this->input->get("ext_ref_no")) ?>"></td>
                <td>

                    <?php
                    $selected_status = $this->input->get("net_diff_status");
                    if ($selected_status == "" || $selected_status == 'ALL')
                        $selected_status = "";

                    if ($net_diff_status) {
                        ?>
                        <select name="net_diff_status">
                            <option value=""></option>
                            <?php
                            foreach ($net_diff_status as $key => $value) {
                                $selected = "";
                                if ($selected_status !== "" && $selected_status == $key) {
                                    $selected = " SELECTED";
                                }
                                ?>
                                <option value="<?= $key ?>" <?= $selected ?>><?= $value ?></option>
                            <?php
                            }
                            ?>

                        </select>
                    <?php
                    }

                    ?>
                </td>
                <td><input name="received_amt" class="input"
                           value="<?= htmlspecialchars($this->input->get("received_amt")) ?>"></td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td colspan="6"></td>
                <td>
                    <?php
                    $selected_hold_status = $this->input->get("hold_status");
                    if ($hold_status_arr) {
                        ?>
                        Hold Status:
                        <select name="hold_status">
                            <option value="">ALL</option>
                            <?php
                            foreach ($hold_status_arr as $key => $value) {
                                $selected = "";
                                if ($selected_hold_status != "" && $selected_hold_status == $key) {
                                    $selected = " SELECTED";
                                }
                                ?>
                                <option value="<?= $key ?>" <?= $selected ?>><?= $value ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    <?php
                    }

                    $selected_hold_reason = $this->input->get("hold_reason");
                    if ($hold_reason_arr) {
                        ?>
                        <br>Hold Reason:
                        <select name="hold_reason">
                            <?php
                            foreach ($hold_reason_arr as $key => $value) {
                                $selected = "";
                                if ($selected_hold_reason != "" && $selected_hold_reason == $key) {
                                    $selected = " SELECTED";
                                }
                                ?>
                                <option value="<?= $key ?>" <?= $selected ?>><?= $value ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    <?php
                    }
                    ?>


                </td>
                <td colspan="2"></td>
            <tr>

            </tr>
            <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <form name="fm3" id="fm3" method="POST">
        <tr>
            <td colspan='10'>
                <!-- <input type='submit' value='Submit' style='float:right;'> -->
            </td>
        </tr>
        <?php
        if ($pagetype == "unknown") {
            # ADD NEW PAYMENT on "Unknown Transfers" page
            ?>
            <tr class="row1">
                <td colspan="9" valign="top"><input type="button" id="unknown_new_payment" value="HIDE ROW"
                                                    onclick="show_hide('unknown_transfer', 'unknown_new_payment')"></td>
            </tr>
            <tr class="row1" id="unknown_transfer" style="display:table-row;">
                <td colspan="6"></td>
                <td colspan="2">
                    <table width="100%">
                        <tr>
                            <td colspan="2" align="center"><b><?= $lang["add_unknown_new_payment"] ?></b></td>
                        </tr>
                        <tr>
                            <td align="right" style="padding-right:5px"><?= $lang["ref_no"] ?></td>
                            <td><input type="text" name="ref_no[0]" value="<?= $rec_amt[0] ? $rec_amt[0] : "" ?>"></td>
                        </tr>
                        <tr>
                            <td align="right" style="padding-right:5px"><?= $lang["rec_acc_no"] ?></td>
                            <td><select name="rec_acc_no[0]" id="rec_acc_no[0]">
                                    <option></option>
                                    <?php
                                    foreach ($bank_acc_list as $bank_acc_obj) {
                                        ?>
                                        <option
                                            value="<?= $bank_acc_obj->get_id() ?>"><?= htmlspecialchars($bank_acc_obj->get_acc_no()) ?>
                                            [<?= htmlspecialchars($bank_acc_obj->get_currency_id()) ?>]
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <td align="right" style="padding-right:5px"><?= $lang["rec_amt"] ?></td>
                            <td><input type="text" name="rec_amt[0]" value="<?= $rec_amt[0] ? $rec_amt[0] : "" ?>"></td>
                        </tr>
                        <tr>
                            <td align="right" style="padding-right:5px"><?= $lang["bank_charges"] ?></td>
                            <td><input type="text" name="bank_charge[0]"
                                       value="<?= $bank_charge[0] ? $bank_charge[0] : "0.00" ?>"></td>
                        </tr>
                        <tr>
                            <td align="right" style="padding-right:5px"><?= $lang["rec_date"] ?></td>
                            <td>
                                <input name="rec_date[0]" id="rec_date[0]"
                                       value="<?= $rec_date[0] ? $rec_date[0] : "" ?>"><img src="/images/cal_icon.gif"
                                                                                            class="pointer"
                                                                                            onclick="showcalendar(event, document.getElementById('rec_date[0]'), false, false, false, '2010-01-01')"
                                                                                            align="absmiddle">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" style="padding-right:5px"><?= $lang["note"] ?></td>
                            <td>
                                <textarea name="note[0]" id="note[0]"
                                          form="fm2"><?= $note[0] ? $note[0] : "" ?></textarea>
                            </td>
                        </tr>
                        <input type="hidden" name="select[0]" id="select[0]" value="0">
                        <input type="hidden" name="type" id="type" value="add_unknown">
                        <input type="hidden" name="posted" value="1">
                        <tr>
                            <td colspan="2"><input type="button" value="<?= $lang['save'] ?>"
                                                   onclick='save("0", "unknown")' style="width:80px; float:right;">
                        </tr>
                    </table>
                </td>
                <td></td>
            </tr>

            <tr>
                <td colspan="10" class="hr">
                    <hr>
                </td>
            </tr>
            <?php
            # END OF ADD NEW PAYMENT on "Unknown Transfers" page
        }
        ?>
    </form>
    <form name="fm2" id="fm2" method="POST">

        <?php
        $i = 0;
        if ($objlist) {
            foreach ($objlist as $obj) {
                $bank_details = $sbt_notes_list = "";
                $pay_count = $net_diff = $net_diff_perc = $total_received = $total_bank_charge = 0;

                // ================== process all client/billing info ==================
                if ($pagetype !== "unknown") {
                    $so_no = $obj->get_so_no();
                    $bill_name = $obj->get_forename() . " " . $obj->get_surname();
                    $del_name = $obj->get_delivery_name();

                    $bill_company_name = $obj->get_bill_company();
                    $del_company_name = $obj->get_delivery_company();
                    list($d1, $d2, $d3) = explode("|", $obj->get_delivery_address());
                    list($b1, $b2, $b3) = explode("|", $obj->get_bill_address());

                    $del_addr = "<i>" . $del_name . "</i><br>";
                    $bill_addr = "<i>" . $bill_name . "</i><br>";
                    $del_addr .= $del_company_name ? "<b>" . $del_company_name . "</b><br>" : "";
                    $bill_addr .= $bill_company_name ? "<b>" . $bill_company_name . "</b><br>" : "";
                    $del_addr .= $d1 . ($d2 != "" ? "<br>" . $d2 : "") . ($d3 != "" ? "<br>" . $d3 : $d3);
                    $bill_addr .= $b1 . ($b2 != "" ? "<br>" . $b2 : "") . ($b3 != "" ? "<br>" . $b3 : $b3);
                    $del_addr .= "<br>" . $obj->get_delivery_city() . " " . $obj->get_delivery_postcode() . "<br>" . $obj->get_delivery_country_id();
                    $bill_addr .= "<br>" . $obj->get_bill_city() . " " . $obj->get_bill_postcode() . "<br>" . $obj->get_bill_country_id();

                    $tel = implode(' ', array($obj->get_tel_1(), $obj->get_tel_2(), $obj->get_tel_3()));
                    $del_tel = implode(' ', array($obj->get_del_tel_1(), $obj->get_del_tel_2(), $obj->get_del_tel_3()));

                    if (trim($del_tel)) {
                        $del_addr .= "<br />Tel: " . $del_tel;
                    }
                    if (trim($tel)) {
                        $bill_addr .= "<br />Tel: " . $tel;
                    }
                }
                // ================== END client/billing info =================

                // ================== process all the GROUP_CONCAT ==================
                if ($obj->get_ext_ref_no())
                    $ext_ref_nos = explode("||", $obj->get_ext_ref_no());
                if ($obj->get_bank_account_id())
                    $received_acc_ids = explode("||", $obj->get_bank_account_id());
                $received_acc_nos = array();
                if ($received_acc_ids) {
                    foreach ($received_acc_ids as $key => $acc_id) {
                        // get actual account numbers to display
                        foreach ($bank_acc_list as $bank_acc_obj) {
                            if ($acc_id == $bank_acc_obj->get_id()) {
                                $received_acc_nos[] = $bank_acc_obj->get_acc_no();
                                break;
                            }
                        }
                    }
                }

                if ($obj->get_received_date_localtime())
                    $received_dates = explode("||", $obj->get_received_date_localtime());
                if ($obj->get_bank_charge())
                    $bank_charges = explode("||", $obj->get_bank_charge());
                if ($obj->get_sbt_create_on())
                    $sbt_create_ons = explode("||", $obj->get_sbt_create_on());
                if ($obj->get_sbt_create_at())
                    $sbt_create_ats = explode("||", $obj->get_sbt_create_at());
                if ($obj->get_sbt_create_by())
                    $sbt_create_bys = explode("||", $obj->get_sbt_create_by());
                if ($obj->get_sbt_modify_on())
                    $sbt_modify_ons = explode("||", $obj->get_sbt_modify_on());
                if ($obj->get_sbt_modify_at())
                    $sbt_modify_ats = explode("||", $obj->get_sbt_modify_at());
                if ($obj->get_sbt_modify_by())
                    $sbt_modify_bys = explode("||", $obj->get_sbt_modify_by());
                if ($obj->get_notes())
                    $sbt_notes = explode("||", $obj->get_notes());
                // ================== END GROUP_CONCAT ========================

                $net_diff_status_key = $obj->get_net_diff_status();
                if (!$net_diff_status_key) {
                    $net_diff_status_key = 0;
                }

                if ($obj->get_received_amt_localcurr()) {
                    $received_amts = explode("||", $obj->get_received_amt_localcurr());
                    foreach ($received_amts as $key => $value) {
                        $date = date('Y-m-d', strtotime($received_dates[$key]));
                        $bank_charge = number_format($bank_charges[$key], 2, '.', '');
                        $bt_timestamp = "Payment record timestamp -&#13;{$lang["create_on"]}:{$sbt_create_ons[$key]}&#13;{$lang["create_at"]}:{$sbt_create_ats[$key]}&#13;{$lang["create_by"]}:{$sbt_create_bys[$key]}&#13;{$lang["modify_on"]}:{$sbt_modify_ons[$key]}&#13;{$lang["modify_at"]}:{$sbt_modify_ats[$key]}&#13;{$lang["modify_by"]}:{$sbt_modify_bys[$key]}";
                        $bank_details .= "<b>BANK/SALES REF.: {$ext_ref_nos[$key]} </b><a href =\"javascript:void(0)\" title='$bt_timestamp'>[*]</a><br>
                                        Received Amt: {$value}<br>
                                        Bank Charge: $bank_charge<br>
                                        Received Acc. No.: {$received_acc_nos[$key]}<br>
                                        Received Date: $date<br>
                                        Notes: {$sbt_notes[$key]}<br>
                                        ---------<br>";

                        $total_received += number_format($value, 2, '.', '');
                        $total_bank_charge += number_format($bank_charge, 2, '.', '');
                        $pay_count++;
                    }

                    $net_diff = number_format(($obj->get_amount() - ($total_received - $total_bank_charge)), 2, '.', '');
                    if ($obj->get_amount()) {
                        $net_diff_perc = number_format(($net_diff / $obj->get_amount() * 100), 2, '.', '');
                    }
                    $bank_details .= "<b>Total no. of payment(s): $pay_count</b>";
                } else {
                    $net_diff_perc = 100.00;
                }
                ?>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                         title='<?= $lang["create_on"] ?>:<?= $obj->get_so_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_so_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_so_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_so_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_so_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_so_modify_by() ?>'>
                    </td>
                    <td><a href="<?= base_url() ?>cs/quick_search/view/<?= $so_no ?>" target="_blank"><?= $so_no ?></a>
                    </td>
                    <td><?= !$unknown_field ? $obj->get_platform_order_id() . "(" . $obj->get_id() . "-" . str_replace("SO", "", $so_no) . ")" : "" ?></td>
                    <td><?= $obj->get_currency_id() ?></td>
                    <td><?= $obj->get_currency_id() ?> <?= $obj->get_amount() ?></td>
                    <td>&nbsp;</td>
                    <td>
                        <?php
                        if ($obj->get_hold_status() == 2) {
                            echo "<font color='red'><b>ON-HOLD</b></font>";

                            if ($hold_reason = $obj->get_reason())
                                echo "<font color='red'><b> - $hold_reason</b></font><br>";
                        } else
                            echo "";

                        ?>
                        <b><?= $net_diff_status[$net_diff_status_key] ?></b>
                        <br>Total Paid Amt: <?= $obj->get_currency_id() ?> <?= $total_received ?>
                        <br>Total Bank Charge: <?= $obj->get_currency_id() ?> <?= $total_bank_charge ?>
                        <br><?= !$unknown_field ? "Unpaid: {$obj->get_currency_id()} $net_diff ($net_diff_perc%)" : "" ?>
                    </td>
                    <td></td>
                    <td colspan="2"></td>
                </tr>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"></td>
                    <td><?= !$unknown_field ? $del_opt_list[$obj->get_delivery_type_id()]->get_display_name() : "" ?></td>
                    <td><?= $obj->get_email() ?></td>
                    <td></td>
                    <td class="bfield<?= $i % 2 ?>"><?= $lang["billing_address"] ?></td>
                    <td class="bfield<?= $i % 2 ?>"><?= $lang["bank_details"] ?></td>
                    <td align="left" valign="top" colspan='2' rowspan="2">
                        <table width="100%">
                            <?php
                            if (!($pagetype == "unknown" && $ext_ref_nos)) {
                                # ADD NEW PAYMENT table
                                # will appear on all pages, except for Unknown Transfers page with existing bank records

                                if ($obj->get_hold_status() !== '2' && $obj->get_hold_status() !== '10') {
                                    #sbf 3676 don't allow add new payment if order is on hold or permanent hold
                                    ?>
                                    <tr>
                                        <td colspan="2" align="center"><b><?= $lang["add_new_payment"] ?></b></td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="padding-right:5px"><?= $lang["ref_no"] ?></td>
                                        <td><input type="text" name="ref_no[<?= $so_no ?>]"
                                                   value="<?= $ref_no[$so_no] ? $ref_no[$so_no] : "" ?>"></td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="padding-right:5px"><?= $lang["rec_acc_no"] ?></td>
                                        <td id="acc_no">
                                            <select name="rec_acc_no[<?= $so_no ?>]" id="rec_acc_no[<?= $so_no ?>]">
                                                <option></option>
                                                <?php
                                                foreach ($bank_acc_list as $bank_acc_obj) {
                                                    ?>
                                                    <option
                                                        value="<?= $bank_acc_obj->get_id() ?>"><?= htmlspecialchars($bank_acc_obj->get_acc_no()) ?>
                                                        [<?= htmlspecialchars($bank_acc_obj->get_currency_id()) ?>]
                                                    </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="padding-right:5px"><?= $lang["rec_amt"] ?>
                                            (<?= $obj->get_currency_id() ?>)
                                        </td>
                                        <td><input type="text" name="rec_amt[<?= $so_no ?>]"
                                                   value="<?= $rec_amt[$so_no] ? $rec_amt[$so_no] : "" ?>"></td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="padding-right:5px"><?= $lang["bank_charges"] ?>
                                            (<?= $obj->get_currency_id() ?>)
                                        </td>
                                        <td><input type="text" name="bank_charge[<?= $so_no ?>]"
                                                   value="<?= $bank_charge[$so_no] ? $bank_charge[$so_no] : "0.00" ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="padding-right:5px"><?= $lang["rec_date"] ?></td>
                                        <td>
                                            <input name="rec_date[<?= $so_no ?>]" id="rec_date[<?= $so_no ?>]"
                                                   value="<?= $rec_date[$so_no] ? $rec_date[$so_no] : "" ?>"><img
                                                src="/images/cal_icon.gif" class="pointer"
                                                onclick="showcalendar(event, document.getElementById('rec_date[<?= $so_no ?>]'), false, false, false, '2010-01-01')"
                                                align="absmiddle">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="padding-right:5px"><?= $lang["note"] ?></td>
                                        <td>
                                            <textarea name="note[<?= $so_no ?>]" id="note[<?= $so_no ?>]"
                                                      form="fm2"><?= $note[$so_no] ? $note[$so_no] : "" ?></textarea>
                                        </td>
                                    </tr>
                                    <input type="hidden" name="select[<?= $so_no ?>]" id="select[<?= $so_no ?>]"
                                           value="0">
                                    <input type="hidden" name="order_amt[<?= $so_no ?>]"
                                           value="<?= $obj->get_amount() ?>">
                                    <input type="hidden" name="prev_received[<?= $so_no ?>]"
                                           value="<?= $total_received ?>">
                                    <input type="hidden" name="prev_bank_charge[<?= $so_no ?>]"
                                           value="<?= $total_bank_charge ?>">
                                    <input type="hidden" name="type" id="type" value="add">
                                    <tr>
                                        <td colspan="2"><input type="button" value="<?= $lang['save'] ?>"
                                                               onclick='save("<?= $so_no ?>")'
                                                               style="width:80px; float:right;">
                                    </tr>
                                <?php
                                }
                            } else {
                                # ADD SO_NO for previously unknown transfers
                                ?>
                                <tr>
                                    <td colspan="2" align="center"><b><?= $lang["add_so_no"] ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"><?= $lang["so_no"] ?><input type="text"
                                                                                               name="so_no[<?= htmlspecialchars($ext_ref_nos[0]) ?>]">
                                    </td>
                                    <input type="hidden" name="select[<?= htmlspecialchars($ext_ref_nos[0]) ?>]"
                                           id="select[<?= htmlspecialchars($ext_ref_nos[0]) ?>]" value="0">
                                    <input type="hidden" name="type" id="type" value="update_so">
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="button" value="<?= $lang['save'] ?>"
                                                           onclick='save("<?= htmlspecialchars($ext_ref_nos[0]) ?>");'
                                                           style="width:80px; float:right;">
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </td>
                    <td colspan="2" rowspan="2"></td>
                </tr>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"></td>
                    <td colspan="3" valign="top">
                    </td>
                    <td class="bvalue<?= $i % 2 ?>" valign="top"><?= $bill_addr ?></td>
                    <td class="bvalue<?= $i % 2 ?>" valign="top"><?= $bank_details ? $bank_details : "" ?></td>

                </tr>
                <tr>
                    <td colspan="10" class="hr">
                        <hr>
                    </td>
                </tr>
                <?php
                $i++;
            }
        }
        ?>
        <input type="hidden" name="posted" value="1">

    </form>
    </table>

    <script>
        InitPMGW(document.fm.payment_gateway_id);
        document.fm.payment_gateway_id.value = '<?=$this->input->get("payment_gateway_id")?>';
    </script>
    <?php
    if ($total)
        echo $this->pagination_service->create_links_with_style();
    else echo "No records found"; ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>