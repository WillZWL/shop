<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="<?= base_url() ?>css/bootstrap.min.css" type="text/css" media="all" />
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
</head>
<body style="width:auto;">
<div id="main" style="width:auto;">
    <?php
    $ar_ostatus = array($lang["inactive"], $lang["new_order"], $lang["paid"], $lang["credit_checked"], $lang["to_po"], $lang["to_ship"], $lang["packed"], $lang["shipped"]);
    ?>
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
        <col width="20">
        <col width="150">
        <col width="150">
        <col width="150">
        <col width="150">
        <col>
        <tr class="header">
            <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                 onClick="Expand(document.getElementById('tr_search'));"></td>
            <td style="white-space:nowrap"><?= $lang["order_id"] ?></td>
            <td style="white-space:nowrap"><?= $lang["gateway_txn_id"] ?></td>
            <td style="white-space:nowrap"><?= $lang["purchase_date"] ?></td>
            <td style="white-space:nowrap"><?= $lang["client_email"] ?></td>
            <td style="white-space:nowrap"><?= $lang["status"] ?></td>
        </tr>
        <?php
        $i = 0;
        if ($objlist) :
            foreach ($objlist as $obj) :
                ?>

                <tr class="bvalue<?= $i % 2 ?>">
                    <td height="20">
                        <img src="<?= base_url() ?>images/info.gif" title='<?= $lang["create_on"] ?>:<?= $obj->getCreateOn() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->getCreateAt() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->getCreateBy() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->getModifyOn() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->getModifyAt() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->getModifyBy() ?>'>
                    </td>
                    <td><?= $obj->getSoNo() ?></td>
                    <td><?= $obj->getTxnId() ?></td>
                    <td><?= $obj->getCreateOn() ?></td>
                    <td><?= $obj->getEmail() ?></td>
                    <td style="line-height:14px;">
                        <?= $obj->getHoldStatus() ? $lang[$obj->getReason()] . "<br><br>" . $lang["fraud_suspicion"] . "<br>" . $obj->getHoldDate() : $ar_ostatus[$obj->getStatus()] ?>
                    </td>
                </tr>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"></td>
                    <td colspan="5">
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
                </tr>
                <tr>
                    <td colspan="6" class="hr">
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
    </script>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>