<?php $this->load->view('header') ?>
<div id="payment_success" class="col-md-12">
    <div id="content">
        <h1 class="page-title"><?= _('Payment Review') ?></h1>
        <div>
            <?= _("Thank you for your purchase!") ?>
            <br><br>
            <p>
                <?=_('For enquiries, please contact us at sales@digitaldiscount.co.uk with the following information and we will capture your order:')?>
            </p>
            <p>
                <?=_('1. Name')?> <br>
                <?=_('2. Delivery Address')?> <br>
                <?=_('3. Phone Number')?> <br>
                <?=_('4. Full Name of Item(s)')?>
            </p>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
