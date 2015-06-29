<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>marketing/product/js_catlist"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>

<body>
<div id="main">
    <?= $notice["img"] ?>
    <?php
    $ar_status = array($lang["inactive"], $lang["created"], $lang["listed"]);
    $ar_proc_status = array($lang["pending"], $lang["pending"], $lang["pending"], $lang["n/a"], $lang["completed"]);
    ?>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="100" align="right" class="title"><input type="button" value="<?= $lang["list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('marketing/upselling/') ?>')">
            </td>
            <td width="100" align="right" class="title"><input type="button" value="<?= $lang["group_list_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('marketing/upselling/group_list') ?>')">
            </td>
            <td width="100" align="right" class="title"><input type="button" value="<?= $lang["add_group_button"] ?>"
                                                               class="button"
                                                               onclick="Redirect('<?= site_url('marketing/upselling/add_group') ?>')">
            </td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>

    <form name="fm" method="get">
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <tr>
                <td height="70" style="padding-left:8px"><b
                        style="font-size:14px"><?= $lang["header"] ?> <?= $ar_proc_status[$this->input->get("proc_status")] ?></b><br><?= $lang["header_message"] ?>
                </td>
            </tr>
        </table>

        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
            <col width="20">
            <col width="60">
            <col>
            <col width="80">
            <col width="115">
            <col width="115">
            <col width="115">
            <col width="105">
            <col width="70">
            <col width="105">
            <col width="80">
            <col width="80">
            <tr class="header">
                <td height="20">
                    <img src="<?= base_url() ?>images/expand.png" class="pointer"
                         onClick="Expand(document.getElementById('tr_search'));">
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'sku', '<?= $xsort["sku"] ?>')"><?= $lang["sku"] ?> <?= $sortimg["sku"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'name', '<?= $xsort["name"] ?>')"><?= $lang["product_name"] ?> <?= $sortimg["name"] ?></a>
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
                       onClick="SortCol(document.fm, 'create_on', '<?= $xsort["create_on"] ?>')"><?= $lang["create_date"] ?> <?= $sortimg["create_on"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'status', '<?= $xsort["status"] ?>')"><?= $lang["status"] ?> <?= $sortimg["status"] ?></a>
                </td>
                <td><a href="#"
                       onClick="SortCol(document.fm, 'proc_status', '<?= $xsort["proc_status"] ?>')"><?= $lang["proc_status"] ?> <?= $sortimg["proc_status"] ?></a>
                </td>
                <td colspan="2"></td>
            </tr>
            <tr class="search" id="tr_search" <?= $searchdisplay ?>>
                <td></td>
                <td><input name="sku" class="input" value="<?= htmlspecialchars($this->input->get("sku")) ?>"></td>
                <td><input name="name" class="input" value="<?= htmlspecialchars($this->input->get("name")) ?>"></td>
                <td><input name="colour" class="input" value="<?= htmlspecialchars($this->input->get("colour")) ?>">
                </td>
                <td>
                    <select name="cat_id" class="input"
                            onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)">
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
                <td><input name="create_on" class="input"
                           value="<?= htmlspecialchars($this->input->get("create_on")) ?>"></td>
                <td>
                    <?php
                    if ($this->input->get("status") != "") {
                        $selected[$this->input->get("status")] = "SELECTED";
                    }
                    ?>
                    <select name="status" class="input">
                        <option value="">
                        <option value="0" <?= $selected[0] ?>><?= $ar_status[0] ?>
                        <option value="1" <?= $selected[1] ?>><?= $ar_status[1] ?>
                        <option value="2" <?= $selected[2] ?>><?= $ar_status[2] ?>
                    </select>
                </td>
                <td>
                    <?php
                    if ($this->input->get("proc_status") != "") {
                        $selected[$this->input->get("proc_status")] = "SELECTED";
                    }
                    ?>
                    <select name="proc_status" class="input">
                        <option value="">
                        <option value="0" <?= $selected[0] ?>><?= $ar_proc_status[0] ?>
                        <option value="3" <?= $selected[3] ?>><?= $ar_proc_status[3] ?>
                        <option value="4" <?= $selected[4] ?>><?= $ar_proc_status[4] ?>
                    </select>
                </td>
                <td align="center" colspan="2"><input type="submit" name="searchsubmit" value="" class="search_button"
                                                      style="background: url('<?= base_url() ?>images/find.gif') no-repeat;">
                </td>
            </tr>

            <?php
            $i = 0;
            if ($objlist) {
                foreach ($objlist as $obj) {
                    ?>
                    <tr class="row<?= $i % 2 ?>" onMouseOver="AddClassName(this, 'highlight')"
                        onMouseOut="RemoveClassName(this, 'highlight')">
                        <td height="20"><img src="<?= base_url() ?>images/info.gif"
                                             title='<?= $lang["create_on"] ?>:<?= $obj->get_create_on() ?>&#13;<?= $lang["create_at"] ?>:<?= $obj->get_create_at() ?>&#13;<?= $lang["create_by"] ?>:<?= $obj->get_create_by() ?>&#13;<?= $lang["modify_on"] ?>:<?= $obj->get_modify_on() ?>&#13;<?= $lang["modify_at"] ?>:<?= $obj->get_modify_at() ?>&#13;<?= $lang["modify_by"] ?>:<?= $obj->get_modify_by() ?>'>
                        </td>
                        <td><?= $obj->get_sku() ?></td>
                        <td><?= $obj->get_name() ?></td>
                        <td><?= $obj->get_colour() ?></td>
                        <td><?= $obj->get_category() ?></td>
                        <td><?= $obj->get_sub_cat() ?></td>
                        <td><?= $obj->get_sub_sub_cat() ?></td>
                        <td><?= substr($obj->get_create_on(), 0, 10) ?></td>
                        <td><?= $ar_status[$obj->get_status()] ?></td>
                        <td><?= $ar_proc_status[$obj->get_proc_status()] ?></td>
                        <td><input type="button"
                                   value="<?= $obj->get_proc_status() == 3 ? $lang["applicable"] : " " . $lang["n/a"] . " " ?>"
                                   onClick="Redirect('<?= site_url("marketing/upselling/proc/{$obj->get_sku()}" . ($obj->get_proc_status() == 3 ? "/0" : "")) ?>')">
                        </td>
                        <td align="right"><input type="button" value="<?= $lang["upselling"] ?>"
                                                 onClick="Pop('<?= site_url("marketing/upselling/add/{$obj->get_sku()}") ?>')">
                        </td>
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

<script language='javascript'>
    ChangeCat('0', document.fm.cat_id);
    document.fm.cat_id.value = '<?=$this->input->get("cat_id")?>';
    ChangeCat('<?=$this->input->get("cat_id")?>', document.fm.sub_cat_id);
    document.fm.sub_cat_id.value = '<?=$this->input->get("sub_cat_id")?>';
    ChangeCat('<?=$this->input->get("sub_cat_id")?>', document.fm.sub_sub_cat_id);
    document.fm.sub_sub_cat_id.value = '<?=$this->input->get("sub_sub_cat_id")?>';
</script>
</body>
</html>