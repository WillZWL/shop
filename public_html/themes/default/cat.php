<?php $this->load->view('header') ?>
<div id="content" style="margin: 20px auto">
    <aside id="sidebar-right" class="col-md-3">
        <div id="column-right" class="hidden-xs sidebar">
            <div class="panel panel-default nopadding">
                <!--<div class="panel-heading"><h4>Categories</h4></div>-->
                <div class="panel-body tree-menu">
                    <ul class="box-category list-group accordion">
                        <li class="list-group-item accordion-group">
                            <p><?= _('Refine Search') ?></p>
                            <li class="list-group-item accordion-group">
                                <a href=""class="active"><span id="list-group-item-title" ><?= _('Categories') ?></span></a>
                                <ul class="collapse accordion-body displaydata in ">
                                    <?php
                                    if ($cat_result) {
                                        $num_cat = 0;
                                        foreach($cat_result as $cat) {
                                            $num_cat = $num_cat + 1;
                                            if ($num_cat == 8)
                                            {
                                    ?>

                                </ul>
                                <ul  id="accordiondata" class="accordion-body collapse">

                                    <?php
                                            }
                                    ?>

                                    <li>
                                        <a href="<?=$cat['url']?>"><?=$cat['name']?> (<?=$cat['total']?>)</a>
                                    </li>


                                    <?php
                                        }
                                    }
                                    ?>
                                </ul>
                                <?php
                                    if ($num_cat > 8)
                                    {
                                ?>
                                        <div class="show-more">
                                            <a href="#accordiondata" data-toggle="collapse" id="lblmore"><?= _('Show more') ?></a>
                                            <a href="#accordiondata" data-toggle="collapse" id="lblless" class="hide"><?= _('Show less') ?></a>
                                            <span data-toggle="collapse"  data-target="#accordiondata" class="bg collapsed">
                                                <i class="fa fa-angle-down"></i>
                                            </span>
                                        </div>
                                <?php
                                    }
                                ?>
                            </li>
                            <li class="list-group-item accordion-group">
                                <a href="" class="list-group-item-title"><?= _('Brands') ?></a>
                                <ul class="collapse accordion-body displaydata in ">
                                    <?php
                                    if ($brand_result) {
                                        $num_brand = 0;
                                        foreach($brand_result as $brand) {
                                            $num_brand = $num_brand + 1;
                                            if ($num_brand == 8)
                                            {
                                    ?>

                                </ul>
                                <ul  id="accordiondata1" class="collapse accordion-body">

                                    <?php
                                            }
                                    ?>
                                        <li>
                                            <a href="?brand_id=<?=$brand['id']?>"><?=$brand['name']?> (<?=$brand['total']?>)</a>
                                        </li>
                                    <?php
                                        }
                                    }
                                    ?>
                                </ul>
                                <?php
                                    if ($num_brand > 8)
                                    {
                                ?>
                                        <div class="show-more">
                                            <a href="#accordiondata1" data-toggle="collapse" id="lblmore1"><?= _('Show more') ?></a>
                                            <a href="#accordiondata1" data-toggle="collapse" id="lblless1" class="hide"><?= _('Show less') ?></a>
                                            <span data-toggle="collapse"  data-target="#accordiondata1" class="bg collapsed">
                                                <i class="fa fa-angle-down"></i>
                                            </span>
                                        </div>
                                <?php
                                    }
                                ?>
                            </li>
                        </li>
                    </ul>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    var active = $('.collapse.in').attr('id');
                    //$('span[data-target=#accordiondata]').html("<i class='fa fa-angle-down'></i>");
                    $('span[data-target=#'+active+']').html("<i class='fa fa-angle-down'></i>");

                    $('.collapse').on('hide.bs.collapse', function () {
                        $('span[data-target=#'+$(this).attr('id')+']').html("<i class='fa fa-angle-down'></i>");
                        if ($(this).attr('id')=='accordiondata')
                        {
                            $('#lblmore').removeClass('hide');
                            $('#lblless').addClass('hide');
                        }
                        else
                        {
                            $('#lblmore1').removeClass('hide');
                            $('#lblless1').addClass('hide');
                        }
                    });
                    $('.collapse').on('show.bs.collapse', function () {
                        $('span[data-target=#'+$(this).attr('id')+']').html("<i class='fa fa-angle-right'></i>");
                        if ($(this).attr('id')=='accordiondata')
                        {
                            $('#lblless').removeClass('hide');
                            $('#lblmore').addClass('hide');
                        }
                        else
                        {
                            $('#lblless1').removeClass('hide');
                            $('#lblmore1').addClass('hide');
                        }
                    });
                });
            </script>
        </div>
    </aside>

    <div class="products-block  col-lg-9 col-sm-9 col-xs-12">
        <div class="category_title"><h3><?=$cat_name?></h3></div>
        <div class="product-filter no-shadow" style="margin:20px auto">
            <div class="inner clearfix">
                <div class="display">
                    <div class="btn-group group-switch">
                        <button data-original-title="<?= _('List') ?>" type="button" id="list-view" class="btn btn-switch" data-toggle="tooltip" title=""><i class="fa fa-th-list"></i></button>
                        <button data-original-title="<?= _('Grid') ?>" type="button" id="grid-view" class="btn btn-switch active" data-toggle="tooltip" title=""><i class="fa fa-th"></i></button>
                    </div>
                </div>
                <div class="filter-right">
                    <!--
                    <div class="product-compare pull-right"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/compare" class="btn btn-link" id="compare-total">Product Compare (0)</a></div>
                    -->
                </div>

                <div class="sort pull-right">
                    <span for="input-sort"><?= _('Sort By').':' ?></span>
                    <?php
                       $rpp_tag = ($rpp ? "?rpp=" . $rpp ."&": "?");
                    ?>

                    <select id="input-sort" class="form-control" onchange="location = this.value;">
                        <option value="/cat/view/<?= $cat_id . $rpp_tag?>"
                            <?= (empty($sort) && empty($order)) ? "selected='selected'" : ""?>><?= _('Default') ?>
                        </option>
                        <option value="/cat/view/<?= $cat_id . $rpp_tag ?>sort=pc.prod_name&order=ASC"
                            <?= ($sort == 'pc.prod_name' && $order == 'ASC') ? "selected='selected'" : ""?>><?= _('Name (A - Z)') ?>
                        </option>
                        <option value="/cat/view/<?= $cat_id . $rpp_tag ?>sort=pc.prod_name&order=DESC"
                            <?= ($sort == 'pc.prod_name' && $order == 'DESC') ? "selected='selected'" : ""?>><?= _('Name (Z - A)') ?>
                        </option>
                        <option value="/cat/view/<?= $cat_id . $rpp_tag ?>sort=pr.price&order=ASC"
                            <?= ($sort == 'pr.price' && $order == 'ASC') ? "selected='selected'" : ""?>><?= _('Price (Low to High)') ?>
                        </option>
                        <option value="/cat/view/<?= $cat_id . $rpp_tag ?>sort=pr.price&order=DESC"
                            <?= ($sort == 'pr.price' && $order == 'DESC') ? "selected='selected'" : ""?>><?= _('Price (High to Low)') ?>
                        </option>
                        <option value="/cat/view/<?= $cat_id . $rpp_tag ?>sort=p.create_on&order=DESC"
                            <?= ($sort == 'p.create_on' && $order == 'DESC') ? "selected='selected'" : ""?>><?= _('New Arrivals') ?>
                        </option>
                        <option value="/cat/view/<?= $cat_id . $rpp_tag ?>sort=p.modify_on&order=DESC"
                            <?= ($sort == 'p.modify_on' && $order == 'DESC') ? "selected='selected'" : ""?>><?= _('Last Updated') ?>
                        </option>
                    </select>
                </div>
                <div class="limit pull-right">
                    <span for="input-limit"><?= _('Display:') ?></span>
                    <?php $rpp_sed[$this->input->get('rpp')] = " selected"; ?>
                    <select id="input-rpp" class="form-control" onchange="location = this.value;">
                        <option value="/cat/view/<?= $cat_id ?>?rpp=12"<?= $rpp_sed[12] ?>>12</option>
                        <option value="/cat/view/<?= $cat_id ?>?rpp=25"<?= $rpp_sed[25] ?>>25</option>
                        <option value="/cat/view/<?= $cat_id ?>?rpp=50"<?= $rpp_sed[50] ?>>50</option>
                        <option value="/cat/view/<?= $cat_id ?>?rpp=75"<?= $rpp_sed[75] ?>>75</option>
                        <option value="/cat/view/<?= $cat_id ?>?rpp=100"<?= $rpp_sed[100] ?>>100</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="products" class="product-grid">
            <div class="row products-row">
                <?php if ($productList) : ?>
                    <?php foreach ($productList as $sku => $prod_obj): ?>
                    <div class="col-lg-3 col-sm-3 col-xs-12 product-col border">
                        <div class="product-block">
                            <div class="image">
                                <div class="product-img img">
                                    <a class="img" title="<?= $prod_obj->getProdName(); ?>" href='<?= $prod_obj->getProductUrl() ?>'>
                                        <img class="img-responsive" src="<?= get_image_file($prod_obj->getImageExt(), 'm', $prod_obj->getSku()) ?>" title="<?= $prod_obj->getProdName(); ?>" alt="<?= $prod_obj->getProdName(); ?>" />
                                    </a>
                                    <div class="quickview hidden-xs">
                                        <a target="_blank" data-toggle="tooltip" data-placement="top" href="<?= base_url("/main-product/view/$sku") ?>" title="<?= _('Quick View') ?>"><i class="fa fa-eye"></i></a>
                                    </div>
                                    <div class="zoom hidden-xs">
                                        <a data-toggle="tooltip" data-placement="top" href="<?= get_image_file($prod_obj->getImageExt(), 'l', $prod_obj->getSku()) ?>" class="product-zoom info-view colorbox cboxElement" title="<?= $prod_obj->getProdName(); ?>"><i class="fa fa-search-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="product-meta">
                                <div class="left">
                                    <h6 class="name"><a href="<?= base_url("/main-product/view/$sku") ?>"><?= $prod_obj->getProdName(); ?></a></h6>
                                    <p class="description">
                                    <?php print $prod_obj->getShortDesc(); ?>
                                    </p>
                                    <div class="price">
                                        <span class="price-old"><font class="list_price"><?= _('List Price') ?> :  </font><?= platform_curr_format($prod_obj->getRrpPrice()); ?></span>
                                        <span class="price-new"><font class="pay_price"><?= _('You Pay') ?> :  </font><?= platform_curr_format($prod_obj->getPrice()); ?></span>
                                    </div>
                                    <div class="save_alter">
                                        <?php
                                        $discount = $prod_obj->getRrpPrice() ? ($prod_obj->getRrpPrice() - $prod_obj->getPrice())/$prod_obj->getRrpPrice() : 0;
                                        $discount = number_format($discount, 3)*100;
                                        ?>
                                        <?= _("Save") ?> - <?=$discount?>%
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="action">
                                        <div class="cart  hidden-md hidden-sm">
                                            <button data-loading-text="Loading..." class="btn btn-primary" type="button" onclick="cart.addcart('<?= $sku ?>');">
                                                <i class="fa fa-shopping-cart"></i>
                                                <span class="add-to-cart "><?= _("Add to Cart") ?></span>
                                            </button>
                                        </div>
                                        <div class="cart  hidden-lg hidden-xs">
                                            <button data-loading-text="Loading..." class="btn btn-primary btn-cart-md" type="button" onclick="cart.addcart('<?= $sku ?>');">
                                                <i class="fa fa-shopping-cart"></i>
                                                <span class="add-to-cart "><?= _("Add to Cart") ?></span>
                                            </button>
                                        </div>
                                        <!--<div class="wishlist">
                                            <a class="btn btn-primary iframe-link" data-toggle="tooltip" data-placement="top" title="More Info" href="<?= base_url("/main-product/view/$sku/sv") ?>">
                                                <i class="fa fa-heart"></i>
                                                <span class="more-info"><?= _("More Info") ?></span>
                                            </a>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
            </div>
                <?php endif; ?>
        </div>
        <div class="pagination paging clearfix pull-right">
            <ul class="pagination" style="margin:0">
                <?php
                    $strin_tag = "?" . $_SERVER['QUERY_STRING'];
                    if($curr_page != 1) :
                ?>
                        <li><a href="<?=base_url('cat/view/' . $cat_id . '/' . ($curr_page-1) . $strin_tag);?>">&lt;&lt;</a></li>
                <?php
                    endif;
                    $start_page = floor($pagination ? ($curr_page / $pagination) : 0) * $pagination + 1;
                    if($curr_page % $pagination == 0){
                        $start_page = $curr_page - $pagination + 1;
                    }
                    for($i = $start_page; $i < ($start_page + $pagination); $i++) :
                        if($i > $total_page) continue;
                        if($i == $curr_page) :
                ?>
                            <li class="active"><span><?=$i?></span></li>
                <?php
                        else:
                ?>
                            <li><a href="<?=base_url('cat/view/' . $cat_id . '/' . $i . $strin_tag);?>"><?=$i?></a></li>
                <?php
                        endif;
                    endfor;

                    if($curr_page != $total_page) :
                ?>
                        <li><a href="<?=base_url('cat/view/' . $cat_id . '/' . ($curr_page+1) . $strin_tag);?>">&gt;&gt;</a></li>
                <?php
                    endif;
                ?>
            </ul>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
