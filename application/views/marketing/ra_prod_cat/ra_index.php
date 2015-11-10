<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body topmargin="0" leftmargin="0"
      style="width:1058px;" <?= ($refresh ? "onLoad='parent.left.document.location.reload();'" : "") ?>>
<div id="main" style="width:1058px;">
    <?= $notice["img"] ?>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td height="30" class="title"><?= $lang["subtitle"] ?></td>
        </tr>
        <tr>
            <td height="2" bgcolor="#000033"></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" height="70" bgcolor="#BBBBFF" width="100%">
        <tr>
            <td height="70" style="padding-left:8px"><b
                    style="font-size:14px"><?= $lang["header"] ?></b><br><?= $lang["subheader"] ?><select
                    onChange='gotoPage("<?= base_url() . "marketing/ra_prod_cat/view/?sscat=" ?>",this.value)'>
                    <option value="">-- <?= $lang["please_select"] ?> --</option><?php
                    foreach ($sscat_list as $obj) {
                        ?>
                        <option value="<?= $obj->get_id() ?>"><?= $obj->get_name() ?></option><?php
                    }
                    ?></select></td>
        </tr>
    </table>
</body>
</html>