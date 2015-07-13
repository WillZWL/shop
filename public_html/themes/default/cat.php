<?php $this->load->view('/default/header') ?>
<div id="content">
    <div class="product-filter no-shadow">
     <div class="inner clearfix">
      <div class="display">
        <div class="btn-group group-switch">
          <button data-original-title="List" type="button" id="list-view" class="btn btn-switch" data-toggle="tooltip" title=""><i class="fa fa-th-list"></i></button>
          <button data-original-title="Grid" type="button" id="grid-view" class="btn btn-switch active" data-toggle="tooltip" title=""><i class="fa fa-th"></i></button>
        </div>
      </div>
      <div class="filter-right">
        <div class="product-compare pull-right"><a href="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/compare" class="btn btn-link" id="compare-total">Product Compare (0)</a></div>
        <div class="sort pull-right">
          <span for="input-sort">Sort By:</span>
          <select id="input-sort" class="form-control" onchange="location = this.value;">
                            <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=p.sort_order&amp;order=ASC" selected="selected">Default</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=pd.name&amp;order=ASC">Name (A - Z)</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=pd.name&amp;order=DESC">Name (Z - A)</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=p.price&amp;order=ASC">Price (Low &gt; High)</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=p.price&amp;order=DESC">Price (High &gt; Low)</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=rating&amp;order=DESC">Rating (Highest)</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=rating&amp;order=ASC">Rating (Lowest)</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=p.model&amp;order=ASC">Model (A - Z)</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;sort=p.model&amp;order=DESC">Model (Z - A)</option>
                          </select>
        </div>
        
        <div class="limit pull-right">
          <span for="input-limit">Show:</span>
          <select id="input-limit" class="form-control" onchange="location = this.value;">
                            <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;limit=12" selected="selected">12</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;limit=25">25</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;limit=50">50</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;limit=75">75</option>
                                    <option value="http://www.themelexus.com/demo/opencart/motozz/demo3/index.php?route=product/category&amp;path=24&amp;limit=100">100</option>
                          </select>
        </div>
      </div>
      
     </div>
    </div>
    <div id="products" class="product-grid">
        <div class="products-block">
            <div class="row products-row">
                <?php if ($productList) : ?>
                    <?php foreach ($productList as $sku => $prod_obj): ?>
                    <div class="col-lg-2 col-sm-2 col-xs-12 product-col border">
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
                                    <?php print $prod_obj->get_short_desc(); ?>
                                    </p>
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
<?php print $total_result; ?><br>
<?php print $curr_page; ?><br>
<?php print $total_page; ?><br>
<?php print $rpp; ?><br>
<div class="pagination paging clearfix">
    <ul class="pagination">
        <li><a href="?page=1&rpp=12&sort=priority_asc&brand_id=">|&lt;</a></li>
        <li><a href="?page=2&rpp=12&sort=priority_asc&brand_id=">&lt;</a></li>
		<?php
		for($i = 1; $i <= $total_page; $i++)
		{
		?>
			<li><a href="<?=$i?>" <?=($i == $curr_page? "class='active'" :;?>)><?=$i?></a></li>
		<?php
		} 
		?>
        <li><a href="">&gt;|</a></li></ul>
    </ul>
</div>
            </div>
        </div>
    </div> 
</div>
<?php $this->load->view('/default/footer') ?>
