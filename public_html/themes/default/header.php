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
    <title><?= _('Digital Discount') ?></title>
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
                                <div id="logo-theme" class="logo-store pull-left">
                                    <a href="/">
                                        <span><?= _('digital discount') ?></span>
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
                                        <div data-toggle="dropdown" data-loading-text="Loading..." class="heading media dropdown-toggle">
                                            <div class="cart-inner media-body">
                                                <a>
                                                    <i class="icon-cart fa fa-shopping-cart"></i>
                                                    <span class="text-cart"><?= _('Shopping Cart') ?></span>
                                                    <span id="cart-total" class="cart-total"><?= sprintf(_('%s item(s) - %s%s'), "0", "$", "0.00") ?>/span>
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <ul class="dropdown-menu content">
                                            <li>
                                                <p class="text-center"><?= _('Your shopping cart is empty!') ?></p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="header-bot">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-3 col-sm-3 col-md-3 hidden-xs hidden-sm top-verticalmenu">
                                <div class="menu-heading d-heading">
                                    <h4>
                                        <?= _('categories              ') ?><span class="fa fa-angle-down pull-right"></span>
                                    </h4>
                                </div>
                                <div id="pav-verticalmenu" class="pav-verticalmenu">
                                    <div class="menu-content d-content">
                                        <div class="pav-verticalmenu fix-top hidden-xs hidden-sm">
                                            <div class="navbar navbar-verticalmenu">
                                                <div class="verticalmenu" role="navigation">
                                                    <div class="navbar-header">
                                                        <a href="javascript:;" data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle">
                                                            <span class="icon-bar"></span>
                                                            <span class="icon-bar"></span>
                                                            <span class="icon-bar"></span>
                                                        </a>
                                                        <div class="collapse navbar-collapse navbar-ex1-collapse">
                                                            <ul class="nav navbar-nav verticalmenu">
                                                                <li class="topdropdow parent dropdown ">
                                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=""></i><span class="menu-title"><?= _('SmartPhones') ?></span><b class="caret"></b></a>
                                                                    <div class="dropdown-menu" style="width:700px">
                                                                        <div class="dropdown-menu-inner">
                                                                            <div class="row">
                                                                                <div class="mega-col col-md-6 ">
                                                                                    <div class="mega-col-inner">
                                                                                        <div class="pavo-widget">
                                                                                            <div class="pavo-widget">
                                                                                                <div class="">
                                                                                                    <ul class="content list-unstyled">
                                                                                                        <li> <a href="#_63"> <span><?= _('Mobile Phones') ?></span> </a> </li>
                                                                                                        <li> <a href="#_36"> <span><?= _('Telephones') ?></span> </a> </li>
                                                                                                        <li> <a href="#_60"> <span><?= _('Media Players') ?></span> </a> </li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="topdropdow parent dropdown ">
                                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=""></i><span class="menu-title"><?= _('Webcams') ?></span><b class="caret"></b></a>
                                                                    <div class="dropdown-menu" style="width:700px">
                                                                        <div class="dropdown-menu-inner">
                                                                            <div class="row">
                                                                                <div class="mega-col col-md-6 ">
                                                                                    <div class="mega-col-inner">
                                                                                        <div class="pavo-widget">
                                                                                            <div class="pavo-widget">
                                                                                                <div class="">
                                                                                                    <ul class="content list-unstyled">
                                                                                                        <li> <a href="#_63"> <span><?= _('HD Webcams') ?></span> </a> </li>
                                                                                                        <li> <a href="#_36"> <span><?= _('TV Webcams') ?></span> </a> </li>
                                                                                                        <li> <a href="#_60"> <span><?= _('IP Camerass') ?></span> </a> </li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="bg1 topdropdow parent dropdown ">
                                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=""></i><span class="menu-title"><?= _('Headsets and Microphones') ?></span><b class="caret"></b></a>
                                                                    <div class="dropdown-menu" style="width:700px">
                                                                        <div class="dropdown-menu-inner">
                                                                            <div class="row">
                                                                                <div class="mega-col col-md-6 ">
                                                                                    <div class="mega-col-inner">
                                                                                        <div class="pavo-widget">
                                                                                            <div class="pavo-widget">
                                                                                                <div class="">
                                                                                                    <ul class="content list-unstyled">
                                                                                                        <li> <a href="#_63"> <span><?= _('Wired Headsets') ?></span> </a> </li>
                                                                                                        <li> <a href="#_36"> <span><?= _('Bluetooth Headsets') ?></span> </a> </li>
                                                                                                        <li> <a href="#_60"> <span><?= _('USB Headset') ?></span> </a> </li>
                                                                                                        <li> <a href="#_60"> <span><?= _('Wireless Headsets') ?></span> </a> </li>
                                                                                                        <li> <a href="#_60"> <span><?= _('In-Ear Headphones') ?></span> </a> </li>
                                                                                                        <li> <a href="#_60"> <span><?= _('Gaming Headsets') ?></span> </a> </li>
                                                                                                        <li> <a href="#_60"> <span><?= _('Microphones') ?></span> </a> </li>
                                                                                                        <li> <a href="#_60"> <span><?= _('Microphones + speakers (speakerphones)') ?></span> </a> </li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="">
                                                                    <a href="#"><i class=""></i><span class="menu-title"><?= _('Conference Phones') ?></span></a>
                                                                </li>
                                                                <li class="">
                                                                    <a href="#"><i class=""></i><span class="menu-title"><?= _('Digital Imaging') ?></span></a>
                                                                </li>
                                                                <li class="">
                                                                    <a href="#"><i class=""></i><span class="menu-title"><?= _('Tablets') ?></span></a>
                                                                </li>
                                                                <li class="">
                                                                    <a href="#"><i class=""></i><span class="menu-title"><?= _('Computing') ?></span></a>
                                                                </li>
                                                                <li class="">
                                                                    <a href="#"><i class=""></i><span class="menu-title"><?= _('Accessories') ?></span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div id="pav-mainnav" class="hidden-xs hidden-sm pull-left">
                                    <nav id="pav-megamenu" class="navbar">
                                        <div class="navbar-header">
                                            <button data-toggle="offcanvas" class="btn btn-primary canvas-menu hidden-lg hidden-md" type="button"><span class="fa fa-bars"></span><?= _(' Menu') ?></button>
                                        </div>
                                        <!--<div class="collapse navbar-collapse" id="bs-megamenu">-->
                                        <!--    <ul class="nav navbar-nav megamenu">-->
                                        <!--        <li class=""><a href="#"><span class="menu-title">DEALS</span></a></li>-->
                                        <!--        <li class=""><a href="#"><span class="menu-title">WARRANTY</span></a></li>-->
                                        <!--        <li class=""><a href="#"><span class="menu-title">CONTACT</span></a></li>-->
                                        <!--        <li class=""><a href="#"><span class="menu-title">HELP</span></a></li>-->
                                        <!--        <li class=""><a href="#"><span class="menu-title">BULK SALES</span></a></li>-->
                                        <!--    </ul>-->
                                        <!--</div>-->
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- /header -->
            <div class="main-columns container">