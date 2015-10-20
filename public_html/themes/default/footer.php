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
                                            <fieldset class="footer-fieldset" style="background-color: #999 !important;">

                                                <ul class="list-inline" style="margin-top:8px;">
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/paypal.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/visa.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/visa-debit.png">
                                                        </li>
                                                        <li class="footer-fieldsetitem">
                                                            <img src="/themes/default/asset/image/mastercard.png">
                                                        </li>
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
                                                        <li class="footer-fieldsetitem">
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
                                            <fieldset class="footer-fieldset" style="background-color: #999 !important;">
                                                <ul class="list-inline" style="margin-top:8px;">
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/Confidence_thawte_EN.jpg">
                                                    </li>
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/Confidence_chat_EN.jpg">
                                                    </li>
                                                    <li class="footer-fieldsetitem">
                                                        <img src="/themes/default/asset/image/Confidence_safe_EN.jpg">
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
                <div  class="footer-center" id="pavo-footer-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <div class="panel panel-gray pavreassurances margin_top">
                                    <div class="row">
                                        <div class="column col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                            <div class="panel-gray">
                                                <a href="<?= base_url('display/view/about_us') ?>"><h4 class="panel-title"><?= _('About Us') ?></h4></a>
                                            </div>
                                            <p class="desc-about">
                                                <?= _('DigitalDiscount prides itself on great deals without compromise on service! ') ?><br /> <?= _('Feel free to contact us anytime for more information') ?>
                                                <br>
                                            </p>
                                        </div>
                                        <div class="column col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                            <div class="panel-gray">
                                                <h4 class="panel-title">Sign up for Newsletter</h4>

                                                <p class="desc-about"><?= _('Subscribe to get our latest news and promotions regularly.') ?></p>

                                                <form action="" method="post" role="form" >
                                                      <input class="input-newsletter" type="text" id="" name="" placeholder="Enter you email address">
                                                      <input type="submit" value="Sign up" class="btn-newsletter" />

                                              </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 column">
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
                                                            //var_dump($siteobj);
                                                            if (in_array($currencyId, array("GBP", "AUD", "EUR", "NZD"))) {
                                                        ?>
                                                            <img src=<?= '/themes/default/asset/image/moneyback_' . strtolower($currencyId) . ".jpg" ?>>
                                                        <?php } else {  ?>
                                                            <img src="/themes/default/asset/image/moneyback.jpg" class="img-footer">
                                                        <?php } ?>
                                                    </div>
                                                    <p class="desc-sm">
                                                        <?= _('14 days ') ?><br/> <?= _('Money Back') ?><br /><?= _('Guarantee') ?><br />
                                                    </p>
                                                </li>
                                                <li style="vertical-align: top;">
                                                    <div class="image-wrap">
                                                        <img src="/themes/default/asset/image/warranty.jpg" class="img-footer">
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
