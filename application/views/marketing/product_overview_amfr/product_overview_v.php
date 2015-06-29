<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/product_overview_amfr/js_overview"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/product/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/brand/js_brandlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>supply/supplier_helper/js_supplist"></script>
    <script language="javascript" type="text/javascript">
        needToConfirm = false;
        window.onbeforeunload = askConfirm;
        function askConfirm() {
            if (needToConfirm) {
                return "<?=$lang["usaved_changes"]?>";
            }
        }
    </script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <?php
    $ar_ws_status = array("I" => $lang["instock"], "O" => $lang["outstock"], "P" => $lang["pre-order"], "A" => $lang["arriving"]);
    $ar_src_status = array("A" => $lang["available"], "C" => $lang["stock_constraint"], "O" => $lang["temp_out_of_stock"], "L" => $lang["last_lot"], "D" => $lang["discontinued"]);
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td width="50" class="title"><img src='<?= base_url() ?>/images/<?= $lang["platform_country_image"] ?>'
                                              height="30"></td>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onClick="Redirect('<?= site_url('marketing/product_overview_amfr/') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <form name="fm" method="get" onSubmit="return CheckForm(this)">
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <col width="150">
            <col width="420">
            <col width="170">
            <col width="420">
            <col>
            <tr>
                <td style="padding-right:8px" align="right"><b><?= $lang["category"] ?></b></td>
                <td>
                    <select name="cat_id" class="input"
                            onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)">
                        <option value="">
                    </select>
                </td>
                <td style="padding-right:8px" align="right"><b><?= $lang["brand"] ?></b></td>
                <td>
                    <select name="brand_id" class="input">
                        <option value="">
                    </select>
                </td>
                <td rowspan="3" align="center"><input type="button" value="<?= $lang["cmd_search_button"] ?>"
                                                      onClick="if (CheckForm(this.form)) this.form.submit();"></td>
            </tr>
            <tr>
                <td style="padding-right:8px" align="right"><b><?= $lang["sub_cat"] ?></b></td>
                <td>
                    <select name="sub_cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)">
                        <option value="">
                    </select>
                </td>
                <td style="padding-right:8px" align="right"><b><?= $lang["supplier"] ?></b></td>
                <td>
                    <select name="supplier_id" class="input">
                        <option value="">
                    </select>
                </td>
            </tr>
            <tr>
                <td style="padding-right:8px" align="right"><b><?= $lang["sub_sub_cat"] ?></b></td>
                <td>
                    <select name="sub_sub_cat_id" class="input">
                        <option value="">
                    </select>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="60">
            <col>
            <col width="85">
            <col width="45">
            <col width="45">
            <col width="40">
            <col width="85">
            <col width="90">
            <col width="80">
            <col width="70">
            <col width="55">
            <col width="55">
            <col width="60">
            <col width="50">
            <col width="50">
            <col width="27">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer"
                         onClick="Expand(document.getElementById('tr_search'));">
                </td>
                <td title="<?= $lang["sku"] ?>"><a href="#"
                                                   onClick="SortCol(document.fm, 'sku', '<?= $xsort["sku"] ?>')"><?= $lang["sku"] ?> <?= $sortimg["sku"] ?></a>
                </td>
                <td title="<?= $lang["product_name"] ?>"><a href="#"
                                                            onClick="SortCol(document.fm, 'prod_name', '<?= $xsort["prod_name"] ?>')"><?= $lang["product_name"] ?> <?= $sortimg["prod_name"] ?></a>
                </td>
                <td title="<?= $lang["platform_code"] ?>"><a href="#"
                                                             onClick="SortCol(document.fm, 'platform_code', '<?= $xsort["platform_code"] ?>')"><?= $lang["platform_code"] ?> <?= $sortimg["platform_code"] ?></a>
                </td>
                <td title="<?= $lang["clearance"] ?>"><a href="#"
                                                         onClick="SortCol(document.fm, 'clearance', '<?= $xsort["clearance"] ?>')"><?= $lang["clearance"] ?> <?= $sortimg["clearance"] ?></a>
                </td>
                <td title="<?= $lang["listing_status"] ?>"><a href="#"
                                                              onClick="SortCol(document.fm, 'listing_status', '<?= $xsort["listing_status"] ?>')"><?= $lang["listing_status"] ?> <?= $sortimg["listing_status"] ?></a>
                </td>
                <td title="<?= $lang["inventory_quantity"] ?>"><a href="#"
                                                                  onClick="SortCol(document.fm, 'inventory', '<?= $xsort["inventory"] ?>')"><?= $lang["qty"] ?> <?= $sortimg["inventory"] ?></a>
                </td>
                <td title="<?= $lang["website_quantity"] ?>"><a href="#"
                                                                onClick="SortCol(document.fm, 'website_quantity', '<?= $xsort["ws_quantity"] ?>')"><?= $lang["ws_qty"] ?> <?= $sortimg["website_quantity"] ?></a>
                </td>
                <td title="<?= $lang["website_status"] ?>"><a href="#"
                                                              onClick="SortCol(document.fm, 'website_status', '<?= $xsort["ws_status"] ?>')"><?= $lang["ws_status"] ?> <?= $sortimg["website_status"] ?></a>
                </td>
                <td title="<?= $lang["sourcing_status"] ?>"><a href="#"
                                                               onClick="SortCol(document.fm, 'sourcing_status', '<?= $xsort["sourcing_status"] ?>')"><?= $lang["src_status"] ?> <?= $sortimg["sourcing_status"] ?></a>
                </td>
                <td title="<?= $lang["updated"] ?>"><a href="#"
                                                       onClick="SortCol(document.fm, 'purchaser_updated_date', '<?= $xsort["purchaser_updated_date"] ?>')"><?= $lang["updated"] ?> <?= $sortimg["purchaser_updated_date"] ?></a>
                </td>
                <td title="<?= $lang["shipptype_name"] ?>"><a href="#"
                                                              onClick="SortCol(document.fm, 'shiptype_name', '<?= $xsort["shiptype_name"] ?>')"><?= $lang["shiptype"] ?> <?= $sortimg["shiptype_name"] ?></a>
                </td>
                <td title="<?= $lang["cost"] ?>"><?= $lang["cost"] ?></td>
                <td title="<?= $lang["price"] ?>"><a href="#"
                                                     onClick="SortCol(document.fm, 'price', '<?= $xsort["price"] ?>')"><?= $lang["price"] ?> <?= $sortimg["price"] ?></a>
                </td>
                <td title="<?= $lang["profit"] ?>"><a href="#"
                                                      onClick="SortCol(document.fm, 'profit', '<?= $xsort["profit"] ?>')"><?= $lang["profit"] ?> <?= $sortimg["profit"] ?></a>
                </td>
                <td title="<?= $lang["margin"] ?>"><a href="#"
                                                      onClick="SortCol(document.fm, 'margin', '<?= $xsort["margin"] ?>')"><?= $lang["margin"] ?> <?= $sortimg["margin"] ?></a>
                </td>
                <td><input type="checkbox" name="chkall" value="1" onClick="checkall(document.fm_edit, this);"></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="sku" class="input" value="<?= htmlspecialchars($this->input->get("sku")) ?>"></td>
                <td><input name="prod_name" class="input"
                           value="<?= htmlspecialchars($this->input->get("prod_name")) ?>"></td>
                <td><input name="platform_code" class="input"
                           value="<?= htmlspecialchars($this->input->get("platform_code")) ?>"></td>
                <td>
                    <?php
                    if ($this->input->get("clearance") != "") {
                        $selected_cr[$this->input->get("clearance")] = "SELECTED";
                    }
                    ?>
                    <select name="clearance" class="input">
                        <option value="">
                        <option value="0"<?= $selected_cr[0] ?>><?= $lang["no"] ?>
                        <option value="1"<?= $selected_cr[1] ?>><?= $lang["yes"] ?>
                    </select>
                </td>
                <td>
                    <?php
                    if ($this->input->get("listing_status") != "") {
                        $selected_ls[$this->input->get("listing_status")] = "SELECTED";
                    }
                    ?>
                    <select name="listing_status" class="input">
                        <option value="">
                        <option value="L"<?= $selected_ls['L'] ?>><?= $lang["listed"] ?>
                        <option value="N"<?= $selected_ls['N'] ?>><?= $lang["not_listed"] ?>
                    </select>
                </td>
                <td><input name="inventory" class="input"
                           value="<?= htmlspecialchars($this->input->get("inventory")) ?>"></td>
                <td><input name="website_quantity" class="input"
                           value="<?= htmlspecialchars($this->input->get("website_quantity")) ?>"></td>
                <td>
                    <?php
                    if ($this->input->get("website_status") != "") {
                        $selected_ws[$this->input->get("website_status")] = "SELECTED";
                    }
                    ?>
                    <select name="website_status" class="input">
                        <option value="">
                            <?php
                            foreach ($ar_ws_status as $rskey => $rsvalue)
                            {
                            ?>
                        <option value="<?= $rskey ?>" <?= $selected_ws[$rskey] ?>><?= $rsvalue ?>
                            <?php
                            }
                            ?>
                    </select>
                </td>
                <td>
                    <?php
                    if ($this->input->get("sourcing_status") != "") {
                        $selected_src[$this->input->get("sourcing_status")] = "SELECTED";
                    }
                    ?>
                    <select name="sourcing_status" class="input">
                        <option value="">
                            <?php
                            foreach ($ar_src_status as $rskey => $rsvalue)
                            {
                            ?>
                        <option value="<?= $rskey ?>" <?= $selected_src[$rskey] ?>><?= $rsvalue ?>
                            <?php
                            }
                            ?>
                    </select>
                </td>
                <td><input name="purchaser_updated_date" class="input"
                           value="<?= htmlspecialchars($this->input->get("purchaser_updated_date")) ?>"></td>
                <td><input name="shiptype_name" class="input"
                           value="<?= htmlspecialchars($this->input->get("shiptype_name")) ?>"></td>
                <td></td>
                <td><input name="price" class="input" value="<?= htmlspecialchars($this->input->get("price")) ?>"></td>
                <td><input name="profit" class="input" value="<?= htmlspecialchars($this->input->get("profit")) ?>">
                </td>
                <td><input name="margin" class="input" value="<?= htmlspecialchars($this->input->get("margin")) ?>">
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
            <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
            <input type="hidden" name="search" value='1'>
    </form>
    <form name="fm_edit" method="post">
        <?= $objlist["tr"] ?>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top:5px;">
            <tr>
                <td align="right" style="padding-right:8px;">
                    <input type="button" value="<?= $lang['cmd_button'] ?>" class="button"
                           onClick="if (CheckProfit(this.form)){needToConfirm=false;this.form.submit()}">
                </td>
            </tr>
        </table>
        <input type="hidden" name="posted" value="1">
    </form>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
