<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="en" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="en" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" class="ltr" lang="en">
<!--<![endif]-->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= SITE_NAME ?></title>
    <meta name="description" content="Digital discount" />
    <meta name="keywords" content="Digital discount" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" type="image/png" href="/themes/default/asset/image/favicon_<?= strtolower(PLATFORM) ?>.png" />

    <link href="/themes/default/asset/image/catalog/cart.png" rel="icon" />
    <link href="/themes/default/asset/css/bootstrap.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/stylesheet.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/animate.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/magnific-popup.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/fonts.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/homebuilder.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/typo.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/pavnewsletter.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/owl.carousel.css" rel="stylesheet" />
    <link href="/themes/default/asset/css/theme.css" rel="stylesheet" />
    <script type="text/javascript" src="/themes/default/asset/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/jquery.magnific-popup.min.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/common.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/common_theme.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/jquery.scrollTo.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/jquery.serialScroll.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/jquery.jscroll.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/jquery.easing.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/checkform.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/iview.js"></script>
</head>
<body class="common-home page-common-home layout-fullwidth ">

    <?php
        $siteobj = \PUB_Controller::$siteInfo;
        $platCountryId = $siteobj->getPlatformCountryId();
        switch (strtolower($platCountryId)) {
            case 'gb' :
                $searchspring_site_id = 'jdajtq';
                $GTM_ID = 'GTM-MHPP6T';
                break;

            case 'es' :
                $searchspring_site_id = '7g2sk7';
                $GTM_ID = 'GTM-TC6F2D';
                break;

            case 'au' :
                $searchspring_site_id = 'dkow9j';
                $GTM_ID = 'GTM-NNL2JB';
                break;

            case 'nz' :
                $searchspring_site_id = '61jj96';
                $GTM_ID = 'GTM-K3GW2F';
                break;

            case 'it' :
                $searchspring_site_id = '1eq9mh';
                $GTM_ID = 'GTM-T33Z3B';
                break;

            case 'fr' :
                $searchspring_site_id = 'rtkr86';
                $GTM_ID = 'GTM-MQ9RSX';
                break;

            case 'be' :
                $searchspring_site_id = 'm15dls';
                $GTM_ID = 'GTM-MPJWJQ';
                break;

            case 'pl' :
                $searchspring_site_id = 'yf45du';
                $GTM_ID = 'GTM-TXWWKC';
                break;

            default   :
                $searchspring_site_id = '';
                $GTM_ID = '';
        }
    ?>

<!-- Google Tag Manager -->
<noscript>
    <iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $GTM_ID; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<script>
    $(document).ready(function(){
        var p = $('.pav-verticalmenu.fix-top.hidden-xs.hidden-sm');
        //console.log("obj:", p);
        $('.dropdown-menu').css("min-height", p.height());
    });

    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo $GTM_ID; ?>');

