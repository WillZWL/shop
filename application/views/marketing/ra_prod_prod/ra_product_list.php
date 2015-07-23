<html>
<head>
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
</head>
<body>
<script>
    function highlight_div(checkbox_node) {
        label_node = checkbox_node.parentNode;

        if (checkbox_node.checked) {
            label_node.style.backgroundColor = '#0a246a';
            label_node.style.color = '#fff';
        }
        else {
            label_node.style.backgroundColor = '#fff';
            label_node.style.color = '#000';
        }
    }
</script>
<div id="main">
    <table width="100%" cellpadding="0" cellspacing="0" class="tb_main">
        <?php
        if ($message != "")
        {
        ?>
        <tr>
            <td class="field" align="centre"><?= $lang["message"] ?></td>
        </tr>
    </table>
    <?php
    }
    else {
        ?>
        <?php
        if ($canedit) {
            ?>
            <form action="<?= base_url() . "marketing/ra_prod_prod/update/" ?>" method="POST">
        <?php
        }
        ?>
        <table width="100%" cellpadding="2" cellspacing="0" class="tb_pad">
            <?php
            for ($i = 1; $i < 9; $i++) {
                $func = "get_rcm_ss_cat_id_" . $i;
                $this_sscat = $ra_obj->$func();
                if ($this_sscat != "") {
                    ?>
                    <tr>
                        <td width="140" align="right" class="field"
                            style="padding-right:8px;"><?= $lang["ra_product"] ?><?= $i ?></td>
                        <td align="left" class="value" width="900">
                            <div class="div_select">
                                <div class="div_select2">

                                    <?php
                                    $this_item_obj = $scat_arr[$this_sscat];
                                    $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
                                    if (count($this_item_obj) && $this_item_obj) {
                                        $ele = 0;
                                        foreach ($this_item_obj as $pobj) {
                                            $gfunc = "get_rcm_prod_id_" . $i;
                                            $price = $pobj->get_price() * 1;
                                            $currency = $pobj->get_platform_currency_id();
                                            ?>
                                            <label for='rprod<?= $i ?>_<?= $ele ?>' class="div_select_lable"><input
                                                    name="rprod<?= $i ?>[]" value='<?= $pobj->get_sku() ?>'
                                                    type='checkbox' id='rprod<?= $i ?>_<?= $ele ?>'
                                                    onclick='highlight_div(this);'><span
                                                    style="width:480px;">[<?= $pobj->get_sku() ?>
                                                    ] <?= $pobj->get_prod_name() ?></span><span
                                                    style="width:100px"><?= $lang["status"] ?>
                                                    : <?= $ar_ws_status[$pobj->get_website_status()] ?></span><span
                                                    style="width:120px"><?= $lang["profit"] ?>
                                                    : <?= $currency ?> <?= number_format($pobj->get_profit(), 2) ?></span><span
                                                    style="width:100px"><?= $lang["margin"] ?>
                                                    : <?= $currency ?> <?= number_format($pobj->get_margin(), 2) ?></span></label>
                                        <?php
                                        if ($ra_prods[$pobj->get_sku()])
                                        {
                                        ?>
                                            <script>document.getElementById('rprod<?=$i?>_<?=$ele?>').checked = true;
                                                highlight_div(document.getElementById('rprod<?=$i?>_<?=$ele?>'));</script>
                                            <?php
                                            $got = 1;
                                        }
                                            $ele++;
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                        <td id="status_<?= $i ?>" class="value" width="100" nowrap style="white-space: nowrap">
                            &nbsp;</td>
                        <td id="profit_<?= $i ?>" class="value" width="100" nowrap style="white-space: nowrap">
                            &nbsp;</td>
                        <td id="margin_<?= $i ?>" class="value">&nbsp;</td>
                    </tr>

                <?php
                }
            }
            ?>
        </table>
        <?php
        if ($canedit) {
            ?>
            <!--
<table border="0" cellpadding="0" cellspacing="0" height="40" bgcolor="#BBBBFF" width="100%">
<tr>
    <td align="right" style="padding-right:8px"><input type="submit" name="submit" value="<?= $lang["update_record"] ?>" style="font-size:11px"></td>
</tr>
</table>
-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
                <tr>
                    <td colspan="2" height="40"></td>
                    <td colspan="2" align="right" style="padding-right:8px;">
                        <input type="submit" value="<?= $lang["update_record"] ?>">
                    </td>
                </tr>
            </table>
            <input type="hidden" name="type" value="<?= $type ?>">
            <input type="hidden" name="sku" value="<?= $this->input->get("sku") ?>">
            <input type="hidden" name="sscat" value="<?= $this->input->get("sscat") ?>">
            <input type="hidden" name="got_ra" value="<?= $got ?>">
            <input type="hidden" name="posted" value="1">
            </form>
        <?php
        }
    }
    ?>
    <div>
        <script>
            if (parent.frames["bundle"]) {
                parent.frames["bundle"].window.location.href = "<?=base_url()?>marketing/bundle/add/<?=$this->input->get("sku")?>/?" + Math.random();
            }
        </script>
</body>
</html>