<div id="content">
	<a href="javascript:history.go(-1);" title="" class="go-back right">Back</a>

	<div class="silver_box product">
		<ul class="breadcrumbs">
			<?php
				foreach ($breadcrumb as $key=>$value)
				{
					foreach($value AS $name=>$url)
					{
						echo '<li><a href="' . $url . '">' . $name . '</a> / </li>';
					}
				}
			?>
			<li class="breadcrumbs_product_name"><?=$prod_name?></li>

			<?=$sbf2183_img_tag?>
			<?=$sbf2618_img_tag?>
			<?=$hcb20130830_img_tag?>
		</ul>

		<div class="p10 clear">
			<div class="product-main p10">
				<div class="top">
					<h1><?=$prod_name?></h1>
					<div class="product-image-slider">       
						<ul>
							<?php
								echo '<li><img src="' . $cdn_url . $default_prod_img['image'] . '" /></li>';

								foreach ($prod_img_arr as $key=>$value)
								{
									echo '<li><img src="' . $cdn_url . $value['image'] . '" /></li>';
								}
							?>
						</ul>    
					</div>
					<ol class="homepage-slider-pages"><li>&nbsp;</li></ol>            
				</div>

				<div class="actions">
					<div class="price-details tac">
						<span style="display:inline-block; padding-right:10px; text-align:left">
							<span style="display:block"><?=$lang_text['price']?>:</span>
							<span class="orange" style="display:block"><?=$lang_text['you_pay']?>:</span>
							<span><?=$stock_status?></span>
						</span>
						<span style="display:inline-block; text-align:left">
							<span style="display:block"><del><?=$display_rrp_price?></del></span>
							<span class="orange" style="display:block"><strong><?=$display_price?></strong></span>
							<span><?=$lang_text['free_shipping']?></span>
						</span>
					</div>

					<form name="review_quantity" action="?" method="post" class="tac">
						<fieldset>
							<legend><?=$lang_text['buy_product']?></legend>
							<label for=""><?=$lang_text['qty']?></label>
							<select name="quantity" id="quantity">
								<?php
									for($i=1; $i<=$qty; $i++)
									{
										echo "<option value='$i'>$i</option>";
									}
								?>
							</select>
							<a class="add-to-basket orange-gradient" onclick="addToCart()"><?=$add_to_basket?></a>

							<?php
								if ($cash_on_delivery_image != '')
								{
							?>
									<div><img src='<?=$cdn_url . $cash_on_delivery_image?>'></div>
							<?php
								}
							?>
						</fieldset>
					</form>   

					<?php
						if ($gst_msg_type != '')
						{
					?>
							<div class="gst_banner">
								<div class="clear">
									<?php
										if ($gst_msg_type == 'UNDER')
										{
											echo '<img style="float:left; padding-right:15px" src="' . $cdn_url . 'resources/images/GST_i_m.png">';
											echo ''.$lang_text['gst_banner_1'] . '<br/>';
										}
										else
											echo '<img src="' . $cdn_url . 'resources/images/GST_i_s.png">';
									?>

									<a href="javascript:;" class="toggle-hidden-tip"><?=$lang_text['gst_banner_2']?></a>
									<p class="hidden-tip">
										<img src='<?=$cdn_url?>resources/images/GST_i_m.png'>
										<span class="gst_policy_heading"><?=$lang_text['gst_policy_1']?></span><br/>
										<span class="gst_policy_content"><?=$lang_text['gst_policy_2']?></span><br/>
										<span class="gst_policy_content"><?=$lang_text['gst_policy_3']?></span><br/>
										<span class="gst_policy_content"><?=$lang_text['gst_policy_4']?></span><br/>
									</p>
								</div>
							</div>
					<?php
						}
					?>

					<?php
						if ($warranty_in_month != '')
						{
					?>
							<div class="tac">
								<a href="javascript:;" class="toggle-hidden-tip" title="<?=$lang_text['warranty_desc']?>"><?=$warranty_in_month . ' ' . $lang_text['month_warranty_text']?></a>
								<p class="hidden-tip"><?=$lang_text['warranty_desc']?></p>
							</div>
					<?php
						}
					?>

					<div class="tac">
						<a href="javascript:;" class="toggle-hidden-tip" title=""><?=$website_status_short_text?></a>
						<p class="hidden-tip"><?=$website_status_long_text?></p>
					</div>   

					<div class="tac Rokkitt features">
						<ul>
							<li>
								<img src="<?=$cdn_url?>resources/mobile/images/product-free-delivery.png" alt=""/>
								<strong><?=$lang_text['free_shipping']?></strong>
							</li>
							<li>
								<img src="<?=$cdn_url?>resources/mobile/images/product-low-prices.png" alt=""/>
								<strong><?=$lang_text['low_price']?></strong>
							</li>
							<li>
								<img src="<?=$cdn_url?>resources/mobile/images/product-easy-and-secury.png" alt=""/>
								<strong><?=$lang_text['easy_secure_ordering']?></strong>
							</li>                    
						</ul>
					</div>    
				</div>

				<div class="accordion"> 
					<!-- every div is accordion element -->
					<?php
						if ($has_overview)
						{
					?>
							<div class="active">
								<strong class="white-grey-gradient" id="overview_div"><?=$lang_text['overview']?></strong>
								<div class="general-text">
									<?=$overview?><br /><br />

									<?php
										if ($feature)
										{
									?>
											<h2><b><?=$prod_name?> <?=$lang_text['feature']?>:</b></h2>
											<p><?=$feature?></p><br />
									<?php
										}

										if ($gst_msg_type != '')
										{
											echo '<p>';

											if ($gst_msg_type == 'UNDER')
											{
												echo $lang_text['gst_supplier_msg_under_amount_1'];
												echo '<a href="' . $base_url . 'display/view/conditions_of_use" name="gst_supplier_msg" id="gst_supplier_msg">' . $lang_text['gst_supplier_msg_under_amount_2'] . '</a>';
											}
											else
											{
												echo $lang_text['gst_supplier_msg_over_amount_1'];
												echo '<a href="' . $base_url . 'display/view/conditions_of_use" name="gst_supplier_msg" id="gst_supplier_msg">' . $lang_text['gst_supplier_msg_over_amount_2'] . '</a>';
											}

											echo '</p>';
										}
									?>

									<b><?=$sbf1597?></b>
								</div>
							</div>
					<?php
						}

						if ($has_specification)
						{
					?>
							<div>
								<strong class="white-grey-gradient"><?=$lang_text['specification']?></strong>
								<div class="general-text"><?=$specification?></div>
							</div>
					<?php
						}

						if ($has_in_the_box)
						{
					?>
							<div>
								<strong class="white-grey-gradient"><?=$lang_text['in_the_box']?></strong>
								<div class="general-text"><?=$in_the_box?></div>
							</div>
					<?php
						}
					?>
				</div>        
			</div>
		</div>

		<!--
		<div class="p10">
			<h2 class="section-title Rokkitt"><?=$lang_text['accessories']?></h2>
			<div class="product-list">
				<ul class="items">
					<li>
						<a href="javascript:;" title="" class="img-link">
							<img src="web/images/temp/p3.png" alt=""/>
							<span>5 In Stock</span>
						</a>
						<div>
							<a href="javascript:;" title="">
								<strong>Sony Cyber-shot DSC-W670 Digital Camera (Silver)</strong>
							</a>
							<span>Price: HK<del>$ 2.004.99</del></span>
							<span class="orange">You Pay: HK <strong>$ 1.699.00</strong></span>
						</div>
						<br class="clear"/>
						<a href="javascript:;" title="" class="more-info grey-gradient">More Info</a>
						<a href="javascript:;" title="" class="add-to-basket orange-gradient">Add to Basket</a>
					</li>
				</ul>

				<div class="p10">
					<span class="results-count">(116 products, 10 pages)</span>
					<ol class="paging right">
						<li><a href="javascript:;" title="" class="prev"><span>&nbsp;</span></a></li>
						<li class="active"><a href="javascript:;" title="">1</a></li>
						<li><a href="javascript:;" title="">2</a></li>
						<li><a href="javascript:;" title="" class="next"><span>&nbsp;</span></a></li>
					</ol>    
					<br class="clear"/>
				</div>
			</div>
		</div>
		-->

		<script type="text/javascript">
			function addToCart()
			{
				var qty = document.getElementById('quantity').value;
				if(qty > 0 && qty != "")
				{
					document.location.href = "<?=$base_url?>cart/add_item_qty/<?=$sku?>/"+qty;
				}
			}
		</script>