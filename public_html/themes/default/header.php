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
    <meta name="description" content="Lexus Motozz - Responsive Opencart Theme" />
    <meta name="keywords" content="Lexus Motozz - Responsive Opencart Theme" />
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
    <!--<script type="text/javascript" src="/themes/default/asset/js/owl.carousel.min.js"></script>-->
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
                            <div class="logo inner  col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="logo-store pull-left">
                                    <a href="/">
                                        <img src='<?=base_url("/images/logo/" . SITE_LOGO)?>' class="img-responsive img-logo">
                                    </a>
                                </div>
                            </div>
                            <!--<div id="search" class="pull-left col-lg-5 col-md-5 col-sm-12 col-xs-12">-->
                            <!--    <div class="quick-access">-->
                            <!--        <div class="input-group pull-right">-->
                            <!--            <input type="text" name="search" value="" placeholder="Search" class="form-control" />-->
                            <!--            <span class="input-group-btn">-->
                            <!--                <button type="button" class="button-search"><i class="fa fa-search"></i></button>-->
                            <!--            </span>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div id="cart-top" class=" inner col-lg-4 col-md-4 col-sm-12 hidden-xs">
                                <div class="cart-top">
                                    <div id="cart" class="pull-right clearfix">
<!--                                        <div data-toggle="dropdown" data-loading-text="Loading..." class="heading media dropdown-toggle">-->
                                            <div class="cart-inner media-body">
                                                <a href="/ReviewOrder">
                                                    <i class="icon-cart fa fa-shopping-cart"></i>
                                                    <span class="text-cart"><?= _('Shopping Cart') ?></span>
                                                    <span id="cart-total" class="cart-total"><?= sprintf(_('%s item(s) - %s%s'), $_SESSION["CART_QUICK_INFO"]["TOTAL_NUMBER_OF_ITEMS"], "$", $_SESSION["CART_QUICK_INFO"]["TOTAL_AMOUNT"]) ?></span>
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                            </div>
    <!--                                    </div> -->
<!--
                                        <ul class="dropdown-menu content">
                                            <li>
                                                <p class="text-center"><?= _('Your shopping cart is empty!') ?></p>
                                            </li>
                                        </ul>
-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('/default/category'); ?>
            </header>
            <!-- /header -->
            <div class="main-columns container">
