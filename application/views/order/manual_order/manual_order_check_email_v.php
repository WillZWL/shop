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
    <?php
    if ($client) {
        ?>
        <b>NAME</b><br>
        <?= $client->get_title(); ?> <?= $client->get_forename() ?>  <?= $client->get_surname() ?><br>
        <br>
        <b>BILLING ADDRESS</b><br>
        <?= $client->get_companyname(); ?><br>
        <?= $client->get_address_1(); ?><br>
        <?= $client->get_address_2(); ?><br>
        <?= $client->get_city(); ?><br>
        <?= $client->get_postcode(); ?><br><br>
        <b>DELIVERY ADDRESS</b><br>
        <?= $client->get_del_company(); ?><br>
        <?= $client->get_del_address_1(); ?><br>
        <?= $client->get_del_address_2(); ?><br>
        <?= $client->get_del_city(); ?><br>
        <?= $client->get_del_postcode(); ?><br>
        <input type="button" value="Use Billing Address"
               onClick="window.parent.response('<?= obj_to_query($client) ?>');parent.document.getElementById('lbClose').onclick()">
    <?php
    } else {
        ?>
        No Client Found!<br>
        <input type="button" value="Enter New Client" onClick="parent.document.getElementById('lbClose').onclick()">
    <?php
    }
    ?>
</div>
</body>
</html>