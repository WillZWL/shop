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
</head>
<body>
<div id="main">
    <?php $ar_status = ["0" => $lang["inactive"], "1" => $lang["active"]]; ?>
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["header" . $type] ?></td>
            <td width="400" align="right" style="background:#286512">
                <input type="button" value="<?= $lang["oc_page"] ?>" onClick="Redirect('<?= base_url() . "order/on_hold_admin/oc_index/" ?>')">&nbsp;
                <input type="button" value="<?= $lang["cc_page"] ?>" onClick="Redirect('<?= base_url() . "order/on_hold_admin/oc_index/cc/" ?>')">&nbsp;
                <input type="button" value="<?= $lang["vv_page"] ?>" onClick="Redirect('<?= base_url() . "order/on_hold_admin/oc_index/vv/" ?>')">
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
            <col width="250">
            <col width="250">
            <col width="140">
            <col width="27">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 'so_no', '<?= $xsort["so_no"] ?>')"><?= $lang["order_id"] ?> <?= $sortimg["so_no"] ?></a>
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 'platform_order_id', '<?= $xsort["platform_order_id"] ?>')"><?= $lang["platform_order_id"] ?> <?= $sortimg["platform_order_id"] ?></a>
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 'payment_gateway_id', '<?= $xsort["payment_gateway_id"] ?>')"><?= $lang["payment_gateway"] ?> <?= $sortimg["payment_gateway_id"] ?></a>
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 'txn_id', '<?= $xsort["txn_id"] ?>')"><?= $lang["gateway_txn_id"] ?> <?= $sortimg["txn_id"] ?></a>
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 'amount', '<?= $xsort["amount"] ?>')"><?= $lang["order_amount"] ?> <?= $sortimg["amount"] ?></a>
                </td>
                <td style="white-space:nowrap">
                    <a href="#" onClick="SortCol(document.fm, 't3m_result', '<?= $xsort["t3m_result"] ?>')"><?= $lang["t3m"] ?> <?= $sortimg["t3m_result"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="so_no" class="input" value="<?= htmlspecialchars($this->input->get("so_no")) ?>"></td>
                <td><input name="platform_order_id" class="input" value="<?= htmlspecialchars($this->input->get("platform_order_id")) ?>"></td>
                <td>
                    <select name="payment_gateway_id" class="input">
                        <option value="">
                    </select>
                </td>
                <td><input name="txn_id" class="input" value="<?= htmlspecialchars($this->input->get("txn_id")) ?>">
                </td>
                <td><input name="amount" class="input" value="<?= htmlspecialchars($this->input->get("amount")) ?>">
                </td>
                <td><input name="t3m_result" class="input" value="<?= htmlspecialchars($this->input->get("t3m_result")) ?>"></td>
                <td align="center">
                    <input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">
                </td>
            </tr>
            <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?php
    $i = 0;
    if ($objlist) :
        foreach ($objlist as $obj) :
            list($d1, $d2, $d3) = explode("||", $obj->getDeliveryAddress());
            list($b1, $b2, $b3) = explode("||", $obj->getBillAddress());

            $del_addr = $d1 . ($d2 != "" ? "<br>" . $d2 : "") . ($d3 != "" ? "<br>" . $d3 : $d3);
            $bill_addr = $b1 . ($b2 != "" ? "<br>" . $b2 : "") . ($b3 != "" ? "<br>" . $b3 : $b3);
            $del_addr .= "<br>" . $obj->getDeliveryCity() . " " . $obj->getDeliveryPostcode() . "<br>" . $obj->getDeliveryCountryId();
            $bill_addr .= "<br>" . $obj->getBillCity() . " " . $obj->getBillPostcode() . "<br>" . $obj->getBillCountryId();
            ?>

            <tr class="row<?= $i % 2 ?>">
                <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                     title='<?= $lang["create_on"] ?>:<?= $obj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->getModifyBy() ?>'>
                </td>
                <td><a href="<?= base_url() ?>cs/quick_search/view/<?= $obj->getSoNo() ?>/lyte"
                       rel="lyteframe[<?= $obj->getSoNo() ?>]" rev="width: 1024px; height: 500px; scrolling: auto;"
                       title="<?= $lang["order_detail"] ?>"><?= $obj->getSoNo() ?></a></td>
                <td><?= $obj->getPlatformOrderId() ?></td>
                <td>
                    <script>w(pmgwlist['<?=$obj->getPaymentGatewayId()?>'])</script>
                </td>
                <td><?= $obj->getTxnId() ?></td>
                <td><?= $obj->getCurrencyId() ?> <?= $obj->getAmount() ?></td>
                <?php
                if ($risk2[$obj->getSoNo()]) :
                    $risk_style = "";
                    if (($risk2[$obj->getSoNo()][0]['style'] == "bad") || ($risk3[$obj->getSoNo()][0]['style'] == "bad")) :
                        $risk_style = "class='risk_bad'";
                    endif;
                    ?>
                    <td <?= $risk_style ?>>AVS:<?= $risk2[$obj->getSoNo()][0]['value'] ?>,
                        CVN: <?= $risk3[$obj->getSoNo()][0]['value'] ?></td>
                <?php
                else :
                    ?>
                    <td><!--?= $obj->get_t3m_result() ?--></td>
                <?php
                endif;
                ?>
                <td></td>
            </tr>
            <tr class="row<?= $i % 2 ?>">
                <td height="20"></td>
                <td><?= $obj->getForename() ?> <?= $obj->getSurname() ?></td>
                <td><?= $obj->getEmail() ?></td>
                <td><?= $lang["password"] ?>: <a
                        href="<?= base_url() ?>order/on_hold_admin/chk_pw/?pw=<?= urlencode($obj->getPassword()) ?>"
                        rel="lyteframe[check_password]" rev="width: 1024px; height: 500px; scrolling: auto;"
                        title="<?= $lang["password"] ?> - <?= $rspw = $this->encryption->decrypt($obj->getPassword()) ?>"><?= $rspw ?></a>
                    (<?= $obj->getPwCount() ?>)
                </td>
                <td class="bfield<?= $i % 2 ?>"><?= $lang["billing_address"] ?></td>
                <td class="bfield<?= $i % 2 ?>"><?= $lang["delivery_address"] ?></td>
                <td align="center"><?= $lang["previous_request"] . ":<br>" . $obj->getReason() ?><br><input
                        type="button" value="<?= $lang["request_refund"] ?>"
                        onClick="Redirect('<?= base_url() ?>order/on_hold_admin/refund/<?= $obj->getSoNo() ?>')"><br><input
                        type="button" value="<?= $lang["approve_for_fulfillment"] ?>"
                        onClick="Redirect('<?= base_url() ?>order/on_hold_admin/oc_approve/<?= $obj->getSoNo() ?>')">
                </td>
                </td>
                <td></td>
            </tr>
            <tr class="row<?= $i % 2 ?>">
                <td height="20"></td>
                <td colspan="3">
                    <?php
                    if ($obj->getItems()) :
                        $items = explode("||", $obj->getItems());
                        foreach ($items as $item) :
                            list($sku, $name, $qty, $u_p, $amount) = @explode("::", $item);
                            ?>
                            <p class="normal_p">[<?= $sku ?>] <?= $name ?> x<?= $qty ?> @<?= $u_p ?>
                                = <?= $amount ?></p>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </td>
                <td class="bvalue<?= $i % 2 ?>"><?= $bill_addr ?></td>
                <td class="bvalue<?= $i % 2 ?>"><?= $del_addr ?></td>
                <td align="center">
                    <select name="reason" id ="reason_<?= $obj->getSoNo() ?>" class="input">
                        <option></option>
                        <?php
                            if ($reason_list) :
                                foreach ($reason_list as $robj) :
                        ?>
                            <?php  if ($robj->getReasonType() == "csvv") : ?>
                            <option value="<?= base_url() . "order/on_hold_admin/oc_request/". $obj->getSoNo() ."/". $robj->getId() ."/". $robj->getReasonType() ?>"
                            ><?= $lang["hrcategory"][$robj->getReasonCat()] . " - " . $lang["request_vv"] ?></option>
                            <?php  endif;?>

                            <?php  if ($robj->getReasonType() == "oc_fraud") : ?>
                            <option value="<?= base_url() . "order/on_hold_admin/". $robj->getReasonType() ."/" . $obj->getSoNo()."/".$robj->getId() ?>"
                            ><?= $lang["hrcategory"][$robj->getReasonCat()] . " - " . $robj->getDescription() ?></option>
                            <?php  endif;?>
                        <?php
                                endforeach;
                            endif;
                        ?>
                    </select>
                    <input type="button" onclick="submit_reason(<?= $obj->getSoNo() ?>)" value="<?= $lang["confirm_submit"] ?>" onClick />
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="8" class="hr">
                    <hr>
                </td>
            </tr>
            <?php
            $i++;
        endforeach;
    endif;
    ?>
    </table>
    <script>
        InitPMGW(document.fm.payment_gateway_id);
        document.fm.payment_gateway_id.value = '<?=$this->input->get("payment_gateway_id")?>';

        function submit_reason(obj)
        {
            var reason = document.getElementById("reason_"+obj).value;
            if (reason != "") {
                var url = reason;
                window.location.href = url;
            } else {
                alert("Please Select Hold Reason");
            }
        }
    </script>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>