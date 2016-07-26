<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>supply/supplier_helper/js_supplist"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/profitVarHelper/jsPlatformlist"></script>
    <!--<script type="text/javascript" src="<?= base_url() ?>mastercfg/region_helper/js_sourcing_region"></script>-->
    <script type="text/javascript"
            src="<?= base_url() ?>mastercfg/exchangeRateHelper/js_xratelist/<?= $default_curr ?>"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/freightHelper/js_freight_cat"></script>
    <script type="text/javascript" language="javascript" src="<?= base_url() ?>js/lytebox.js"></script>
    <script>
        ar_creditor = ["<?=$lang["no"]?>", "<?=$lang["yes"]?>"];
    </script>
</head>
<body style="width:auto;">
<?php //var_dump($lang); ?>
<div id="main" style="width:auto;">
    <?= $notice["img"] ?>
    <?php
    $ars_status = [
            "A" => $lang["available"],
            "C" => $lang["stock_constraint"],
            "O" => $lang["temp_out_of_stock"],
            "L" => $lang["last_lot"],
            "D" => $lang["discontinued"],
            "P" => $lang["pre-order"]
        ];
    ?>
    <?php  $ar_src_status = [
            "A" => $lang["available"],
            "C" => $lang["stock_constraint"],
            "O" => $lang["temp_out_of_stock"],
            "L" => $lang["last_lot"],
            "D" => $lang["discontinued"],
            "P" => $lang["pre-order"]
        ];
    ?>
    <table class="page_header" border="0" cellpadding="0" cellspacing="0" height="70" width="100%">
        <tr>
            <td height="70" style="padding-left:8px">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td height="70" style="padding-left:8px;line-height:17px;">
                            <div style="float:left"><img
                                    src='<?= get_image_file($prod["low_profit"]->getImage(), 's', $prod["low_profit"]->getSku()) ?>'>
                                &nbsp;</div>
                            <b style="font-size:14px"><?= $prod["low_profit"]->getProdName() ?><?= $prod["low_profit"]->getClearance() ? " <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>" : "" ?></b>
                            <br>
                            <?= $lang["sku"] ?>: <b><?= $prod["low_profit"]->getSku() ?></b> &nbsp; &nbsp; &nbsp;
                            <?= $lang["master_sku"] ?>: <b><?= $master_sku ?></b> &nbsp; &nbsp; &nbsp;
                            <?= $lang["freight_category"] ?>: <b>
                                <?php
                                if ($prod["low_profit"]->getFreightCatId()) {
                                    ?>
                                    <script>w(fcatlist[<?=$prod["low_profit"]->getFreightCatId()?>][0])</script>
                                <?php
                                }
                                ?>
                            </b>
                            <br>
                            <?= $lang["ean"] ?>: <b><?= $prod["low_profit"]->getEan() ?></b> &nbsp; &nbsp; &nbsp;
                            <?= $lang["mpn"] ?>: <b><?= $prod["low_profit"]->getMpn() ?></b> &nbsp; &nbsp; &nbsp;
                            <?= $lang["upc"] ?>: <b><?= $prod["low_profit"]->getUpc() ?></b>
                            <br>
                            <?php
                            if ($prod["low_profit"]->getPrice()) {
                                ?>
                                <?= $lang["selling_price"] ?>: <b><?= $default_curr ?>
                                    <script>w((exc('<?=$prod["low_profit"]->getPlatformCurrencyId()?>', '<?=$default_curr?>', <?=$prod["low_profit"]->getPrice()?>) * 1).toFixed(2))</script>
                                </b> &nbsp; &nbsp; &nbsp;
                                <?= $lang["profit_margin"] ?>: <b id="b_margin"><?= $prod["low_profit"]->getMargin() ?>
                                    %</b> <span
                                    class="remark">(<?= $lang["lowest_profit_base_on"] ?> <?= $lang["platform"] ?>: <script>w(platformlist['<?=$prod["low_profit"]->getPlatformId()?>'])</script> &nbsp;&nbsp;)</span>
                            <?php
                            } else {
                                ?>
                                <span class="warn"><b><?= $lang["selling_price_not_set"] ?></b></span>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php
    if (method_exists($brand, "getRegions") && $brand->getRegions()) {
        ?>
        <table border="0" cellpadding="2" cellspacing="0" width="100%">
            <tr class="marked warn">
                <td style="padding-left:8px;"><?= $lang["restricted_region"] ?>: <?= $brand->getRegions() ?></td>
            </tr>
        </table>
    <?php
    }
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
        <form name="fm_search" method="get">
            <tr class="header" valign="top">
                <td style="line-height:14px;"><?= $lang["last_update"] ?></td>
                <td width="40"></td>
                <td><?= $lang["supplier_name"] ?></td>
                <td style="line-height:14px;"><?= $lang["origin_country"] ?></td>
                <td><?= $lang["creditor"] ?></td>
                <td><?= $lang["status"] ?></td>
                <td width="50"><?= $lang["moq"] ?></td>
                <td width="85"><?= $lang["freight_cost"] ?></td>
                <td width="85"><?= $lang["local_cost"] ?></td>
                <td width="85"><?= $lang["total_cost"] ?></td>
                <td width="20"></td>
            </tr>
            <input type="hidden" name="freight_region" value="">
        </form>
        <form name="fm" method="post" onSubmit="return CheckForm(this)">
            <input type="hidden" name="order_default">
            <input type="hidden" name="region_default">
            <?php
            $i = 0;
            if ($purchaser) {
                foreach ($purchaser as $obj) {
                    $tc_name = "sp[{$obj->getSupplierId()}][total_cost]";
                if ($obj->getOrderDefault())
                {
                    ?>
                    <script>document.fm.order_default.value =<?=$obj->getSupplierId()?>;</script>
                <?php
                }

                if ($this->input->get("sourcing_reg") == 0 || $obj->getSourcingReg() == $this->input->get("sourcing_reg"))
                {
                ?>
                    <tr class="row<?= $i % 2 ?>" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')">
                        <td style="line-height:14px;"><?= substr($obj->getModifyOn(), 0, 10) ?></td>
                        <td>
                            <?php
                            if ($obj->getOrderDefault()) {
                                ?>
                                <span class="block">
                <?php
                if ($this->input->get("sourcing_reg") != 0 && $obj->getRegionDefault() != $this->input->get("sourcing_reg")) {
                    ?>
                    <input name="o_default" type="radio" value="<?= $obj->getSupplierId() ?>"
                           onClick="ChangeDefault(this);">
                <?php
                }
                ?>
                </span>
                                <img src='/images/tick.gif'>
                            <?php
                            }
                            else
                            {
                                ?>
                                <span class="block">
                <!--<input name="o_default" type="radio" value="<?= $obj->getSupplierId() ?>" onClick="ChangeDefault(this);">-->
                </span>
                            <?php
                            }
                            ?>
                        </td>
                        <td style="line-height:14px;"><a
                                href="<?= base_url() ?>supply/supplier/view/<?= $obj->getSupplierId() ?>/1"
                                rel="lyteframe[supplier_note]" rev="width: 800px; height: 275px; scrolling: auto;"
                                title="<?= $lang["supplier_note"] ?> - <?= $obj->getSupplierName() ?>"><?= $obj->getSupplierName() ?></a>
                        </td>
                        <td nowrap style='white-space: nowrap'><?= $obj->getOriginCountry() ?></td>
                        <td>
                            <script>w(ar_creditor[supplist[<?=$obj->getSupplierId()?>][3]])</script>
                        </td>
                        <td>
                            <?php
                            $ss_selected = array();
                            $ss_selected[$obj->getSupplierStatus()] = "SELECTED";
                            ?>
                            <select name="sp[<?= $obj->getSupplierId() ?>][supplier_status]"
                                    onChange="if (ChkChg(this, '<?= $obj->getSupplierStatus() ?>')) this.form.elements['check[<?= $obj->getSupplierId() ?>]'].checked=true; else this.form.elements['check[<?= $obj->getSupplierId() ?>]'].checked=false;">
                                <option value="A" <?= $ss_selected["A"] ?>><?= $ars_status["A"] ?>
                                <option value="C" <?= $ss_selected["C"] ?>><?= $ars_status["C"] ?>
                                <option value="O" <?= $ss_selected["O"] ?>><?= $ars_status["O"] ?>
                                <option value="L" <?= $ss_selected["L"] ?>><?= $ars_status["L"] ?>
                                <option value="D" <?= $ss_selected["D"] ?>><?= $ars_status["D"] ?>
                                <option value="P" <?= $ss_selected["P"] ?>><?= $ars_status["P"] ?>
                            </select>
                        </td>
                        <td><input class="input" name="sp[<?= $obj->getSupplierId() ?>][moq]"
                                   value="<?= $obj->getMoq() ?>" notEmpty isInteger min="0"
                                   onKeyUp="if (ChkChg(this, <?= $obj->getMoq() ?>)) this.form.elements['check[<?= $obj->getSupplierId() ?>]'].checked=true; else this.form.elements['check[<?= $obj->getSupplierId() ?>]'].checked=false;">
                        </td>
                        <td>
                            <?php
                            if ($prod["low_profit"]->getFreightCost()) {
                                ?>
                                <?= $default_curr ?>
                                <script>w(exc('<?=$prod["low_profit"]->getPlatformCurrencyId()?>', '<?=$default_curr?>', <?=$prod["low_profit"]->getFreightCost()?>))</script>
                            <?php
                            }
                            ?>
                        </td>
                        <td><?= $obj->getCurrencyId() ?>
                            <input class="int_input"
                                name="sp[<?= $obj->getSupplierId() ?>][cost]"
                                value="<?= $obj->getCost() ?>"
                                onKeyUp="if (ChkChg(this, <?= $obj->getCost() ?>)){ this.form.elements['sp[<?= $obj->getSupplierId() ?>][supplier_status]'].value='A'; this.form.elements['check[<?= $obj->getSupplierId() ?>]'].checked=true;} else{ this.form.elements['sp[<?= $obj->getSupplierId() ?>][supplier_status]'].value='<?= $obj->getSupplierStatus() ?>'; this.form.elements['check[<?= $obj->getSupplierId() ?>]'].checked=false} exc('<?= $obj->getCurrencyId() ?>', '<?= $default_curr ?>', this.value, document.fm.elements['<?= $tc_name ?>']);
                                    <?php
                                        if ($obj->getOrderDefault()) {
                                    ?>
                                    CalcProfit(this.value)
                                    <?php
                                        }
                                    ?>" notEmpty isNumber min="0">
                        </td>
                        <td><?= $default_curr ?> <input name="<?= $tc_name ?>" class="int_input read"
                                                        style="text-align:left;"
                                                        value="<?= number_format($obj->getTotalCost(), 2, ".", "") ?>"
                                                        READONLY></td>
                        <td>
                            <input type="checkbox" name="check[<?= $obj->getSupplierId() ?>]"
                                   value="<?= $obj->getSupplierId() ?>" onClick='Marked(this);'>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                }
            }
            ?>
            <tr>
                <td colspan="11" align="right" class="bg_row" style="padding-right:8px">
                    <input type="submit" value="<?= $lang["update_button"] ?>" class="button"> &nbsp; &nbsp; &nbsp;
                </td>
            </tr>
    </table>
    <input type="hidden" name="sku" value='<?= $sku ?>'>
    <input type="hidden" name="sourcing_reg" value='<?= $this->input->get("sourcing_reg") ?>'>
    <input type="hidden" name="cmd" value='edit'>
    <input type="hidden" name="posted" value='1'>
    </form>
    <script>
        function ChangeDefault(el) {
            if (confirm("<?=$lang["change_default"]?>")) {
                el.form.<?=$this->input->get("sourcing_reg")?"region_default":"order_default"?>.value = el.value;
                el.form.submit();
            }
            else {
                el.checked = false;
            }
        }
    </script>
</div>
<?= $notice["js"] ?>
</body>
</html>