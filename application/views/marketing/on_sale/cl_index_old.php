<html>
<head>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <title><?= $lang["title"] ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url() . "css/style.css" ?>">
    <script src="<?= base_url() ?>/marketing/product/js_catlist" type="text/javascript"></script>
</head>
<body topmargin="0" leftmargin="0">
<div id="main">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><b style="font-size:16px;color:#000000"><?= $lang["subtitle"] ?></b></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <form name="fm" action="<?= base_url() ?>marketing/best_seller/view/" method="get" target="bottom">
        <table border="0" cellpadding="0" cellspacing="0" height="70" bgcolor="#BBBBFF" width="100%">
            <col width="15%">
            <col width="35%">
            <col width="50%">
            <tr>
                <td colspan="3" height="70" style="padding-left:8px"><b style="font-size:14px"><?= $lang["title"] ?></b><br><?= $lang["subheader"] ?>
                </td>
            </tr>
            <tr>
                <td height="20" align="right" style="padding-right:5px;"><?= $lang["category"] ?></td>
                <td><select name="cat_id" class="input"
                            onChange="ChangeCat(this.value, this.form.sub_cat_id, this.form.sub_sub_cat_id)">
                        <option value="">
                    </select>
                </td>
                <td width="50%">&nbsp;</td>
            <tr>
            </tr>
            <td height="20" align="right" style="padding-right:5px;"><?= $lang["sub_category"] ?></td>
            <td><select name="sub_cat_id" class="input" onChange="ChangeCat(this.value, this.form.sub_sub_cat_id)">
                    <option value="">
                </select>
            </td>
            <td width="50%">&nbsp;</td>
            </tr>
            <tr>
                <td height="20" align="right" style="padding-right:5px;"><?= $lang["sub_sub_category"] ?></td>
                <td><select name="sub_sub_cat_id" class="input">
                        <option value="">
                    </select>
                </td>
                <td width="50%">&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2" height="35" valign="middle" align="left"><input type="submit"
                                                                                value="<?= $lang["submit"] ?>"></td>
            </tr>
        </table>
    </form>
    <iframe width="100%" frameborder="0" src="<?= base_url() ?>marketing/best_seller/view/" height="420"
            name="bottom"></iframe>
    <script language="javascript">
        ChangeCat('0', document.fm.cat_id);
    </script>
</div>
<?= $notice["js"] ?>
</body>
</html>
