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
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["download_batch"] ?>" class="button"
                       onclick="Redirect('<?= site_url('account/GatewayReport') ?>')">&nbsp&nbsp
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
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?><br>
                <?= $lang["pmgw"] ?> :
                <select name="country_id"
                        onChange="Redirect('<?= base_url() ?>account/GatewayReport/upload/'+this.value)">
                    <option value=""></option>
                    <option
                        value="worldpay" <?= $pmgw == "worldpay" ? "SELECTED" : "" ?>><?= $lang["worldpay"] ?></option>
                    <option
                        value="worldpay_moto" <?= $pmgw == "worldpay_moto" ? "SELECTED" : "" ?>><?= $lang["worldpay_moto"] ?></option>
                    <option
                        value="paypal_hk" <?= $pmgw == "paypal_hk" ? "SELECTED" : "" ?>><?= $lang["paypal_hk"] ?></option>
                    <option
                        value="paypal_au" <?= $pmgw == "paypal_au" ? "SELECTED" : "" ?>><?= $lang["paypal_au"] ?></option>
                    <option value="trustly" <?= $pmgw == "trustly" ? "SELECTED" : "" ?>><?= $lang["trustly"] ?></option>
                    <option
                        value="paypal_nz" <?= $pmgw == "paypal_nz" ? "SELECTED" : "" ?>><?= $lang["paypal_nz"] ?></option>
                    <option
                        value="inpendium" <?= $pmgw == "inpendium" ? "SELECTED" : "" ?>><?= $lang["inpendium"] ?></option>
                    <option
                        value="moneybookers" <?= $pmgw == "moneybookers" ? "SELECTED" : "" ?>><?= $lang["moneybookers"] ?></option>
                    <option value="trademe" <?= $pmgw == "trademe" ? "SELECTED" : "" ?>><?= $lang["trademe"] ?></option>
                    <option
                        value="global_collect" <?= $pmgw == "global_collect" ? "SELECTED" : "" ?>><?= $lang["global_collect"] ?></option>
                    <option value="fnac" <?= $pmgw == "fnac" ? "SELECTED" : "" ?>><?= $lang["fnac"] ?></option>
                    <option value="lzmy" <?= $pmgw == "lzmy" ? "SELECTED" : "" ?>><?= $lang["lzmy"] ?></option>
                    <option value="lzdth" <?= $pmgw == "lzdth" ? "SELECTED" : "" ?>><?= $lang["lzdth"] ?></option>
                    <option value="lzdph" <?= $pmgw == "lzdph" ? "SELECTED" : "" ?>><?= $lang["lzdph"] ?></option>
                    <option value="adyen" <?= $pmgw == "adyen" ? "SELECTED" : "" ?>><?= $lang["adyen"] ?></option>
                    <option value="altapay" <?= $pmgw == "altapay" ? "SELECTED" : "" ?>><?= $lang["altapay"] ?></option>
                    <option
                        value="newegg_us" <?= $pmgw == "newegg_us" ? "SELECTED" : "" ?>><?= $lang["newegg_us"] ?></option>
                    <option value="qoo10" <?= $pmgw == "qoo10" ? "SELECTED" : "" ?>><?= $lang["qoo10"] ?></option>
                </select>
            </td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
        <col width="20">
        <col>
        <col width="100">
        <?php
        if ($file_list)
        {
        ?>
        <tr class="header">
            <td height="20"></td>
            <td><a href="#"><?= $lang["file_in_ftp"] ?></a></td>
            <td></td>
        </tr>
        <?php
        foreach ($file_list AS $i => $file_name) {
            ?>
            <tr class="row<?= $i % 2 ?>">
                <td height="20"><?= ++$i ?></td>
                <td height="20" colspan=2><?= $file_name ?></td>

            </tr>
        <?php
        }
        ?>
    </table>
    <form name="fm" method="post">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
            <tr align="right" style="padding-right:8px;">
                <td>
                    <input type="submit" value="<?= $lang["process_all"] ?>">
                    <input type="hidden" name="posted" value="1">
                </td>
            </tr>
        </table>
    </form>
<?php
}
else {
    ?>
    <tr class="row<?= $i % 2 ?>">
        <td colspan="3">Please Choose the payment gateway</td>
    </tr>
</table>
    <?php
}
?>
    <?= $notice["js"] ?>
</div>
</body>
</html>