<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
</head>
<body style="width:auto;margin:4px;">
<div style="width:auto;text-align:left">
    <?= $result ?>
    <? unset($_SESSION["result"]); ?>
</div>
</body>
</html>