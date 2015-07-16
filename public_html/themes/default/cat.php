<?php $this->load->view('/default/header') ?>
<div id="content" style="margin: 20px auto">
	<aside id="sidebar-right" class="col-md-3">	
		<div id="column-right" class="hidden-xs sidebar">
			<div class="panel panel-default nopadding">
			<!--<div class="panel-heading"><h4>Categories</h4></div>-->
			<div class="panel-body tree-menu">
				<ul class="box-category list-group accordion">
					<li class="list-group-item accordion-group">
						<li class="list-group-item accordion-group">
							<a href="" class="active">BRANDS</a>
							<div class="accordion-heading pull-right">
								<span data-toggle="collapse"  data-target="#accordiondata" class="bg">
									<i class="fa fa-angle-down"></i>
								</span>
							</div>
							<ul id="accordiondata" class="collapse accordion-body in">
								<?php
									foreach($brand_result as $brand) {
										break;
								?>
									<li>
										<a href="<?=$brand['id']?>"><?=$brand['name']?> (<?=$brand['total']?>)</a>
									</li>
								<?php
									}
								?>
							</ul>
						</li>
						<li class="list-group-item accordion-group">
							<a href="" class="active">Categories</a>
							<div class="accordion-heading pull-right">
								<span data-toggle="collapse" data-target="#accordiondata1" class="bg"><i class="fa fa-angle-down"></i></span>
							</div>
							<ul id="accordiondata1" class="collapse accordion-body in">
								<li>
									<a href="1">SmartPhones</a>
								</li>
								<li>
									<a href="2">Webcams</a>
								</li>
								<li>
									<a href="4">Conference Phones</a>
								</li>
								<li>
									<a href="6">Accessories</a>
								</li>
								<li>
									<a href="29">Software</a>
								</li>
								<li>
									<a href="44">Computing</a>
								</li>
							</ul>
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

				<div class="sort pull-right">
					<span for="input-sort">Sort By:</span>
					<select id="input-sort" class="form-control" onchange="location = this.value;">
						<option value="?sort=p.sort_order&order=ASC" selected="selected">Default</option>
						<option value="?sort=pd.name&order=ASC">Name (A - Z)</option>
						<option value="?sort=pd.name&order=DESC">Name (Z - A)</option>
						<option value="?sort=p.price&order=ASC">Price (Low &gt; High)</option>
						<option value="?sort=p.price&order=DESC">Price (High &gt; Low)</option>
						<option value="?sort=rating&order=DESC">Rating (Highest)</option>
						<option value="?sort=rating&order=ASC">Rating (Lowest)</option>
						<option value="?sort=p.model&order=ASC">Model (A - Z)</option>
						<option value="?sort=p.model&order=DESC">Model (Z - A)</option>
					</select>
				</div> 
				<div class="limit pull-right">
					<span for="input-limit">Display:</span>
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
                                    <a class="img" title="<?= $prod_obj->get_prod_name(); ?>" href='<?= site_url("/mainproduct/view/$sku") ?>'>
                                        <img class="img-responsive" src="<?= get_image_file($prod_obj->get_image_ext(), 'm', $prod_obj->get_sku()) ?>" title="<?= $prod_obj->get_prod_name(); ?>" alt="<?= $prod_obj->get_prod_name(); ?>" />
                                    </a>
                                    <div class="quickview hidden-xs">
                                        <a class="iframe-link" data-toggle="tooltip" data-placement="top" href="<?= site_url("/mainproduct/view/$sku/sv") ?>" title="Quick View"><i class="fa fa-eye"></i></a>
                                    </div>
                                    <div class="zoom hidden-xs">
                                        <a data-toggle="tooltip" data-placement="top" href="<?= get_image_file($prod_obj->get_image_ext(), 'l', $prod_obj->get_sku()) ?>" class="product-zoom info-view colorbox cboxElement" title="<?= $prod_obj->get_prod_name(); ?>"><i class="fa fa-search-plus"></i></a>
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
            </div>
	</div>
    </div>
</div>
<?php $this->load->view('/default/footer') ?>
