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
                            <div class="image">
                                <span class="product-label exist"><span class="product-label-special"><?= _('Sale') ?></span></span>
                                <a href="<?= get_image_file($image, 'l', $sku)?>" class="imagezoom">
                                    <img src="<?= get_image_file($image, 'l', $sku)?>" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" id="image" data-zoom-image="<?= get_image_file($image, 'l', $sku)?>" class="product-image-zoom img-responsive">
                                </a>
                            </div>
                            <div class="thumbs-preview horizontal">
                                <div class="image-additional olw-carousel horical" id="image-additional">
                                    <div id="image-additional-carousel" class="owl-carousel owl-theme" style="opacity: 1; display: block;">
                                        <div class="owl-wrapper-outer">
                                            <div class="owl-wrapper" style="width: 1456px; left: 0px; display: block;">
                                                <div class="owl-item" style="width: 104px;">
                                                    <div class="item clearfix active">
                                                        <a href="<?= get_image_file($image, 'l', $sku)?>" title="<?= $prod_name ?>" class="imagezoom" data-zoom-image="<?= get_image_file($image, 'l', $sku)?>" data-image="<?= get_image_file($image, 'l', $sku)?>">
                                                            <img src="<?= get_image_file($image, 'l', $sku)?>" style="max-width:80px" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" data-zoom-image="<?= get_image_file($image, 'l', $sku)?>" class="product-image-zoom img-responsive">
                                                        </a>
                                                    </div>
                                                </div>
<!--                                                 <div class="owl-item" style="width: 104px;">
                                                    <div class="item clearfix">
                                                        <a href="/themes/default/asset/image/demo/15-500x500.jpg" title="<?= $prod_name ?>" class="imagezoom" data-zoom-image="/themes/default/asset/image/demo/15-500x500.jpg" data-image="/themes/default/asset/image/demo/15-500x500.jpg">
                                                            <img src="/themes/default/asset/image/demo/15-80x80.jpg" style="max-width:80px" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" data-zoom-image="/themes/default/asset/image/demo/15-500x500.jpg" class="product-image-zoom img-responsive">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="owl-item" style="width: 104px;">
                                                    <div class="item clearfix">
                                                        <a href="/themes/default/asset/image/demo/16-500x500.jpg" title="<?= $prod_name ?>" class="imagezoom" data-zoom-image="/themes/default/asset/image/demo/16-500x500.jpg" data-image="/themes/default/asset/image/demo/16-500x500.jpg">
                                                            <img src="/themes/default/asset/image/demo/16-80x80.jpg" style="max-width:80px" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" data-zoom-image="/themes/default/asset/image/demo/16-500x500.jpg" class="product-image-zoom img-responsive">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="owl-item" style="width: 104px;">
                                                    <div class="item clearfix">
                                                        <a href="/themes/default/asset/image/demo/17-500x500.jpg" title="<?= $prod_name ?>" class="imagezoom" data-zoom-image="/themes/default/asset/image/demo/17-500x500.jpg" data-image="/themes/default/asset/image/demo/17-500x500.jpg">
                                                            <img src="/themes/default/asset/image/demo/17-80x80.jpg" style="max-width:80px" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" data-zoom-image="/themes/default/asset/image/demo/17-500x500.jpg" class="product-image-zoom img-responsive">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="owl-item" style="width: 104px;">
                                                    <div class="item clearfix">
                                                        <a href="/themes/default/asset/image/demo/18-500x500.jpg" title="<?= $prod_name ?>" class="imagezoom" data-zoom-image="/themes/default/asset/image/demo/18-500x500.jpg" data-image="/themes/default/asset/image/demo/18-500x500.jpg">
                                                            <img src="/themes/default/asset/image/demo/18-80x80.jpg" style="max-width:80px" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" data-zoom-image="/themes/default/asset/image/demo/18-500x500.jpg" class="product-image-zoom img-responsive">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="owl-item" style="width: 104px;">
                                                    <div class="item clearfix">
                                                        <a href="/themes/default/asset/image/demo/19-500x500.jpg" title="<?= $prod_name ?>" class="imagezoom" data-zoom-image="/themes/default/asset/image/demo/19-500x500.jpg" data-image="/themes/default/asset/image/demo/19-500x500.jpg">
                                                            <img src="/themes/default/asset/image/demo/19-80x80.jpg" style="max-width:80px" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" data-zoom-image="/themes/default/asset/image/demo/19-500x500.jpg" class="product-image-zoom img-responsive">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="owl-item" style="width: 104px;">
                                                    <div class="item clearfix">
                                                        <a href="/themes/default/asset/image/demo/20-500x500.jpg" title="<?= $prod_name ?>" class="imagezoom" data-zoom-image="/themes/default/asset/image/demo/20-500x500.jpg" data-image="/themes/default/asset/image/demo/20-500x500.jpg">
                                                            <img src="/themes/default/asset/image/demo/20-80x80.jpg" style="max-width:80px" title="<?= $prod_name ?>" alt="<?= $prod_name ?>" data-zoom-image="/themes/default/asset/image/demo/20-500x500.jpg" class="product-image-zoom img-responsive">
                                                        </a>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
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
                                        })
                                        $("#image-additional .carousel-control.right").click(function() {
                                            $carousel.trigger('owl.next');
                                        })
                                    });
                                    </script>
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
                                <script type="text/javascript">
                                $('#image-additional .item:first').addClass('active');
                                $('#image-additional').carousel({
                                    interval: false
                                })
                                </script>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                            <div class="product-info-bg">
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
                                    <li><span class="check-box text-success"><i class="fa fa-check"></i></span> <b><?= _('Availability:') ?></b><?= _(' In Stock') ?></li>
                                </ul>
                                <div class="border-success space-30">
                                    <ul class="list-unstyled">
