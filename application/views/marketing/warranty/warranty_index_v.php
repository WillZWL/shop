<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/category/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
    <?php
    $ar_status = array("inactive", "created", "listed");
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
            <td style="text-align:right;padding-right:33px;">
                <span>Select a default value: </span>
                <select style="width:135px;" onchange="setWarrantyDefault(this)">
                    <?php
                    foreach ($warranty_list as $value) {
                        ?>
                        <option value="<?= $value ?>"><?= $value ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>

    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="60">
            <col>
            <col width="80">
            <col width="150">
            <col width="150">
            <col width="150">
            <col width="100">
            <col width="200">
            <col width="26">
            <tr class="header">
                <td height="20">
                    <?php
                    if ($prod_grp_cd == "") {
                        ?>
                        <img src="<?= base_url() ?>images/expand.png" class="pointer"
                             onClick="Expand(document.getElementById('tr_search'));">
                    <?php
                    }
                    ?>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sku', '<?= $xsort["sku"] ?>')"><?= $lang["sku"] ?> <?= $sortimg["sku"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')"><?= $lang["product_name"] ?> <?= $sortimg["name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'category', '<?= $xsort["category"] ?>')"><?= $lang["category"] ?> <?= $sortimg["category"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sub_cat', '<?= $xsort["sub_cat"] ?>')"><?= $lang["sub_cat"] ?> <?= $sortimg["sub_cat"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sub_sub_cat', '<?= $xsort["sub_sub_cat"] ?>')"><?= $lang["sub_sub_cat"] ?> <?= $sortimg["sub_sub_cat"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'brand', '<?= $xsort["brand"] ?>')"><?= $lang["brand"] ?> <?= $sortimg["brand"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'status', '<?= $xsort["status"] ?>')"><?= $lang["status"] ?> <?= $sortimg["status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'warranty_in_month', '<?= $xsort["warranty_in_month"] ?>')"><?= $lang["Warranty_Given"] ?> <?= $sortimg["warranty_in_month"] ?></a>
                </td>
                <td align="center"><input type="checkbox" onclick="selectAll(this)"></td>
            </tr>
            <?php
            if ($prod_grp_cd == "") {
                ?>
                <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                    <td></td>
                    <td><input name="sku" class="input" value="<?= htmlspecialchars($this->input->get("sku")) ?>"></td>
                    <td><input name="name" class="input" value="<?= htmlspecialchars($this->input->get("name")) ?>">
                    </td>
                    <td><select name="cat_id" class="input"
                                onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)">
                            <option value="">
                        </select></td>
                    <td><select name="sub_cat_id" class="input"
                                onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)">
                            <option value="">
                        </select></td>
                    <td><select name="sub_sub_cat_id" class="input">
                            <option value="">
                        </select></td>
                    <td><input name="brand" class="input" value="<?= htmlspecialchars($this->input->get("brand")) ?>">
                    </td>
                    <td>
                        <?php
                        if ($this->input->get("status") != "") {
                            $selected[$this->input->get("status")] = "SELECTED";
                        }
                        ?>
                        <select name="status" class="input">
                            <option value="">
                            <option value="0" <?= $selected[0] ?>><?= $lang[$ar_status[0]] ?>
                            <option value="1" <?= $selected[1] ?>><?= $lang[$ar_status[1]] ?>
                            <option value="2" <?= $selected[2] ?>><?= $lang[$ar_status[2]] ?>
                        </select>
                    </td>
                    <td><select name="warranty_in_month" class="input">
                            <option></option>
                            <?php
                            foreach ($warranty_list as $value) {
                                ?>
                                <option
                                    value="<?= $value ?>" <?php echo ($this->input->get("warranty_in_month") === $value) ? "selected" : "" ?>><?= $value ?></option>
                            <?php
                            }
                            ?>
                        </select></td>
                    <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                              style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">
                    </td>
                </tr>
            <?php
            }
            ?>
            <?php
            $i = 0;
            if ($objlist) {
                foreach ($objlist as $obj) {
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                        </td>
                        <td><?= $obj->get_sku() ?></td>
                        <td><?= $obj->get_name() ?></td>
                        <td><?= $obj->get_category() ?></td>
                        <td><?= $obj->get_sub_cat() ?></td>
                        <td><?= $obj->get_sub_sub_cat() ?></td>
                        <td><?= $obj->get_brand() ?></td>
                        <td><?= $lang[$ar_status[$obj->get_status()]] ?></td>
                        <td>
                            <select class="warranty_value" name='warranty_value[<?= $obj->get_sku() ?>]'
                                    id="warranty_value[<?= $obj->get_sku() ?>]" style="width: 71%;">
                                <?php foreach ($warranty_list as $value) {
                                    ?>
                                    <option
                                        value="<?= $value ?>" <?php echo ($obj->get_warranty_in_month() == $value) ? "selected" : ""?> ><?= $value ?></option>

                                <?php
                                }?>
                            </select>
                            <img src="/images/add_sign.png" id="warranty_add_sign_btn[<?= $obj->get_sku() ?>]"
                                 class="add_sign_btn" style="cursor:pointer;">
                            <?php
                            // SBF 4402 warranty for different countries
                            $warranty_field_counter = 0;
                            $country_list = $this->product_model->get_list("country", array("status" => 1, "allow_sell" => 1), array("orderby" => "name ASC"));

                            $warranty_country_list = $this->warranty_model->get_country_warranty_list(array('sku' => $obj->get_sku()));

                            foreach ($warranty_country_list as $warranty_country_obj) {
                                echo '<p><select class="warranty_country" id="warranty_country_' . $warranty_field_counter . '[' . $obj->get_sku() . ']" name="warranty_country_' . $warranty_field_counter . '[' . $obj->get_sku() . ']">';
                                foreach ($selling_platform_list as $country_obj) {
                                    $platform_id = $country_obj->getSellingPlatformId();
                                    if ($platform_id == $warranty_country_obj->get_platform_id()) {
                                        $selected = "SELECTED";
                                    } else {
                                        $selected = "";
                                    }
                                    echo "<option value='" . $platform_id . "' " . $selected . ">" . $platform_id . "</option>";
                                }

                                echo "</select>";
                                echo '<select name="warranty_in_month_' . $warranty_field_counter . '[' . $obj->get_sku() . ']" notEmpty>';
                                echo "<option value=''>None</option>";
                                foreach ($warranty_list as $warranty_period) {
                                    if ($warranty_period == $warranty_country_obj->get_warranty_in_month())
                                        $selected = "SELECTED";
                                    else
                                        $selected = "";
                                    echo "<option value='" . $warranty_period . "'" . $selected . '>' . $warranty_period . "</option>";
                                }
                                echo '</select></p>';
                                $warranty_field_counter++;
                            }
                            ?>
                        </td>
                        <td><input type="checkbox" name='warranty_check[<?= $obj->get_sku() ?>]' value=1
                                   class="warranty_check"></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
            <tr>
                <td colspan="10" style="text-align:right;"><input type="submit" value="Update" name="update"></td>
            </tr>
        </table>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
        <input type="hidden" name="search" value="1">
    </form>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
<script language='javascript'>
    ChangeCat('0', document.fm.cat_id);
    document.fm.cat_id.value = '<?=$this->input->get("cat_id")?>';
    ChangeCat('<?=$this->input->get("cat_id")?>', document.fm.sub_cat_id);
    document.fm.sub_cat_id.value = '<?=$this->input->get("sub_cat_id")?>';
    ChangeCat('<?=$this->input->get("sub_cat_id")?>', document.fm.sub_sub_cat_id);
    document.fm.sub_sub_cat_id.value = '<?=$this->input->get("sub_sub_cat_id")?>';

    function selectAll(e) {
        if (e.checked == true) {
            var checkboxes = document.getElementsByClassName("warranty_check");
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = true;
            }
        }
        else {
            var checkboxes = document.getElementsByClassName("warranty_check");
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = false;
            }
        }
    }

    function setWarrantyDefault(e) {
        var warranty_fields = document.getElementsByClassName("warranty_value");
        var checkboxes = document.getElementsByClassName("warranty_check");
        for (var i = 0; i < warranty_fields.length; i++) {
            if (checkboxes[i].checked == true) {
                warranty_fields[i].value = e.value;
            }
        }
    }

    jQuery(function () {
        // SBF 4402 warranty for different countries
        for (i = 0; i < document.getElementsByClassName('add_sign_btn').length; i++) {
            document.getElementsByClassName('add_sign_btn')[i].addEventListener('click', function () {
                var sku = this.getAttribute('id').split('warranty_add_sign_btn[')[1].split(']')[0];
                var warranty_new_field_counter = 0 + this.parentNode.getElementsByTagName('p').length; //counting number of existing country fields
                var new_warranty_field = document.createElement("p");
                var inner_html = '<select class="warranty_country" id="warranty_country_' + warranty_new_field_counter + '[' + sku + ']" name="warranty_country_' + warranty_new_field_counter + '[' + sku + ']">';
                //console.log(warranty_new_field_counter);

                <?php
                    foreach ($selling_platform_list as $country_obj)
                    {
                        $platform_id_list[] = $country_obj["platform_id"];
                    }
                ?>
                var platform_id_list = ['<?php echo implode('\',\'', $platform_id_list) ?>'];
                //var existing_platform_list = $("input[id^='warranty_country']");
                var existing_platform_list = document.getElementsByClassName('warranty_country');

                if (existing_platform_list.length == platform_id_list.length) {
                    alert("No more platform");
                    return false;
                }

                for (i = 0; i < platform_id_list.length; i++) {
                    for (k = 0; k < existing_platform_list.length; k++) {
                        var skip = false;
                        if (existing_platform_list[k].value.trim() == platform_id_list[i]) {
                            skip = true;
                            break;
                        }
                    }
                    if (!skip) {
                        inner_html = inner_html + "<option value='" + platform_id_list[i] + "'>" + platform_id_list[i] + "</option>";
                    }
                }

                <?php
                echo 'inner_html += "</select>";';
                echo 'inner_html += "<select name=\"warranty_in_month_"+ warranty_new_field_counter +"["+ sku +"]\" notEmpty>";';
                echo 'inner_html += "<option value=\'\'>None</option>";';
                foreach($warranty_list as $warranty_period)
                {
                    echo 'inner_html += "<option value=\'' . $warranty_period . '\'>' . $warranty_period .'</option>";';
                }
                ?>
                inner_html += '</select>';
                new_warranty_field.innerHTML = inner_html;
                this.parentNode.appendChild(new_warranty_field);
                warranty_new_field_counter++;
            });
        }
    });
</script>
</body>
</html>