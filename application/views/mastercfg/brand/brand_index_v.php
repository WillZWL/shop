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
    <?php $ar_status = array($lang["inactive"], $lang["active"]); ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('mastercfg/brand/') ?>')">
                &nbsp; <input type="button" value="<?= $lang["add_button"] ?>" class="button"
                              onclick="Redirect('<?= site_url('mastercfg/brand/add/') ?>')"></td>
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
        <table border="0" cellpadding="0" cellspacing="0" bgcolor="#000000" width="100%" class="tb_list">
            <col width="20">
            <col width="180">
            <col>
            <col width="70">
            <col width="26">
            <tr class="header">
                <td height="20"><img src="<?= base_url() ?>images/expand.png" class="pointer"
                                     onClick="Expand(document.getElementById('tr_search'));"></td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'brand_name', '<?= $xsort["brand_name"] ?>')"><?= $lang["brand"] ?> <?= $sortimg["brand_name"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'description', '<?= $xsort["description"] ?>')"><?= $lang["description"] ?> <?= $sortimg["description"] ?></a>
                </td>
                <!--
        <td><a href="#" onClick="SortCol(document.fm, 'regions', '<?= $xsort["regions"] ?>')"><?= $lang["sales_regions"] ?>--<?= $lang["sourcing_regions"] ?> <?= $sortimg["regions"] ?></a></td>
        -->
                <td><a href="#"
                       onClick="SortCol(document.fm, 'status', '<?= $xsort["status"] ?>')"><?= $lang["status"] ?> <?= $sortimg["status"] ?></a>
                </td>
                <td></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="brand_name" class="input"
                           value="<?= htmlspecialchars($this->input->get("brand_name")) ?>"></td>
                <td><input name="description" class="input"
                           value="<?= htmlspecialchars($this->input->get("description")) ?>"></td>
                <!--
        <td><input name="regions" class="input" value="<?= htmlspecialchars($this->input->get("regions")) ?>"></td>
        -->
                <td>
                    <?php
                    if ($this->input->get("status") !== FALSE) {
                        $selected[$this->input->get("status")] = " SELECTED";
                    }
                    ?>
                    <select name="status" class="input" notEmpty>
                        <option value="">
                        <option value="1"<?= $selected[1] ?>><?= $lang["active"] ?>
                        <option value="0"<?= $selected[0] ?>><?= $lang["inactive"] ?>
                    </select>
                </td>
                <td align="center"><input type="submit" name="searchsubmit" value="" class="search_button"
                                          style="background: url('<?= base_url() ?>images/find.gif') no-repeat;"></td>
            </tr>
            <?php
            $i = 0;
            if ($brandlist) {
                foreach ($brandlist as $brand) {
                    ?>

                    <tr class="row<?= $i % 2 ?> pointer" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')"
                        onClick="Redirect('<?= site_url('mastercfg/brand/view/' . $brand->get_id()) ?>')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $brand->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $brand->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $brand->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $brand->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $brand->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $brand->get_modify_by() ?>'>
                        </td>
                        <td><?= $brand->get_brand_name() ?></td>
                        <td><?= $brand->get_description() ?></td>
                        <!--<td><?= $brand->get_regions() ?></td>-->
                        <td><?= $ar_status[$brand->get_status()] ?></td>
                        <td></td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
        </table>
        <input type="hidden" name="showall" value='<?= $this->input->get("showall") ?>'>
        <input type="hidden" name="sort" value='<?= $this->input->get("sort") ?>'>
        <input type="hidden" name="order" value='<?= $this->input->get("order") ?>'>
    </form>
    <?= $this->pagination_service->create_links_with_style() ?>
    <?= $notice["js"] ?>
</div>
</body>
</html>