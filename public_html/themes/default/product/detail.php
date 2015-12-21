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
                        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 image-container">
                            <!--<div class="image">
                                <span class="product-label exist"><span class="product-label-special"><?= _('Sale') ?></span></span>
                                <a href="<?= get_image_file($image, 'l', $sku)?>" class="imagezoom">
                                    <img src="<?= get_image_file($image, 'l', $sku)?>" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" id="image" data-zoom-image="<?= get_image_file($image, 'l', $sku)?>" class="product-image-zoom img-responsive">
                                </a>
                            </div>-->

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
                                        <!-- Controls -->
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
                        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
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
                                        <li> <span class="price-new"> <?= $prod_price ?> </span> <span class="price-old"><?= $prod_rrp_price ?></span> </li>
                                    </ul>
                                </div>

                                <ul class="list-unstyled">
                                    <li><span class="check-box text-success"><i class="fa fa-check"></i></span> <b><?= _('Availability').':' ?></b><?= _(' In Stock') ?></li>
                                </ul>
                                <div class="border-success space-30">
                                    <ul class="list-unstyled">
                                        <!-- <li><b>Brand:</b> <a href="#">Apple</a></li>
                                        <li><b>Product Code:</b> product 11</li> -->
                                    </ul>
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
                                            <button type="button" id="button-cart" data-loading-text="Loading..." class="btn btn-primary" onclick="cart.addcart('<?=$sku?>');"><?= _('Add to Cart') ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div> <?= _('4-7 working days delivery') ?> </div>
                                <div class="tags">
                                </div>
                            </div>
                        </div>
                        <!-- End div bg -->
                    </div>
                    <div class="clearfix box-product-infomation tab-v4 none-border text-center">
                        <ul class="nav nav-tabs" role="tablist">
                            <li><a href="#tab-specification" data-toggle="tab"><?= _('Specification') ?></a></li>
                            <li class="active"><a href="#tab-overview" data-toggle="tab"><?= _('Overview') ?></a></li>
                            <li><a href="#tab-in_the_box" data-toggle="tab"><?= _('In the box') ?></a></li>
                            <!--<li><a href="#tab-accesories" data-toggle="tab"><?= _('Recommended Accessories') ?></a></li>-->
                            <!-- <li><a href="#tab-review" data-toggle="tab"><?= sprintf(_('Reviews %s'), "(0)") ?></a></li> -->
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
                            <div class="tab-pane" id="tab-in_the_box">
                                <p class="intro">
                                    <?= $in_the_box ?>
                                </p>
                            </div>
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
