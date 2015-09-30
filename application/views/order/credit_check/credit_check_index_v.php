<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/PaymentGateway/jsPmgwlist"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
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

    </script>

</head>
<body>
<div id="main">
    <? $ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]); ?>
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
                <input type="button" value="<?= $lang["list_button"] ?>"
                       onclick="Redirect('<?= site_url('order/credit_check/') ?>')"> &nbsp;
                <input type="button" class="button" value="<?= $lang["list_pending"] ?>"
                       onclick="Redirect('<?= site_url('order/credit_check/index/pending') ?>')"> &nbsp;
                <input type="button" class="button" value="<?= $lang["list_challenged"] ?>"
                       onclick="Redirect('<?= site_url('order/credit_check/index/challenged') ?>')"> &nbsp;
                <input type="button" class="button" value="<?= $lang["list_comcenter"] ?>"
                       onclick="Redirect('<?= site_url('order/credit_check/index/comcenter') ?>')">
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
            <col width="150">
            <col>
            <col>
            <col width="220">
            <col width="220">
            <col width="180">
            <col width="140">
            <col width="27">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'so_no', '<?= $xsort["so_no"] ?>')"><?= $lang["order_id"] ?> <?= $sortimg["so_no"] ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'platform_order_id', '<?= $xsort["platform_order_id"] ?>')"><?= $lang["platform_order_id"] ?> <?= $sortimg["platform_order_id"] ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'payment_gateway_id', '<?= $xsort["payment_gateway_id"] ?>')"><?= $lang["payment_gateway"] ?> <?= $sortimg["payment_gateway_id"] ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'currency_id', '<?= $xsort["currency_id"] ?>')"><?= $lang["currency_id"] ?> <?= $sortimg["currency_id"] ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'txn_id', '<?= $xsort["txn_id"] ?>')"><?= $lang["gateway_txn_id"] ?> <?= $sortimg["txn_id"] ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'amount', '<?= $xsort["amount"] ?>')"><?= $lang["order_amount"] ?> <?= $sortimg["amount"] ?></a>
                </td>
                <td colspan="2" style="white-space:nowrap"><a href="#"
                                                              onClick="SortCol(document.fm, 'risk_var1', '<?= $xsort["risk_var1"] ?>')"><?= $lang["cybs"] ?> <?= $sortimg["risk_var1"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="so_no" class="input" value="<?= htmlspecialchars($this->input->get("so_no")) ?>"></td>
                <td><input name="platform_order_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("platform_order_id")) ?>"></td>
                <td>
                    <select name="payment_gateway_id" class="input">
                        <option value="">
                    </select>
                </td>
                <td>
                    <input name="currency_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("currency_id")) ?>">
                </td>
                <td><input name="txn_id" class="input" value="<?= htmlspecialchars($this->input->get("txn_id")) ?>">
                </td>
                <td><input name="amount" class="input" value="<?= htmlspecialchars($this->input->get("amount")) ?>">
                </td>
                <td colspan="2">
                    <!-- <input name="t3m_result" class="input" value="<?= htmlspecialchars($this->input->get("t3m_result")) ?>"> -->
                    <?php
                    $cybs = array();
                    $cybs[$this->input->get('cybs')] = 'selected';
                    ?>
                    <select name="cybs" class="input">
                        <option value=''></option>
                        <option value='accept'  <?= $cybs['accept'] ?>>accept</option>
                        <option value='review'  <?= $cybs['review'] ?>>review</option>
                        <option value='reject'  <?= $cybs['reject'] ?>>reject</option>
                    </select>
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <!-- #2309 -->
    <form action="<?= base_url() ?>order/credit_check/operation_integrator" method="POST">
        <tr>
            <td colspan='10'>
                <input type='submit' value='Submit' style='float:right;'>
            </td>
        </tr>
        <?php
        $t3m_status = array("0" => $lang["release"], "1" => $lang["hold"]);
        $extend_info = array("paypal", "global_collect", "moneybookers");
        $i = 0;
        if ($objlist) {
            foreach ($objlist as $obj) {
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
                $t3m_arr = explode("|", $obj->get_t3m_result());
                ?>

                <tr class="row<?= $i % 2 ?>">
                    <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                         title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                    </td>
                    <td><a href="<?= base_url() ?>cs/quick_search/view/<?= $obj->get_so_no() ?>"
                           target="_blank"><?= $obj->get_so_no() ?></a></td>
                    <td><?= $obj->get_platform_order_id() . "(" . $obj->get_id() . "-" . str_replace("SO", "", $obj->get_so_no()) . ")" ?></td>
                    <td style="background:#99CCCC;">
                        <script>w(pmgwlist['<?=$obj->get_payment_gateway_id()?>'])</script>
                    </td>
                    <td>
                        <?= $obj->get_currency_id() ?>
                    </td>
                    <td><?= $obj->get_txn_id() ?></td>
                    <td><?= $obj->get_currency_id() ?> <?= $obj->get_amount() ?></td>

                    <?php
                    if ($risk2[$obj->get_so_no()]) {
                        $risk_style = "";
                        if (($risk2[$obj->get_so_no()][0]['style'] == "bad") || ($risk3[$obj->get_so_no()][0]['style'] == "bad")) {
                            $risk_style = "class='risk_bad'";
                        }
                        ?>
                        <td colspan='2' <?= $risk_style ?>>RESULT:<?= $risk1[$obj->get_so_no()][0]['value'] ?>,
                            AVS:<?= $risk2[$obj->get_so_no()][0]['value'] ?>,
                            CVN: <?= $risk3[$obj->get_so_no()][0]['value'] ?></td>
                    <?php
                    } else {
                        ?>
                        <td colspan="2"><?= $obj->get_t3m_result() != "" ? $t3m_status[$t3m_arr[0]] . ":" . $t3m_arr[1] : "" ?></td>
                    <?php
                    }
                    ?>
                    <td></td>
                </tr>
                <?php
                if (in_array(($pmgw_id = $obj->get_payment_gateway_id()), $extend_info)) {
                    ?>
                    <tr class="row<?= $i % 2 ?>">
                        <td height="20"></td>
                        <td colspan="9" style="background:#99CCCC;">
                            <?php
                            switch ($pmgw_id) {
                                case "paypal":
                                    ?>
                                    PROTECTIONELIGIBILITY: <font
                                    color="#0000FF"><?= $obj->get_risk_ref1() ?></font> &nbsp; / &nbsp; PROTECTIONELIGIBILITYTYPE:
                                    <font
                                        color="#0000FF"><?= $obj->get_risk_ref2() ?></font> &nbsp; / &nbsp; ADDRESSSTATUS:
                                    <font
                                        color="#0000FF"><?= $obj->get_risk_ref3() ?></font> &nbsp; / &nbsp; PAYERSTATUS:
                                    <font color="#0000FF"><?= $obj->get_risk_ref4() ?></font>
                                    <?php
                                    break;
                                case "global_collect":
                                    if ($obj->get_card_type()) { ?> PAYMENT TYPE: <font
                                        color="#0000FF"><?= $pmgw_card_list[$obj->get_card_type()] ?></font>
                                        <br> <?php } ?>
                                    AVSRESULT: <font
                                    color="#0000FF"><?= $obj->get_risk_ref1() ?></font> - <?= $obj->get_risk_ref_desc() ?><?= $obj->get_pending_action() == "P" ? " <font color='#FFFF00'>(PROCESS_CHALLENGED)</font>" : "" ?>
                                    <br>
                                    FRAUDRESULT: <font color="#0000FF"><?= $obj->get_risk_ref2() ?></font>
                                    <?php
                                    break;
                                case "moneybookers":
                                    if ($obj->get_card_type()) { ?> PAYMENT TYPE: <font
                                        color="#0000FF"><?= $pmgw_card_list[$obj->get_card_type()] ?></font>
                                        <br> <?php } ?>
                                    VERIFICATION LEVEL: <font color="#0000FF"><?= $obj->get_risk_ref1() ?></font>
                                    <?php
                                    break;

                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"></td>
                    <td><?= $del_opt_list[$obj->get_delivery_type_id()]->get_display_name() ?></td>
                    <td><?= $obj->get_email() ?></td>
                    <td><?= $lang["password"] ?>: <a
                            href="<?= base_url() ?>order/credit_check/chk_pw/?pw=<?= urlencode($obj->get_password()) ?>"
                            rel="lyteframe[check_password]" rev="width: 1024px; height: 500px; scrolling: auto;"
                            title="<?= $lang["password"] ?> - <?= $rspw = $this->encrypt->decode($obj->get_password()) ?>"><?= $rspw ?></a>
                        (<?= $obj->get_pw_count() ?>)
                    </td>
                    <td class="bfield<?= $i % 2 ?>"><?= $lang["billing_address"] ?></td>
                    <td class="bfield<?= $i % 2 ?>"><?= $lang["delivery_address"] ?></td>
                    <td><?= $lang["order_note"] ?></td>

                    <?php
                    if ($pagetype == "comcenter") {
                        ?>
                        <td align="center" rowspan="2">
                            <input type="button" value="<?= $lang["high_risk_cc"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/high_risk_cc/<?= $obj->get_so_no() ?>')"><br>
                            <input type="button" value="<?= $lang["low_risk_cc"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/low_risk_cc/<?= $obj->get_so_no() ?>')"><br>
                            <input type="button" value="<?= $lang["further_check"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/further_check/<?= $obj->get_so_no() ?>')"><br>
                            <input type="button" value="<?= $lang["apr_fulfill"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/apr_fulfill/<?= $obj->get_so_no() ?>')"><br>
                            <input type="button" value="<?= $lang["failcc_refund"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/failcc_refund/<?= $obj->get_so_no() ?>')"><br>
                            <!-- SBF #4646 add save order button -->
                            <input type="button" id="reason" name="reason" value="Save Order"
                                   onClick="Redirect('<?= base_url() ?>order/on_hold_admin/hold/<?= $obj->get_so_no() ?>?cf=confirmation_required')"><br>
                            <input type="button" value="<?= $lang["refuse_cc_refund"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/refuse_cc_refund/<?= $obj->get_so_no() ?>')">
                            <input type="button" value="<?= $lang["confirmed_fraud"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/confirmed_fraud/<?= $obj->get_so_no() ?>')">
                        </td>
                    <?php
                    } else {
                        ?>
                        <td align="center" colspan='2'>
                            <?php
                            if ($pagetype == "") {
                                //#2309
                                ?>
                                <input type="button" value="<?= $lang["delete"] ?>"
                                       onClick="Redirect('<?= base_url() ?>order/credit_check/delete/<?= $obj->get_so_no() ?>')"
                                       style="width:70px; float:left;"> <input type="radio"
                                                                               name="operation[<?= $obj->get_so_no() ?>]"
                                                                               ondblclick="cancel_check(this)"
                                                                               value="delete" style="float:right;">
                                <br/><br/>
                                <input type="button" value="<?= $lang["approve"] ?>"
                                       onClick="Redirect('<?= base_url() ?>order/credit_check/approve/<?= $obj->get_so_no() ?>')"
                                       style="width:70px; float:left;"> <input type="radio"
                                                                               name="operation[<?= $obj->get_so_no() ?>]"
                                                                               ondblclick="cancel_check(this)"
                                                                               value="approve" style="float:right;">
                            <?php
                            } elseif ($pagetype == "challenged") {
                                ?>
                                <input type="button" value="<?= $lang["approve"] ?>"
                                       onClick="Redirect('<?= base_url() ?>order/credit_check/approve_challenged/<?= $obj->get_so_no() ?>')"> &nbsp;
                                <input type="button" value="<?= $lang["reject"] ?>"
                                       onClick="Redirect('<?= base_url() ?>order/credit_check/reject_challenged/<?= $obj->get_so_no() ?>')">
                            <?php
                            }
                            ?>
                        </td>
                    <?php
                    }
                    ?>
                    <td></td>
                </tr>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"></td>
                    <td colspan="3">
                        <?php
                        if ($obj->get_items()) {
                            $items = explode("||", $obj->get_items());
                            foreach ($items as $item) {
                                list($sku, $name, $qty, $u_p, $amount) = @explode("::", $item);
                                ?>
                                <p class="normal_p">[<?= $sku ?>] <?= $name ?> x<?= $qty ?> @<?= $u_p ?>
                                    = <?= $amount ?></p>
                            <?php
                            }
                        }
                        ?>
                    </td>
                    <td class="bvalue<?= $i % 2 ?>"><?= $bill_addr ?></td>
                    <td class="bvalue<?= $i % 2 ?>"><?= $del_addr ?></td>
                    <td><?= $order_note[$obj->get_so_no()] ?></td>

                    <?php
                    if ($pagetype != "comcenter") {
                        ?>
                        <td align="center" colspan='2'>
                            <?php
                            if ($pagetype == "") {#2309
                                ?>
                                <!-- <form action="<?= base_url() ?>order/credit_check/hold/<?= $obj->get_so_no() ?>" method="post"> -->
                                <select name="reason[<?= $obj->get_so_no() ?>]" class="input"
                                        style="width:110px; float:left;">
                                    <option value="change_of_address"><?= $lang["change_of_address"] ?></option>
                                    <option value="confirmation_required"><?= $lang["confirmation_required"] ?></option>
                                    <option value="customer_request"><?= $lang["customer_request"] ?></option>
                                    <option value="confirmed_fraud"><?= $lang["confirmed_fraud"] ?>
                                    <option value="csvv"><?= $lang["csvv"] ?>
                                    <option value="cscc"><?= $lang["cscc"] ?>
                                </select>
                                <input type="radio" name="operation[<?= $obj->get_so_no() ?>]"
                                       ondblclick="cancel_check(this)" value="hold" style="float:right;">
                                <br>
                                <!-- <input type="submit" value="<?= $lang["on_hold"] ?>"> -->
                                <!-- </form> -->
                            <?php
                            }
                            ?>
                        </td>
                    <?php
                    }
                    ?>
                    <td></td>
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
        <tr>
            <td colspan='10'>
                <input type='submit' value='Submit' style='float:right;'>
            </td>
        </tr>
    </form>
    </table>

    <script>
        InitPMGW(document.fm.payment_gateway_id);
        document.fm.payment_gateway_id.value = '<?=$this->input->get("payment_gateway_id")?>';
    </script>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>