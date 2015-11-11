<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script language="javascript">
        <!--
        function lockqty(value) {
            if (value == 'O') {
                document.list.webqty.readOnly = true;
            }
            else {
                document.list.webqty.readOnly = false;
            }
        }

        function showHide(platform) {
            if (platform) {
                var target = 'prow_' + platform;
                var sign = 'sign_' + platform;
                var sp = 'sp_' + platform;
                var tobj = document.getElementById(target);
                var sobj = document.getElementById(sign);
                var spobj = document.getElementById(sp);
                if (tobj && sobj && spobj) {
                    if (tobj.style.display == 'block') {
                        tobj.style.display = 'none';
                        sobj.innerHTML = '+';
                        spobj.style.display = 'block';
                    }
                    else if (tobj.style.display == 'none') {
                        tobj.style.display = 'block';
                        sobj.innerHTML = '-';
                        spobj.style.display = 'none';
                    }
                    else {
                        return;
                    }
                }
            }
        }

        function selectAll(f) {
            var st = getEle(document.list, "input", "name", f);
            for (var key in st) {
                if (st[key].disabled == false) {
                    st[key].checked = true;
                }
                else {
                    st[key].checked = false;
                }
            }
        }

        function deSelectAll(f) {
            var st = getEle(document.list, "input", "name", f);
            for (var key in st) {
                if (st[key].disabled == false) {
                    st[key].checked = false;
                }
                else {
                    st[key].checked = false;
                }
            }
        }
        -->
    </script>
</head>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" class="frame_left">
<div id="main" style="width:auto">
    <?= $notice["img"] ?>
    <?php
    if ($value != "") {
        if ($canedit) {
            ?>
            <form name="list" action="<?= base_url() ?>marketing/product_identifier/view/<?= $prod_obj->get_sku() . ($this->input->get('target') == "" ? "" : "?target=" . $this->input->get('target')) ?>" method="POST" onSubmit="return CheckForm(this)">
            <input type="hidden" name="sku" value="<?= $value ?>">
            <input type="hidden" name="posted" value="1">
            <input type="hidden" name="formtype" value="<?= $action ?>">
            <input type="hidden" name="target" value="<?= $target ?>">
        <?php
        }
        ?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="60" align="left" style="padding-left:8px;">
                    <div style="float:left"><img
                            src='<?= get_image_file($prod_obj->get_image(), 's', $prod_obj->get_sku()) ?>'> &nbsp;</div>
                    <b style="font-size: 12px; color: rgb(0, 0, 0);"><?= $lang["header"] ?></b><br><?= $lang["header_message"] . " - " ?>
                    <b><a href="<?= $website_link . "mainproduct/view/" . $prod_obj->get_sku() ?>" target="_blank"><font
                                style="text-decoration:none; color:#000000; font-size:14px;"><?= $prod_obj->get_sku() . " - " . $prod_obj->get_name() ?><?= $prod_obj->get_clearance() ? " <span style='color:#0072E3; font-size:14px;'>(Clearance)</span>" : "" ?></font></a></b><br><?= $lang["master_sku"] . " " . $master_sku ?>
                </td>
            </tr>
            <tr>
                <td height="10" class="field">&nbsp;</td>
            </tr>

            <tr class="header">
                <td height="20" align="left" style="padding-left:8px;"><b
                        style="font-size:price_obj 12px; color: rgb(255, 255, 255);"><?= $lang["product_info"] ?></b>
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="tb_main">
                        <col width="15%">
                        <col width="23%">
                        <col width="23%">
                        <col width="23%">
                        <col width="10%">
                        <tr>
                            <td class="field"><b><?= $lang["country_name"] ?></b></td>
                            <td class="field"><b><?= $lang["ean"] ?></b></td>
                            <td class="field"><b><?= $lang["mpn"] ?></b></td>
                            <td class="field"><b><?= $lang["upc"] ?></b></td>
                            <td class="field" align="centre" style="text-align:center">
                                <b><?= $lang["status"] ?></b><br/><input type="button" value="Select"
                                                                         style="font-size:10px;width:40%"
                                                                         onClick="javascript:selectAll('status')">&nbsp;<input
                                    type="button" value="Deselect" style="font-size:10px;width:50%"
                                    onclick="deSelectAll('status')"></td>
                        </tr>
                        <?php
                        if ($country_list) {
                            $i = 0;
                            foreach ($country_list as $country_obj) {
                                $curr_country_id = $country_obj->get_id();
                                if ($product_identifier = $product_identifier_list[$curr_country_id]) {
                                    $ean = $product_identifier->get_ean();
                                    $mpn = $product_identifier->get_mpn();
                                    $upc = $product_identifier->get_upc();
                                    $status = $product_identifier->get_status();
                                } else {
                                    $ean = $mpn = $upc = "";
                                    $status = 1;
                                }
                                ?>
                                <tr>
                                    <td class="row<?= $i % 2 ?>"><?= $country_obj->get_name() ?></td>
                                    <td class="row<?= $i % 2 ?>"><input type="text" class="input"
                                                                        name="ean[<?= $curr_country_id ?>]"
                                                                        value="<?= $ean ?>"></td>
                                    <td class="row<?= $i % 2 ?>"><input type="text" class="input"
                                                                        name="mpn[<?= $curr_country_id ?>]"
                                                                        value="<?= $mpn ?>"></td>
                                    <td class="row<?= $i % 2 ?>"><input type="text" class="input"
                                                                        name="upc[<?= $curr_country_id ?>]"
                                                                        value="<?= $upc ?>"></td>
                                    <td class="row<?= $i % 2 ?>" align="center"><input type="checkbox"
                                                                                       name="status[<?= $curr_country_id ?>]"
                                                                                       value="1" <?= $status ? "checked" : "" ?>><input
                                            type="hidden" name="country_id[<?= $curr_country_id ?>]"
                                            value="<?= $curr_country_id ?>"></td>
                                </tr>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </table>
                </td>
            </tr>
        </table>
        <?php
        if ($canedit) {
            ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
                <tr>
                    <td align="right" style="padding-right:8px;" height="30"><input type="button" value="Update"
                                                                                    class="button"
                                                                                    onClick="if(CheckForm(this.form)) this.form.submit();">
                    </td>
                </tr>
            </table>
            </form>
        <?php
        }
    }
    ?>
</div>
<?= $notice["js"] ?>
<?php

if ($prompt_notice) {
    ?>
    <script language="javascript">alert('<?=$lang["update_notice"]?>')</script><?php
}
?>
<script language="javascript">
    if (document.list) {
        lockqty(document.list.status.value);
    }
</script>
</body>
</html>