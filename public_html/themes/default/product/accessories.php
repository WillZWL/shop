<div id="access_text_box" style="clear:both"><?= 'Customers who bought '?><?= $prod_name ?><?= ' also bought:'?></div>

<ul class="accesories_tabs" >
    <div id="access_hint"><?= 'Click the '?><span style="color:black">&#91;+]</span><?=' icon to expand the selection.' ?></div>

    <?php
        foreach($category as $title => $prod) :
            if ($prod) :
    ?>

    <li>
        <div >
            <div onclick="showDetail('ra_','31');" >
                <span id="access_oc_tag">
                    <span id="access_open_31" style="display:inline" >&#91;+]</span>
                    <span id="access_close_31" style="display:none" >&#91;-]</span>
                </span>
                <span id="access_title"><?= $prod_obj->getProdName(); ?></span>
            </div>
        </div>
        <ul class="accessories border-radius-3">
            <div id="ra_31" name="ra_31" style="display:none">
                <table>
                    <col width="20%"><col width="1%"><col><col width="20%"><col width="15%">

                        <?php
                            foreach ($prod as $sku => $prod_obj):
                        ?>
                        <tr id="ra_row1_13276-AA-NA">
                            <td style="vertical-align:top" rowspan="8"><a id="ra_fb_31" href="http://www.valuebasket.com/images/product/13276-AA-NA_m.jpg" class="fancybox" title=""><img id="ra_img_31" src="http://www.valuebasket.com/images/product/13276-AA-NA_m.jpg" alt="" style="width:120px;height:120px" /></a></td>
                            <td onmouseover="updateDisplayImage('31','http://www.valuebasket.com/images/product/13276-AA-NA_m.jpg','13276-AA-NA',1,'ra');"><span name="lb_13276-AA-NA" id="lb_13276-AA-NA" style="display:none"><img src="/images/loading-small.gif"/ style="width:13px;height:13px;padding-left:0px;float:left"></span><input name="cb_13276-AA-NA" id="cb_13276-AA-NA" onclick="checkCart(this, '20232-AA-SL','13276-AA-NA');" type="checkbox"  name="ra_item" value="ra_item" /></td>

                            <td onclick="ra_onclick('20232-AA-SL','13276-AA-NA');" onmouseover="ra_onmouseover('ra','13276-AA-NA',1);updateDisplayImage('31','http://www.valuebasket.com/images/product/13276-AA-NA_m.jpg','13276-AA-NA',1,'ra');" onmouseout="ra_onmouseout('ra','13276-AA-NA',1);"><a id="ra_col1_13276-AA-NA" style="cursor:pointer;text-decoration:none;" ><h4>3-in-1 Lightning/30-Pin/Micro USB to USB Charging Cable</h4></a></td>

                            <td onclick="ra_onclick('20232-AA-SL','13276-AA-NA');" onmouseover="updateDisplayImage('31','http://www.valuebasket.com/images/product/13276-AA-NA_m.jpg','13276-AA-NA',1,'ra');" onmouseout="ra_onmouseout('ra','13276-AA-NA',1);"><a style="cursor:pointer;text-decoration:none;" ><h4><ins class=""  id="ra_col1_2_13276-AA-NA" onmouseover="ra_onmouseover('ra','13276-AA-NA',1);">HK$ 15.00</ins></h4></a></td>

                            <td onmouseover="updateDisplayImage('31','http://www.valuebasket.com/images/product/13276-AA-NA_m.jpg','13276-AA-NA',1,'ra');"><a onclick="Pop('http://www.valuebasket.com/3-in-1-Lightning-30-Pin-Micro-USB-to-USB-Charging-Cable/mainproduct/view/13276-AA-NA')" title="" class="more_info_btn23 border-radius-2">
                                <span class="border-radius-2 fixpng">More Info</span>
                            </a></td>
                        </tr>

                        <?php
                            endforeach;
                        ?>
                </table>
            </div>
        </ul>
    </li>

    <?php
            endif;
        endforeach;
    ?>

</ul>








<div class="pav-container ">
    <div class="pav-inner container space-50">
        <div class="row row-level-1 ">
            <div class="row-inner clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                    <div class="col-inner ">
                        <div class="widget bg-carousel panel-left panel panel-default">
                            <div class="widget-heading panel-heading nopadding  space-10">
                                <h3 class="panel-title"><?= str_replace('_', ' ', $title) ?></h3>
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

                                            <div class="col-lg-2 col-sm-2 col-xs-12  product-col border">
                                                <div class="product-block">
                                                    <div class="image">
                                                        <div class="product-img img">
                                                            <a class="img" title="<?= $prod_obj->getProdName(); ?>" href='<?= $prod_obj->getProductUrl() ?>'>
                                                                <img class="img-responsive" src="<?=getImageUrl($prod_obj->getImageExt(), 'm', $prod_obj->getSku())?>" title="<?= $prod_obj->getProdName(); ?>" alt="<?= $prod_obj->getProdName(); ?>" />
                                                            </a>
                                                            <div class="quickview hidden-xs">
                                                                <a class="iframe-link" data-toggle="tooltip" data-placement="top" href='<?= $prod_obj->getProductUrl() ?>' title="Quick View"><i class="fa fa-eye"></i></a>
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
                                                                <!--<div class="wishlist">
                                                                    <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="Add to Wish List" onclick="wishlist.addwishlist('<?= $sku ?>');">
                                                                        <i class="fa fa-heart"></i>
                                                                    </button>
                                                                </div>-->
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