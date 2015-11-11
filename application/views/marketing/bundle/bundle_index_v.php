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
    <?php
    $ar_status = array("inactive", "created", "listed");
    ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title">
                <input type="button" value="<?= $lang["list_button"] ?>" class="button"
                       onclick="Redirect('<?= site_url('marketing/bundle/') ?>')">
                <?php
                if ($prod_grp_cd != "") {
                    ?>
                    &nbsp; <input type="button" value="<?= $lang["back_button"] ?>" class="button"
                                  onclick="history.go(-2)">
                <?php
                }
                ?>
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
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["header_message"] ?></td>
        </tr>
    </table>
    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="60">
            <col>
            <col width="80">
            <col width="115">
            <col width="115">
            <col width="115">
            <col width="100">
            <col width="70">
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
                       onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')"><?= $cmd == "list" ? $lang["bundle_name"] : $lang["product_name"] ?> <?= $sortimg["name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'colour', '<?= $xsort["colour"] ?>')"><?= $lang["colour"] ?> <?= $sortimg["colour"] ?></a>
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
                <td></td>
            </tr>
            <?php
            if ($prod_grp_cd == "") {
                ?>
                <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                    <td></td>
                    <td><input name="sku" class="input" value="<?= htmlspecialchars($this->input->get("sku")) ?>"></td>
                    <td><input name="name" class="input" value="<?= htmlspecialchars($this->input->get("name")) ?>">
                    </td>
                    <td><input name="colour" class="input" value="<?= htmlspecialchars($this->input->get("colour")) ?>">
                    </td>
                    <td><input name="category" class="input"
                               value="<?= htmlspecialchars($this->input->get("category")) ?>"></td>
                    <td><input name="sub_cat" class="input"
                               value="<?= htmlspecialchars($this->input->get("sub_cat")) ?>"></td>
                    <td><input name="sub_sub_cat" class="input"
                               value="<?= htmlspecialchars($this->input->get("sub_sub_cat")) ?>"></td>
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
                    <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                              style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">
                    </td>
                </tr>
            <?php
            }
            ?>
            <?php
            $i = 0;
            $rscmd = ($cmd == "list") ? "view" : "add";
            if ($objlist) {
                foreach ($objlist as $obj) {
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= site_url("marketing/bundle/{$rscmd}/" . $obj->get_sku()) ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                        </td>
                        <td><?= $obj->get_sku() ?></td>
                        <td><?= $obj->get_name() ?></td>
                        <td><?= $obj->get_colour() ?></td>
                        <td><?= $obj->get_category() ?></td>
                        <td><?= $obj->get_sub_cat() ?></td>
                        <td><?= $obj->get_sub_sub_cat() ?></td>
                        <td><?= $obj->get_brand() ?></td>
                        <td><?= $lang[$ar_status[$obj->get_status()]] ?></td>
                        <td></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
        </table>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>