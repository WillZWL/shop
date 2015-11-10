<?php $this->load->view('header') ?>
<!-- sys-notification -->
<div id="sys-notification">
    <div class="container">
        <div id="notification"></div>
    </div>
</div>


<div class="pav-container ">
                        <div class="pav-inner ">
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
                                            <div class="layerslider-wrapper carousel slide pavcontentslider">
                                                <div class="fix-margin-right" style="padding: 0;margin: 0 0 50px !important;">
                                                      <div id="iview" class="hidden-xs" style="width:100%;height:300px; " >
                                                        <!--<div data-iview-thumbnail="<?= base_url('/images/banner/banner.jpg') ?>" data-iview-image="<?= base_url('/images/banner/banner.jpg') ?>" data-iview-transition="slice-top-fade,slice-right-fade">
                                                        </div>-->
                                                        <div data-iview-thumbnail="<?= base_url('/images/banner/banner.jpg') ?>" data-iview-image="<?= base_url('/images/banner/banner.jpg') ?>" data-iview-transition="slice-top-fade,slice-right-fade">
                                                            <div class="iview-caption tp-caption " data-start="632" data-x="299" data-y="172" data-width="600" data-transition="fade">
                                                            </div>
                                                        </div>
                                                        <!--<div data-iview-thumbnail="<?= base_url('/images/banner/banner3.png') ?>" data-iview-image="<?= base_url('/images/banner/banner3.png') ?>" data-iview-transition="slice-top-fade,slice-right-fade">
                                                            <div class="iview-caption tp-caption " data-start="632" data-x="299" data-y="172" data-width="600" data-transition="fade">
                                                            </div>
                                                        </div>-->

                                                        <!--<div data-iview-thumbnail="<?= base_url('/images/banner/banner.jpg') ?>" data-iview-image="<?= base_url('/images/banner/banner.jpg') ?>" data-iview-transition="slice-top-fade,slice-right-fade">
                                                            <div class="iview-caption tp-caption " data-start="632" data-x="299" data-y="172" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/skyline-psyco.png" alt="catalog/demo/slider1/skyline-psyco.png" />
                                                            </div>
                                                            <div class="iview-caption tp-caption softred2" data-start="1291" data-x="300" data-y="220" data-width="600" data-transition="fade">
                                                                specifically<br>design
                                                            </div>
                                                            <div class="iview-caption tp-caption " data-start="2000" data-x="301" data-y="408" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/true-performance.png" alt="catalog/demo/slider1/true-performance.png" />
                                                            </div>
                                                            <div class="iview-caption tp-caption " data-start="2556" data-x="505" data-y="439" data-width="600" data-transition="fade">
                                                                <img src="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/slider1/view-more.png" alt="catalog/demo/slider1/view-more.png" />
                                                            </div>
                                                        </div>-->
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

<!-- /sys-notification -->
<div class="main-columns container-fluid">
    <div class="row">
        <div id="sidebar-main" class="col-md-12">
            <div id="content">
                <div id="pav-homebuilder1802001919" class="homebuilder clearfix header-v3" data-home="header-v3">


                    <?php if ($product) : ?>
                        <?php $this->load->view('product/grid.php', ['product' => $product]); ?>
                    <?php endif; ?>
                    <div class="pav-container ">
                        <div class="pav-inner">
                            <div class="row row-level-1 ">
                                <div class="row-inner clearfix">
                                   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="col-inner ">
                                            <div class="panel-left panel panel-default">
                                                <div class="panel-heading nopadding text-left">
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
                        <div class="pav-inner space-40">
                            <div class="row row-level-1 ">
                                <div class="row-inner clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="col-inner wow bounceInLeft">
                                            <div class="interactive-banner space-15 interactive-banner-v1 effect-default center">
                                                <div class="interactive-banner-body">
                                                    <a href="<?= base_url('digital-cameras/category/1') ?>">
                                                        <img alt="" src="<?= base_url('/images/banner/camera.jpg') ?>" class="img-responsive">
                                                        <div class="interactive-banner-profile text-center">
                                                            <div class="banner-title">
                                                                <h2>camera collection</h2>
                                                            </div>
                                                            <div class="light-style">
                                                            </div>
                                                            <p class="action">Shop now</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="col-inner wow bounceInRight">
                                            <div class="interactive-banner space-15 interactive-banner-v1 effect-default center">
                                                <div class="interactive-banner-body">
                                                    <a href="<?= base_url('mobile-phones/category/4') ?>">
                                                        <img alt="" src="<?= base_url('/images/banner/mobile.jpg') ?>" class="img-responsive">

                                                        <div class="interactive-banner-profile text-center">
                                                            <div class="banner-title">
                                                                <h2>mobile collection</h2>
                                                            </div>
                                                            <div class="light-style">
                                                            </div>
                                                            <p class="action">Shop now</p>
                                                        </div>
                                                    </a>
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
<?php $this->load->view('footer') ?>
