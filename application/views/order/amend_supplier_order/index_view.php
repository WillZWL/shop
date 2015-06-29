<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Language" content="en-gb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
</head>
<body
    onResize="SetFrameFullHeight(document.getElementById('left'));SetFrameFullHeight(document.getElementById('right'));">
<div id="main">
    <iframe src="<?= base_url() . "order/amend_supplier_order/add_left" ?>" noresize frameborder="0" name="left"
            id="left" marginwidth="0" marginheight="0" hspace=0 vspace=0 width="200" height="600"
            style="float:left;border-right:1px solid #000000;" onLoad="SetFrameFullHeight(this)"></iframe>
    <iframe src="<?= base_url() . "order/amend_supplier_order/view_right/" . $po_number ?>" noresize frameborder="0"
            name="right" id="right" marginwidth="0" marginheight="0" height="600" hspace=0 vspace=0 width="1058"
            style="float:left;border-left:1px solid #999999;" onLoad="SetFrameFullHeight(this)"></iframe>
    <div>
</body>
</html>