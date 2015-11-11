<html>
<head>
    <title><?= $lang["title"] ?></title>
    <style type="text/css">
        .button3 {
            width: 170px;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/calendar.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/calendar.css" type="text/css" media="all"/>
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
    <form name="fm" action='<?= base_url() . "report/expect_delivery_date_report/export_csv"; ?>' method="post"
          onSubmit="return verfiy_date(this)">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="page_header">
            <tr height="80">
                <td align="left" style="padding-left:8px;">
                    <b style="font-size: 14px; color: rgb(0, 0, 0);"><?= $_title ?></b><br>
                </td>
                <td align="right">
                    <table border="0" cellpadding="0" cellspacing="0" style="line-height:8px;">
                        <col>
                        <col width="5%">
                        <col width="25%">
                        <col width="10%">
                        <col width="5%">
                        <col width="25%">
                        <col width="10px">
                        <tr>
                            <td></td>
                            <td><b>From</b></td>
                            <td><input id="oc_start_date" name="start_date[order_create]"
                                       value='<?= htmlspecialchars($start_date) ?>'><img src="/images/cal_icon.gif"
                                                                                         class="pointer"
                                                                                         onclick="showcalendar(event, document.getElementById('oc_start_date'), false, false, false, '2010-01-01')"
                                                                                         align="absmiddle"></td>
                            <td></td>
                            <td><b>To</b></td>
                            <td><input id="oc_end_date" name="end_date[order_create]"
                                       value='<?= htmlspecialchars($end_date) ?>'><img src="/images/cal_icon.gif"
                                                                                       class="pointer"
                                                                                       onclick="showcalendar(event, document.getElementById('oc_end_date'), false, false, false, '2010-01-01')"
                                                                                       align="absmiddle"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="10" align="right" style="padding-top:5px; padding-right:8px;">
                                <input type="submit" value="Export CSV" class="button3">
                            </td>
                        </tr>
                        <input type="hidden" name="is_query" value="1">
                    </table>
                </td>
            </tr>
    </form>

    <form name="fm_2" action='<?= base_url() . "report/expect_delivery_date_report/index"; ?>' method="post"
          onSubmit="return getDateInfo(this)">
        <tr>
            <td style='padding:0;padding-right:8px;' colspan='7' align='right'>
                <input type="submit" value="Show Report" class="button3">
                <input type="hidden" value="1" name="display_report">
                <input type='hidden' value='' name='expect_delivery_date_approve_start_date'>
                <input type='hidden' value='' name='expect_delivery_date_approve_end_date'>
            </td>
        </tr>
    </form>
    </table>


    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list" style="text-align:center">
        <col width="30">
        <col width="15">
        <col width="15">
        <col width="15">
        <col width="70">
        <col width="70">
        <col width="25">
        <col width="70">
        <col width="70">
        <col width="100">
        <col width="100">
        <col width="100">
        <col width="70">
        <col width="70">
        <col width="70">
        <col width="10">
        <col width="10">
        <form name="fm_3" method="get" onSubmit="return CheckForm(this)">
            <tr class="header">
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.so_no', '<?= $xsort["so.so_no"] ?>')"><?= $lang["order_number"] ?><?= $sortimg["so.so_no"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.platfrom_id', '<?= $xsort["so.platfrom_id"] ?>')"><?= $lang["platform"] ?><?= $sortimg["so.platfrom_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'sops.payment_gateway_id', '<?= $xsort["sops.payment_gateway_id"] ?>')"><?= $lang["paym_gateway"] ?><?= $sortimg["sops.payment_gateway_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.platform_id', '<?= $xsort["so.platform_id"] ?>')"><?= $lang["platform_order_id"] ?><?= $sortimg["so.platform_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'c.ext_client_id', '<?= $xsort["c.ext_client_id"] ?>')"><?= $lang["ebay_id"] ?><?= $sortimg["c.ext_client_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.txn_id', '<?= $xsort["so.txn_id"] ?>')"><?= $lang["transaction_number"] ?><?= $sortimg["so.txn_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.amount', '<?= $xsort["so.amount"] ?>')"><?= $lang["order_amout"] ?><?= $sortimg["so.amount"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.order_create_date', '<?= $xsort["so.order_create_date"] ?>')"><?= $lang["create_date"] ?><?= $sortimg["so.order_create_date"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.expect_delivery_date', '<?= $xsort["so.expect_delivery_date"] ?>')"><?= $lang["edd"] ?><?= $sortimg["so.expect_delivery_date"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.bill_name', '<?= $xsort["so.bill_name"] ?>')"><?= $lang["client_name"] ?><?= $sortimg["so.bill_name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'c.email', '<?= $xsort["c.email"] ?>')"><?= $lang["client_email"] ?><?= $sortimg["c.email"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'contact_no', '<?= $xsort["contact_no"] ?>')"><?= $lang["contact_number"] ?><?= $sortimg["contact_no"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.dispatch_date', '<?= $xsort["so.dispatch_date"] ?>')"><?= $lang["shipped_on"] ?><?= $sortimg["so.dispatch_date"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.status', '<?= $xsort["so.status"] ?>')"><?= $lang["order_status"] ?><?= $sortimg["so.status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.hold_status', '<?= $xsort["so.hold_status"] ?>')"><?= $lang["hold_status"] ?><?= $sortimg["so.hold_status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.refund_status', '<?= $xsort["so.refund_status"] ?>')"><?= $lang["refund_status"] ?><?= $sortimg["so.refund_status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'sps.score', '<?= $xsort["sps.score"] ?>')"><?= $lang["priority_score"] ?><?= $sortimg["sps.score"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm_3, 'so.modify_by', '<?= $xsort["so.modify_by"] ?>')"><?= $lang["modify_by"] ?><?= $sortimg["so.modify_by"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td><input name="so_no" type="text" class="input" value="<?= $this->input->get("so_no") ?>"></td>
                <td><input name="platform_id" type="text" class="input" value="<?= $this->input->get("platform_id") ?>">
                </td>
                <td><input name="platform_gateway_id" type="text" class="input"
                           value="<?= $this->input->get("platform_gateway_id") ?>"></td>
                <td><input name="platform_order_id" type="text" class="input"
                           value="<?= $this->input->get("platform_order_id") ?>"></td>
                <td><input name="ext_client_id" type="text" class="input"
                           value="<?= $this->input->get("ext_client_id") ?>"></td>
                <td><input name="txn_id" type="text" class="input" value="<?= $this->input->get("txn_id") ?>"></td>
                <td><input name="amount" type="text" class="input" value="<?= $this->input->get("amount") ?>"></td>
                <td><input name="order_create_date" type="text" class="input"
                           value="<?= $this->input->get("order_create_date") ?>"></td>
                <td><input name="expect_delivery_date" type="text" class="input"
                           value="<?= $this->input->get("expect_delivery_date") ?>"></td>
                <td><input name="bill_name" type="text" class="input" value="<?= $this->input->get("bill_name") ?>">
                </td>
                <td><input name="email" type="text" class="input" value="<?= $this->input->get("email") ?>"></td>
                <td><input name="contact_no" type="text" class="input" value="<?= $this->input->get("contact_no") ?>">
                </td>
                <td><input name="dispatch_date" type="text" class="input"
                           value="<?= $this->input->get("dispatch_date") ?>"></td>
                <td><input name="status" type="text" class="input" value="<?= $this->input->get("status") ?>"></td>
                <td><input name="hlod_status" type="text" class="input" value="<?= $this->input->get("hlod_status") ?>">
                </td>
                <td><input name="refund_status" type="text" class="input"
                           value="<?= $this->input->get("refund_status") ?>"></td>
                <td><input name="score" type="text" class="input" value="<?= $this->input->get("score") ?>"></td>
                <td><input name="modify_by" type="text" class="input" value="<?= $this->input->get("modify_by") ?>">
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="search" value=1>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        </form>
        <?php
        $i = 0;
        if (!empty($list))
        {
        foreach ($list as $obj)
        {
        ?>
        <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
            onMouseOut="RemoveClassName(this, 'highlight')">
            <td><?= $obj->get_so_no() ?></td>
            <td><?= $obj->get_platform_id() ?></td>
            <td><?= $obj->get_payment_gateway() ?></td>
            <td><?= $obj->get_platform_order_id() ?></td>
            <td><?= $obj->get_ebay_id() ?></td>
            <td><?= $obj->get_transaction_no() ?></td>
            <td><?= $obj->get_order_amount() ?></td>
            <td><?= $obj->get_create_date() ?></td>
            <td><?= $obj->get_edd() ?></td>
            <td><?= $obj->get_client_name() ?></td>
            <td><?= $obj->get_client_email() ?></td>
            <td><?= $obj->get_contact_no() ?></td>
            <td><?= $obj->get_shipped_on() ?></td>
            <td><?= $obj->get_order_status() ?></td>
            <td><?= $obj->get_hold_status() ?></td>
            <td><?= $obj->get_refund_status() ?></td>
            <td><?= $obj->get_priority_score() ?></td>
            <td><?= $obj->get_modify_by() ?></td>
            <td>&nbsp;</td>
            <?php
            $i++;
            }
            }
            ?>
        <tr class="header">
            <td></td>
            <td colspan="17"></td>
        </tr>
    </table>

    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
<?php print $content; ?>

<script>
    function getDateInfo(fm_2) {
        //alert(fm_2.name);
        var start_date = document.getElementById("oc_start_date").value.trim();
        var end_date = document.getElementById("oc_end_date").value.trim();
        fm_2.expect_delivery_date_approve_start_date.value = start_date;
        fm_2.expect_delivery_date_approve_end_date.value = end_date;

        if (!start_date.match(/^\d{4}-\d{2}-\d{2}$/) || !end_date.match(/^\d{4}-\d{2}-\d{2}$/)) {
            alert("please input the correct date");
            return false;
        }
    }

    function verfiy_date() {
        var start_date = document.getElementById("oc_start_date").value.trim();
        var end_date = document.getElementById("oc_end_date").value.trim();

        if (!start_date.match(/^\d{4}-\d{2}-\d{2}$/) || !end_date.match(/^\d{4}-\d{2}-\d{2}$/)) {
            alert("please input the correct date");
            return false;
        }
    }

</script>
</body>
</html>