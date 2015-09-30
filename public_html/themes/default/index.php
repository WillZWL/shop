<?php $this->load->view('header') ?>
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                            <div class="layerslider-wrapper carousel slide pavcontentslider" style="max-width:1170px;">
                                                <div class="fix-margin-right" style="padding: 0;margin: 18px 0 50px 0px !important;">
                                                    <div id="iview" class="hidden-xs" style="width:100%;height:300px; ">
                                                        <div data-iview-thumbnail="/images/banner/summer_1170X300.jpg" data-iview-image="/images/banner/summer_1170X300.jpg" data-iview-transition="slice-top-fade,slice-right-fade">
                                                        </div>
                                                        <div data-iview-thumbnail="/images/banner/easter_1170X300.jpg" data-iview-image="/images/banner/easter_1170X300.jpg" data-iview-transition="slice-top-fade,slice-right-fade">
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

                    <?php if ($product) : ?>
                        <?php $this->load->view('product/grid.php', ['product' => $product]); ?>
                    <?php endif; ?>
                    </br>
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
                    </br>
                    <?php if ($product) : ?>
                        <?php $this->load->view('product/grid.php', ['product' => $product]); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
