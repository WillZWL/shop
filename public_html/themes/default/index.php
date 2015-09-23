<?php $this->load->view('header') ?>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
