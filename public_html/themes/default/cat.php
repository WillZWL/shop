<?php $this->load->view('header') ?>
<div id="content" style="margin: 20px auto">
    <aside id="sidebar-right" class="col-md-3">
        <div id="column-right" class="hidden-xs sidebar">
            <div class="panel panel-default nopadding">
                <!--<div class="panel-heading"><h4>Categories</h4></div>-->
                <div class="panel-body tree-menu">
                    <ul class="box-category list-group accordion">
                        <li class="list-group-item accordion-group">
                            <p>Refine Search</p>
                            <li class="list-group-item accordion-group">
                                <a href=""class="active"><span id="list-group-item-title" ><?= _('Categories') ?></span></a>
                                <ul class="collapse accordion-body in">
                                    <li>
                                        <a href="<?=base_url('cat/view/1');?>"><?= _('SmartPhones') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?=base_url('cat/view/2');?>"><?= _('Webcams') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?=base_url('cat/view/4');?>"><?= _('Conference Phones') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?=base_url('cat/view/6');?>"><?= _('Accessories') ?></a>
                                    </li>
                                    <li>
                                        <div class="show-more">
                                            Show more
                                            <span data-toggle="collapse" data-target="#accordiondata1" class="bg collapsed"><i class="fa fa-angle-down"></i></span>
                                        </div>
                                    </li>
                                </ul>
                                <ul id="accordiondata1" class="collapse">
                                    <li>
                                        <a href="<?=base_url('cat/view/29');?>"><?= _('Software') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?=base_url('cat/view/44');?>"><?= _('Computing') ?></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="list-group-item accordion-group">
                                <a href="" class="active list-group-item-title"><?= _('BRANDS') ?></a>
                                <ul class="collapse accordion-body in">
                                    <?php
                                    if ($brand_result) {
                                        foreach($brand_result as $brand) {
                                    ?>
                                        <li>
                                            <a href="<?=$brand['id']?>"><?=$brand['name']?> (<?=$brand['total']?>)</a>
                                        </li>
                                    <?php
                                        }
                                    }
                                    ?>

                                    <li>
                                        <div class="show-more">
                                            Show more
                                            <span data-toggle="collapse"  data-target="#accordiondata" class="bg collapsed">
                                                <i class="fa fa-angle-down"></i>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                                <ul id="accordiondata" class="collapse accordion-body">
                                    <?php
                                    if ($brand_result) {
                                        foreach($brand_result as $brand) {
                                    ?>
                                        <li>
                                            <a href="<?=$brand['id']?>"><?=$brand['name']?> (<?=$brand['total']?>)</a>
                                        </li>
                                    <?php
                                        }
                                    }
                                    ?>
                                    </li>
                                </ul>
                            </li>
                        </li>
                    </ul>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    var active = $('.collapse.in').attr('id');
                    $('span[data-target=#'+active+']').html("<i class='fa fa-angle-down'></i>");

                    $('.collapse').on('show.bs.collapse', function () {
                        $('span[data-target=#'+$(this).attr('id')+']').html("<i class='fa fa-angle-down'></i>");
                    });
                    $('.collapse').on('hide.bs.collapse', function () {
                        $('span[data-target=#'+$(this).attr('id')+']').html("<i class='fa fa-angle-right'></i>");
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
                        <button data-original-title="List" type="button" id="list-view" class="btn btn-switch" data-toggle="tooltip" title=""><i class="fa fa-th-list"></i></button>
                        <button data-original-title="Grid" type="button" id="grid-view" class="btn btn-switch active" data-toggle="tooltip" title=""><i class="fa fa-th"></i></button>
                    </div>
                </div>
                <div class="filter-right">
                    <!--
                    <div class="product-compare pull-right"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/compare" class="btn btn-link" id="compare-total">Product Compare (0)</a></div>
                    -->
                </div>

                <div class="sort pull-right">
                    <span for="input-sort"><?= _('Sort By:') ?></span>
                    <select id="input-sort" class="form-control" onchange="location = this.value;">
                        <option value="?sort=p.sort_order&order=ASC" selected="selected"><?= _('Default') ?></option>
                        <option value="?sort=pd.name&order=ASC"><?= _('Name (A - Z)') ?></option>
                        <option value="?sort=pd.name&order=DESC"><?= _('Name (Z - A)') ?></option>
                        <option value="?sort=p.price&order=ASC"><?= _('Price (Low to High)') ?></option>
                        <option value="?sort=p.price&order=DESC"><?= _('Price (High to Low)') ?></option>
                        <option value="?sort=rating&order=DESC"><?= _('Rating (Highest)') ?></option>
                        <option value="?sort=rating&order=ASC"><?= _('Rating (Lowest)') ?></option>
                        <option value="?sort=p.model&order=ASC"><?= _('Model (A - Z)') ?></option>
                        <option value="?sort=p.model&order=DESC"><?= _('Model (Z - A)') ?></option>
                    </select>
                </div>
                <div class="limit pull-right">
                    <span for="input-limit"><?= _('Display:') ?></span>
                    <select id="input-limit" class="form-control" onchange="location = this.value;">
                        <option value="?limit=12" selected="selected">12</option>
                        <option value="?limit=25">25</option>
                        <option value="?limit=50">50</option>
                        <option value="?limit=75">75</option>
                        <option value="?limit=100">100</option>
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
                                    <a class="img" title="<?= $prod_obj->getProdName(); ?>" href='<?= base_url("/main-product/view/$sku") ?>'>
                                        <img class="img-responsive" src="<?= get_image_file($prod_obj->getImageExt(), 'm', $prod_obj->getSku()) ?>" title="<?= $prod_obj->getProdName(); ?>" alt="<?= $prod_obj->getProdName(); ?>" />
                                    </a>
                                    <div class="quickview hidden-xs">
                                        <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="<?= base_url("/main-product/view/$sku/sv") ?>" title="Quick View"><i class="fa fa-eye"></i></a>
                                    </div>
                                    <div class="zoom hidden-xs">
                                        <a data-toggle="tooltip" data-placement="top" href="<?= get_image_file($prod_obj->getImageExt(), 'l', $prod_obj->getSku()) ?>" class="product-zoom info-view colorbox cboxElement" title="<?= $prod_obj->getProdName(); ?>"><i class="fa fa-search-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="product-meta">
                                <div class="left">
                                    <h6 class="name"><a href='<?= base_url("/main-product/view/$sku") ?>'><?= $prod_obj->getProdName(); ?></a></h6>
                                    <p class="description">
                                    <?php print $prod_obj->getShortDesc(); ?>
                                    </p>
                                    <div class="price">
                                        <span class="price-old"><font class="list_price"><?= _('List Price') ?> :  </font><?= $prod_obj->getRrpPrice(); ?></span>
                                        <span class="price-new"><font class="pay_price"><?= _('You Pay') ?> :  </font><?= $prod_obj->getPrice(); ?></span>
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
                                                <span class="add-to-cart"><?= _("Add to Cart") ?></span>
                                            </button>
                                        </div>
                                        <div class="wishlist">
                                            <button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="top" title="More Info" onclick="wishlist.addwishlist('<?= $sku ?>');">
                                                <i class="fa fa-heart"></i>
                                                <span class="more-info"><?= _("More Info") ?></span>
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
        </div>
        <div class="pagination paging clearfix pull-right">
            <ul class="pagination" style="margin:0">
                <?php
                    if($curr_page != 1) :
                ?>
                        <li><a href="<?=base_url('cat/view/' . $cat_id . '/' . ($curr_page-1));?>">&lt;&lt;</a></li>
                <?php
                    endif;
                    $start_page = floor($curr_page / $pagination) * $pagination + 1;
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
                            <li><a href="<?=base_url('cat/view/' . $cat_id . '/' . $i);?>"><?=$i?></a></li>
                <?php
                        endif;
                    endfor;

                    if($curr_page != $total_page) :
                ?>
                        <li><a href="<?=base_url('cat/view/' . $cat_id . '/' . ($curr_page+1));?>">&gt;&gt;</a></li>
                <?php
                    endif;
                ?>
            </ul>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
