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
</head>
<body>
<div id="main">
    <?php  $ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]); ?>
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               onclick="Redirect('<?= site_url('order/offline_credit_check/') ?>')">
                &nbsp; <input type="button" value="<?= $lang["list_button2"] ?>"
                              onclick="Redirect('<?= site_url('order/offline_credit_check/index/1/') ?>')"></td>
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
                                                  onClick="SortCol(document.fm, 'txn_id', '<?= $xsort["txn_id"] ?>')"><?= $lang["gateway_txn_id"] ?> <?= $sortimg["txn_id"] ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 'amount', '<?= $xsort["amount"] ?>')"><?= $lang["order_amount"] ?> <?= $sortimg["amount"] ?></a>
                </td>
                <td style="white-space:nowrap"><a href="#"
                                                  onClick="SortCol(document.fm, 't3m_result', '<?= $xsort["t3m_result"] ?>')"><?= $lang["t3m"] ?> <?= $sortimg["t3m_result"] ?></a>
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
                <td><input name="txn_id" class="input" value="<?= htmlspecialchars($this->input->get("txn_id")) ?>">
                </td>
                <td><input name="amount" class="input" value="<?= htmlspecialchars($this->input->get("amount")) ?>">
                </td>
                <td><input name="t3m_result" class="input"
                           value="<?= htmlspecialchars($this->input->get("t3m_result")) ?>"></td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?php
    $i = 0;
    if ($objlist) {
        foreach ($objlist as $obj) {
            list($d1, $d2, $d3) = explode("|", $obj->get_delivery_address());
            list($b1, $b2, $b3) = explode("|", $obj->get_bill_address());

            $del_addr = $d1 . ($d2 != "" ? "<br>" . $d2 : "") . ($d3 != "" ? "<br>" . $d3 : $d3);
            $bill_addr = $b1 . ($b2 != "" ? "<br>" . $b2 : "") . ($b3 != "" ? "<br>" . $b3 : $b3);
            $del_addr .= "<br>" . $obj->get_delivery_city() . " " . $obj->get_delivery_postcode() . "<br>" . $obj->get_delivery_country_id();
            $bill_addr .= "<br>" . $obj->get_bill_city() . " " . $obj->get_bill_postcode() . "<br>" . $obj->get_bill_country_id();

            ?>

            <tr class="row<?= $i % 2 ?>">
                <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                     title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                </td>
                <td><?= $obj->get_so_no() ?></td>
                <td><?= $obj->get_platform_order_id() ?></td>
                <td>
                    <script>w(pmgwlist['<?=$obj->get_payment_gateway_id()?>'])</script>
                </td>
                <td><?= $obj->get_txn_id() ?></td>
                <td><?= $obj->get_currency_id() ?> <?= $obj->get_amount() ?></td>
                <td><?= $obj->get_t3m_result() ?></td>
                <td></td>
            </tr>
            <tr class="row<?= $i % 2 ?>">
                <td height="20"></td>
                <td><?= $obj->get_forename() ?> <?= $obj->get_surname() ?></td>
                <td><?= $obj->get_email() ?></td>
                <td><?= $lang["password"] ?>: <a
                        href="<?= base_url() ?>order/offline_credit_check/chk_pw/?pw=<?= urlencode($obj->get_password()) ?>"
                        rel="lyteframe[check_password]" rev="width: 1024px; height: 500px; scrolling: auto;"
                        title="<?= $lang["password"] ?> - <?= $rspw = $this->encrypt->decode($obj->get_password()) ?>"><?= $rspw ?></a>
                    (<?= $obj->get_pw_count() ?>)
                </td>
                <td class="bfield<?= $i % 2 ?>"><?= $lang["billing_address"] ?></td>
                <td class="bfield<?= $i % 2 ?>"><?= $lang["delivery_address"] ?></td>
                <td align="center"><input type="button" value="<?= $lang["delete"] ?>"
                                          onClick="Redirect('<?= base_url() ?>order/offline_credit_check/delete/<?= $obj->get_so_no() ?>')">
                    &nbsp; <input type="button" value="<?= $lang["approve"] ?>"
                                  onClick="Redirect('<?= base_url() ?>order/offline_credit_check/approve/<?= $obj->get_so_no() ?>')">
                </td>
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
                <td align="center">
                    <form action="<?= base_url() ?>order/offline_credit_check/hold/<?= $obj->get_so_no() ?>"
                          method="post">
                        <select name="reason" class="input">
                            <option value="confirmed_fraud"><?= $lang["confirmed_fraud"] ?></option>
                            <option value="csvv"><?= $lang["csvv"] ?></option>
                            <option value="csvv"><?= $lang["cscc"] ?></option>
                        </select><br>
                        <input type="submit" value="<?= $lang["on_hold"] ?>">
                    </form>
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
        }
    }
    ?>
    </table>
    <script>
        InitPMGW(document.fm.payment_gateway_id);
        document.fm.payment_gateway_id.value = '<?=$this->input->get("payment_gateway_id")?>';
    </script>
    <?= $this->paginationService->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>