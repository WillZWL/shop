<?php
    if($sv) {
?>
<script>
    $(document).ready(function() {
        $('#header-layout').hide();
        $('#related-product').hide();
        $('#footer').hide();
    });
</script>
<?php
    }
?>
<div class="main-columns container">
    <div class="row">
        <div id="product-detail" class="col-md-12">
            <div id="content">
                <div class="product-info">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 image-container">
                            <?php if (count($prod_image) !== 0): ?>
                                <div class="image">
                                    <span class="product-label exist"><span class="product-label-special"><?= _('Sale') ?></span></span>
                                    <a href="<?= base_url($default_image)?>" class="imagezoom">
                                        <img src="<?= base_url($default_image)?>" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" id="image" data-zoom-image="<?= base_url($default_image) ?>" class="product-image-zoom img-responsive">
                                    </a>
                                </div>
                                <div class="thumbs-preview horizontal">
                                    <div class="image-additional olw-carousel horical" id="image-additional">
                                        <div id="image-additional-carousel">
                                        <?php foreach ($prod_image as $img): ?>
                                            <div class="item clearfix">
                                                <a href="<?= base_url($img['image'])?>" title="<?= $prod_name ?>" class="imagezoom" data-zoom-image="<?= base_url($img['image'])?>" data-image="<?= base_url($img['image'])?>">
                                                    <img src="<?= base_url($img['image'])?>" style="max-width:80px" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" data-zoom-image="<?= base_url($img['image'])?>" class="product-image-zoom img-responsive"/>
                                                </a>
                                            </div>
                                        <?php endforeach ?>
                                        </div>
                                        <!-- <div class="carousel-controls"> -->
                                        <a class="carousel-control left carousel-sm" href="#image-additional" data-slide="next">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                        <a class="carousel-control right carousel-sm" href="#image-additional" data-slide="prev">
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                        <!-- </div> -->
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class=" <?= ($countryid == "GB") ? "col-xs-6 col-sm-6 col-md-6 col-lg-6" : "col-xs-8 col-sm-8 col-md-8 col-lg-8"?>">
                            <div class="product-info-bg">
                                <div itemscope itemtype="http://schema.org/Product">
                                    <meta itemprop="brand" content="<?= $microdata['brand'] ?>" />
                                    <meta itemprop="name" content="<?= $prod_name ?>" />
                                    <meta itemprop="sku" content="<?= $sku ?>" />
                                    <meta itemprop="productID" content="sku:<?= $sku ?>" />
                                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <meta itemprop="price" content="<?= $microdata['price'] ?>" />
                                        <meta itemprop="priceCurrency" content="<?= $microdata['currency'] ?>" />
                                        <meta itemprop="availability" content="<?= $microdata['availability'] ?>" />
                                        <meta itemprop="itemCondition" content="<?= $microdata['itemCondition'] ?>" />
                                    </div>
                                </div>

                                <h1 class="title-product"><?= $prod_name ?></h1>
                                <div class="rating">
                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                                    <a href="#review-form" class="popup-with-form" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?= sprintf(_('%s review(s)'), "0") ?></a> / <a href="#review-form" class="popup-with-form" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?= _('Write a review') ?></a>
                                </div>
                                <div class="price detail space-20">
                                    <ul class="list-unstyled">
                                        <li> <span class="price-new"> <?= platform_curr_format($prod_price) ?> </span> <span class="price-old"><?= platform_curr_format($prod_rrp_price) ?></span> </li>
                                    </ul>
                                </div>

                                <ul class="list-unstyled">
                                    <li>
                                        <span class="check-box text-success"><i class="fa fa-check"></i></span>
                                        <b><?= _('Availability').':' ?></b>
                                        <?php
                                            if ($listing_status == 'I') {
                                        ?>
                                            <?= _(' In Stock') ?>
                                        <?php }elseif ($listing_status == 'O') { ?>
                                            <?= _('Out of Stock') ?>
                                        <?php }elseif ($listing_status == 'P') { ?>
                                            <?= _('Pre Order') ?>
                                        <?php }else { ?>
                                            <?= _(' In Stock') ?>
                                        <?php } ?>
                                    </li>
                                </ul>
                                <div class="border-success space-30">
                                    <ul class="list-unstyled"></ul>
                                </div>
                                <div id="product">
                                    <div class="product-extra">
                                        <label class="control-label pull-left qty"><?= _('Qty:') ?></label>
                                        <div class="quantity-adder pull-left space-40">
                                            <div class="quantity-number pull-left">
                                                <input type="text" name="quantity" value="1" size="2" id="input-quantity" class="form-control">
                                            </div>
                                            <span class="add-down add-action btn-default pull-left"><i class="fa fa-minus"></i></span>
                                            <span class="add-up add-action btn-default pull-left"> <i class="fa fa-plus"></i> </span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="product_id" value="50">
                                    <div class="action pull-left">
                                        <div class="cart pull-left">
                                            <?php
                                                $is_allow_to_add = "onclick='cart.addcart($sku)'";
                                                if ($listing_status == 'O') {
                                                    $is_allow_to_add = "style='cursor:not-allowed;'";
                                                }
                                            ?>
                                            <button type="button" id="button-cart" data-loading-text="Loading..." class="btn btn-primary" <?=$is_allow_to_add?> ><?= _('Add to Cart') ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <?php if ($listing_status == 'I'): ?>
                                <div>
                                    <a title="<?= _('Ships in') ?> <?=$delivery_day['ship_min_day']?> - <?=$delivery_day['ship_max_day']?> <?= _('working days') ?> <?= _('and') ?> <?= _('delivered in') ?> <?=$delivery_day['del_min_day']?> - <?=$delivery_day['del_max_day']?> <?= _('working days') ?>" name="website_status_short_desc" id="website_status_short_desc">
                                    <?= _('Ships in') ?> <?=$delivery_day['ship_min_day']?> - <?=$delivery_day['ship_max_day']?> <?= _('working days') ?>
                                    </a>
                                </div>
                                <?php endif ?>
                                <div class="tags">
                                </div>
                            </div>
                        </div>
                        <?php if ($countryid == "GB") :?>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                          <link href="http://www.reviewcentre.com/css/seo_badge.v3.css" rel="stylesheet" type="text/css">
                            <script type="text/javascript" src="http://www.reviewcentre.com/js/RC.SeoBadge.v3.min.js"></script>
                            <script type="text/javascript">RC.Badge.initialize("http://www.reviewcentre.com", 3709767)</script>
                            <div id="rc-badge-wrapper" class="style-150x100 color-gray" style="width: 150px; height: 100px; ">
                            <div class="rc-top-corners"></div>
                            <div class="rc-content">
                            <div class="rc-logo">
                            <a title="Review Centre - Consumer Reviews" href="http://www.reviewcentre.com">http://www.reviewcentre.com</a>
                            </div>
                            <p class="rc-rating"></p>
                            <div class="rc-stars"></div>
                            <div class="rc-overview">
                            <p class="rc-category"><a href="http://www.reviewcentre.com/products977.html" rel="nofollow">Online Electronic Shops</a></p>
                            <p class="rc-item"><a href="http://www.reviewcentre.com/Online-Electronic-Shops/Digital-Discount-www-digitaldiscount-co-uk-reviews_3709767" rel="nofollow">Digital Discount - www.digitaldiscount.co.uk</a></p>
                            <p class="rc-date"></p>
                            <p class="rc-extract"></p>
                            </div>
                            </div>
                            <div class="rc-write-review"><a href="http://www.reviewcentre.com/write-a-review-3709767.html" rel="nofollow">Write a review</a></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>
                    <div class="clearfix box-product-infomation tab-v4 none-border text-center">
                        <ul class="nav nav-tabs" role="tablist">
                            <li><a href="#tab-specification" data-toggle="tab"><?= _('Specification') ?></a></li>
                            <li class="active"><a href="#tab-overview" data-toggle="tab"><?= _('Overview') ?></a></li>
                            <?php if ($in_the_box): ?>
                            <li><a href="#tab-in_the_box" data-toggle="tab"><?= _('In the box') ?></a></li>
                            <?php endif ?>
                        </ul>
                        <div class="tab-content text-left">
                            <div class="tab-pane active" id="tab-overview">
                                <p class="intro">
                                    <?= $overview ?>
                                </p>
                            </div>
                            <div class="tab-pane" id="tab-specification">
                                <p class="intro">
                                    <?= $specification ?>
                                </p>
                            </div>
                            <?php if ($in_the_box): ?>
                                <div class="tab-pane" id="tab-in_the_box">
                                    <p class="intro">
                                        <?= $in_the_box ?>
                                    </p>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#image-additional .item:first').addClass('active');
    $('#image-additional').carousel({
        interval: false
    })
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var $carousel = $("#image-additional-carousel");
        $carousel.owlCarousel({
            autoPlay: false, //Set AutoPlay to 3 seconds
            items: 4,
            pagination: false
        });
        $("#image-additional .carousel-control.left").click(function() {
            $carousel.trigger('owl.prev');
        });
        $("#image-additional .carousel-control.right").click(function() {
            $carousel.trigger('owl.next');
        });

        $('.product-info .image a').click(
            function(){
                $.magnificPopup.open({
                  items: {
                    src:  $('img',this).attr('src')
                  },
                  type: 'image'
                });
                return false;
            }
        );
        $("#website_status_short_desc").tooltip();
    });
</script>
<script type="text/javascript" src="http://www.themelexus.com/demo/opencart/motozz/demo3/catalog/view/javascript/jquery/elevatezoom/elevatezoom-min.js"></script>
<script type="text/javascript">
        var zoomCollection = '#image';
        $( zoomCollection ).elevateZoom({
                lensShape : "basic",
                lensSize    : 150,
                easing:true,
                gallery:'image-additional-carousel',
                cursor: 'pointer',
                galleryActiveClass: "active"
            });
</script>
