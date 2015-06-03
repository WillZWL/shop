<link rel="stylesheet" type="text/css" href="/resources/mobile/css/searchspring.css">

<div id="content">
	<div class="p10">
		<div id="search_sidebar" class="searchspring-facets_container">
			<div style="display:none" id="searchspring-sidebar" class="sidebar searchspring-widget_container left">
				<h3 class="filter"><?=$lang_text['filter_result']?></h3>
				<div id="searchspring-history_container"><h3 id="searchspring-history_header"><?=$lang_text['history']?></h3><ul id="searchspring-history"></ul></div>
				<div id="searchspring-summary_container"><h3 id="searchspring-summary_header"><?=$lang_text['summary']?></h3><ul id="searchspring-summary"></ul></div>
				<!--<div id="searchspring-reset">Reset</div>-->
				<!--<form id="searchspring-refine_search">-->
					<!--<h3 id="searchspring-refine_header">Search Within Results</h3>-->
					<!--<input type="text" name="rq" id="searchspring-refine_query" />-->
					<!--<input type="submit" value="Search" id="searchspring-refine_submit">-->
				<!--</form>-->
				<ul id="searchspring-facets">
				</ul>
				<div class="merchandising" id="searchspring-merch_left"></div>
				<div class="dummy"></div>
			</div>	<!-- /navigation -->

			<div class="clear"></div>
		</div>

		<div class="clear"></div>
	</div>

	<div class="p10" id="content">
		<div id="search-result" class="searchspring-results_container">
			<div id="searchspring" class="searchspring-widget_container left">
				<div class="container">
					<div id="searchspring-loading"></div>
					<div id="searchspring-main" style="display:none">
						<div id="searchspring-options">
							<h1><span id="searchspring-first_item" class="searchspring-first_item">1</span> <strong>-</strong> <span id="searchspring-last_item" class="searchspring-last_item">30</span> <?=$lang_text['of']?> <span id="searchspring-total_items" class="searchspring-total_items">0</span> <?=$lang_text['search_results_for']?> <span id="searchspring-query" class="searchspring-query_display"></span></h1>
						</div>	<!-- sort-->

						<div id="searchspring-sub_bar">
							<p class="sort-by"><?=$lang_text['sort_by']?>: <span id="searchspring-sorting"></span></p>
							<div id="searchspring-per_page_container"><?=$lang_text['show']?>: <span class="per-page"></span></div>
							<p class="view-type">
								<a id="searchspring-list_result_layout" title="List View" class="searchspring-list_result_layout result_layout"><span></span></a>
								<a id="searchspring-grid_result_layout" title="Grid View" class="searchspring-grid_result_layout result_layout"><span></span></a>
							</p>

							<table class="pagination top">
								<tbody>
									<tr>
										<td class="searchspring-previous"><div></div></td>
										<td class="searchspring-pageOf"> <?=$lang_text['of']?> </td>
										<td class="searchspring-total_pages">1</td>
										<td class="searchspring-next"><div></div></td>
									</tr>
								</tbody>
							</table>

							<div class="breadcrumbs"></div>
						</div>

						<div id="searchspring-full_container">
							<div class="merchandising" id="searchspring-merch_header"></div><div class="merchandising" id="searchspring-merch_banner"></div>
							<div id="searchspring-compare_box" class="searchspring-compare_box" style="display: none">
								<div><span id="searchspring-compare_text"> <?=$lang_text['compare']?> </span> <span id="searchspring-compare_button"></span><div class="clear"></div></div>
								<div class="searchspring-compare_image_container" id="searchspring-compare_image_container1"></div>
								<div class="searchspring-compare_image_container" id="searchspring-compare_image_container2"></div>
								<div class="searchspring-compare_image_container" id="searchspring-compare_image_container3"></div>
								<div class="searchspring-compare_image_container" id="searchspring-compare_image_container4"></div>
							</div>
							<div style="clear:right"></div>
							<div id="searchspring-did_you_mean"></div>
							<div id="searchspring-search_results" class="grid"></div>
							<div class="clear"></div>
							<div class="merchandising" id="searchspring-merch_footer"></div>
							<div id="searchspring-bottom_container">
								<table class="pagination bottom">
									<tbody>
										<tr>
											<td class="searchspring-previous"><div></div></td>
											<td class="searchspring-pageOf"> <?=$lang_text['of']?> </td>
											<td class="searchspring-total_pages">1</td>
											<td class="searchspring-next"><div></div></td>
										</tr>
									</tbody>
								</table>
								<a style="border:0; float: right;" href="http://www.searchspring.net" target="_blank" title="Powered by SearchSpring"><img id="searchspring-powered_by" src="//d2r7ualogzlf1u.cloudfront.net/ajax_search/img/powered.png" alt="SearchSpring" width="138" height="25" /></a>
								<div class="clear"></div>
							</div>
						</div>
					</div>	<!-- /main -->
				</div>	<!-- /container -->
				<div id="searchspring-compare_results"></div>
			</div>
		</div>

		<div class="clear"></div>
	</div>

	<!--<link href="http://dev.clients.com/resources/valuebasket.css" rel="stylesheet" type="text/css" media="screen, print" />-->
	<script type='text/javascript' src='//s3.amazonaws.com/a.cdn.searchspring.net/ajax_search/sites/<?=$searchspring_site_id?>/js/<?=$searchspring_site_id?>.js'></script>
	<script type='text/javascript' src='//d2r7ualogzlf1u.cloudfront.net/ajax_search/js/searchspring-catalog.min.js'></script>
	<script type='text/javascript'>SearchSpring.Catalog.init({
		results_per_page: 24,
		maxFacetOptions: 6,
		sortType: 'dropdown',
		resultsPerPageType: 'dropdown',
		expandedText : '<?=$lang_text['collapse']?>',
		collapsedText : '<?=$lang_text['expand']?>',
		showSearchHistory: true,
		showSummary: true,
		result_layout:'list',
		forwardSingle: false,
		filterData: function(d) {
			for(var i = 0; i < d.facets.length; i++){
				d.facets[i].collapse = 1;
			}
			console.log(d);
			return d;
		},
		afterResultsChange: function() {
			SearchSpring.jQuery("#searchspring .vb-item .info").each(function() {
				var desc = SearchSpring.jQuery(this).text()
				if(desc.length > 300) {
					SearchSpring.jQuery(this).text(desc.substr(0,300) + '...');
				}
			});

			var skus = [];
			SearchSpring.jQuery('.vb-item').each(function() {
				skus.push(SearchSpring.jQuery(this).attr('id'));
			});

			skus = skus.join(',');

			SearchSpring.jQuery.ajax(
					('https:' == document.location.protocol ? 'https://' : 'http://') + 'm.valuebasket.com/search/ss_live_price/<?=PLATFORMID?>?sku=' + skus,
					{
						dataType : 'json',
						success : function(data) {
							for(var sku in data) {
								var price = data[sku];
								SearchSpring.jQuery('#before_'+sku).text(price[0]);
								SearchSpring.jQuery('#price_'+sku).text(price[1]);
								SearchSpring.jQuery('#discount_'+sku).text(price[2]);
								SearchSpring.jQuery('#stock_'+sku).text(price[3]);

								if (price[4] == 'O' || price[4] == 'A') {
									SearchSpring.jQuery('#add_'+sku).hide();
								}
							}
						}
					}
			);

			var i = 0;
			SearchSpring.jQuery('.per-page select option').each(function(){
				i++;
				if(i == 1) {
					SearchSpring.jQuery(this).text('12');
				} else if(i == 2) {
					SearchSpring.jQuery(this).text('24');
				} else {
					SearchSpring.jQuery(this).text('48');
				}
			});
			
			SearchSpring.jQuery('.vb-item a').each(function() {
				var a = SearchSpring.jQuery(this).attr("href");
				SearchSpring.jQuery(this).attr("href", a.replace(/www.valuebasket\.(.+?)(\/<?=str_replace('/', '', $lang_country_pair)?>)?\//, 'm.valuebasket.com<?=($lang_country_pair == '' ? '/' : $lang_country_pair)?>'));
			});


			if(SearchSpring.jQuery('#searchspring-summary li').length > 0) {
				SearchSpring.jQuery('#searchspring-summary_container').show();
			} else {
				SearchSpring.jQuery('#searchspring-summary_container').hide();
			}

			if(SearchSpring.jQuery('#searchspring .no-results').length > 0) {
				SearchSpring.jQuery('#search_sidebar').hide();
				SearchSpring.jQuery('#searchspring').css('width', '970px');
			} else {
				SearchSpring.jQuery('#search_sidebar').show();
				SearchSpring.jQuery('#searchspring').css('width', '800px');
			}

			var dym = SearchSpring.jQuery('#searchspring-did_you_mean:visible');
			if(dym.length > 0) {
				var dym_text = dym.find('a').text();
				var dym_link = SearchSpring.jQuery('<a />').text(dym_text).click(function() {
					SearchSpring.jQuery.address.parameter('q', dym_text);
					SearchSpring.jQuery.address.parameter('page', 1);
					SearchSpring.jQuery.address.update();
				});
				dym.empty().append('<?=$lang_text['did_you_mean']?>').append(dym_link).append('?');
			}
		}
	});</script>
</div>