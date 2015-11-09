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
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?> - Amazon UK Orders</td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["integration_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('integration/integration/?' . $_SESSION["int_query"]) ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b style="font-size:14px"><?= $lang["header"] ?> - Amazon UK
                    Orders</b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="200">
            <col width="200">
            <col width="200">
            <col>
            <col width="100">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'trans_id', '<?= $xsort["trans_id"] ?>')"><?= $lang["trans_id"] ?> <?= $sortimg["trans_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'platform_order_id', '<?= $xsort["platform_order_id"] ?>')"><?= $lang["platform_order_id"] ?> <?= $sortimg["platform_order_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'batch_status', '<?= $xsort["batch_status"] ?>')"><?= $lang["batch_status"] ?> <?= $sortimg["batch_status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'failed_reason', '<?= $xsort["failed_reason"] ?>')"><?= $lang["failed_reason"] ?> <?= $sortimg["failed_reason"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="trans_id" class="input" value="<?= htmlspecialchars($this->input->get("trans_id")) ?>">
                </td>
                <td><input name="platform_order_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("platform_order_id")) ?>"></td>
                <td>
                    <?php
                    if ($this->input->get("batch_status") != "") {
                        $selected[$this->input->get("batch_status")] = "SELECTED";
                    }
                    ?>
                    <select name="batch_status" class="input">
                        <option value="">
                            <?php
                            foreach ($ar_status as $rskey => $rsvalue)
                            {
                            ?>
                        <option value="<?= $rskey ?>" <?= $selected[$rskey] ?>
                                style="color:<?= $ar_color[$rskey] ?>"><?= $rsvalue ?>
                            <?php
                            }
                            ?>
                    </select>
                </td>
                <td><input name="failed_reason" class="input"
                           value="<?= htmlspecialchars($this->input->get("failed_reason")) ?>"></td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?php
    $i = 0;
    if (!empty($objlist))
    {
    foreach ($objlist as $obj)
    {
    $baddr = explode("|", $obj->get_bill_address());
    $daddr = explode("|", $obj->get_delivery_address());

    $ba = $da = "";

    foreach ($baddr as $key => $value) {
        if ($value != "") {
            $ba .= $value . ",<br>";
        }

        if ($daddr[$key] != "") {
            $da .= $daddr[$key] . ",<br>";
        }
    }

    $ba .= $obj->get_bill_city() . ",<br> " . $obj->get_bill_state() . " " . $obj->get_bill_postcode() . ",<br>" . $obj->get_bill_country_id();
    $da .= $obj->get_delivery_city() . ",<br> " . $obj->get_delivery_state() . " " . $obj->get_delivery_postcode() . ",<br>" . $obj->get_delivery_country_id();
    if ($this->input->get('trans') != $obj->get_trans_id()) {
        ?>
        <tr class="row<?= $i % 2 ?>">
            <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                 title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
            </td>
            <td><?= $obj->get_trans_id() ?></td>
            <td><?= $obj->get_platform_order_id() ?></td>
            <td style="color:<?= $ar_color[$obj->get_batch_status()] ?>"><?= $ar_status[$obj->get_batch_status()] ?></td>
            <td><?= lang($obj->get_failed_reason(), $lang) ?></td>
            <td align="center">&nbsp;
                <?php
                if (!in_array($obj->get_batch_status(), array("R", "S"))) {
                    ?>
                    <input type="button" value="<?= $lang["modify"] ?>" class="button"
                           onClick="Redirect('<?= base_url() . "integration/integration/view_amazon/" . $func . "/" . $obj->get_batch_id() . "?" . $_SERVER["QUERY_STRING"] . "&trans=" . $obj->get_trans_id() ?>')">
                <?php
                }
                ?>
            </td>
        </tr>
    <?php
    } else {
        ?>
        <tr class="row<?= $i % 2 ?>">
            <form name="fm2"
                  action="<?= base_url() . "integration/integration/view_amazon/" . $func . "/" . $obj->get_batch_id() . "?" . $_SERVER["QUERY_STRING"] ?>"
                  method="POST" onSubmit="return CheckForm(this);">
                <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                     title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                </td>
                <td><?= $obj->get_trans_id() ?></td>
                <td><input type="text" name="porder_id" value="<?= $obj->get_platform_order_id() ?>" class="input"
                           notEmpty></td>
                <td style="color:<?= $ar_color[$obj->get_batch_status()] ?>"><?= $ar_status[$obj->get_batch_status()] ?></td>
                <td><?= lang($obj->get_failed_reason(), $lang) ?></td>
                <td align="center">
                    <input type="button" value="<?= $lang["submit_change"] ?>" class="button"
                           onClick="if(CheckForm(this.form)) document.fm2.submit();">
                    <input type="hidden" name="edit" value="1">
                    <input type="hidden" name="trans" value="<?= $obj->get_trans_id() ?>">
                </td>
            </form>
        </tr>
    <?php

    }
    ?>
    <tr class="row<?= $i % 2 ?>">
        <td></td>
        <td class="bfield0"><?= $lang["client_information"] ?></td>
        <td class="bfield0"><?= $lang["order_information"] ?></td>
        <td class="bfield0"><?= $lang["billing_addr"] ?></td>
        <td class="bfield0"><?= $lang["delivery_addr"] ?></td>
        <td></td>
    </tr>
    <tr class="row<?= $i % 2 ?>">
        <td></td>
        <td class="bvalue0"><a
                href="<?= base_url() ?>integration/integration/examine_client/<?= $obj->get_batch_id() ?>/<?= $obj->get_client_trans_id() ?>"
                rel="lyteframe[examine_client_<?= $obj->get_trans_id() ?>]"
                rev="width: 1024px; height: 500px; scrolling: auto;"
                title="Client Detail - Order with Transaction ID <?= $obj->get_trans_id() ?>"><?= $lang["examine_client_detail"] ?></a>
        </td>
        <td class="bvalue0"><a
                href="<?= base_url() ?>integration/integration/examine_order/<?= $obj->get_batch_id() ?>/<?= $obj->get_trans_id() ?>"
                rel="lyteframe[examine_order_<?= $obj->get_trans_id() ?>]"
                rev="width: 1024px; height: 500px; scrolling: auto;"
                title="Order Detail - Order with Transaction ID <?= $obj->get_trans_id() ?>"><?= $lang["examine_order_detail"] ?></a>
        </td>
        <td class="bvalue0"><?= $ba ?></td>
        <td class="bvalue0"><?= $da ?></td>
        <td align="center"><?php if (in_array($obj->get_batch_status(), array('F', 'I'))) { ?><input type="button"
                                                                                                     value="<?= $lang["reprocess"] ?>"
                                                                                                     onClick="document.location.href='<?= base_url() ?>integration/integration/reprocess_amazon/<?= $obj->get_batch_id() ?>/<?= $obj->get_trans_id() ?>/AMUK';"><?php
            }?></td>
    </tr>
    <tr height="5">
        <td style="padding-left:5px; padding-right:5px;" width="100%" colspan="6">
            <hr>
        </td>
    <tr>
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