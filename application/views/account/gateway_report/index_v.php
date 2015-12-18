<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/category/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="600" align="right" class="title">
                <input type="button" value="<?= $lang["upload_report"] ?>" class="button"
                       onclick="Redirect('<?= site_url('account/GatewayReport/upload') ?>')">&nbsp&nbsp
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
            <col width="26">
            <col width="150">
            <col width="200">
            <col>
            <col width="26">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer"
                         onClick="Expand(document.getElementById('tr_search'));">
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'batch_id', '<?= $xsort["batch_id"] ?>')"><?= $lang["batch_id"] ?> <?= $sortimg["batch_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'gateway_id', '<?= $xsort["gateway_id"] ?>')"><?= $lang["gateway_id"] ?> <?= $sortimg["gateway_id"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'filename', '<?= $xsort["filename"] ?>')"><?= $lang["filename"] ?> <?= $sortimg["filename"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="batch_id" class="input" value="<?= htmlspecialchars($this->input->get("batch_id")) ?>">
                </td>
                <td>
                    <select name="gateway_id" class="input">
                        <?php
                        if ($this->input->get("gateway_id") != "") {
                            $selected_gw[$this->input->get("gateway_id")] = "SELECTED";
                        }
                        ?>
                        <option value=""></option>
                        <option value="worldpay" <?= $selected_gw["worldpay"] ?>><?= $lang["worldpay"] ?></option>
                        <option
                            value="worldpay_moto" <?= $selected_gw["worldpay_moto"] ?>><?= $lang["worldpay_moto"] ?></option>
                        <option value="paypal_hk" <?= $selected_gw["paypal_hk"] ?>><?= $lang["paypal_hk"] ?></option>
                        <option value="paypal_au" <?= $selected_gw["paypal_au"] ?>><?= $lang["paypal_au"] ?></option>
                        <option value="trustly" <?= $selected_gw["trustly"] ?>><?= $lang["trustly"] ?></option>
                        <option value="paypal_nz" <?= $selected_gw["paypal_nz"] ?>><?= $lang["paypal_nz"] ?></option>
                        <option value="inpendium" <?= $selected_gw["inpendium"] ?>><?= $lang["inpendium"] ?></option>
                        <option
                            value="moneybookers" <?= $selected_gw["moneybookers"] ?>><?= $lang["moneybookers"] ?></option>
                        <option value="trademe" <?= $selected_gw["trademe"] ?>><?= $lang["trademe"] ?></option>
                        <option
                            value="global_collect" <?= $selected_gw["global_collect"] ?>><?= $lang["global_collect"] ?></option>
                        <option value="fnac" <?= $selected_gw["fnac"] ?>><?= $lang["fnac"] ?></option>
                        <option value="lzmy" <?= $selected_gw["lzmy"] ?>><?= $lang["lzmy"] ?></option>
                        <option value="lzdth" <?= $selected_gw["lzdth"] ?>><?= $lang["lzdth"] ?></option>
                        <option value="lzdph" <?= $selected_gw["lzdph"] ?>><?= $lang["lzdph"] ?></option>
                        <option value="adyen" <?= $selected_gw["adyen"] ?>><?= $lang["adyen"] ?></option>
                        <option value="altapay" <?= $selected_gw["altapay"] ?>><?= $lang["altapay"] ?></option>
                        <option value="newegg_us" <?= $selected_gw["newegg_us"] ?>><?= $lang["newegg_us"] ?></option>
                        <option value="qoo10" <?= $pmgw == "qoo10" ? "SELECTED" : "" ?>><?= $lang["qoo10"] ?></option>
                    </select>
                </td>
                <td><input name="filename" class="input" value="<?= htmlspecialchars($this->input->get("filename")) ?>">
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            if ($objlist) {
                foreach ($objlist as $obj) {
                    ?>

                    <tr class="row<?= $i % 2 ?>">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $obj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->getModifyBy() ?>'>
                        </td>
                        <td><?= $obj->getId() ?></td>
                        <td><?= $lang[$obj->getGatewayId()] ?></td>
                        <td>
                            <span class="pointer"
                                  onClick="Redirect('<?= site_url('account/GatewayReport/downloadBatch/' . $obj->getId()) ?>')"><?= $obj->getFilename() ?></span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="pointer"
                                  onClick="Redirect('<?= site_url('account/GatewayReport/downloadFeedbackReport/' . $obj->getId()) ?>')"><input
                                    class="pointer" type="button"
                                    value="<?php echo $lang['download_feedback_report'] ?>"></span>
                        </td>
                        <td></td>
                    </tr>
                <?php
                }
            }
            ?>
        </table>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        <input type="hidden" name="search" value="1">
    </form>
    <?= $this->sc['Pagination']->createLinksWithStyle(); ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>