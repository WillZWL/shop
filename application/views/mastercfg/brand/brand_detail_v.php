<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>mastercfg/region_helper/js_sourcing_region"></script>
</head>
<body>
<div id="main">
    <?= $notice["img"] ?>
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
    <form name="fm" method="post" onSubmit="return CheckForm(this);">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
            <col width="160">
            <col width="470">
            <col width="160">
            <col>
            <tr class="header">
                <td height="20" colspan="4"><?= $lang["table_header"] ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["brand_name"] ?></td>
                <td class="value"><input name="brand_name" class="input"
                                         value="<?= htmlspecialchars($brand->get_brand_name()) ?>" notEmpty></td>
                <?php
                if ($cmd == "edit") {
                    ?>
                    <td class="field"><?= $lang["status"] ?></td>
                    <td class="value">
                        <?php
                        $selected[$brand->get_status()] = "SELECTED";
                        ?>
                        <select name="status" class="input" notEmpty>
                            <option value="">
                            <option value="1" <?= $selected[1] ?>><?= $lang["active"] ?>
                            <option value="0" <?= $selected[0] ?>><?= $lang["inactive"] ?>
                        </select>
                    </td>
                <?php
                } else {
                    ?>
                    <td class="field"></td>
                    <td class="value"></td>
                <?php
                }
                ?>
            </tr>
            <tr>
                <td class="field"><?= $lang["description"] ?></td>
                <td class="value" colspan="3"><input name="description" class="input"
                                                     value="<?= htmlspecialchars($brand->get_description()) ?>"></td>
            </tr>
            <?php
            if ($cmd != "add") {
                ?>
                <tr>
                    <td class="field"><?= $lang["create_on"] ?></td>
                    <td class="value"><?= $brand->get_create_on() ?></td>
                    <td class="field"><?= $lang["modify_on"] ?></td>
                    <td class="value"><?= $brand->get_modify_on() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_at"] ?></td>
                    <td class="value"><?= $brand->get_create_at() ?></td>
                    <td class="field"><?= $lang["modify_at"] ?></td>
                    <td class="value"><?= $brand->get_modify_at() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_by"] ?></td>
                    <td class="value"><?= $brand->get_create_by() ?></td>
                    <td class="field"><?= $lang["modify_by"] ?></td>
                    <td class="value"><?= $brand->get_modify_by() ?></td>
                </tr>
            <?php
            }
            ?>
            <tr class="tb_detail">
                <td colspan="2" height="40"><input type="button" name="back" value="<?= $lang['back_list'] ?>"
                                                   onClick="Redirect('<?= isset($_SESSION['LISTPAGE']) ? $_SESSION['LISTPAGE'] : base_url() . '/mastercfg/brand' ?>')">
                </td>
                <td colspan="2" align="right" style="padding-right:8px;">
                    <?php
                    if ($cmd == "add") {
                        ?>
                        <input type="submit" value="<?= $lang['header'] ?>">
                    <?php
                    } elseif ($cmd == "edit") {
                        ?>
                        <input type="submit" value="<?= $lang['update_button'] ?>">
                    <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
        <input name="id" type="hidden" value="<?= $brand->get_id() ?>">
        <input type="hidden" name="cmd" value="edit">
        <input type="hidden" name="posted" value="1">
    </form>
    <!--
<?php
    if ($cmd == "edit") {
        ?>
<form name="fm_region" action="<?= base_url() ?>/mastercfg/brand/add_region" method="post" onSubmit="return CheckForm(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_list">
    <tr class="header">
        <td height="20"><?= $lang["add_regions"] ?></td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_add">
    <tr class="add_header">
        <td height="20" width="45%"><?= $lang["sales_region"] ?></td>
        <td width="45%"><?= $lang["sourcing_region"] ?></td>
        <td width="10%"></td>
    </tr>
    <tr class="add_row">
        <td>
            <select name="sales_region_id" class="input" notEmpty>
                <option value="">
            </select>
        </td>
        <td>
            <select name="src_region_id" class="input" notEmpty>
                <option value="">
            </select>
        </td>
        <td align="center"><input type="submit" value="<?= $lang["add_regions"] ?>"></td>
    </tr>
    <tr class="header">
        <td colspan="3"><?= $lang["existing_regions"] ?></td>
    </tr>
    <?php
        if ($br_list) {
            $i = 0;
            foreach ($br_list as $obj) {
                ?>
    <tr class="row<?= $i % 2 ?>">
        <td><input name="del_sales_region_id[<?= $i ?>]" type="hidden" value="<?= $obj->get_sales_region_id() ?>"><script>w(src_region_list[<?= $obj->get_sales_region_id() ?>])</script></td>
        <td><input name="del_src_region_id[<?= $i ?>]" type="hidden" value="<?= $obj->get_src_region_id() ?>"><script>w(src_region_list[<?= $obj->get_src_region_id() ?>])</script></td>
        <td align="center"><input type="checkbox" name="check[]" value="<?= $i ?>"></td>
    </tr>
    <?php
                $i++;
            }
        }
        ?>
    <tr>
        <td colspan="3" align="right" style="padding-right:8px;">
            <input type="button" value="<?= $lang['delete_regions'] ?>" onClick="this.form.action='<?= base_url() ?>/mastercfg/brand/del_region'; this.form.submit()">
        </td>
    </tr>
</table>
<input name="brand_id" type="hidden" value="<?= $brand->get_id() ?>">
<input type="hidden" name="cmd" value="add">
<input type="hidden" name="posted" value="1">
</form>
<script>
InitSrcReg(document.fm_region.sales_region_id);
ChangeSrcReg('<?= ($br) ? $br->get_sales_region_id() : "" ?>', document.fm_region.sales_region_id);
InitSrcReg(document.fm_region.src_region_id);
ChangeSrcReg('<?= ($br) ? $br->get_src_region_id() : "" ?>', document.fm_region.src_region_id);
</script>
<?php
    }
    ?>
-->
    <?= $this->pagination_service->create_links_with_style() ?>
</div>
<?= $notice["js"] ?>
</body>
</html>