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
    <script type="text/javascript" src="/themes/default/asset/js/raphael-min.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/jquery.easing.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/checkform.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="/themes/default/asset/js/iview.js"></script>
</head>
<body class="common-home page-common-home layout-fullwidth ">
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
                                    <div class="pull-left top-desc col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                       <span style="margin-left: 10px;">
                                          We won't be beaten on electronics!
                                       </span>
                                       <img src="/themes/default/asset/image/icon-bag.png">
                                    </div>
                                    <div class="header-links col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                        <!--<a href="/warranty"><span class=""><?= _('Warranty') ?></span></a>&nbsp;&nbsp;&nbsp;-->
                                        <a href="<?=base_url()?>display/view/contact"><span class="desc-about"><?= _('Contact Us') ?></span></a>&nbsp;&nbsp;&nbsp;
                                        <a href="<?=base_url()?>display/view/conditions_of_use"><span class="desc-about"><?= _('Help') ?></span></a>&nbsp;&nbsp;&nbsp;
                                        <!--<a href="/clearance"><span class="clearance"><?= _('Clearance') ?></span></a>&nbsp;&nbsp;&nbsp;-->
                                        <?php if ($_SESSION["client"]["loggedIn"]) { ?>
                                            <a class="desc-about" href="<?=base_url()?>myaccount/index">
                                                <?= _("My Account") ?></a>
                                        <?php } else { ?>
                                              <a  class="desc-about" href="<?=base_url()?>login/index">
                                                <?= _("Sign in") ?></a>
                                        <?php } ?>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <ul class="list-inline header-desc">
                                            <li >
                                                <img src="/themes/default/asset/image/icon-refund.png">
                                                <span><?= _("14 Days Money Back Guarantee") ?></span>
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
					$lang_id = substr(SITE_LANG, 0, 2);
					//$menu_script = file_get_contents(APPPATH."views/template/menu/". $lang_id."/menu_".strtolower(PLATFORM).".html", true);
					$menu_script = file_get_contents(APPPATH."views/template/menu/en/menu_webgb.html", true);
					print $menu_script;
				?>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 top-verticalmenu">
                                <div class="quick-access col-lg-10 col-md-10 col-sm-8 col-xs-8">
                                    <div id="search" class="input-group pull-right" style="margin-top: 4px;height: 24px !important;">
                                        <input type="text" name="search" value="" placeholder="Search" class="form-control"  style="height: 24px !important;" />
                                        <span class="input-group-btn">
                                            <button type="button" class="button-search" style="height: 24px !important;"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                    <span class="suggestions-title">Top search:</span>
                                    <span class="suggestions">Sleepace, New tab s2, ghostdrone, iPhone 5c</span>
                                </div>
                                <div id="cart-top" class=" inner">
                                    <div class="cart-top">
                                        <div id="cart" class="pull-right clearfix">
                                            <div class="cart-inner media-body">
                                                <a href="/ReviewOrder">
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
                </div>
            </header>
            <!-- /header -->
            <div class="main-columns container">
				<div class="bottom-offcanvas">
					<div class="container">
						<button data-toggle="offcanvas" class="btn btn-primary visible-xs visible-sm" type="button"><i class="fa fa-bars"></i></button>
					</div>
				</div>
