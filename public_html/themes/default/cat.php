<?php $this->load->view('/default/header') ?>
    <?php if ($productList) : ?>
        <?php foreach ($productList as $sku => $prod_obj): ?>
        <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
            <div class="product-block">
                <div class="image">
                    <div class="product-img img">
                        <a class="img" title="<?= $prod_obj->get_prod_name(); ?>" href='<?= site_url("/mainproduct/view/$sku") ?>'>
                            <img class="img-responsive" src="<?= get_image_file($prod_obj->get_image_ext(), 'm', $prod_obj->get_sku()) ?>" title="<?= $prod_obj->get_prod_name(); ?>" alt="<?= $prod_obj->get_prod_name(); ?>" />
                        </a>
                        <div class="quickview hidden-xs">
                            <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=themecontrol/product&amp;product_id=51" title="Quick View"><i class="fa fa-eye"></i></a>
                        </div>
                        <div class="zoom hidden-xs">
                            <a data-toggle="tooltip" data-placement="top" href="http://www.themelexus.com/demo/opencart/motozz/demo3/image/catalog/demo/product/10.jpg" class="product-zoom info-view colorbox cboxElement" title="<?= $prod_obj->get_prod_name(); ?>"><i class="fa fa-search-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="product-meta">
                    <div class="left">
                        <h6 class="name"><a href='<?= site_url("/mainproduct/view/$sku") ?>'><?= $prod_obj->get_prod_name(); ?></a></h6>
                        <p class="description">
                            Unprecedented power. The next generation of processing technology has arrived. Built into the newest VAIO notebooks lies Intel's latest, most powerful innovation yet: Intel® Centrino® 2 processor t...</p>
                        <div class="price">
                            <span class="price-new"><?= $prod_obj->get_price(); ?></span>
                            <span class="price-old"><?= $prod_obj->get_rrp_price(); ?></span>
                        </div>
                        <div class="save_alter">
                            Save -30%
                        </div>
                    </div>
                    <div class="right">
                        <div class="action">
                            <div class="cart">
                                <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('<?= $sku ?>');">
                                    <i class="fa fa-shopping-cart"></i>
                                </button>
                            </div>
                            <div class="wishlist">
                                <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('<?= $sku ?>');">
                                    <i class="fa fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
    <?php endif; ?>
<?php $this->load->view('/default/footer') ?>
