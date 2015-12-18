<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/category/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/brand/js_brandlist"></script>
</head>
<body style="width:auto">
<div style="width:auto">
    <form name="fm_cart" method="post" onSubmit="return CheckForm(this)">
        <table width="100%" cellspacing="0" cellpadding="4" class="tb_list">
            <tr class="header">
                <td><?= $lang["basket"] ?></td>
            </tr>

            <tr style="font-weight:bold;background:#DDDDDD">
            </tr>
            <?php
            $totalcart = count($cart);
            for ($i = 0; $i < $totalcart; $i++) {

                ?>
                <tr class="row<?= $i % 2 ?>">
                    <td align="left" style="border-left:1px solid #BBBBBB;padding-right:4px;"><?= $cart[$i]["name"] ?>
                        <br>

                        <div style="float:left"><?= $default_curr ?> <?= number_format($cart[$i]["price"], 2) ?><br>
                        </div>
                        <div style="float:right">
                            <?php
                            if ($cart[$i]["promo"]) {
                                ?>
                                <input value="<?= $cart[$i]["qty"] ?>" size="2" class="read"> &nbsp; &nbsp; &nbsp;
                            <?php
                            } else {
                                ?>
                                <input name="qty[<?= $cart[$i]["sku"] ?>]" value="<?= $cart[$i]["qty"] ?>" size="2"
                                       style="text-align:right" dname="Qty" isNatural>
                                <a href="javascript:remove('qty[<?= $cart[$i]["sku"] ?>]');"><?= $lang["remove"] ?></a>
                            <?php
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php
            }

            if ($totalcart)
            {
            /*
                        foreach ($dc as $rskey=>$rsvalue)
                        {
                            $courier[] = $rskey;
                        }
                        $cur_delivery = $cur_courier?$cur_courier:$courier[0];
            */
            ?>
            <tr>
                <td align="right" style="padding-right:4px;">
                    <?= $lang["cost_of_items"] ?>: <?= $default_curr ?>  <?= number_format($total, 2) ?><br>
                    <?= $lang["delivery"] ?>
                    : <?= $default_curr ?>  <?= number_format($dc[$dc_default["courier"]]["charge"], 2) ?><br>
                    <?php
                    #SBF #2978 offline_fee cannot be negative if margin < 7%
                    if ($cart_profit_margin >= 7) {
                        ?>
                        <?= $lang["offline_fee"] ?>: <?= $default_curr ?>  <input name="offline_fee" id="offline_fee"
                                                                                  size="2"
                                                                                  value="<?= $offline_fee ? $offline_fee : $this->input->post("offline_fee") ?>"
                                                                                  isNumber><br>
                    <?php
                    } else {
                        ?>
                        <?= $lang["offline_fee"] ?>: <?= $default_curr ?>  <input name="offline_fee" id="offline_fee"
                                                                                  size="2"
                                                                                  value="<?= $offline_fee ? $offline_fee : $this->input->post("offline_fee") ?>"
                                                                                  onchange="checkNegative()"
                                                                                  onkeyup="checkNegative()" isNumber>
                        <br>
                    <?php
                    }
                    ?>

                    <?= $lang["vat_exempt"] ?> &nbsp;&nbsp;&nbsp; <input name="vat_exempt" type="checkbox"
                                                                         value="1" <?= $this->input->post("vat_exempt") ? "CHECKED" : "" ?>>
                    <br>
                    <?= $lang["promotion_code"] ?>: <input name="promotion_code"
                                                           value="<?= $_SESSION["promotion_code"] ?>" size="12"><br>
                    <?php
                    if ($allow_see_margin) {
                        #SBF #2799 temp only allow cs_man to see cart_profit_margin
                        echo "{$lang["cart_profit_margin"]}: " . number_format($cart_profit_margin, 2) . "%";
                    }
                    ?>
                    <br><b><?= $lang["total"] ?>
                        : <?= $default_curr ?>  <?= number_format($total_cart_price, 2) ?></b><br>

                    <?php
                    if (isset($promo)) {
                        if (!$promo["valid"] || $promo["error"]) {
                            $display_color = "red";
                            $display_msg = ($promo["valid"] && $promo["error"] == "FI") ? $lang["free_item_outstock_inactive"] : $lang["promotion_code_invalid"];
                        } else {
                            $display_color = "green";
                            $display_msg = $lang["promotion_code_accepted"];
                        }
                        ?>
                        <span style="color:<?= $display_color ?>"><?= $display_msg ?></span><br>
                    <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
        <input type="submit" value="<?= $lang["update_cart"] ?>" onClick="this.form.reload.value=1"><br><br>
        <input type="button" value="<?= $lang["take_order"] ?>" onClick="take_order();">
        <?php
        }
        ?>
        <input type="hidden" name="posted" value="1">
        <input type="hidden" name="add" value="">
        <input type="hidden" name="clientid" value="<?= $this->input->post("clientid") ?>">
        <input type="hidden" name="country" value="<?= $this->input->post("country") ?>">
        <input type="hidden" name="reload" value="0">
        <input type="hidden" name="cart_profit_margin" value="<?= number_format($cart_profit_margin, 2) ?>">
        <input type="hidden" name="took" value="<?= $this->input->post("took") ?>">
    </form>
</div>
<script>
    function remove(el) {
        document.fm_cart.elements[el].value = 0;
        document.fm_cart.reload.value = 1;
        document.fm_cart.submit();
    }

    function take_order() {
        document.fm_cart.took.value = 1;
        document.fm_cart.action = '<?=base_url()?>order/phone_sales/take_order/<?=$platform_id?>';
        document.fm_cart.target = 'fprod'
        document.fm_cart.submit();
        document.fm_cart.action = '';
        document.fm_cart.target = ''
        document.fm_cart.submit();
        document.fm_cart.focus();
        document.fm_cart.promotion_code.focus();
    }

    function checkNegative() {
        var x = document.getElementById("offline_fee").value;
        if (x < 0) {
            alert("Offline fee cannot be negative for this cart's orders. Please amend.");
            document.fm_cart.elements['offline_fee'].value = '';
            document.fm_cart.reload.value = 1;
            document.fm_cart.submit();
        }
    }

    <?php
    if ($this->input->post("reload"))
    {
    ?>
    if (document.fm_cart.took.value == 1) {
        take_order();
    }
    <?php
    }
    ?>
    function changeCountry(country_id, client_id) {
        fm = document.fm_cart;
        fm.elements["country"].value = country_id;
        if (client_id != '') {
            fm.elements["clientid"].value = client_id;
        }
        take_order();
    }
</script>

</script>

</
body >
< / html >