<?= $objlist["js"] ?>
<script>
    InitBrand(document.fm.brand_id);
    document.fm.brand_id.value = '<?=$this->input->get("brand_id")?>';
    InitSupp(document.fm.supplier_id);
    document.fm.supplier_id.value = '<?=$this->input->get("supplier_id")?>';
    ChangeCat('0', document.fm.cat_id);
    document.fm.cat_id.value = '<?=$this->input->get("cat_id")?>';
    ChangeCat('<?=$this->input->get("cat_id")?>', document.fm.sub_cat_id);
    document.fm.sub_cat_id.value = '<?=$this->input->get("sub_cat_id")?>';
    ChangeCat('<?=$this->input->get("sub_cat_id")?>', document.fm.sub_sub_cat_id);
    document.fm.sub_sub_cat_id.value = '<?=$this->input->get("sub_sub_cat_id")?>';

    function CheckProfit(f) {
        var margin = "";
        var margin_value = 0;
        if (needToConfirm) {
            check_eles = document.getElementsByName("check[]");
            count = false;
            for (i = 0; i < check_eles.length; i++) {
                check_ele = check_eles[i];
                if (check_ele.checked) {
                    sku = check_ele.value;
                    price_ele = f.elements["price[" + sku + "][price]"];
                    if (!CheckSetElements(check_ele.parentNode.parentNode, sku)) {
                        return false;
                    }
                    margin = document.getElementById("margin[" + sku + "]").innerHTML;
                    margin_value = margin.substring(0, (margin.length - 2)) * 1;
                    if (margin_value < 0 && !prod[sku]["clearance"])
                    //if (f.elements["cost["+ sku +"]"].value*1 > price_ele.value*1 && !prod[sku]["clearance"])
                    {
                        if (!confirm(sku + "<?=$lang["make_loss"]?>")) {
                            price_ele.focus();
                            return false;
                        }
                    }
                    count = true;
                }
            }
            return count;
        }
        return false;
    }
</script>
</body>
</html>