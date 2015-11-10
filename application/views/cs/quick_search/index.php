<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $lang["title"] ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/profitVarHelper/jsPlatformlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/PaymentGateway/jsPmgwlist"></script>
    <script language="javascript">
        <!--
        function drawList(value) {
            var selected = "";
            var output = "";
            for (var i in platform) {
                output = "<option value='" + platform[i][0] + "' " + selected + ">" + platform[i][1] + "</option>";
                document.write(output);
            }
        }
        -->
    </script>
</head>
<?php
$currency_arr = array("EUR" => "&euro;", "GBP" => "&pound;");
?>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="25" class="title"><?= $lang["subtitle"] ?></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <tr>
                <td colspan="2">
                    <table border="0" cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr>
                            <td height="25" width="10%" align="right"><?= $lang["surname"] ?></td>
                            <td width="25%" align="left">&nbsp;&nbsp;
                                <input class="input" type="text" name="surname" value="<?= $this->input->get('surname') ?>">
                            </td>
                            <td width="10%"
                                align="right"><?= (check_app_feature_access_right($app_id, "CS000100_password")) ? $lang["password"] : "" ?></td>
                            <?php  if (check_app_feature_access_right($app_id, "CS000100_password")) :
                                ?>
                                <td width="25%" align="left">&nbsp;&nbsp;
                                    <input class="input" type="text" name="password" value="<?= $this->input->get('password') ?>">
                                </td>
                            <?php  else : ?>
                                <td width="25%" align="left">&nbsp;</td>
                            <?php  endif; ?>
                            <td width="30%" rowspan="2">&nbsp;&nbsp;
                                <input type="button" value="<?= $lang["submit"] ?>" onClick="document.fm.submit();"></td>
                        </tr>
                        <tr>
                            <td height="25" width="10%" align="right"><?= $lang["tracking_no"] ?></td>
                            <td width="25%" align="left">&nbsp;&nbsp;
                                <input class="input" type="text" name="tracking_no" value="<?= $this->input->get('tracking_no') ?>">
                            </td>
                            <td width="10%" align="right"><?= $lang["ip_address"] ?></td>
                            <td width="25%" align="left">&nbsp;&nbsp;
                                <input class="input" type="text" name="ip_address" value="<?= $this->input->get('ip_address') ?>">
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="1" border="0" class="tb_list">
            <col width="20">
            <col width="77">
            <col width="80">
            <col width="122">
            <col width="120">
            <col width="70">
            <col width="95">
            <col width="80">
            <col width="110">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="122">
            <col width="20">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));"></td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'so_no', '<?= $xsort["so_no"] ?>')"><?= $lang["order_no"] ?> <?= $sortimg["so_no"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'platform_id', '<?= $xsort["platform_id"] ?>')"><?= $lang["platform"] ?> <?= $sortimg["platform_id"] ?></a>
                </td>
                <td align="center" TITLE="<?= $lang["payment_gateway"] ?>">
                    <a href="#" onClick="SortCol(document.fm , 'payment_gateway_id', '<?= $xsort["payment_gateway_id"] ?>')"><?= $lang["paym_gateway"] ?> <?= $sortimg["payment_gateway_id"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'platform_order_id', '<?= $xsort["platform_order_id"] ?>')"><?= $lang["platform_order_id"] ?> <?= $sortimg["platform_order_id"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'ext_client_id', '<?= $xsort["ext_client_id"] ?>')"><?= $lang["ext_client_id"] ?> <?= $sortimg["ext_client_id"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'txn_id', '<?= $xsort["txn_id"] ?>')"><?= $lang["txn_id"] ?> <?= $sortimg["txn_id"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'amount', '<?= $xsort["amount"] ?>')"><?= $lang["amount"] ?> <?= $sortimg["amount"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'order_create_date', '<?= $xsort["order_create_date"] ?>')"><?= $lang["create_date"] ?> <?= $sortimg["order_create_date"] ?></a>
                </td>
                <td align="center" TITLE="<?= $lang["expect_delivery_date"] ?>">
                    <a href="#" onClick="SortCol(document.fm , 'expect_delivery_date', '<?= $xsort["expect_delivery_date"] ?>')"><?= $lang["edd"] ?> <?= $sortimg["expect_delivery_date"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'delivery_name', '<?= $xsort["delivery_name"] ?>')"><?= $lang["cname"] ?> <?= $sortimg["delivery_name"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'email', '<?= $xsort["email"] ?>')"><?= $lang["email"] ?> <?= $sortimg["email"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'tel', '<?= $xsort["tel"] ?>')"><?= $lang["tel"] ?> <?= $sortimg["tel"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'dispatch_date', '<?= $xsort["dispatch_date"] ?>')"><?= $lang["shipped_on"] ?> <?= $sortimg["dispatch_date"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'status', '<?= $xsort["status"] ?>')"><?= $lang["status"] ?> <?= $sortimg["status"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'hold_status', '<?= $xsort["hold_status"] ?>')"><?= $lang["hold_status"] ?> <?= $sortimg["hold_status"] ?></a>
                </td>
                <td align="center">
                    <a href="#" onClick="SortCol(document.fm , 'refund_status', '<?= $xsort["refund_status"] ?>')"><?= $lang["refund_status"] ?> <?= $sortimg["refund_status"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td>&nbsp;</td>
                <td><input name="so_no" class="input"></td>
                <td>
                    <select name="platform_id" class="input">
                        <option value=""><?= $lang["please_select"] ?></option>
                    </select>
                </td>
                <td>
                    <select name="payment_gateway_id" id="pmgw" class="input">
                        <option value=""><?= $lang["please_select"] ?></option>
                    </select>
                </td>
                <td><input name="platform_order_id" type="text" class="input"></td>
                <td><input name="ext_client_id" type="text" class="input"></td>
                <td><input name="txn_id" type="text" class="input"></td>
                <td><input name="amount" type="text" class="input"></td>
                <td><input name="order_create_date" class="input"></td>
                <td><input name="expect_delivery_date" class="input"></td>
                <td><input name="delivery_name" class="input"></td>
                <td><input name="cemail" class="input"></td>
                <td><input name="tel" class="input"></td>
                <td><input name="dispatch_date" class="input"></td>
                <td>
                    <select name="order_status" class="input">
                        <option value=""><?= $lang["please_select"] ?></option>
                        <?php
                        foreach ($lang["status_name"] as $key => $value) :
                            ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </td>
                <td><select name="hold_status" class="input">
                        <option value=""><?= $lang["please_select"] ?></option>
                        <?php
                        foreach ($lang["hold_status_name"] as $key => $value) :

                            ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select></td>
                <td>
                    <?php if (check_app_feature_access_right($app_id, "CS000100_refund_status")) : ?>
                        <select name="refund_status" class="input">
                            <option value=""><?= $lang["please_select"] ?></option>
                            <?php
                            foreach ($lang["refund_status_name"] as $key => $value) :

                                ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    <?php else :
                        print "&nbsp;";
                          endif;
                    ?>
                </td>
                <td><input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            if ($search) :
                $pmgw = $this->input->get("payment_gateway_id");
                $row_color = array("#EEEEFF", "#DDDDFF");
                $i = 0;
                if ($total) :
                    foreach ($result as $obj) :
                        $item_arr = explode("||", $obj->getItems());

                        $split_parent_so_no = "";
                        $split_so_group = $obj->getSplitSoGroup();
                        if (!empty($split_so_group) && $split_parent != $obj->getSoNo()) :
                            $split_parent_so_no = "<br><i>P: $split_so_group</i>";
                        endif;
                        ?>
                        <tr class="row<?= $i % 2 ?>" onMouseOver="AddClassName(this, 'highlight')"
                            onMouseOut="RemoveClassName(this, 'highlight')" height="25"
                            onClick="Pop('<?= base_url() ?>cs/quick_search/view/<?= $obj->getSoNo() ?>','qsd_<?= $obj->getSoNo() ?>');"
                            onmouseout="ChgBg(this, '#EEEEFF')" onmouseover="ChgBg(this, '#DDFFDD')"
                            style="cursor:pointer;">
                            <td align="center">&nbsp;</td>
                            <td align="center"><?= $obj->getSoNo() ?><?= $split_parent_so_no ?></td>
                            <td align="center"><?= $obj->getName() ?></td>
                            <td align="center"><?= $obj->getPaymentGatewayName() ?></td>
                            <td align="center"><?= $obj->getPlatformOrderId() ?></td>
                            <td align="center"><?= $obj->getExtClientId() ?></td>
                            <td align="center"><?= $obj->getTxnId() ?></td>
                            <td align="center"><?= $currency_arr[$obj->getCurrencyId()] . $obj->getAmount() ?></td>
                            <td align="center"><?= substr($obj->getOrderCreateDate(), 0, 10) ?></td>
                            <td align="center"><?= substr($obj->getExpectDeliveryDate(), 0, 10) ?></td>
                            <td align="center"><?= $obj->getDeliveryName() ?></td>
                            <td align="center"><?= $obj->getEmail() ?></td>
                            <td align="center"><?= $obj->getTel() ?></td>
                            <td align="center">
                                <?= substr($obj->getDispatchDate(), 0, 10); ?>
                            </td>
                            <td align="center">
                                <?php
                                if ($display_finance_ship_date[$obj->getSoNo()])
                                    print $lang["status_name"][6];
                                else
                                    print $lang["status_name"][$obj->getStatus()];
                                ?>
                            </td>
                            <td align="center"><?= $lang["hold_status_name"][$obj->getHoldStatus()] ?></td>
                            <td align="center"><?= $lang["refund_status_name"][$obj->getRefundStatus()] ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="detail">
                            <td colspan="18" style="padding-left:20px;">
                                <?php
                                $content = array();
                                foreach ($item_arr as $line) {
                                    $line_arr = explode("::", $line);
                                    $content[] = "[" . $line_arr[0] . "] " . $line_arr[1] . "<font color='red'> (" . $line_arr[2] . ")</font> x" . $line_arr[3] . "@" . $line_arr[4] . "=" . $line_arr[5];
                                }
                                echo implode("<br>", $content);
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                else :
                    ?>
                    <tr bgcolor="<?= $row_color[0] ?>">
                        <td width="1240" colspan="18" align="center" height="20"><?= $lang["no_matching_record_found"] ?></td>
                    </tr>
                <?php
                endif;
            endif;
            ?>
        </table>
        <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        <input type="hidden" name="search" value="1">
    </form>
    <?= $links ?>
</div>
</body>
<script>
    InitPMGW(document.getElementById("pmgw"));

    <?php
        if($pmgw != '') :
    ?>
    document.getElementById('pmgw').value = '<?=$pmgw?>';
    <?php
        endif;
    ?>
    InitPlatform(document.fm.platform_id);
    document.fm.platform_id.value = '<?=$this->input->get("platform_id")?>';
</script>
<?= $notice["js"] ?>
</html>