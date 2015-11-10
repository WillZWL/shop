<?php if ($cross_sell_product_list): ?>
<div id="related-product" class="panel panel-default nopadding-title nopadding product-grid">
    <div class="panel-heading hightlight">
        <h4 class="panel-title"><?= _('Related Products') ?></h4>
    </div>
    <div class="panel-body products-owl-carousel" id="wrap41f258ed38ee9e4ef2548ba34470c86c">
        <div class="products-block products-owl owl-carousel owl-theme" id="41f258ed38ee9e4ef2548ba34470c86c" style="opacity: 1; display: block;">
            <div class="owl-wrapper-outer">
                <div class="owl-wrapper" style="width: 3120px; left: 0px; display: block;">
                    <?php foreach ($cross_sell_product_list as $sku => $item): ?>
                    <div class="owl-item" style="width: 195px;">
                        <div class="product-col-wrap">
                            <div class="product-col">
                                <div class="product-block">
                                    <div class="image">
                                        <span class="product-label sale-exist"><span class="product-label-special"><?= _('Sale') ?></span></span>
                                        <div class="product-img img">
                                            <a class="img" title="<?= $prod_name ?>" href="#">
                                                <img class="img-responsive" src="<?= get_image_file($image, 'm', $sku)?>" title="<?= $prod_name ?>" alt="<?= $prod_name ?>">
                                            </a>
                                            <div class="quickview hidden-xs">
                                                <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="#" title="" data-original-title="Quick View"><i class="fa fa-eye"></i></a>
                                            </div>
                                            <div class="zoom hidden-xs">
                                                <a data-toggle="tooltip" data-placement="top" href="<?= get_image_file($image, 'l', $sku)?>" class="product-zoom info-view colorbox cboxElement" title="" data-original-title="<?= $prod_name ?>"><i class="fa fa-search-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-meta">
                                        <div class="left">
                                            <h6 class="name"><a href="#"><?= $prod_name ?></a></h6>
                                            <div class="price">
                                                <span class="price-new"><?= $prod_price ?></span>
                                                <span class="price-old"><?= $prod_rrp_price ?></span>
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
                                                    <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('<?= $sku ?>');">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <span class="add-to-cart"><?= _("Add to Cart") ?></span>
                                                    </button>
                                                </div>
                                                <!--<div class="wishlist">
                                                    <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="" onclick="wishlist.addwishlist('<?= $sku ?>');" data-original-title="Add to Wish List"><i class="fa fa-heart"></i></button>
                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
        <!-- Controls -->
        <div class="carousel-controls hidden-xs hidden-sm">
            <a class="carousel-control left" href="#image-additional" data-slide="next">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="carousel-control right" href="#image-additional" data-slide="prev">
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        var $carousel = $("#41f258ed38ee9e4ef2548ba34470c86c");
        $carousel.owlCarousel({
            autoPlay: false, //Set AutoPlay to 3 seconds
            items: 6,
            lazyLoad: true,
            navigation: false,
            navigationText: false,
            rewindNav: false,
            pagination: false

        });
        $("#wrap41f258ed38ee9e4ef2548ba34470c86c .carousel-control.left").click(function() {
            $carousel.trigger('owl.prev');
        })
        $("#wrap41f258ed38ee9e4ef2548ba34470c86c .carousel-control.right").click(function() {
            $carousel.trigger('owl.next');
        })
    });
    </script>
</div>
<?php endif ?>