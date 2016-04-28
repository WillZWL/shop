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
            <td height="30" class="title"><?= $lang["title"] ?> - Tracking Information</td>
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
            <td height="70" style="padding-left:8px"><b style="font-size:14px"><?= $lang["header"] ?> - Tracking
                    Information</b><br><?= $lang["header_message"] ?></td>
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
                       onClick="SortCol(document.fm, 'sh_no', '<?= $xsort["sh_no"] ?>')"><?= $lang["sh_no"] ?> <?= $sortimg["sh_no"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'tracking_no', '<?= $xsort["tracking_no"] ?>')"><?= $lang["tracking_no"] ?> <?= $sortimg["tracking_no"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'courier_id', '<?= $xsort["courier_id"] ?>')"><?= $lang["courier_id"] ?> <?= $sortimg["courier_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'ship_method', '<?= $xsort["ship_method"] ?>')"><?= $lang["ship_method"] ?> <?= $sortimg["ship_method"] ?></a>
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
                <td><input name="sku" class="input" value="<?= htmlspecialchars($this->input->get("sku")) ?>"></td>
                <td><input name="tracking_info" class="input"
                           value="<?= htmlspecialchars($this->input->get("tracking_info")) ?>"></td>
                <td><input name="courier_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("courier_id")) ?>"></td>
                <td><input name="ship_method" class="input"
                           value="<?= htmlspecialchars($this->input->get("ship_method")) ?>"></td>
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
    if (!empty($objlist)) :
        foreach ($objlist as $obj) :
            if ($this->input->get('trans') != $obj->getTransId()) :
                ?>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                         title='<?= $lang["create_on"] ?>:<?= $obj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->getModifyBy() ?>'>
                    </td>
                    <td><?= $obj->getShNo() ?></td>
                    <td><?= $obj->getTrackingNo() ?></td>
                    <td><?= $obj->getCourierId() ?></td>
                    <td><?= $obj->getShipMethod() ?></td>
                    <td style="color:<?= $ar_color[$obj->getBatchStatus()] ?>"><?= $ar_status[$obj->getBatchStatus()] ?></td>
                    <td><?= lang($obj->getFailedReason(), $lang) ?></td>
                    <td align="center">&nbsp;
                        <?php
                        if (!in_array($obj->getBatchStatus(), array("R", "S"))) :
                            ?>
                            <!--    <input type="button" value="<?= $lang["modify"] ?>" class="button" onClick="Redirect('<?= base_url() . "integration/integration/view_amazon/" . $func . "/" . $obj->getBatchId() . "?" . $_SERVER["QUERY_STRING"] . "&trans=" . $obj->getTransId() ?>')">-->
                        <?php
                        endif;
                        ?>
                    </td>
                </tr>
            <?php
            endif;
            $i++;
        endforeach;
    endif;
    ?>
    </table>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>