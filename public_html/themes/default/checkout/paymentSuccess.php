<?php $this->load->view('header') ?>
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
<?php $this->load->view('footer') ?>
