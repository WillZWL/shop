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
            <td height="70" style="padding-left:8px"><?= $lang["header"] ?><br><b
                    style="font-size:14px"><?= $product->get_name() ?></b><br><?= $lang["sku"] ?>
                : <?= $product->get_sku() ?></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tb_main">
        <tr class="header">
            <td height="20"><?= $lang["ra_product"] ?></td>
        </tr>
    </table>
    <iframe name="ra_prod_new" id="ra_prod_new" class="iframe"
            src="<?= base_url() ?>marketing/ra_product/get_ra_product/<?= $product->get_sku() ?>" scrolling="auto"
            marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0"
            onLoad="SetFrameHeight(this)"></iframe>
    <?= $notice["js"] ?>
</div>
</body>
</html>