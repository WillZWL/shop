<?php $this->load->view('/default/header') ?>
<!-- header -->
<header id="header-layout" class="header-v1">
    <div id="topbar">
        <div class="container">
            <div class="login pull-left hidden-xs hidden-sm">
                <ol class="breadcrumb">
                    <li><a class="" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/account">My Account</a></li>
                    <li><a class="wishlist" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/wishlist" id="wishlist-total">Wish List (0)</a></li>
                    <li><a class="shoppingcart" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=checkout/cart">Shopping Cart</a></li>
                    <li><a class="last" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=checkout/checkout">Checkout</a></li>
                    <li> <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/register">Register</a></li>
                    <li> <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/login">Login</a> </li>
                </ol>
            </div>
            <!-- Show Mobile -->
            <div class="show-mobile hidden-lg hidden-md pull-right">
                <div class="quick-user pull-left">
                    <div class="quickaccess-toggle">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="inner-toggle">
                        <div class="login links">
                            <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/register">Register</a>
                            <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/login">Login</a>
                        </div>
                    </div>
                </div>
                <div class="quick-access pull-left">
                    <div class="quickaccess-toggle">
                        <i class="fa fa-list"></i>
                    </div>
                    <div class="inner-toggle">
                        <ul class="links pull-left">
                            <li><a class="wishlist" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/wishlist" id="mobile-wishlist-total"><i class="fa fa-list-alt"></i>Wish List (0)</a></li>
                            <li><a class="shoppingcart" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=checkout/cart"><i class="fa fa-bookmark"></i>Shopping Cart</a></li>
                            <li><a class="last checkout" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=checkout/checkout"><i class="fa fa-share"></i>Checkout</a></li>
                            <li><a class="account" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=account/account"><i class="fa fa-user"></i>My Account</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End -->
            <div class="current-lang pull-right">
                <div class="btn-group box-language">
                    <div class="pull-left">
                        <form action="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=common/language/language" method="post" enctype="multipart/form-data" id="language">
                            <div class="btn-group dropdown">
                                <button class="btn-link dropdown-toggle" data-toggle="dropdown">
                                    <img src="/themes/default/asset/image/flags/gb.png" alt="English" title="English">
                                    <span class="hidden-xs hidden-sm hidden-md">Language</span> <i class="fa fa-caret-down"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="en"><img src="/themes/default/asset/image/flags/gb.png" alt="English" title="English" /> English</a>
                                    </li>
                                    <li>
                                        <a href="ar"><img src="/themes/default/asset/image/flags/ar.png" alt="Arabic" title="Arabic" /> Arabic</a>
                                    </li>
                                </ul>
                            </div>
                            <input type="hidden" name="code" value="" />
                            <input type="hidden" name="redirect" value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=common/home" />
                        </form>
                    </div>
                </div>
                <!-- currency -->
                <div class="btn-group box-currency">
                    <div class="pull-left">
                        <form action="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=common/currency/currency" method="post" enctype="multipart/form-data" id="currency">
                            <div class="btn-group dropdown">
                                <button class="btn-link dropdown-toggle" data-toggle="dropdown">
                                    <strong>$</strong>
                                    <span class="hidden-xs hidden-sm hidden-md">Currency</span> <i class="fa fa-caret-down"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="currency-select btn-link btn-block" type="button" name="EUR">€ Euro</button>
                                    </li>
                                    <li>
                                        <button class="currency-select btn-link btn-block" type="button" name="GBP">£ Pound Sterling</button>
                                    </li>
                                    <li>
                                        <button class="currency-select btn-link btn-block" type="button" name="USD">$ US Dollar</button>
                                    </li>
                                </ul>
                            </div>
                            <input type="hidden" name="code" value="" />
                            <input type="hidden" name="redirect" value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=common/home" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="header-main">
        <div class="container">
            <div class="row">
                <div class="logo inner  col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div id="logo-theme" class="logo-store pull-left">
                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=common/home">
                            <span>Motozz</span>
                        </a>
                    </div>
                </div>
                <div id="search" class="pull-left col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="quick-access">
                        <div class="input-group pull-right">
                            <input type="text" name="search" value="" placeholder="Search" class="form-control" />
                            <span class="input-group-btn">
                                <button type="button" class="button-search"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div id="cart-top" class=" inner col-lg-4 col-md-4 col-sm-12 hidden-xs">
                    <div class="cart-top">
                        <div id="cart" class="pull-right clearfix">
                            <div data-toggle="dropdown" data-loading-text="Loading..." class="heading media dropdown-toggle">
                                <div class="cart-inner media-body">
                                    <a>
                                        <i class="icon-cart fa fa-shopping-cart"></i>
                                        <span class="text-cart">Shopping Cart</span>
                                        <span id="cart-total" class="cart-total">0 item(s) - $0.00</span>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                </div>
                            </div>
                            <ul class="dropdown-menu content">
                                <li>
                                    <p class="text-center">Your shopping cart is empty!</p>
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
                            categories              <span class="fa fa-angle-down pull-right"></span>
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
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24" class="dropdown-toggle" data-toggle="dropdown"><i class=""></i><span class="menu-title">Electronics</span><b class="caret"></b></a>
                                                        <div class="dropdown-menu" style="width:300px">
                                                            <div class="dropdown-menu-inner">
                                                                <div class="row">
                                                                    <div class="mega-col col-md-12 sidebar">
                                                                        <div class="mega-col-inner">
                                                                            <div class="pavo-widget">
                                                                                <div class="pavo-widget">
                                                                                    <h4 class="widget-heading">Products Latest</h4>
                                                                                    <div class="widget-content ">
                                                                                        <div class="widget-inner list products-row">
                                                                                            <div class="w-product product-col clearfix col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                                                <div class="product-block">
                                                                                                    <div class="image pull-left">
                                                                                                        <span class="product-label sale-exist"><span class="product-label-special">Sale</span></span>
                                                                                                        <div class="product-img img">
                                                                                                            <a class="img" title="Apple iPhone 6 128GB" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=50">
                                                                                                                <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/2-100x100.jpg" title="Apple iPhone 6 128GB" alt="Apple iPhone 6 128GB" />
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="media-body">
                                                                                                        <div class="product-meta">
                                                                                                            <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=50">Apple iPhone 6 128GB</a></h6>
                                                                                                            <div class="price">
                                                                                                                <span class="price-new">$38.00</span>
                                                                                                                <span class="price-old">$1,214.00</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="w-product product-col clearfix col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                                                <div class="product-block">
                                                                                                    <div class="image pull-left">
                                                                                                        <div class="product-img img">
                                                                                                            <a class="img" title="Samsung UN55HU7250 Curved" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=51">
                                                                                                                <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/10-100x100.jpg" title="Samsung UN55HU7250 Curved" alt="Samsung UN55HU7250 Curved" />
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="media-body">
                                                                                                        <div class="product-meta">
                                                                                                            <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=51">Samsung UN55HU7250 Curved</a></h6>
                                                                                                            <div class="price">
                                                                                                                <span class="price-new">$1,202.00</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="w-product product-col clearfix col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                                                <div class="product-block">
                                                                                                    <div class="image pull-left">
                                                                                                        <div class="product-img img">
                                                                                                            <a class="img" title="Sony DSC-HX50V/B" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=43">
                                                                                                                <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/11-100x100.jpg" title="Sony DSC-HX50V/B" alt="Sony DSC-HX50V/B" />
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="media-body">
                                                                                                        <div class="product-meta">
                                                                                                            <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=43">Sony DSC-HX50V/B</a></h6>
                                                                                                            <div class="price">
                                                                                                                <span class="price-new">$602.00</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="w-product product-col clearfix col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                                                <div class="product-block">
                                                                                                    <div class="image pull-left">
                                                                                                        <div class="product-img img">
                                                                                                            <a class="img" title="Sony DSC-HX50V/B 20.4MP Digital Camera" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=36">
                                                                                                                <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/14-100x100.jpg" title="Sony DSC-HX50V/B 20.4MP Digital Camera" alt="Sony DSC-HX50V/B 20.4MP Digital Camera" />
                                                                                                            </a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="media-body">
                                                                                                        <div class="product-meta">
                                                                                                            <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=36">Sony DSC-HX50V/B 20.4MP Digital Camera</a></h6>
                                                                                                            <div class="price">
                                                                                                                <span class="price-new">$122.00</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
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
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=46" class="dropdown-toggle" data-toggle="dropdown"><i class=""></i><span class="menu-title">Accessories</span><b class="caret"></b></a>
                                                        <div class="dropdown-menu" style="width:500px">
                                                            <div class="dropdown-menu-inner">
                                                                <div class="row">
                                                                    <div class="mega-col col-md-6 ">
                                                                        <div class="mega-col-inner">
                                                                            <div class="pavo-widget"></div>
                                                                            <div class="pavo-widget"></div>
                                                                            <div class="pavo-widget">
                                                                                <div class="pavo-widget">
                                                                                    <h4 class="widget-heading title">Automative</h4>
                                                                                    <div class="">
                                                                                        <ul class="content list-unstyled">
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_63">
                                                                                                    <span>Camera &amp; Photo</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_36">
                                                                                                    <span>Computer Peripherals</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_60">
                                                                                                    <span>Mouse &amp; Keyboards</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_35">
                                                                                                    <span>Networking</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_61">
                                                                                                    <span>Tablet Accessories</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_62">
                                                                                                    <span>Wireless Routers</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_28">
                                                                                                    <span>Monitors</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_30">
                                                                                                    <span>Printers</span>
                                                                                                </a>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mega-col col-md-5 ">
                                                                        <div class="mega-col-inner">
                                                                            <div class="pavo-widget">
                                                                                <div class="pavo-widget">
                                                                                    <div class="widget-html    ">
                                                                                        <h4 class="widget-heading title">
                                                                                        <div class="widget-inner -content clearfix">
                                                                                            <div class="content ">
                                                                                                <p>Lorem ipsum dolor sit amet consectetuer adipiscing eli Aenean commodo ligula bus et magnis dis parturient eu pretium quis sem.</p>
                                                                                                <p>Lorem ipsum dolor sit amet consectetuer adipiscing eli Aenean commodo ligula.</p>
                                                                                            </div>
                                                                                        </div>
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
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=45" class="dropdown-toggle" data-toggle="dropdown"><i class=""></i><span class="menu-title">Home &amp; Garden</span><b class="caret"></b></a>
                                                        <div class="dropdown-menu" style="width:700px">
                                                            <div class="dropdown-menu-inner">
                                                                <div class="row">
                                                                    <div class="mega-col col-md-6 ">
                                                                        <div class="mega-col-inner">
                                                                            <div class="pavo-widget">
                                                                                <div class="pavo-widget">
                                                                                    <h4 class="widget-heading title">Electronics</h4>
                                                                                    <div class="">
                                                                                        <ul class="content list-unstyled">
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_63">
                                                                                                    <span>Camera &amp; Photo</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_36">
                                                                                                    <span>Computer Peripherals</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_60">
                                                                                                    <span>Mouse &amp; Keyboards</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_35">
                                                                                                    <span>Networking</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_61">
                                                                                                    <span>Tablet Accessories</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_62">
                                                                                                    <span>Wireless Routers</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_28">
                                                                                                    <span>Monitors</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_30">
                                                                                                    <span>Printers</span>
                                                                                                </a>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mega-col col-md-6 ">
                                                                        <div class="mega-col-inner">
                                                                            <div class="pavo-widget">
                                                                                <div class="pavo-widget">
                                                                                    <h4 class="widget-heading title">Electronics</h4>
                                                                                    <div class="">
                                                                                        <ul class="content list-unstyled">
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_63">
                                                                                                    <span>Camera &amp; Photo</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_36">
                                                                                                    <span>Computer Peripherals</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_60">
                                                                                                    <span>Mouse &amp; Keyboards</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_35">
                                                                                                    <span>Networking</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_61">
                                                                                                    <span>Tablet Accessories</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_62">
                                                                                                    <span>Wireless Routers</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_28">
                                                                                                    <span>Monitors</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_30">
                                                                                                    <span>Printers</span>
                                                                                                </a>
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
                                                    </li>
                                                    <li class="">
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=57"><i class=""></i><span class="menu-title">Automotive</span></a>
                                                    </li>
                                                    <li class="">
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=42"><i class=""></i><span class="menu-title">Beauty &amp; Health</span></a>
                                                    </li>
                                                    <li class="">
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=17"><i class=""></i><span class="menu-title">Toys, Kids &amp; Baby</span></a>
                                                    </li>
                                                    <li class="">
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=33"><i class=""></i><span class="menu-title">Jewelry &amp; Watches</span></a>
                                                    </li>
                                                    <li class="">
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=45"><i class=""></i><span class="menu-title">Bags &amp; Shoes</span></a>
                                                    </li>
                                                    <li class="">
                                                        <a href="#"><i class=""></i><span class="menu-title">Sports &amp; Outdoor</span></a>
                                                    </li>
                                                    <li class="">
                                                        <a href="index.php?route=product/category&amp;path=33"><i class=""></i><span class="menu-title">Cameras</span></a>
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
                                <button data-toggle="offcanvas" class="btn btn-primary canvas-menu hidden-lg hidden-md" type="button"><span class="fa fa-bars"></span> Menu</button>
                            </div>
                            <div class="collapse navbar-collapse" id="bs-megamenu">
                                <ul class="nav navbar-nav megamenu">
                                    <li class="parent dropdown home aligned-left"><a class="dropdown-toggle" data-toggle="dropdown" href="?route=common/home"><span class=""></span><span class="menu-title">Home</span><b class="caret"></b></a>
                                        <div class="dropdown-menu level1">
                                            <div class="dropdown-menu-inner">
                                                <div class="row">
                                                    <div class="mega-col col-xs-12 col-sm-12 col-md-12" data-type="menu">
                                                        <div class="mega-col-inner">
                                                            <ul>
                                                                <li class=" "><a href="?route=common/home&amp;home_id=58"><span class="menu-title">Home 2</span></a></li>
                                                                <li class=" "><a href="?route=common/home&amp;home_id=59"><span class="menu-title">Home 3</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="bg1 aligned-left parent dropdown "><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=34" class="dropdown-toggle" data-toggle="dropdown"><span class=""></span><span class="menu-title">Dresses</span><b class="caret"></b></a>
                                        <div class="dropdown-menu" style="width:596px">
                                            <div class="dropdown-menu-inner">
                                                <div class="row">
                                                    <div class="mega-col col-xs-12 col-sm-12 col-md-4 ">
                                                        <div class="mega-col-inner">
                                                            <div class="pavo-widget" id="pavowid-52">
                                                                <div class="pavo-widget" id="pavowid-1043093976">
                                                                    <h4 class="widget-heading title">Info</h4>
                                                                    <div class="">
                                                                        <ul class="content">
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_63">
                                                                                    <span>Camera &amp; Photo</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_36">
                                                                                    <span>Computer Peripherals</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_60">
                                                                                    <span>Mouse &amp; Keyboards</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_35">
                                                                                    <span>Networking</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_61">
                                                                                    <span>Tablet Accessories</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_62">
                                                                                    <span>Wireless Routers</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_28">
                                                                                    <span>Monitors</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_30">
                                                                                    <span>Printers</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_31">
                                                                                    <span>Scanners</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_29">
                                                                                    <span>Tablet</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mega-col col-xs-12 col-sm-12 col-md-4 ">
                                                        <div class="mega-col-inner">
                                                            <div class="pavo-widget" id="pavowid-53">
                                                                <div class="pavo-widget" id="pavowid-143574695">
                                                                    <h4 class="widget-heading title">Electronics</h4>
                                                                    <div class="">
                                                                        <ul class="content">
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_63">
                                                                                    <span>Camera &amp; Photo</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_36">
                                                                                    <span>Computer Peripherals</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_60">
                                                                                    <span>Mouse &amp; Keyboards</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_35">
                                                                                    <span>Networking</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_61">
                                                                                    <span>Tablet Accessories</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=25_62">
                                                                                    <span>Wireless Routers</span>
                                                                                </a>
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
                                    </li>
                                    <li class="parent dropdown  aligned-center"><a class="dropdown-toggle" data-toggle="dropdown" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=33"><span class="menu-title">Accessories</span><b class="caret"></b></a>
                                        <div class="dropdown-menu level1">
                                            <div class="dropdown-menu-inner">
                                                <div class="row">
                                                    <div class="mega-col col-xs-12 col-sm-12 col-md-12" data-type="menu">
                                                        <div class="mega-col-inner">
                                                            <ul>
                                                                <li class=" "><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=33"><span class="menu-title">Mauris amattis</span></a></li>
                                                                <li class=" "><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=33"><span class="menu-title">Nunc imperdiet</span></a></li>
                                                                <li class="parent dropdown-submenu "><a class="dropdown-toggle" data-toggle="dropdown" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=34"><span class="menu-title">Lacus sed iaculis</span><b class="caret"></b></a>
                                                                    <div class="dropdown-menu level2">
                                                                        <div class="dropdown-menu-inner">
                                                                            <div class="row">
                                                                                <div class="mega-col col-xs-12 col-sm-12 col-md-12" data-type="menu">
                                                                                    <div class="mega-col-inner">
                                                                                        <ul>
                                                                                            <li class="parent dropdown-submenu ">
                                                                                                <a class="dropdown-toggle" data-toggle="dropdown" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=17"><span class="menu-title">Litterarum</span><b class="caret"></b></a>
                                                                                                <div class="dropdown-menu level3">
                                                                                                    <div class="dropdown-menu-inner">
                                                                                                        <div class="row">
                                                                                                            <div class="mega-col col-xs-12 col-sm-12 col-md-12" data-type="menu">
                                                                                                                <div class="mega-col-inner">
                                                                                                                    <ul>
                                                                                                                        <li class=" "><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=33"><span class="menu-title">Claritas</span></a></li>
                                                                                                                        <li class=" "><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=34"><span class="menu-title">Sollemnes</span></a></li>
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </li>
                                                                                            <li class=" "><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=20"><span class="menu-title">Vulputate</span></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class=" "><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=33"><span class="menu-title">Hendrerit</span></a></li>
                                                                <li class=" "><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=33"><span class="menu-title">Aliquam</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class=""><a href="index.php?route=pavdeals/deals"><span class="menu-title">Deals</span></a></li>
                                    <li class=""><a href="?route=pavblog/blogs"><span class="menu-title">Blog</span></a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- /header -->
