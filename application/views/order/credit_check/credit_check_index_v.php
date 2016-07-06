<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap.min.css" type="text/css" media="all" />
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
    <?php  $ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]); ?>
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
            <td align="right" style="background:#286512">
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
                                                              onClick="SortCol(document.fm, 'risk_var_1', '<?= $xsort["risk_var_1"] ?>')"><?= $lang["cybs"] ?> <?= $sortimg["risk_var_1"] ?></a>
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
                $bill_name = $obj->getForename() . " " . $obj->getSurname();
                $del_name = $obj->getDeliveryName();

                $bill_company_name = $obj->getBillCompany();
                $del_company_name = $obj->getDeliveryCompany();
                list($d1, $d2, $d3) = explode("|", $obj->getDeliveryAddress());
                list($b1, $b2, $b3) = explode("|", $obj->getBillAddress());

                $del_addr = "<i>" . $del_name . "</i><br>";
                $bill_addr = "<i>" . $bill_name . "</i><br>";
                $del_addr .= $del_company_name ? "<b>" . $del_company_name . "</b><br>" : "";
                $bill_addr .= $bill_company_name ? "<b>" . $bill_company_name . "</b><br>" : "";
                $del_addr .= $d1 . ($d2 != "" ? "<br>" . $d2 : "") . ($d3 != "" ? "<br>" . $d3 : $d3);
                $bill_addr .= $b1 . ($b2 != "" ? "<br>" . $b2 : "") . ($b3 != "" ? "<br>" . $b3 : $b3);
                $del_addr .= "<br>" . $obj->getDeliveryCity() . " " . $obj->getDeliveryPostcode() . "<br>" . $obj->getDeliveryCountryId();
                $bill_addr .= "<br>" . $obj->getBillCity() . " " . $obj->getBillPostcode() . "<br>" . $obj->getBillCountryId();

                $tel = implode(' ', array($obj->getTel1(), $obj->getTel2(), $obj->getTel3()));
                $del_tel = implode(' ', array($obj->getDelTel1(), $obj->getDelTel2(), $obj->getDelTel3()));

                if (trim($del_tel)) {
                    $del_addr .= "<br />Tel: " . $del_tel;
                }
                if (trim($tel)) {
                    $bill_addr .= "<br />Tel: " . $tel;
                }
                $t3m_arr = explode("|", $obj->getT3mResult());
                ?>

                <tr class="row<?= $i % 2 ?>">
                    <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                         title='<?= $lang["create_on"] ?>:<?= $obj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->getModifyBy() ?>'>
                    </td>
                    <td><a href="<?= base_url() ?>cs/quick_search/view/<?= $obj->getSoNo() ?>"
                           target="_blank"><?= $obj->getSoNo() ?></a></td>
                    <td><?= $obj->getPlatformOrderId() . "(" . $obj->getId() . "-" . str_replace("SO", "", $obj->getSoNo()) . ")" ?></td>
                    <td style="background:#99CCCC;">
                        <script>w(pmgwlist['<?=$obj->getPaymentGatewayId()?>'])</script>
                    </td>
                    <td>
                        <?= $obj->getCurrencyId() ?>
                    </td>
                    <td><?= $obj->getTxnId() ?></td>
                    <td><?= $obj->getCurrencyId() ?> <?= $obj->getAmount() ?></td>

                    <?php
                    if ($risk2[$obj->getSoNo()]) {
                        $risk_style = "";
                        if (($risk2[$obj->getSoNo()][0]['style'] == "bad") || ($risk3[$obj->getSoNo()][0]['style'] == "bad")) {
                            $risk_style = "class='risk_bad'";
                        }
                        ?>
                        <td colspan='2' <?= $risk_style ?>>RESULT:<?= $risk1[$obj->getSoNo()][0]['value'] ?>,
                            AVS:<?= $risk2[$obj->getSoNo()][0]['value'] ?>,
                            CVN: <?= $risk3[$obj->getSoNo()][0]['value'] ?></td>
                    <?php
                    } else {
                        ?>
                        <td colspan="2"><?= $obj->getT3mResult() != "" ? $t3m_status[$t3m_arr[0]] . ":" . $t3m_arr[1] : "" ?></td>
                    <?php
                    }
                    ?>
                    <td></td>
                </tr>
                <?php
                if (in_array(($pmgw_id = $obj->getPaymentGatewayId()), $extend_info)) {
                    ?>
                    <tr class="row<?= $i % 2 ?>">
                        <td height="20"></td>
                        <td colspan="9" style="background:#99CCCC;">
                            <?php
                            switch ($pmgw_id) {
                                case "paypal":
                                    ?>
                                    PROTECTIONELIGIBILITY: <font
                                    color="#0000FF"><?= $obj->getRiskRef1() ?></font> &nbsp; / &nbsp; PROTECTIONELIGIBILITYTYPE:
                                    <font
                                        color="#0000FF"><?= $obj->getRiskRef2() ?></font> &nbsp; / &nbsp; ADDRESSSTATUS:
                                    <font
                                        color="#0000FF"><?= $obj->getRiskRef3() ?></font> &nbsp; / &nbsp; PAYERSTATUS:
                                    <font color="#0000FF"><?= $obj->getRiskRef4() ?></font>
                                    <?php
                                    break;
                                case "global_collect":
                                    if ($obj->getCardType()) { ?> PAYMENT TYPE: <font
                                        color="#0000FF"><?= $pmgw_card_list[$obj->getCardType()] ?></font>
                                        <br> <?php } ?>
                                    AVSRESULT: <font
                                    color="#0000FF"><?= $obj->getRiskRef1() ?></font> - <?= $obj->getRiskRefDesc() ?><?= $obj->getPendingAction() == "P" ? " <font color='#FFFF00'>(PROCESS_CHALLENGED)</font>" : "" ?>
                                    <br>
                                    FRAUDRESULT: <font color="#0000FF"><?= $obj->getRiskRef2() ?></font>
                                    <?php
                                    break;
                                case "moneybookers":
                                    if ($obj->getCardType()) { ?> PAYMENT TYPE: <font
                                        color="#0000FF"><?= $pmgw_card_list[$obj->getCardType()] ?></font>
                                        <br> <?php } ?>
                                    VERIFICATION LEVEL: <font color="#0000FF"><?= $obj->getRiskRef1() ?></font>
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
                    <td><?= $del_opt_list[$obj->getDeliveryTypeId()]->getDisplayName() ?></td>
                    <td><?= $obj->getemail() ?></td>
                    <td><?= $lang["password"]; ?>: <a
                            href="<?= base_url() ?>order/credit_check/chk_pw/?pw=<?= urlencode($obj->getPassword()) ?>"
                            rel="lyteframe[check_password]" rev="width: 1024px; height: 500px; scrolling: auto;"
                            title="<?= $lang["password"] ?> - <?= $rspw = $this->encryption->decrypt($obj->getPassword())?>"><?= $rspw ?></a>
                        (<?= $obj->getPwCount() ?>)
                    </td>
                    <td class="bfield<?= $i % 2 ?>"><?= $lang["billing_address"] ?></td>
                    <td class="bfield<?= $i % 2 ?>"><?= $lang["delivery_address"] ?></td>
                    <td><?= $lang["order_note"] ?></td>

                    <?php
                    if ($pagetype == "comcenter") {
                        ?>
                        <td align="center" rowspan="2">
                            <input type="button" value="<?= $lang["high_risk_cc"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/high_risk_cc/<?= $obj->getSoNo() ?>')"><br>
                            <input type="button" value="<?= $lang["low_risk_cc"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/low_risk_cc/<?= $obj->getSoNo() ?>')"><br>
                            <input type="button" value="<?= $lang["further_check"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/further_check/<?= $obj->getSoNo() ?>')"><br>
                            <input type="button" value="<?= $lang["apr_fulfill"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/apr_fulfill/<?= $obj->getSoNo() ?>')"><br>
                            <input type="button" value="<?= $lang["failcc_refund"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/failcc_refund/<?= $obj->getSoNo() ?>')"><br>
                            <input type="button" value="<?= $lang["refuse_cc_refund"] ?>"
                                   onClick="Redirect('<?= base_url() ?>order/credit_check/refuse_cc_refund/<?= $obj->getSoNo() ?>')">
                            <!-- SBF #4646 add save order button -->

                            <?php
                            if ($reason_list) :
                                foreach ($reason_list as $robj) :
                            ?>
                                        <?php
                                            if ($robj->getReasonType() == "confirmation_required") :
                                                $is_disabled = ($save_order[$obj->getSoNo()]) ? " disabled" : '';
                                        ?>
                                            <input type="button"<?= $is_disabled ?> id="reason" name="reason" value="<?= $lang["hrcategory"][$robj->getReasonCat()] . " - Save Order" ?> "
                                                onClick="Redirect('<?= base_url() ?>order/on_hold_admin/hold/<?= $obj->getSoNo() ?>?cf=<?= $robj->getId() ?>')"><br>
                                        <?php
                                            endif;
                                        ?>

                                        <?php
                                            if ($robj->getReasonType() == "oc_fraud") :
                                        ?>
                                            <input type="button" value="<?= $lang["hrcategory"][$robj->getReasonCat()] . " - ". $lang["oc_fraud"] ?>"
                                                onClick="Redirect('<?= base_url() ?>order/credit_check/oc_fraud/<?= $obj->getSoNo() ?>/<?= $robj->getId() ?>')">
                                        <?php
                                            endif;
                                        ?>
                        <?php
                                endforeach;
                            endif;
                        ?>

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
                                       onClick="Redirect('<?= base_url() ?>order/credit_check/delete/<?= $obj->getSoNo() ?>')"
                                       style="width:70px; float:left;"> <input type="radio"
                                                                               name="operation[<?= $obj->getSoNo() ?>]"
                                                                               ondblclick="cancel_check(this)"
                                                                               value="delete" style="float:right;">
                                <br/><br/>
                                <input type="button" value="<?= $lang["approve"] ?>"
                                       onClick="Redirect('<?= base_url() ?>order/credit_check/approve/<?= $obj->getSoNo() ?>')"
                                       style="width:70px; float:left;"> <input type="radio"
                                                                               name="operation[<?= $obj->getSoNo() ?>]"
                                                                               ondblclick="cancel_check(this)"
                                                                               value="approve" style="float:right;">
                            <?php
                            } elseif ($pagetype == "challenged") {
                                ?>
                                <input type="button" value="<?= $lang["approve"] ?>"
                                       onClick="Redirect('<?= base_url() ?>order/credit_check/approve_challenged/<?= $obj->getSoNo() ?>')"> &nbsp;
                                <input type="button" value="<?= $lang["reject"] ?>"
                                       onClick="Redirect('<?= base_url() ?>order/credit_check/reject_challenged/<?= $obj->getSoNo() ?>')">
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
                        if ($obj->getItems()) {
                            $items = explode("||", $obj->getItems());
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
                    <td><?= $order_note[$obj->getSoNo()] ?></td>

                    <?php
                    if ($pagetype != "comcenter") {
                        ?>
                        <td align="center" colspan='2'>
                            <?php
                            if ($pagetype == "") {#2309
                                ?>
                                <!-- <form action="<?= base_url() ?>order/credit_check/hold/<?= $obj->getSoNo() ?>" method="post"> -->
                                <select  class="input" style="width:110px; float:left;" name="reason[<?= $obj->getSoNo() ?>]">
                                    <?php
                                        if ($reason_list) :
                                            foreach ($reason_list as $robj) :
                                    ?>
                                        <option value="<?= $robj->getId() ?>"><?= $lang["hrcategory"][$robj->getReasonCat()] . " - " . $robj->getDescription() ?></option>
                                    <?php
                                            endforeach;
                                        endif;
                                    ?>
                                </select>
                                <input type="radio" name="operation[<?= $obj->getSoNo() ?>]"
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
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>