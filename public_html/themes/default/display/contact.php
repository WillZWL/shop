<div class="contact">
    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="center-block">
                    <div class="">
                        <h2 class="text-left"><?=_('CONTACT US')?></h2>
                        <p class="text-left note-alert">
                            <?=_('Having an issue? There are a number of ways you can get in touch with our experienced Sales and Customer Service teams. ')?>
                            <?=_("Select one of the enquiry types below and we'll repond to you as soon as we can!")?>
                        </p>
                    </div>
                    <div class="container-fluid img-box">
                        <div class="row">
                            <div class="col-md-4">
                                <a class="iframe-link" data-toggle="tooltip" data-placement="top"
                                    href="<?= $contact_url_1 ?>" title=""
                                    data-original-title="<?=_('GENERAL AND AFTER-SALES')?>">
                                    <div class="box img1 container-fluid">
                                        <?=_('GENERAL AND AFTER-SALES')?>
                                    </div>
                                </a>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-md-4">
                                <a class="iframe-link" data-toggle="tooltip" data-placement="top"
                                    href="<?= $contact_url_2 ?>" title=""
                                    data-original-title="<?=_('PRE-SALES')?>">
                                    <div class="box img2 container-fluid">
                                        <?=_('PRE-SALES')?>
                                    </div>
                                </a>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-md-4">
                                <a class="iframe-link" data-toggle="tooltip" data-placement="top"
                                    href="<?= $contact_url_3 ?>" title=""
                                    data-original-title="<?=_('FAULTY GOODS OR RETURNED ITEMS')?>">
                                    <div class="box img3 container-fluid">
                                        <?=_('FAULTY GOODS OR RETURNED ITEMS')?>
                                    </div>
                                </a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-left">
                        <div>
                            <p class="addr"><?=_('Our Address')?>&nbsp;: &nbsp;</p>
                            <p class="addr">
                                <p>
                                ChatandVision (HK) Limited, Flat/RM 12, 25/F Langham Place Office Tower 8 Argyle Street, Kowloon, Hong Kong
                                <br/>
                                Chatandvision (UK) Limited, Dalton House, 60 Windsor Avenue, London, SW19 2RR
                                </p>
                            </p>
                        </div>
                        <?php if ($contact_email) : ?>
                        <div class="text-left note-alert">
                            <?=_('Email')?>&nbsp;: <?=$contact_email?>
                        </div>
                        <?php endif; ?>
                         <?php if ($contact_tel) : ?>
                        <div class="text-left note-alert">
                            <?=_('Tel')?>&nbsp;: <?=$contact_tel?> <?=$contact_hotline?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
</div>