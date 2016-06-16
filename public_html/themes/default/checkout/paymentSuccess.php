<?php $this->load->view('header') ?>
<?php
    $siteobj = \PUB_Controller::$siteInfo;
    $countryid = $siteobj->getPlatformCountryId();
?>
<div id="payment_success" class="col-md-12">
    <div id="content">
        <h1 class="page-title"><?= _('Payment Successful') ?></h1>
        <div>
            <?= _("Thank you for your purchase!") ?>
            <br><br>
            <?= sprintf(_("Your order number is %s."), $so->getSoNo()) ?>
            <br>
            <?= _("You will receive an order confirmation email with details of your order and a link to track its progress.") ?>
            <br>
            <?= _("Please note all charges will appear on your statement as CHATANDVISION.") ?>
            <br>
            <?= _("We'll send you an email as soon as it's shipped!") ?>
        </div>
    </div>
</div>
<?php if ($countryid == "GB") {  ?>
<script type="text/javascript"> var sa_values = { "site":22170 }; function saLoadScript(src) { var js = window.document.createElement("script"); js.src = src; js.type = "text/javascript"; document.getElementsByTagName("head")[0].appendChild(js); } var d = new Date(); if (d.getTime() - 172800000 > 1465980195000) saLoadScript("//www.shopperapproved.com/thankyou/rate/22170.js"); else saLoadScript("//direct.shopperapproved.com/thankyou/rate/22170.js?d=" + d.getTime()); </script>
<?php } elseif ($countryid == "AU") {  ?>
<script type="text/javascript"> var sa_values = { 'site':22171 }; function saLoadScript(src) { var js = window.document.createElement("script"); js.src = src; js.type = "text/javascript"; document.getElementsByTagName("head")[0].appendChild(js); } var d = new Date(); if (d.getTime() - 172800000 > 1465979711000) saLoadScript("//www.shopperapproved.com/thankyou/rate/22171.js"); else saLoadScript("//direct.shopperapproved.com/thankyou/rate/22171.js?d=" + d.getTime()); </script>
<?php }?>
<?php $this->load->view('footer') ?>
