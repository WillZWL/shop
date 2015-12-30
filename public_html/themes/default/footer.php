            </div>
            <footer id="footer" class="nostylingboxs">
                <div class="footer-middle " id="pavo-footer-middle">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <div class="panel panel-white pavreassurances margin_top">
                                    <div class="row">
                                        <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <span class="footer-legend"><?= _(' PAY WITH CONFIDENCE ') ?></span>
                                        </br>
                                            <fieldset class="footer-fieldset" style="background-color: #999 !important;border:0px !important;">

                                                <ul class="list-inline" style="margin-top:8px;">
                                                    <?php
                                                        $siteobj = \PUB_Controller::$siteInfo;
                                                        $countryid = $siteobj->getPlatformCountryId();

                                                        if ($countryid == "IT") {  ?>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/postepay.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/cartasi.png">
                                                        </li>
                                                    <?php } elseif ($countryid == "FR") {  ?>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/carte-bleue.png">
                                                        </li>
                                                    <?php } elseif ($countryid == "ES") {  ?>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/maestro.png">
                                                        </li>
                                                    <?php } elseif ($countryid == "PL") {  ?>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/Przelewy24.png">
                                                        </li>
                                                    <?php } ?>
                                                    <!-- only uncomment to check all the icons-->
                                                        <!--<li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/postepay.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/cartasi.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/carte-bleue.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/maestro.png">
                                                        </li>
                                                         <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/Przelewy24.png">
                                                        </li>-->
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/visa.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/visa-debit.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/mastercard.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/paypal.png">
                                                        </li>
                                                </ul>
                                           </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-middle " id="pavo-footer-middle">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <div class="panel panel-white pavreassurances margin_top">
                                    <div class="row">
                                        <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                            <span class="footer-legend"><?= _(' SHOP WITH CONFIDENCE ') ?></span>
                                        </br>
                                            <fieldset class="footer-fieldset" style="background-color: #999 !important;border:0px !important;">
                                                <ul class="list-inline" style="margin-top:8px;">
                                                <?php
                                                        $siteobj = \PUB_Controller::$siteInfo;
                                                        $countryid = $siteobj->getPlatformCountryId();

                                                        if ($countryid == "FR" || $countryid == "BE") {  ?>
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/ssl_fr.jpg">
                                                    </li>
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/chat_fr.jpg">
                                                    </li>
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/data_fr.jpg">
                                                    </li>
                                                <?php } else { ?>
                                                    <li class="footer-fieldsetitem">
                                                        <link href="http://www.reviewcentre.com/css/seo_badge.v3.css" rel="stylesheet" type="text/css">
                                                        <script type="text/javascript" src="http://www.reviewcentre.com/js/RC.SeoBadge.v3.min.js"></script>
                                                        <script type="text/javascript">RC.Badge.initialize("http://www.reviewcentre.com", 3709767)</script>
                                                        <div id="rc-badge-wrapper" class="style-150x100 color-gray" style="margin-bottom:-45px;width: 150px; height: 100px;">
                                                        <div class="rc-top-corners"></div>
                                                        <div class="rc-content">
                                                        <div class="rc-logo">
                                                        <a title="Review Centre - Consumer Reviews" href="http://www.reviewcentre.com">http://www.reviewcentre.com</a>
                                                        </div>
                                                        <p class="rc-rating"></p>
                                                        <div class="rc-stars"></div>
                                                        <div class="rc-overview">
                                                        <p class="rc-category"><a href="http://www.reviewcentre.com/products977.html" rel="nofollow">Online Electronic Shops</a></p>
                                                        <p class="rc-item"><a href="http://www.reviewcentre.com/Online-Electronic-Shops/Digital-Discount-www-digitaldiscount-co-uk-reviews_3709767" rel="nofollow">Digital Discount- www.digitaldiscount.co.uk</a></p>
                                                        <p class="rc-date"></p>
                                                        <p class="rc-extract"></p>
                                                        </div>
                                                        </div>
                                                        <div class="rc-write-review"><a href="http://www.reviewcentre.com/write-a-review-3709767.html" rel="nofollow">Write a review</a></div>
                                                        </div>
                                                    </li>
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/ssl_en.jpg">
                                                    </li>
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/chat_en.jpg">
                                                    </li>
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/data_en.jpg">
                                                    </li>
                                                <?php } ?>
                                                </ul>
                                           </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="footer-center" id="pavo-footer-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <div class="panel panel-gray pavreassurances margin_top">
                                    <div class="row">
                                        <div class="column col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                            <div class="panel-gray">
                                                <a href="<?= base_url('display/view/contact') ?>"><h4 class="panel-title"><?= _('About Us') ?></h4></a>
                                            </div>
                                            <p class="desc-about">
                                                <?= sprintf(_("%s prides itself on great deals without compromise on service! "), SITE_NAME) ?><br /> <?= _('Feel free to contact us anytime for more information') ?>
                                                <br>
                                            </p>
                                        </div>
                                        <div class="column col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                            <div class="panel-gray">
                                                <!--<h4 class="panel-title">Sign up for Newsletter</h4>

                                                <p class="desc-about"><?= _('Subscribe to get our latest news and promotions regularly.') ?></p>

                                                <form action="" method="post" role="form" >
                                                      <input class="input-newsletter" type="text" id="" name="" placeholder="Enter you email address">
                                                      <input type="submit" value="Sign up" class="btn-newsletter" />

                                              </form>-->
                                                <!-- Begin MailChimp Signup Form -->
                                                <div id="mc_embed_signup">
                                                    <form action="//digitaldiscount.us12.list-manage.com/subscribe/post?u=811fba9bd26f35cade0854986&id=c81a013806" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                                        <div id="mc_embed_signup_scroll">
                                                            <h4 class="panel-title"><?= _('Sign up for Newsletter') ?></h4>
                                                            <p class="desc-about"><?= _('Subscribe to get our latest news and promotions regularly.') ?></p>
                                                            <div class="mc-field-group">
                                                                <!--<label for="mce-EMAIL">Email Address <span class="asterisk">*</span>
                                                                </label>-->
                                                                <input type="email" value="" name="EMAIL" class="required email input-newsletter" id="mce-EMAIL" placeholder="<?= _('Enter you email address') ?> *">
                                                            </div>
                                                            <div id="mce-responses" class="clear">
                                                                <div class="response" id="mce-error-response" style="display:none"></div>
                                                                <div class="response" id="mce-success-response" style="display:none"></div>
                                                            </div> <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                                            <div style="position: absolute; left: -5000px;">
                                                                <input type="text" name="b_811fba9bd26f35cade0854986_c81a013806" tabindex="-1" value="">
                                                            </div>
                                                            <div class="clear">
                                                                <input type="submit" value="<?= _('Subscribe') ?>" name="subscribe" id="mc-embedded-subscribe" class="btn-newsletter">
                                                            </div>
                                                            </br>
                                                            <div class="indicates-required desc-about">
                                                                    <span class="asterisk">*</span> <?= _('indicates required') ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!--End mc_embed_signup-->
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 column">
                                            <ul class="list-inline guarantee">
                                                <li style="vertical-align: top;">
                                                    <div class="image-wrap">
                                                        <img src="/themes/default/asset/image/ssl.jpg" class="img-footer">
                                                    </div>
                                                    <p class="desc-sm">
                                                        <?= _('100% Secure ') ?><br /> <?= _('with SSL encryption') ?>
                                                    </p>
                                                </li>
                                                <li style="vertical-align: top;">
                                                    <div class="image-wrap">
                                                        <img src="/themes/default/asset/image/shipping.jpg" class="img-footer">
                                                    </div>
                                                    <p class="desc-sm">
                                                        <?= _('Free Delivery') ?><br /><?= _('For All Orders*') ?><br /><?= _('Shipping:') ?><br /><?= _('3-5 Working Days') ?>
                                                    </p>
                                                </li>
                                                <li style="vertical-align: top;">
                                                    <div class="image-wrap">
                                                        <?php
                                                            $siteobj = \PUB_Controller::$siteInfo;
                                                            $currencyId = $siteobj->getPlatformCurrencyId();
                                                            $countryid = $siteobj->getPlatformCountryId();
                                                            $guaranteedays = "14";
                                                            if ($countryid == "GB")
                                                                $guaranteedays = "30";
                                                            if (in_array($currencyId, array("GBP", "AUD", "EUR", "NZD"))) {
                                                        ?>
                                                            <img src=<?= '/themes/default/asset/image/moneyback_' . strtolower($currencyId) . ".jpg" ?>>
                                                        <?php } else {  ?>
                                                            <img src="/themes/default/asset/image/moneyback.jpg" class="img-footer">
                                                        <?php } ?>
                                                    </div>
                                                    <p class="desc-sm">
                                                        <?= sprintf(_("%s Days Money Back Guarantee"), $guaranteedays) ?>
                                                    </p>
                                                </li>
                                                <li style="vertical-align: top;">
                                                    <div class="image-wrap">
                                                    <?php
                                                        $siteobj = \PUB_Controller::$siteInfo;
                                                        $countryid = $siteobj->getPlatformCountryId();

                                                        if ($countryid == "FR" || $countryid == "BE") {  ?>
                                                        <img src="/themes/default/asset/image/warranty_fr.jpg" class="img-footer">
                                                    <?php } else { ?>
                                                        <img src="/themes/default/asset/image/warranty.jpg" class="img-footer">
                                                    <?php } ?>
                                                    </div>
                                                    <p class="desc-sm">
                                                        <?= _('Warranty ') ?><br/> <?= _('Up to 2 Years') ?>
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="panel-gray">
                                    <span class="desc-about" style="font-weight: 400;"><?= _('2015 All Right reserved') ?></span></br>
                                    <a href="<?= base_url('display/view/conditions_of_use') ?>"><span class="desc-about"><?= _('Conditions of Use') ?></span></a>&nbsp;|&nbsp;
                                    <a href='<?= base_url("display/view/shipping") ?>'><span class="desc-about"><?= _('Shipping') ?></span></a>&nbsp;|&nbsp;
                                    <a href="<?= base_url('display/view/privacy_policy') ?>"><span class="desc-about"><?= _('Privacy Policy') ?></span></a>
                                </div>
                            </div>
                            <!--<div class="column col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="panel-white">
                                        <h4 class="panel-title"><?= _('SELECT SHIPPING COUNTRY') ?></h4>
                                    <div>
                                        <select name="footer_custom_country_id" id="footer_custom_country_id" onchange="change_country(2)" tabindex="-1" style="padding-top: 4px;">
                                            <option class="desc-sm" value="en_AU" data-image="/images/icons/en_AU.png"><?= _('Australia') ?></option>
                                            <option value="fr_BE" data-image="/images/icons/fr_BE.png"><?= _('Belgium') ?></option>
                                            <option value="en_FI" data-image="/images/icons/en_FI.png"><?= _('Finland') ?></option>
                                            <option value="fr_FR" data-image="/images/icons/fr_FR.png"><?= _('France') ?></option>
                                            <option value="en_GB" data-image="/images/icons/en_GB.png"><?= _('Great Britain') ?></option>
                                            <option value="en_HK" data-image="/images/icons/en_HK.png" selected=""><?= _('Hong Kong') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <div class="sidebar-offcanvas sidebar  visible-xs visible-sm">
            <div class="offcanvas-inner panel panel-offcanvas">
                <div class="offcanvas-heading panel-heading">
                    <button type="button" class="btn btn-primary" data-toggle="offcanvas"> <span class="fa fa-times"></span></button>
                </div>
                <div class="offcanvas-body panel-body">
                    <div id="offcanvasmenu"></div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        $("#offcanvasmenu").html($("#bs-megamenu").html());
        </script>
    </div>
   </body>
</html>
