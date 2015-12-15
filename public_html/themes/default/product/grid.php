<?php
    foreach($product as $title => $prod) :
        if ($prod) :
?>

<div class="pav-container ">
    <div class="pav-inner container space-50">
        <div class="row row-level-1 ">
            <div class="row-inner clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                    <div class="col-inner ">
                        <div class="widget bg-carousel panel-left panel panel-default">
                            <div class="widget-heading panel-heading nopadding  space-10">
                                <h3 class="panel-title"><?= _(str_replace('_', ' ', $title)) ?></h3>
                            </div>
                            <div class="list box-products slide" id=<?= 'product_list' . $title ?>>

                                <div class="carousel-inner product-grid">
                                <?php
                                        $i = 1;
                                        foreach ($prod as $sku => $prod_obj):
                                            if ($i == 1):
                                ?>
                                    <div class="item active products-block">
                                            <div class="row products-row last">
                                <?php
                                            elseif (($i-1) % 6 === 0):
                                ?>
                                    <div class="item products-block">
                                        <div class="row products-row last">
                                <?php       endif; ?>

                                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12  product-col border">
                                                <div class="product-block">
                                                    <div class="image">
                                                        <div class="product-img img">
                                                            <a class="img" title="<?= $prod_obj->getProdName(); ?>" href='<?= $prod_obj->getProductUrl() ?>'>
                                                                <img class="img-responsive" src="<?=getImageUrl($prod_obj->getImageExt(), 'm', $prod_obj->getSku())?>" title="<?= $prod_obj->getProdName(); ?>" alt="<?= $prod_obj->getProdName(); ?>" />
                                                            </a>
                                                            <div class="quickview hidden-xs">
                                                                <a class="iframe-link" data-toggle="tooltip" data-placement="top" href='<?= $prod_obj->getProductUrl() ?>' title="<?= _('Quick View') ?>"><i class="fa fa-eye"></i></a>
                                                            </div>
                                                            <div class="zoom hidden-xs">
                                                                <a data-toggle="tooltip" data-placement="top" href="<?= get_image_file($prod_obj->getImageExt(), 'l', $prod_obj->getSku()) ?>" class="product-zoom info-view colorbox cboxElement" title="<?= $prod_obj->getProdName(); ?>"><i class="fa fa-search-plus"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-meta">
                                                        <div class="left">
                                                            <h6 class="name"><a href='<?= $prod_obj->getProductUrl() ?>'><?= $prod_obj->getProdName(); ?></a></h6>
                                                            <div class="price">
                                                                <span class="price-new"><?= platform_curr_format($prod_obj->getPrice()); ?></span>
                                                                <span class="price-old"><?= platform_curr_format($prod_obj->getRrpPrice()); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="right">
                                                            <div class="action">
                                                                <div class="cart hidden-md hidden-sm">
                                                                    <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('<?= $sku ?>');">
                                                                        <i class="fa fa-shopping-cart"></i>
                                                                        <span class="add-to-cart "><?= _("Add to Cart") ?></span>
                                                                    </button>
                                                                </div>
                                                                <div class="cart hidden-lg hidden-xs">
                                                                    <button data-loading-text="Loading..." class="btn btn-primary btn-cart-md" type="button" onclick="cart.addcart('<?= $sku ?>');">
                                                                        <i class="fa fa-shopping-cart"></i>
                                                                        <span class="add-to-cart "><?= _("Add to Cart") ?></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                <?php
                                            if ($i % 6 === 0 || $i == count($prod)):
                                ?>
                                        </div>
                                    </div>
                                <?php       endif;
                                        $i = $i + 1;
                                        endforeach;?>

                                </div>

                                <a class="carousel-control left center" href=<?= '#product_list' . $title ?> data-slide="prev">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                    <a class="carousel-control right center" href=<?= '#product_list' . $title ?> data-slide="next">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $("<?= '#product_list' . $title ?>").carousel({
                                interval: false,
                                pause: 'hover',
                                wrap: true
                            });
                            $("<?= '#product_list' . $title ?>").carousel({
                                interval: false,
                                pause: 'hover',
                                wrap: true
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
        endif;
    endforeach;
?>
