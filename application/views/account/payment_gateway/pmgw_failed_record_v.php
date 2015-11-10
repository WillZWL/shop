<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <link rel="stylesheet" href="<?= base_url() ?>css/lytebox.css" type="text/css" media="screen"/>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <?php
    $ar_status = array("N" => $lang["new"], "R" => $lang["ready_update_to_master"], "S" => $lang["success"], "F" => $lang["failed"], "I" => $lang["investigated"]);
    $ar_color = array("N" => "#000000", "R" => "#0000CC", "S" => "#009900", "F" => "#CC0000", "I" => "#440088");
    $ar_payment_type = array("R" => "Refund", "P" => "Paid");
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["process_report"] ?>" class="button"
                       onclick="Redirect('<?= site_url('account/payment_gateway') ?>')">&nbsp&nbsp
                <input type="button" value="<?= $lang["edit_failed_record"] ?>" class="button"
                       onclick="Redirect('<?= site_url('account/payment_gateway/pmgw_failed_record') ?>')">&nbsp&nbsp
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="100">
            <col>
            <col width="150">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'pmgw', '<?= $xsort["pmgw"] ?>')"><?= $lang["pmgw"] ?> <?= $sortimg["pmgw"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'platform_order_id', '<?= $xsort["txn_id"] ?>')"><?= $lang["txn_id"] ?> <?= $sortimg["txn_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'payment_type', '<?= $xsort["payment_type"] ?>')"><?= $lang["payment_type"] ?> <?= $sortimg["payment_type"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'so_no', '<?= $xsort["so_no"] ?>')"><?= $lang["so_no"] ?> <?= $sortimg["so_no"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'batch_status', '<?= $xsort["batch_status"] ?>')"><?= $lang["batch_status"] ?> <?= $sortimg["batch_status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'failed_reason', '<?= $xsort["failed_reason"] ?>')"><?= $lang["failed_reason"] ?> <?= $sortimg["failed_reason"] ?></a>
                </td>
                <td></td>
            </tr>
            <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?php
    $i = 0;
    if (!empty($objlist)) {
        foreach ($objlist as $obj) {
            $is_edit = ($cmd == "edit" && $trans_id == $obj->get_trans_id());
            ?>

            <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                onMouseOut="RemoveClassName(this, 'highlight')" <?php if (!($is_edit)){
            ?>onClick="Redirect('<?= site_url('account/payment_gateway/pmgw_failed_record/' . $obj->get_trans_id()) ?>?<?= $_SERVER['QUERY_STRING'] ?>')"<?php
            }?>>
                <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                     title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                </td>
                <td><?= $obj->get_payment_gateway_id() ?></td>
                <?php
                if ($is_edit) {
                    ?>
                    <form name="fm_edit"
                          action="<?= base_url() ?>account/payment_gateway/edit_failed_record/<?= $obj->get_trans_id() ?>/?<?= $_SERVER['QUERY_STRING'] ?>"
                          method="post" onSubmit="return CheckForm(this)">
                        <input type="hidden" name="posted" value="1">
                        <input type="hidden" name="cmd" value="edit">
                        <input type="hidden" name="trans_id" value="<?= $obj->get_trans_id() ?>">
                        <?php
                        if ($this->input->post("posted")) {
                            ?>
                            <td><input name="code" class="input" value="<?= $this->input->post("code") ?>" notEmpty
                                       maxLen=20></td>
                            <td><input name="description" class="input" value="<?= $this->input->post("description") ?>"
                                       maxLen=255></td>
                            <td><input name="duty_pcent" class="input" value="<?= $this->input->post("duty_pcent") ?>"
                                       notEmpty isNumber min=0></td>
                        <?php
                        } else {
                            ?>
                            <td><input name="txn_id" class="input" value="<?= $obj->get_txn_id() ?>"></td>
                            <td><input name="so_no" class="input"
                                       value="<?= $ar_payment_type[$obj->get_payment_type()] ?>"></td>
                            <td><input name="so_no" class="input" value="<?= $obj->get_so_no() ?>"></td>
                            <td style="color:<?= $ar_color[$obj->get_batch_status()] ?>"><?= $ar_status[$obj->get_batch_status()] ?></td>
                            <td><?= $obj->get_failed_reason() ?></td>
                        <?php
                        }
                        ?>
                        <td align="center"><input type="submit" value="<?= $lang["update"] ?>"> &nbsp; <input
                                type="button" value="<?= $lang["remove"] ?>"
                                onClick="Redirect('<?= site_url('account/payment_gateway/remove_failed_record/' . $obj->get_trans_id()) ?>')">
                        </td>
                    </form>
                <?php
                } else {
                    ?>
                    <td><?= $obj->get_txn_id() ?></td>
                    <td><?= $ar_payment_type[$obj->get_payment_type()] ?></td>
                    <td><?= $obj->get_so_no() ?></td>
                    <td style="color:<?= $ar_color[$obj->get_batch_status()] ?>"><?= $ar_status[$obj->get_batch_status()] ?></td>
                    <td><?= $obj->get_failed_reason() ?></td>
                    <td>&nbsp;</td>
                <?php
                }
                ?>
            </tr>
            <?php
            $i++;
        }
    }
    ?>
    </table>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>
