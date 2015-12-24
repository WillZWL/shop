<html>
<head>
    <title><?= $lang["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?= base_url() ?>js/common.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>js/checkform.js"></script>
    <script type="text/javascript">
        function clearForm() {
            window.parent.document.fm_checkout.elements['client[delivery_company]'].value = "";
            window.parent.document.fm_checkout.elements['client[delivery_name]'].value = "";
            window.parent.document.fm_checkout.elements['client[delivery_address_1]'].value = "";
            window.parent.document.fm_checkout.elements['client[delivery_address_2]'].value = "";
            window.parent.document.fm_checkout.elements['client[delivery_city]'].value = "";
            window.parent.document.fm_checkout.elements['client[delivery_postcode]'].value = "";
            window.parent.document.fm_checkout.elements['client[tel_1]'].value = "";
            window.parent.document.fm_checkout.elements['client[tel_2]'].value = "";
            window.parent.document.fm_checkout.elements['client[tel_3]'].value = "";
        }

        function deactivateTheBox(yesOrNo) {
            <?php if ($client) : ?>
            window.parent.postMessage("<?=$client->getClientId()?>", "*");
            <?php endif; ?>
            window.parent.document.fm_checkout.elements['client[delivery_country_id]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[delivery_company]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[title]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[delivery_name]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[delivery_address_1]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[delivery_address_2]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[delivery_city]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[delivery_postcode]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[tel_1]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[tel_2]'].disabled = yesOrNo;
            window.parent.document.fm_checkout.elements['client[tel_3]'].disabled = yesOrNo;
        }
    </script>
</head>
<body style="width:auto">
<div style="width:auto;text-align:left">
    <?php if ($client) : ?>
        <b>NAME</b><br>
        <?= $client->getTitle(); ?> <?= $client->getDeliveryName() ?><br>
        <br>
        <b>Delivery ADDRESS</b><br>
        <?= $client->getDeliveryAddress(); ?><br>
        <?= $client->getDeliveryAddress1(); ?><br>
        <?= $client->getDeliveryAddress2(); ?><br>
        <?= $client->getDeliveryAddress3(); ?><br>
        <?= $client->getDeliveryCity(); ?><br>
        <?= $client->getDeliveryPostcode(); ?><br>
        <input type="button" value="Use Delivery Address"
                   onClick="deactivateTheBox(true);window.parent.response('<?= obj_to_query($client) ?>');parent.document.getElementById('lbClose').onclick();">
    <?php else : ?>
        No Client Found!<br>
        <input type="button" value="Enter New Client(not allow)"
                   onClick="deactivateTheBox(true);clearForm();parent.document.getElementById('lbClose').onclick();">
    <?php endif; ?>
</div>
</body>
</html>