<!--                                         <li><b>Brand:</b> <a href="#">Apple</a></li>
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
                                        <div class="pull-left">
                                            <a data-toggle="tooltip" class="wishlist" title="" onclick="wishlist.addwishlist('50');" data-original-title="Add to Wish List"><?= _('Add to Wish List') ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div>
                                    <?= _('4-7 working days delivery') ?>
                                </div>
                                <div class="tags">
                                </div>
                            </div>
                        </div>
                        <!-- End div bg -->
                    </div>
                    <div class="clearfix box-product-infomation tab-v4 none-border text-center">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#tab-description" data-toggle="tab"><?= _('Description') ?></a></li>
                            <li><a href="#tab-review" data-toggle="tab"><?= sprintf(_('Reviews %s'), "(0)") ?></a></li>
                        </ul>
                        <div class="tab-content text-left">
                            <div class="tab-pane active" id="tab-description">
                                <p class="intro">
                                    <?= $overview ?>
                                </p>
                            </div>
                            <div class="tab-pane" id="tab-review">
                                <div id="review" class="space-20">
                                    <p><?= _('There are no review for this product.') ?></p>
                                </div>
                                <p> <a href="#review-form" class="popup-with-form btn btn-sm btn-primary" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?= _('Write a review') ?></a></p>
                                <div class="hide">
                                    <div id="review-form" class="panel review-form-width">
                                        <div class="panel-body">
                                            <form class="form-horizontal" id="form-review">
                                                <h2><?= _('Write a review') ?></h2>
                                                <div class="form-group required">
                                                    <div class="col-sm-12">
                                                        <label class="control-label" for="input-name"><?= _('Your Name') ?></label>
                                                        <input type="text" name="name" value="" id="input-name" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group required">
                                                    <div class="col-sm-12">
                                                        <label class="control-label" for="input-review"><?= _('Your Review') ?></label>
                                                        <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                                                        <div class="help-block"><span class="text-danger"><?= _('Note:') ?></span><?= _(' HTML is not translated!') ?></div>
                                                    </div>
                                                </div>
                                                <div class="form-group required">
                                                    <div class="col-sm-12">
                                                        <label class="control-label"><?= _('Rating') ?></label>
                                                        &nbsp;&nbsp;&nbsp; <?= _('Bad') ?>&nbsp;
                                                        <input type="radio" name="rating" value="1"> &nbsp;
                                                        <input type="radio" name="rating" value="2"> &nbsp;
                                                        <input type="radio" name="rating" value="3"> &nbsp;
                                                        <input type="radio" name="rating" value="4"> &nbsp;
                                                        <input type="radio" name="rating" value="5"> &nbsp;<?= _('Good') ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="g-recaptcha" data-sitekey="6LdMxwYTAAAAAPfQS6XqEkGGBsjGLe1HMpRlR2hn">
                                                            <div>
                                                                <div style="width: 304px; height: 78px;">
                                                                    <iframe frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="" tabindex="0" vspace="0" width="304" title="recaptcha widget" role="presentation" height="78" id="I0_1435699332735" name="I0_1435699332735" src="https://www.google.com/recaptcha/api2/anchor?k=6LdMxwYTAAAAAPfQS6XqEkGGBsjGLe1HMpRlR2hn&amp;co=aHR0cDovL3d3dy50aGVtZWxleHVzLmNvbQ..&amp;hl=en&amp;v=r20150624112436&amp;size=normal&amp;usegapi=1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.zh_CN.mZRQEKnr40Y.O%2Fm%3D__features__%2Fam%3DEQ%2Frt%3Dj%2Fd%3D1%2Ft%3Dzcms%2Frs%3DAGLTcCOgeRQNYW6GVuOw8PTwf8Izzuo2NQ#id=I0_1435699332735&amp;parent=http%3A%2F%2Fwww.themelexus.com&amp;pfname=&amp;rpctoken=12971757"></iframe>
                                                                </div>
                                                                <textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid #c1c1c1; margin: 10px 25px; padding: 0px; resize: none;  display: none; "></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="buttons">
                                                    <div class="pull-right">
                                                        <button type="button" id="button-review" data-loading-text="Loading..." class="btn btn-primary"><?= _('Continue') ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
