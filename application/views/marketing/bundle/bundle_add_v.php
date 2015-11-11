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
    <?= $notice["img"] ?>
    <form name="fm" method="post" onSubmit="return CheckComponents(this);" enctype="multipart/form-data">
        <input type="hidden" name="brand_id" value="<?= $product->get_brand_id() ?>">
        <input type="hidden" name="cat_id" value="<?= $product->get_cat_id() ?>">
        <input type="hidden" name="sub_cat_id" value="<?= $product->get_sub_cat_id() ?>">
        <input type="hidden" name="sub_sub_cat_id" value="<?= $product->get_sub_sub_cat_id() ?>">
        <input type="hidden" name="freight_cat_id" value="<?= $product->get_freight_cat_id() ?>">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
            <tr>
                <td class="field" width="142"><?= $lang["bundle_name"] ?></td>
                <td class="value"><input name="name" class="input read"
                                         value="<?= htmlspecialchars($product->get_name()) ?>" notEmpty READONLY></td>
            </tr>
            <tr>
                <td class="field" valign="top" width="142"><?= $lang["bundle_components"] ?></td>
                <td class="value">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
                        <?= $ra_prod ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="field" width="142"><?= $lang["discount"] ?></td>
                <td class="value"><input name="discount" class="int_input"
                                         value="<?= htmlspecialchars($product->get_discount()) ?>" min="0" isNumber> %
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_detail">
            <tr>
                <td colspan="2" height="40"></td>
                <td colspan="2" align="right" style="padding-right:8px;">
                    <input type="submit" value="<?= $lang['add_button'] ?>">
                </td>
            </tr>
        </table>
        <input name="comp_sku" type="hidden" value="">
        <input name="sku" type="hidden" value="<?= $product->get_sku() ?>">
        <input name="version_id" type="hidden" value="<?= $product->get_version_id() ?>">
        <input name="colour_id" type="hidden" value="<?= $product->get_colour_id() ?>">
        <input type="hidden" name="posted" value="1">
    </form>
    <?= $notice["js"] ?>
</div>
<script>
    function ChangeName() {
        f = document.fm;
        eles = getEle(f, "input", "name", "components");
        el_name = new Array();
        for (i = 0; i < eles.length; i++) {
            ele = eles[i];
            if (ele.checked) {
                ar_el = ele.value.split("::");
                el_name[el_name.length] = ar_el[1];
            }
        }
        f.name.value = el_name.join(" + ");
    }

    function CheckComponents(f) {
        eles = getEle(f, "input", "name", "components");
        el_sku = new Array();
        count = 0;
        for (i = 0; i < eles.length; i++) {
            ele = eles[i];
            if (ele.checked) {
                ar_el = ele.value.split("::");
                el_sku[el_sku.length] = ar_el[0];
                count++;
            }
        }
        f.comp_sku.value = el_sku.join(",");

        if (count > 1) {
            return CheckForm(f);
        }
        else {
            alert("<?=$lang["only_main_product"]?>");
            return false;
        }
    }
    <?php
        if ($this->input->post("components"))
        {
            foreach ($this->input->post("components") as $prod_order=>$prod)
            {
    ?>
    document.fm.elements["components[<?=$prod_order?>]"].checked = true;
    <?php
            }
    ?>
    ChangeName();
    <?php
        }
    ?>
</script>
</body>
</html>