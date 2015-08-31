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
                <td height="20" colspan="4"><?= $lang["header_message"] ?></td>
            </tr>
            <tr>
                <td class="field"><?= $lang["brand_name"] ?></td>
                <td class="value"><input name="brand_name" class="input"
                                         value="<?= htmlspecialchars($brand->getBrandName()) ?>" notEmpty></td>
                <?php
                if ($cmd == "edit") {
                    ?>
                    <td class="field"><?= $lang["status"] ?></td>
                    <td class="value">
                        <?php
                        $selected[$brand->getStatus()] = "SELECTED";
                        ?>
                        <select name="status" class="input" notEmpty>
                            <option value="">
                            <option value="1"<?= !empty($selected[1]) ? $selected[1] : "" ?>><?= $lang["active"] ?>
                            <option value="0"<?= !empty($selected[0]) ? $selected[0] : "" ?>><?= $lang["inactive"] ?>
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
                                                     value="<?= htmlspecialchars($brand->getDescription()) ?>"></td>
            </tr>
            <?php
            if ($cmd != "add") {
                ?>
                <tr>
                    <td class="field"><?= $lang["create_on"] ?></td>
                    <td class="value"><?= $brand->getCreateOn() ?></td>
                    <td class="field"><?= $lang["modify_on"] ?></td>
                    <td class="value"><?= $brand->getModifyOn() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_at"] ?></td>
                    <td class="value"><?= $brand->getCreateAt() ?></td>
                    <td class="field"><?= $lang["modify_at"] ?></td>
                    <td class="value"><?= $brand->getModifyAt() ?></td>
                </tr>
                <tr>
                    <td class="field"><?= $lang["create_by"] ?></td>
                    <td class="value"><?= $brand->getCreateBy() ?></td>
                    <td class="field"><?= $lang["modify_by"] ?></td>
                    <td class="value"><?= $brand->getModifyBy() ?></td>
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
        <input name="id" type="hidden" value="<?= $brand->getId() ?>">
        <input type="hidden" name="cmd" value="edit">
        <input type="hidden" name="posted" value="1">
    </form>
    <?= $this->pagination_service->create_links_with_style() ?>
</div>
<?= $notice["js"] ?>
</body>
</html>