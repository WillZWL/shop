<?php $this->load->view('header') ?>
<div id="payment_success" class="col-md-12">
    <div id="content">
        <h1 class="page-title"><?= _('Payment Fail') ?></h1>
        <div>
            <?= _("We're sorry. This payment has been declined by your card issuer.") ?>
            <br><br>
            <ul>
                <li><?= _("1 - make sure that the billing address entered matches your bank record") ?></li>
                <li><?= _("2 - double check your card details especially the CVV number (last 3 digits on the back of the card) and expiry date are correct") ?></li>
                <li><?= _("3 - check with your bank before re-attempting to order if payment was blocked for any particular reason and ask them to unblock your card if so") ?></li>
                <li><?= _("4 - try again with the same, or a different card") ?></li>
                <li><?= _("5 - still having trouble? ")?> <a href="/display/view/contact"> <?=_("Contact us")?></a> <?=_(" via email") ?></li>
            </ul>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
