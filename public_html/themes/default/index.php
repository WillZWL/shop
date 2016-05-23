<?php $this->load->view('header') ?>
<div style="display:none">
<?php print _('best seller') ?>
<?php print _('latest arrival') ?>
</div>
<!-- sys-notification -->
<div id="sys-notification">
    <div class="container">
        <div id="notification"></div>
    </div>
</div>
<?php
    $siteobj = \PUB_Controller::$siteInfo;
    $lang_id = $siteobj->getLangId();

    $banner_total = 2;
?>
<div class="pav-container ">
    <div class="pav-inner ">
        <div class="row row-level-1 ">
            <div class="row-inner clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-inner ">
                        <div id="carousel-example-generic" class="carousel slide carousel-banner" data-ride="carousel">
                            <ol class="carousel-indicators">
                            <?php
                                for ($i=1; $i <= $banner_total; $i++) :
                                    $active = $i == 1 ? 'active' : "";
                            ?>
                                <li data-target="#carousel-example-generic" data-slide-to="<?= ($i - 1) ?>" class="<?= $active ?>"></li>
                            <?php
                                endfor;
                            ?>
                            </ol>
                            <div class="carousel-inner" role="listbox" style="width:100%;max-height:300px; ">
                            <?php
                                for ($i=1; $i <= $banner_total; $i++) :
                                    $active = $i == 1 ? ' active' : "";
                            ?>
                                <div class="item<?= $active ?>">
                                    <img data-src="<?= base_url('/images/banner/'. $lang_id .'_banner'.$i.'.jpg') ?>"
                                    src="<?= base_url('/images/banner/'. $lang_id .'_banner'.$i.'.jpg') ?>"
                                    data-holder-rendered="true">
                                </div>
                            <?php
                                endfor;
                            ?>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
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
                                                    <h3 class="panel-title"><?= _('Collections') ?></h3>
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
                                                                <h2><?= _('camera collection') ?></h2>
                                                            </div>
                                                            <div class="light-style">
                                                            </div>
                                                            <p class="action"><?= _('Shop now') ?></p>
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
                                                                <h2><?= _('mobile collection') ?></h2>
                                                            </div>
                                                            <div class="light-style">
                                                            </div>
                                                            <p class="action"><?= _('Shop now') ?></p>
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
