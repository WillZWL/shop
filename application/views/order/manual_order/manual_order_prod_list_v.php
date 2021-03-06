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
    <?= $notice["img"] ?>
    <?php
    $ar_status = ["I" => "instock4.gif", "O" => "outofstock4.gif", "P" => "preorder4.gif", "A" => "1-3days4.gif"];
    $ar_ws_status = ["I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]];
    ?>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="40">
            <col width="60">
            <col>
            <col width="150">
            <col width="150">
            <col width="150">
            <col width="100">
            <col width="50">
            <col width="70">
            <col width="26">
            <tr class="header">
                <td height="20">
                    <?php if ($prod_grp_cd == "") : ?>
                        <img src="<?= base_url() ?>images/expand.png" class="pointer" onClick="Expand(document.getElementById('tr_search'));">
                    <?php endif; ?>
                </td>
                <td></td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'sku', '<?= $xsort["sku"] ?>')">
                        <?= $lang["sku"] ?> <?= $sortimg["sku"] ?>
                    </a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')">
                        <?= $lang["product_name"] ?> <?= $sortimg["name"] ?>
                    </a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'category', '<?= $xsort["category"] ?>')">
                        <?= $lang["category"] ?> <?= $sortimg["category"] ?>
                    </a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'sub_category', '<?= $xsort["sub_category"] ?>')">
                        <?= $lang["sub_cat"] ?> <?= $sortimg["sub_category"] ?>
                    </a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'sub_sub_category', '<?= $xsort["sub_sub_category"] ?>')">
                        <?= $lang["sub_sub_cat"] ?> <?= $sortimg["sub_sub_category"] ?>
                    </a>
                </td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'brand_name', '<?= $xsort["brand_name"] ?>')">
                        <?= $lang["brand"] ?> <?= $sortimg["brand_name"] ?>
                    </a>
                </td>
                <td><?= $lang["price"] ?></td>
                <td>
                    <a href="#" onClick="SortCol(document.fm, 'website_status', '<?= $xsort["website_status"] ?>')">
                        <?= $lang["status"] ?> <?= $sortimg["website_status"] ?>
                    </a>
                </td>
                <td></td>
            </tr>
            <?php if ($prod_grp_cd == "") : ?>
                <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                    <td></td>
                    <td></td>
                    <td><input name="sku" class="input" value="<?= htmlspecialchars($this->input->get("sku")) ?>"></td>
                    <td><input name="name" class="input" value="<?= htmlspecialchars($this->input->get("name")) ?>">
                    </td>
                    <td>
                        <select name="cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_cat_id)">
                            <option value="">
                        </select>
                    </td>
                    <td>
                        <select name="sub_cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)">
                            <option value="">
                        </select>
                    </td>
                    <td>
                        <select name="sub_sub_cat_id" class="input">
                            <option value="">
                        </select>
                    </td>
                    <td>
                        <select name="brand_id">
                            <option value="">
                        </select>
                    </td>
                    <td></td>
                    <td>
                        <select name="website_status" class="input">
                            <option value="">
                            <?php
                            $selected_wss[$this->input->get("website_status")] = "SELECTED";
                            foreach ($ar_ws_status as $rskey => $rsvalue) :
                            ?>
                            <option value="<?= $rskey ?>" <?= $selected_wss[$rskey] ?>><?= $rsvalue ?>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </td>
                    <td align="center">
                        <input type="submit" name="searchsubmit" value="" class="search_button" style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">
                    </td>
                </tr>
            <?php endif; ?>
            <?php
            $i = 0;
            if ($objlist) :
                foreach ($objlist as $obj) :
                    ?>

                    <tr class="row<?= $i % 2 ?>" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')">
                        <td height="20"></td>
                        <td><img src="<?= get_image_file($obj->getImage(), 's', $obj->getSku()) ?>"></td>
                        <td><?= $obj->getSku() ?></td>
                        <td><?= $obj->getName() ?></td>
                        <td><?= $obj->getCategory() ?></td>
                        <td><?= $obj->getSubCategory() ?></td>
                        <td><?= $obj->getSubSubCategory() ?></td>
                        <td><?= $obj->getBrandName() ?></td>
                        <?php $wsstatus = ($obj->getWebsiteStatus() == "I" && $obj->getWebsiteQuantity() < 1) ? "O" : $obj->getWebsiteStatus()?>
                        <td><?= number_format($curprice = $obj->getPrice(), 2, ".", "") ?></td>
                        <td><img src="/images/<?= $ar_status[$wsstatus] ?>"></td>
                        <td>
                            <input type="button" value="<?= $lang["select"] ?>"
                                   onClick="window.parent.additem('<?= obj_to_query($obj) ?>', <?= $line ?>, '<?= $curprice ?>');window.parent.calcTotal();parent.document.getElementById('lbClose').onclick()">
                        </td>
                    </tr>
                    <?php
                    $i++;
                endforeach;
            endif;
            ?>
        </table>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        <input type="hidden" name="search" value='1'>
    </form>
    <?= $links ?>
    <?= $notice["js"] ?>
</div>
<script>
    InitBrand(document.fm.brand_id);
    document.fm.brand_id.value = '<?=$this->input->get("brand_id")?>';
    ChangeCat('0', document.fm.cat_id);
    document.fm.cat_id.value = '<?=$this->input->get("cat_id")?>';
    ChangeCat('<?=$this->input->get("cat_id")?>', document.fm.sub_category_id);
    document.fm.sub_category_id.value = '<?=$this->input->get("sub_category_id")?>';
    ChangeCat('<?=$this->input->get("sub_category_id")?>', document.fm.sub_sub_category_id);
    document.fm.sub_sub_category_id.value = '<?=$this->input->get("sub_sub_category_id")?>';
</script>
</body>
</html>