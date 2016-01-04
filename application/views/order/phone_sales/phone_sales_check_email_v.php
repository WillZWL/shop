<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
</head>
<body style="width:auto">
<div style="width:auto;text-align:left">
    <?php if ($client) : ?>
        <b>NAME</b><br>
        <?= $client->getTitle(); ?> <?= $client->getForename() ?>  <?= $client->getSurname() ?><br>
        <br>
        <b>BILLING ADDRESS</b><br>
        <?= $client->getAddress1(); ?><br>
        <?= $client->getAddress2(); ?><br>
        <?= $client->getCity(); ?><br>
        <?= $client->getPostcode(); ?><br>
        <input type="button" value="Use Billing Address"
               onClick="top.frames['fcart'].changeCountry('<?= $country ?>','<?= $client->getId() ?>');">
    <?php else : ?>
        No Client Found!<br>
        <input type="button" value="Enter New Client" onClick="parent.document.getElementById('lbClose').onclick()">
    <?php endif; ?>
</div>
</body>
</html>