<div class="bottom-offcanvas">
    <div class="container">
        <button data-toggle="offcanvas" class="btn btn-primary visible-xs visible-sm" type="button"><i class="fa fa-bars"></i></button>
    </div>
</div>
<!-- sys-notification -->
<div id="sys-notification">
    <div class="container">
        <div id="notification"></div>
    </div>
</div>
<!-- /sys-notification -->
<div class="main-columns container-fluid">
    <div class="row">
        <div id="sidebar-main" class="col-md-12">
            <div id="content">
                <div id="pav-homebuilder1802001919" class="homebuilder clearfix header-v3" data-home="header-v3">
                    <div class="pav-container ">
                        <div class="pav-inner container ">
                            <div class="row row-level-1 ">
                                <div class="row-inner clearfix">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 ">
                                        <div class="col-inner col-lg-offset-3 col-md-offset-3">
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 ">
                                        <div class="col-inner ">
                                            <script type="text/javascript">
                                                $(document).ready(function() {
                                                    $('#iview').iView({
                                                        pauseTime: 9000,
                                                        directionNav: false,
                                                        directionNavHide: false,
                                                        controlNavNextPrev: true,
                                                        controlNav: true,
                                                        tooltipY: -15,
                                                    });
                                                });
                                            </script>
                                            <div class="layerslider-wrapper carousel slide pavcontentslider" style="max-width:873px;">
                                                <div class="fix-margin-right" style="padding: 0;margin: 18px 0 50px 0px !important;">
                                                    <div id="iview" class="hidden-xs" style="width:100%;height:502px; ">
                                                        <div data-iview-thumbnail="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider2/layer1.jpg" data-iview-image="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider2/layer1.jpg" data-iview-transition="slice-top-fade,slice-right-fade">
                                                            <div class="iview-caption tp-caption " data-start="1024" data-x="251" data-y="100" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/skyline-psyco.png" alt="catalog/demo/slider1/skyline-psyco.png" />
                                                            </div>
                                                            <div class="iview-caption tp-caption softred2 black" data-start="1785" data-x="250" data-y="160" data-width="600" data-transition="fade">
                                                                specifically
                                                                <br>design </div>
                                                            <div class="iview-caption tp-caption " data-start="2000" data-x="251" data-y="330" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/true-performance.png" alt="catalog/demo/slider1/true-performance.png" />
                                                            </div>
                                                            <div class="iview-caption tp-caption " data-start="2800" data-x="453" data-y="360" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/view-more.png" alt="catalog/demo/slider1/view-more.png" />
                                                            </div>
                                                        </div>
                                                        <div data-iview-thumbnail="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider2/layer2.jpg" data-iview-image="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider2/layer2.jpg" data-iview-transition="slice-top-fade,slice-right-fade">
                                                            <div class="iview-caption tp-caption softred2" data-start="800" data-x="150" data-y="160" data-width="600" data-transition="fade">
                                                                Professional"s
                                                                <br>Choice </div>
                                                            <div class="iview-caption tp-caption " data-start="2000" data-x="150" data-y="99" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/skyline-psyco.png" alt="catalog/demo/slider1/skyline-psyco.png" />
                                                            </div>
                                                            <div class="iview-caption tp-caption " data-start="2000" data-x="152" data-y="332" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/true-performance.png" alt="catalog/demo/slider1/true-performance.png" />
                                                            </div>
                                                            <div class="iview-caption tp-caption " data-start="2800" data-x="346" data-y="361" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/view-more.png" alt="catalog/demo/slider1/view-more.png" />
                                                            </div>
                                                        </div>
                                                        <div data-iview-thumbnail="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider2/layer3.jpg" data-iview-image="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider2/layer3.jpg" data-iview-transition="slice-top-fade,slice-right-fade">
                                                            <div class="iview-caption tp-caption softred2" data-start="800" data-x="250" data-y="160" data-width="600" data-transition="fade">
                                                                protect
                                                                <br>yourself </div>
                                                            <div class="iview-caption tp-caption " data-start="2000" data-x="250" data-y="101" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/skyline-psyco.png" alt="catalog/demo/slider1/skyline-psyco.png" />
                                                            </div>
                                                            <div class="iview-caption tp-caption " data-start="2000" data-x="250" data-y="331" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/true-performance.png" alt="catalog/demo/slider1/true-performance.png" />
                                                            </div>
                                                            <div class="iview-caption tp-caption " data-start="2800" data-x="447" data-y="360" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/view-more.png" alt="catalog/demo/slider1/view-more.png" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pav-container ">
                        <div class="pav-inner container space-40 style-adv">
                            <div class="row row-level-1 ">
                                <div class="row-inner clearfix">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                                        <div class="col-inner ">
                                            <div class="widget-images box   ">
                                                <div class="widget-heading">
                                                    <h3 class="panel-title">get ready for game day</h3>
                                                </div>
                                                <div class="widget-inner img-adv box-content clearfix">
                                                    <div class="image-item effect-adv">
                                                        <img class="img-responsive" alt=" " src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/banners/adv1-279x140.png" />
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/banners/adv1.png" class="pts-popup fancybox" title="Large Image"><span class="icon icon-expand"></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                                        <div class="col-inner ">
                                            <div class="widget-images box   ">
                                                <div class="widget-heading">
                                                    <h3 class="panel-title">customize your helmet</h3>
                                                </div>
                                                <div class="widget-inner img-adv box-content clearfix">
                                                    <div class="image-item effect-adv">
                                                        <img class="img-responsive" alt=" " src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/banners/adv2-279x140.png" />
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/banners/adv2.png" class="pts-popup fancybox" title="Large Image"><span class="icon icon-expand"></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                                        <div class="col-inner ">
                                            <div class="widget-images box   ">
                                                <div class="widget-heading">
                                                    <h3 class="panel-title">new bell closeouts</h3>
                                                </div>
                                                <div class="widget-inner img-adv box-content clearfix">
                                                    <div class="image-item effect-adv">
                                                        <img class="img-responsive" alt=" " src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/banners/adv3-279x140.png" />
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/banners/adv3.png" class="pts-popup fancybox" title="Large Image"><span class="icon icon-expand"></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                                        <div class="col-inner ">
                                            <div class="widget-images box   ">
                                                <div class="widget-heading">
                                                    <h3 class="panel-title">buy one get one</h3>
                                                </div>
                                                <div class="widget-inner img-adv box-content clearfix">
                                                    <div class="image-item effect-adv">
                                                        <img class="img-responsive" alt=" " src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/banners/adv4-279x140.png" />
                                                        <a href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/banners/adv4.png" class="pts-popup fancybox" title="Large Image"><span class="icon icon-expand"></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pav-container ">
                        <div class="pav-inner container ">
                            <div class="row row-level-1 ">
                                <div class="row-inner clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="col-inner ">
                                            <div class="panel-left panel panel-default">
                                                <div class="panel-heading nopadding hightlight text-left">
                                                    <h3 class="panel-title">Collections</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pav-container ">
                        <div class="pav-inner container space-40">
                            <div class="row row-level-1 ">
                                <div class="row-inner clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="col-inner wow bounceInLeft">
                                            <div class="interactive-banner space-15 interactive-banner-v1 effect-default center">
                                                <div class="interactive-banner-body">
                                                    <img alt="" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/banners/collection2-h3-576x320.jpg" class="img-responsive">
                                                    <div class="interactive-banner-profile text-center">
                                                        <div class="banner-title">
                                                            <h2>street collection</h2>
                                                        </div>
                                                        <div class="light-style">
                                                        </div>
                                                        <p class="action">Aenean adipiscing purus in odio aliquet gravida convallis metus.</p>
                                                    </div>
                                                    <a class="mask-link" href="#"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="col-inner wow bounceInRight">
                                            <div class="interactive-banner space-15 interactive-banner-v1 effect-default center">
                                                <div class="interactive-banner-body">
                                                    <img alt="" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/banners/collection1-h3-576x320.jpg" class="img-responsive">
                                                    <div class="interactive-banner-profile text-center">
                                                        <div class="banner-title">
                                                            <h2>touring collection</h2>
                                                        </div>
                                                        <div class="light-style">
                                                        </div>
                                                        <p class="action">Aenean adipiscing purus in odio aliquet gravida convallis metus.</p>
                                                    </div>
                                                    <a class="mask-link" href="#"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pav-container ">
                        <div class="pav-inner container space-50">
                            <div class="row row-level-1 ">
                                <div class="row-inner clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="col-inner ">
                                            <div class="widget bg-carousel panel-left panel panel-default">
                                                <div class="widget-heading panel-heading nopadding hightlight space-10">
                                                    <h3 class="panel-title">latest products</h3>
                                                </div>
                                                <div class="list box-products slide" id="product_list1375675222">
                                                    <div class="carousel-controls">
                                                        <a class="carousel-control left center" href="#product_list1375675222" data-slide="prev">
                                                            <i class="fa fa-angle-left"></i>
                                                        </a>
                                                        <a class="carousel-control right center" href="#product_list1375675222" data-slide="next">
                                                            <i class="fa fa-angle-right"></i>
                                                        </a>
                                                    </div>
                                                    <div class="carousel-inner product-grid">
                                                        <div class="item active products-block">
                                                            <div class="row products-row last">
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Samsung UN55HU7250 Curved" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=51">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/10-500x500.jpg" title="Samsung UN55HU7250 Curved" alt="Samsung UN55HU7250 Curved" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=51" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/10.jpg" class="product-zoom info-view colorbox cboxElement" title="Samsung UN55HU7250 Curved"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=51">Samsung UN55HU7250 Curved</a></h6>
                                                                                <p class="description">
                                                                                    Unprecedented power. The next generation of processing technology has arrived. Built into the newest VAIO notebooks lies Intel's latest, most powerful innovation yet: Intel® Centrino® 2 processor t...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$1,202.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('10111-AA-BK');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('51');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('51');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <span class="product-label sale-exist"><span class="product-label-special">Sale</span></span>
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Apple iPhone 6 128GB" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=50">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/2-500x500.jpg" title="Apple iPhone 6 128GB" alt="Apple iPhone 6 128GB" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=50" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/2.jpg" class="product-zoom info-view colorbox cboxElement" title="Apple iPhone 6 128GB"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=50">Apple iPhone 6 128GB</a></h6>
                                                                                <p class="description">
                                                                                    iPhone is a revolutionary new mobile phone that allows you to make a call by simply tapping a name or number in your address book, a favorites list, or a call log. It also automatically syncs all y...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$38.00</span>
                                                                                    <span class="price-old">$1,214.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('50');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('50');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('50');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Samsung Galaxy Tab 10.1" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=49">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/8-500x500.jpg" title="Samsung Galaxy Tab 10.1" alt="Samsung Galaxy Tab 10.1" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=49" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/8.jpg" class="product-zoom info-view colorbox cboxElement" title="Samsung Galaxy Tab 10.1"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=49">Samsung Galaxy Tab 10.1</a></h6>
                                                                                <p class="description">
                                                                                    Samsung Galaxy Tab 10.1, is the world’s thinnest tablet, measuring 8.6 mm thickness, running with Android 3.0 Honeycomb OS on a 1GHz dual-core Tegra 2 processor, similar to its younger brother Sams...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$241.99</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('49');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('49');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('49');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <span class="product-label sale-exist"><span class="product-label-special">Sale</span></span>
                                                                            <div class="product-img img">
                                                                                <a class="img" title="MacBook Pro" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=45">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/6-500x500.jpg" title="MacBook Pro" alt="MacBook Pro" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=45" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/6.jpg" class="product-zoom info-view colorbox cboxElement" title="MacBook Pro"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=45">MacBook Pro</a></h6>
                                                                                <p class="description">
                                                                                    Latest Intel mobile architecture Powered by the most advanced mobile processors from Intel, the new Core 2 Duo MacBook Pro is over 50% faster than the original Core Duo MacBook Pro...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$70.00</span>
                                                                                    <span class="price-old">$2,000.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('45');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('45');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('45');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Sony DSC-HX50V/B" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=43">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/11-500x500.jpg" title="Sony DSC-HX50V/B" alt="Sony DSC-HX50V/B" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=43" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/11.jpg" class="product-zoom info-view colorbox cboxElement" title="Sony DSC-HX50V/B"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=43">Sony DSC-HX50V/B</a></h6>
                                                                                <p class="description">
                                                                                    Intel Core 2 Duo processor Powered by an Intel Core 2 Duo processor at speeds up to 2.16GHz, the new MacBook is the fastest ever. 1GB memory, larger hard drives The new MacBoo...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$602.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('43');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('43');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('43');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12 last product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <span class="product-label sale-exist"><span class="product-label-special">Sale</span></span>
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Apple Cinema 30&quotquot;" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=42">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/14-500x500.jpg" title="Apple Cinema 30&quot;" alt="Apple Cinema 30&quot;" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=42" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/14.jpg" class="product-zoom info-view colorbox cboxElement" title="Apple Cinema 30&quot;"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=42">Apple Cinema 30&quot;</a></h6>
                                                                                <p class="description">
                                                                                    The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all ...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$110.00</span>
                                                                                    <span class="price-old">$122.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('42');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('42');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('42');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item  products-block">
                                                            <div class="row products-row last">
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <span class="product-label sale-exist"><span class="product-label-special">Sale</span></span>
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Sony DSC-HX50V/B 20.4MP" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=40">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/12-500x500.jpg" title="Sony DSC-HX50V/B 20.4MP" alt="Sony DSC-HX50V/B 20.4MP" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=40" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/12.jpg" class="product-zoom info-view colorbox cboxElement" title="Sony DSC-HX50V/B 20.4MP"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=40">Sony DSC-HX50V/B 20.4MP</a></h6>
                                                                                <p class="description">
                                                                                    iPhone is a revolutionary new mobile phone that allows you to make a call by simply tapping a name or number in your address book, a favorites list, or a call log. It also automatically syncs all y...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$38.00</span>
                                                                                    <span class="price-old">$123.20</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('40');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('40');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('40');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Sony DSC-HX50V/B 20.4MP Digital Camera" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=36">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/14-500x500.jpg" title="Sony DSC-HX50V/B 20.4MP Digital Camera" alt="Sony DSC-HX50V/B 20.4MP Digital Camera" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=36" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/14.jpg" class="product-zoom info-view colorbox cboxElement" title="Sony DSC-HX50V/B 20.4MP Digital Camera"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=36">Sony DSC-HX50V/B 20.4MP Digital Camera</a></h6>
                                                                                <p class="description">
                                                                                    Video in your pocket. Its the small iPod with one very big idea: video. The worlds most popular music player now lets you enjoy movies, TV shows, and more on a two-inch display thats 65% ...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$122.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('36');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('36');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('36');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <div class="product-img img">
                                                                                <a class="img" title="IMAC shuffle born to be worn." href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=34">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/5-500x500.jpg" title="IMAC shuffle born to be worn." alt="IMAC shuffle born to be worn." />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=34" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/5.jpg" class="product-zoom info-view colorbox cboxElement" title="IMAC shuffle born to be worn."><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=34">IMAC shuffle born to be worn.</a></h6>
                                                                                <p class="description">
                                                                                    Born to be worn. Clip on the worlds most wearable music player and take up to 240 songs with you anywhere. Choose from five colors including four new hues to make your musical fashion stateme...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$122.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('34');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('34');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('34');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Samsung SyncMaster 941BW" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=33">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/9-500x500.jpg" title="Samsung SyncMaster 941BW" alt="Samsung SyncMaster 941BW" />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=33" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/9.jpg" class="product-zoom info-view colorbox cboxElement" title="Samsung SyncMaster 941BW"><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=33">Samsung SyncMaster 941BW</a></h6>
                                                                                <p class="description">
                                                                                    Imagine the advantages of going big without slowing down. The big 19" 941BW monitor combines wide aspect ratio with fast pixel response time, for bigger images, more room to work and crisp motion. ...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$242.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('33');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('33');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('33');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Sony Xperia Z2 D6503 White QuadCore 3GB " href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=32">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/15-500x500.jpg" title="Sony Xperia Z2 D6503 White QuadCore 3GB " alt="Sony Xperia Z2 D6503 White QuadCore 3GB " />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=32" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/15.jpg" class="product-zoom info-view colorbox cboxElement" title="Sony Xperia Z2 D6503 White QuadCore 3GB "><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=32">Sony Xperia Z2 D6503 White QuadCore 3GB </a></h6>
                                                                                <p class="description">
                                                                                    Revolutionary multi-touch interface. iPod touch features the same multi-touch screen technology as iPhone. Pinch to zoom in on a photo. Scroll through your songs and videos with a flick. Flip thr...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$122.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('32');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('32');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('32');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-xs-12 last product-col border">
                                                                    <div class="product-block">
                                                                        <div class="image">
                                                                            <div class="product-img img">
                                                                                <a class="img" title="Vitamix 1709 CIA Professional Series." href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=31">
                                                                                    <img class="img-responsive" src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/product/15-500x500.jpg" title="Vitamix 1709 CIA Professional Series." alt="Vitamix 1709 CIA Professional Series." />
                                                                                </a>
                                                                                <div class="quickview hidden-xs">
                                                                                    <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=31" title="Quick View"><i class="fa fa-eye"></i></a>
                                                                                </div>
                                                                                <div class="zoom hidden-xs">
                                                                                    <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/15.jpg" class="product-zoom info-view colorbox cboxElement" title="Vitamix 1709 CIA Professional Series."><i class="fa fa-search-plus"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="product-meta">
                                                                            <div class="left">
                                                                                <h6 class="name"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/product&amp;product_id=31">Vitamix 1709 CIA Professional Series.</a></h6>
                                                                                <p class="description">
                                                                                    Engineered with pro-level features and performance, the 12.3-effective-megapixel D300 combines brand new technologies with advanced features inherited from Nikon's newly announced D3 profession...</p>
                                                                                <div class="price">
                                                                                    <span class="price-new">$98.00</span>
                                                                                </div>
                                                                                <div class="rating">
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="right">
                                                                                <div class="action">
                                                                                    <div class="cart">
                                                                                        <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('31');">
                                                                                            <i class="fa fa-shopping-cart"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="compare">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Compare this Product" onclick="compare.addcompare('31');"><i class="fa fa-exchange"></i></button>
                                                                                    </div>
                                                                                    <div class="wishlist">
                                                                                        <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('31');"><i class="fa fa-heart"></i></button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                            <!--
                                                $('#product_list1375675222').carousel({
                                                    interval: false,
                                                    pause: 'hover'
                                                });
                                            -->
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pav-container ">
                        <div class="pav-inner container border-dashed">
                            <div class="row row-level-1 ">
                                <div class="row-inner clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="col-inner ">
                                            <div id="pavcarousel8" class="widget-carousel box carousel carousel-ed slide   ">
                                                <div class="carousel-inner">
                                                    <div class="item active no-margin">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/4-130x130.png" alt="Coca Cola" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/3-130x130.png" alt="Sony" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/2-130x130.png" alt="RedBull" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/1-130x130.png" alt="NFL" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/5-130x130.png" alt="Burger King" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/6-130x130.png" alt="Canon" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="item  no-margin">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/1-130x130.png" alt="Harley Davidson" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/2-130x130.png" alt="Dell" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/3-130x130.png" alt="Disney" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/4-130x130.png" alt="Starbucks" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <div class="item-inner">
                                                                    <a href="index.php?route=product/manufacturer/info&amp;manufacturer_id=8"><img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/cache/catalog/demo/manufacturer/5-130x130.png" alt="Nintendo" class="img-responsive" /></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="carousel-controls">
                                                    <a class="carousel-control left" href="#pavcarousel8" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                                                    <a class="carousel-control right" href="#pavcarousel8" data-slide="next"><i class="fa fa-angle-right"></i></a>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                            <!--
                                            $('#pavcarousel8').carousel({
                                                interval: false
                                            });
                                            -->
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var head = $(".homebuilder").attr("data-home");
    if (head == 'header-v3') {
        $(".top-verticalmenu").addClass("always-show");
    }
</script>
<?php $this->load->view('/default/footer') ?>