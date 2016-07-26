<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body style="width:auto">
<div id="main" style="width:auto">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["title"] ?></td>
            <td width="400" align="right" class="title"></td>
        </tr>
        <tr>
            <td height="2" class="line"></td>
            <td height="2" class="line"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" class="page_header" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["supplier_note"] ?></b><br><?= $lang["id"] ?>:
                <b><?= $supplier->getId() ?></b><br><?= $lang["supplier_name"] ?>: <b><?= $supplier->getName() ?></b>
            </td>
        </tr>
    </table>
    <form name="fm" method="post" onSubmit="return CheckForm(this);">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
            <tr class="header">
                <td height="20"><?= $lang["supplier_note"] ?></td>
            </tr>
            <tr>
                <td class="value"><textarea class="input" name="note"
                                            rows="6"><?= htmlspecialchars($supplier->getNote()) ?></textarea></td>
            </tr>
            <tr>
                <td height="40" align="right" class="tb_detail" style="padding-right:8px;">
                    <input type="submit" value="<?= $lang['update_button'] ?>">
                </td>
            </tr>
        </table>
        <input name="id" type="hidden" value="<?= $supplier->getId() ?>">
        <input type="hidden" name="posted" value="1">
    </form>
</div>
<?= $notice["js"] ?>
<script>
    window.focus();
    document.fm.focus();
</script>
</body>
</html>