</script>
<!-- End Google Tag Manager -->

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true" id="pleaseWaitDialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="pleaseWaitDialogHeader">Processing ...</h3>
                </div>
                <div class="modal-body">
                    <div class="progress progress-striped active">
                        <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row-offcanvas row-offcanvas-left">
        <div id="page">
            <!-- header -->
            <header id="header-layout" class="header-v1">
                <div id="header-main">
                    <div class="container">
                        <div class="row">
                            <div class="logo inner  col-lg-3 col-md-3 col-sm-4 col-xs-4">
                                <div class="logo-store pull-left">
                                    <a href="/">
                                        <img src='<?=base_url("/images/logo/" . SITE_LOGO)?>' class="img-responsive img-logo">
                                    </a>
                                </div>
                            </div>
                            <div class="logo inner col-lg-9 col-md-9 col-sm-8 col-xs-8">
                                <div class="row">
                                    <div class="pull-left top-desc col-lg-6 col-md-6 hidden-sm hidden-xs">
                                       <span style="margin-left: 10px;">
                                          <?=_("We won't be beaten on electronics!")?>
                                       </span>
                                       <img src="/themes/default/asset/image/icon-bag.png">
                                    </div>
                                    <div class="pull-right header-links col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <!--<a href="/warranty"><span class=""><?= _('Warranty') ?></span></a>&nbsp;&nbsp;&nbsp;-->
                                        <a href="<?=base_url()?>display/view/contact"><span class="desc-about"><?= _('Contact Us') ?></span></a>&nbsp;&nbsp;&nbsp;
                                        <a href="<?=base_url()?>display/view/conditions_of_use"><span class="desc-about"><?= _('Help') ?></span></a>&nbsp;&nbsp;&nbsp;
                                        <!--<a href="/clearance"><span class="clearance"><?= _('Clearance') ?></span></a>&nbsp;&nbsp;&nbsp;-->
                                        <?php if ($_SESSION["client"]["loggedIn"]) { ?>
                                            <a class="desc-about" href="<?=base_url()?>myaccount/index"><?= _("My Account") ?></a>&nbsp;&nbsp;&nbsp;
                                            <a class="desc-about" href="<?=base_url()?>Logout/index"><?= _("Log Out") ?></a>
                                        <?php } else { ?>
                                              <a  class="desc-about" href="<?=base_url()?>login/index">
                                                <?= _("Sign in") ?></a>
                                        <?php } ?>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12  hidden-sm hidden-xs">
                                        <ul class="list-inline header-desc">
                                            <li >
                                            <?php
                                                $currencyId = $siteobj->getPlatformCurrencyId();
                                                $countryid = $siteobj->getPlatformCountryId();
                                                $guaranteedays = "14";
                                                if ($countryid == "GB")
                                                    $guaranteedays = "30";
                                                //var_dump($siteobj);
                                                if (in_array($currencyId, array("GBP", "AUD", "EUR", "NZD"))) {
                                            ?>
                                                <img src=<?= '/themes/default/asset/image/icon-refund_' . strtolower($currencyId) . ".png" ?>>
                                            <?php } else {  ?>
                                                <img src="/themes/default/asset/image/icon-refund.png">
                                            <?php } ?>
                                                <span><?= sprintf(_("%s Days Money Back Guarantee"), $guaranteedays) ?></span>
                                            </li>
                                            <li >
                                                <img src="/themes/default/asset/image/icon-truck.png">
                                                <span><?= _("Free Delivery For All Orders") ?></span>
                                            </li>
                                            <li >
                                                <img src="/themes/default/asset/image/icon-win.png">
                                                <span><?= _("Up to 2 Years Warranty") ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div id="cart-top" class=" inner hidden-lg hidden-md col-sm-8 col-xs-8">
                                        <div class="cart-top">
                                            <div id="cart" class="pull-right clearfix">
                                                <div class="cart-inner media-body">
                                                    <a href="/revieworder">
                                                        <img src="/themes/default/asset/image/icon-cart.png">
                                                        <span id="cart-total" class="cart-total"><?= sprintf(_('%s item(s) - %s%s'), $_SESSION["CART_QUICK_INFO"]["TOTAL_NUMBER_OF_ITEMS"], "$", $_SESSION["CART_QUICK_INFO"]["TOTAL_AMOUNT"]) ?></span>
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">

                        </div>
                    </div>
                </div>
                <div id="header-bot">
                       <div class="container">
                          <div class="row">
                <?php
                    $lang_id = $siteobj->getLangId();
                    $menu_script = file_get_contents(APPPATH."views/template/menu/". $lang_id."/menu_".strtolower(PLATFORM).".html", true);
                    //$menu_script = file_get_contents(APPPATH."views/template/menu/en/menu_webgb.html", true);
                    print $menu_script;
                ?>
                            <div class="col-lg-9 col-md-9 hidden-sm hidden-xs top-verticalmenu">
                                <div class="quick-access col-lg-9 col-md-9 hidden-sm hidden-xs">
                                    <!--<div id="search" class="input-group pull-right" style="margin-top: 4px;height: 24px !important;">-->
                                        <!--<input type="text" name="search" value="" placeholder="Search" class="form-control"  style="height: 24px !important;" />
                                        <span class="input-group-btn">
                                            <button type="button" class="button-search" style="height: 24px !important;"><i class="fa fa-search"></i></button>
                                        </span>-->
                                        <form id="search" class="input-group pull-right" style="margin-top: 4px;height: 24px !important;" action="/search/search-by-ss" name="searchform" method="GET">
                                            <input type="text" autocomplete="off" onfocus="this.value=''''" value="" name="w" class="searchspring-query input form-control" title="<?= _('Find your product') ?>" style="height: 24px !important;">
                                            <span class="input-group-btn">
                                                <button type="button" class="button-search" style="height: 24px !important;"><i class="fa fa-search"></i></button>
                                            </span>
                                        </form>
                                        <link rel="stylesheet" type="text/css" href="https://d2r7ualogzlf1u.cloudfront.net/autocomplete/autocomplete.css">
                                        <link rel="stylesheet" type="text/css" href="/themes/default/asset/css/searchspring.css">
                                        <script type="text/javascript" src="https://d2r7ualogzlf1u.cloudfront.net/autocomplete/searchspring-autocomplete.min.js"></script>
                                        <script type="text/javascript">
                                            SearchSpring.Autocomplete.init({
                                                siteId: '<?php echo $searchspring_site_id; ?>',
                                                queryClass : "searchspring-query",
                                                currencySymbol: '<?php echo $currencyId; ?>',
                                                offsetY: 10,
                                                offsetX: -15
                                            });
                                        </script>
                                    <!--</div>-->
                                    <span class="suggestions-title"><?= _('Top search').':' ?></span>
                                    <span class="suggestions">Sleepace, New tab s2, ghostdrone, iPhone 5c</span>
                                </div>
                                <div id="cart-top" class=" inner">
                                    <div class="cart-top">
                                        <div id="cart" class="pull-right clearfix">
                                            <div class="cart-inner media-body">
                                                <a href="/review-order">
                                                    <img src="/themes/default/asset/image/icon-cart.png">
                                                    <span id="cart-total" class="cart-total"><?= sprintf(_('%s item(s) - %s'), $_SESSION["CART_QUICK_INFO"]["TOTAL_NUMBER_OF_ITEMS"], platform_curr_format($_SESSION["CART_QUICK_INFO"]["TOTAL_AMOUNT"])) ?></span>
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- /header -->
            <div class="main-columns container">
                <div class="bottom-offcanvas">
                    <div class="container">
                        <button data-toggle="offcanvas" class="btn btn-primary visible-xs visible-sm" type="button"><i class="fa fa-bars"></i></button>
                    </div>
                </div>
