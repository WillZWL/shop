<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<div id="main">
    <?php  $ar_status = array("0" => $lang["inactive"], "1" => $lang["active"]); ?>
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('supply/supplier/') ?>')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= site_url('supply/supplier/add/') ?>')"></td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <tr>
                <td height="70" style="padding-left:8px"><b
                        style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="40">
            <col>
            <col width="85">
            <col width="200">
            <col width="200">
            <col width="100">
            <col width="100">
            <col width="27">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'id', '<?= $xsort["id"] ?>')"><?= $lang["id"] ?> <?= $sortimg["id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')"><?= $lang["name"] ?> <?= $sortimg["name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'currency_id', '<?= $xsort["currency_id"] ?>')"><?= $lang["currency"] ?> <?= $sortimg["currency_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'supplier_reg', '<?= $xsort["supplier_reg"] ?>')"><?= $lang["supplier_region"] ?> <?= $sortimg["supplier_reg"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sourcing_reg', '<?= $xsort["sourcing_reg"] ?>')"><?= $lang["sourcing_region"] ?> <?= $sortimg["sourcing_reg"] ?></a>
                </td>
                <td>FULFILMENT CENTRE</td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'status', '<?= $xsort["status"] ?>')"><?= $lang["status"] ?> <?= $sortimg["status"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="id" class="input" value="<?= htmlspecialchars($this->input->get("id")) ?>"></td>
                <td><input name="name" class="input" value="<?= htmlspecialchars($this->input->get("name")) ?>"></td>
                <td><input name="currency_id" class="input"
                           value="<?= htmlspecialchars($this->input->get("currency_id")) ?>"></td>
                <td><input name="supplier_reg" class="input"
                           value="<?= htmlspecialchars($this->input->get("supplier_reg")) ?>"></td>
                <td><input name="sourcing_reg" class="input"
                           value="<?= htmlspecialchars($this->input->get("sourcing_reg")) ?>"></td>
                <td>&nbsp;</td>
                <td>
                    <select name="status" class="input">
                        <?php
                        if ($this->input->get("status") != "") {
                            $selected_ar[$this->input->get("status")] = "SELECTED";
                        }
                        ?>
                        <option value="">
                        <option value="0" <?= $selected_ar[0] ?>><?= $lang["inactive"] ?>
                        <option value="1" <?= $selected_ar[1] ?>><?= $lang["active"] ?>
                    </select>
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            $i = 0;
            if ($objlist) {
                foreach ($objlist as $obj) {
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= site_url('supply/supplier/view/' . $obj->getId()) ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $obj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->getModifyBy() ?>'>
                        </td>
                        <td><?= $obj->getId() ?></td>
                        <td><?= $obj->getName() ?></td>
                        <td><?= $obj->getCurrencyId() ?></td>
                        <td><?= $obj->getSupplierReg() ?></td>
                        <td><?= $obj->getSourcingReg() ?></td>
                        <td><?= $obj->getFcId() ?></td>
                        <td><?= $ar_status[$obj->getStatus()] ?></td>
                        <td></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
        </table>
        <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>