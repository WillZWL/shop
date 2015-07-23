<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
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
        if ($objlist) {
            foreach ($objlist as $obj) {
                ?>

                <tr class="bvalue<?= $i % 2 ?>">
                    <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                         title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                    </td>
                    <td><?= $obj->get_so_no() ?></td>
                    <td><?= $obj->get_txn_id() ?></td>
                    <td><?= $obj->get_create_on() ?></td>
                    <td><?= $obj->get_email() ?></td>
                    <td style="line-height:14px;">
                        <?= $obj->get_hold_status() ? $lang[$obj->get_reason()] . "<br><br>" . $lang["fraud_suspicion"] . "<br>" . $obj->get_hold_date() : $ar_ostatus[$obj->get_status()] ?>
                    </td>
                </tr>
                <tr class="row<?= $i % 2 ?>">
                    <td height="20"></td>
                    <td colspan="5">
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
                </tr>
                <tr>
                    <td colspan="6" class="hr">
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
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>