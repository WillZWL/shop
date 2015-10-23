<?php $this->load->view('header') ?>
<div id="payment_success" class="col-md-12">
    <div id="content">
        <h1 class="page-title"><?= _('Payment Review') ?></h1>
        <div>
            <?= _("Thank you for your purchase!") ?>
            <br><br>
            <?= sprintf(_("Your order is in a queue for the payment to be checked and processed by our payment service provider [%s] which can take a few hours to a couple of days’ time. Money has not been taken from your credit card yet."), ucfirst(strtolower($soPaymentStatus->getPaymentGatewayName()))) ?>
            <?= _("We'll send you an email as soon as the payment has been verified!") ?>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
