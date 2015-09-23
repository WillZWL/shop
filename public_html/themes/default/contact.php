<?php $this->load->view('header') ?>
    <div id="contact-container">
        <h1><?= _('CONTACT US') ?></h1>
        <h4><?= _("We're here to help you!") ?></h4>
        <div class="text-block">
            <?= _('Our principal route for all enquiries is via our Sales and Customer Care Team, which has gained extensive experience and knowledge of our products and services in order to serve our customers better.') ?>
             <?= _('Our goal is to assist you with your queries in a friendly and helpful manner.') ?>
        </div>
        <div class="text-block address">
            <?= _('DigitalDiscount.co.uk Flat/RM 12, Tower 8, 25/F Langham Place Office Argyle Street, Kowloon, Hong Kong') ?>
            <br>
            <br>
            <?= _('Tel:') ?> 0870 295 9128
        </div>
        <h4><?= _('Email Enquiries') ?></h4>
        <div class="text-block">
            <?= _('Our ticketing system is designed to ensure that your queries are responded by the most qualified staff and as quickly as possible.') ?>
             <?= _('In order to take advantage of this, please kindly choose the correct department and the most relevant "Query".') ?>
        </div>
        <div class="query-form">
            <form method="post" action="/contact/queryForm">
              <div class="form-group">
                <label for="query"><?= _('Subject') ?></label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
              </div>
              <div class="form-group">
                <label for="query"><?= _('Message') ?></label>
                <textarea placeholder="Message" id="message" name="message" class="form-control" rows="3"></textarea>
              </div>
              <div class="form-group">
                <label for="query"><?= _('Your Name') ?></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name">
              </div>
              <div class="form-group">
                <label for="query"><?= _('Email') ?></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
              </div>
              <div class="form-group">
                <label for="query"><?= _('Order Number') ?></label>
                <input type="text" class="form-control" id="orderNumber" name="orderNumber" placeholder="Order Number">
              </div>
              <button type="submit" class="btn btn-default"><?= _('Submit') ?></button>
            </form>
        </div>
    </div>
<?php $this->load->view('footer') ?>
