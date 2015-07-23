<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="imagetoolbar" content="no">
    <script language="javascript" src="<?= base_url() . 'js/common.js' ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
</head>
<body topmargin="0" leftmargin="0" style="width:1058px;">
<div id="main" style="width:1058px;">
    <?= $notice["img"] ?>
    <form name="catview"
          action="<?= $_SERVER["PHP_SELF"] ?>?level=<?= $this->input->get('level') ?>&parent=<?= $this->input->get('parent') ?>"
          method="POST">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td height="30" class="title"><b style="font-size:16px;color:#000000"><?= $lang["subtitle"] ?></b></td>
                <td width="400" align="right" class="title">&nbsp;</td>
            </tr>
            <tr>
                <td height="2" bgcolor="#000033"></td>
                <td height="2" bgcolor="#000033"></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
            <tr>
                <td height="70" style="padding-left:8px"><b
                        style="font-size:14px"><?= $lang["title"] ?></b><br><?= $lang["subheader"] ?></td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="1" height="20" width="100%">
            <tr class="header">
                <td width="150" height="20">&nbsp;&nbsp;<?= $lang["category_info"] ?></td>
                <td height="20">&nbsp;&nbsp;<?= $lang["assoc_value"] ?></td>
            </tr>
        </table>
        <table border="0" cellpadding="4" cellspacing="1" class="page_header" width="100%">
            <tr>
                <td width="142" valign="top" class="field" align="left">&nbsp;&nbsp;<?= $lang["category_type"] ?></td>
                <td align="left" class="value">&nbsp;&nbsp;<?= $lang["type" . $level] ?></td>
            </tr>
            <tr>
                <td width="142" height="20" valign="top" class="field" align="left">
                    &nbsp;&nbsp;<?= $lang["category_name"] ?></td>
                <td height="20" valign="top" class="value" align="left">&nbsp;&nbsp;<input type="text" name="name"
                                                                                           value=""></td>
            </tr>
            <tr>
                <td width="142" valign="top" class="field" align="left">&nbsp;&nbsp;<?= $lang["category_desc"] ?></td>
                <td align="left" class="value">&nbsp;&nbsp;<textarea cols="60" rows="5" name="description"></textarea>
                </td>
            </tr>
            <tr>
                <td width="142" valign="top" class="field" align="left">&nbsp;&nbsp;<?= $lang["category_status"] ?></td>
                <td align="left" class="value">&nbsp;&nbsp;<select name="status" style="width:120px;"><?php
                        for ($i = 0; $i < 2; $i++) {
                            ?>
                            <option value="<?= $i ?>"><?= $lang["status" . $i] ?></option><?php
                        }
                        ?></select></td>
            </tr>
            <?php
            if ($level > 1) {
                if ($level == 2) {
                    ?>
                    <tr>
                        <td width="142" height="20" valign="top" class="field" align="left">
                            &nbsp;&nbsp;<?= $lang["category_cat"] ?></td>
                        <td height="20" valign="top" class="value" align="left">
                            &nbsp;&nbsp;<?= $parent_obj->get_name() ?></td>
                    </tr>
                <?php
                } else {
                    ?>
                    <tr>
                        <td width="142" height="20" valign="top" class="field" align="left">
                            &nbsp;&nbsp;<?= $lang["category_cat"] ?></td>
                        <td height="20" valign="top" class="value" align="left">
                            &nbsp;&nbsp;<?= $parent_obj->get_cat_name() ?></td>
                    </tr>
                    <tr>
                        <td width="142" height="20" valign="top" class="field" align="left">
                            &nbsp;&nbsp;<?= $lang["category_subcat"] ?></td>
                        <td height="20" valign="top" class="value" align="left">
                            &nbsp;&nbsp;<?= $parent_obj->get_name() ?></td>
                    </tr>
                <?php
                }
            }
            ?>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" height="40" class="page_header" width="100%">
            <tr>
                <td align="left" style="padding-left:8px;" width="50%" style="padding-left:8px"><input type="button"
                                                                                                       name="back"
                                                                                                       value="Back To Main Page"
                                                                                                       onClick="Redirect('<?= base_url() . "marketing/category/top/" ?>')">
                </td>
                <td align="right" style="padding-right:8px"><input type="submit" name="submit"
                                                                   value="<?= $lang["add_category"] ?>"
                                                                   style="font-size:11px"></td>
            </tr>
        </table>
        <input type="hidden" name="level" value="<?= $level ?>">
        <input type="hidden" name="parent_cat_id" value="<?= $parent ?>">
        <input type="hidden" name="add" value="1">
    </form>
</div>
<?= $notice["js"] ?>
</body>
</html>