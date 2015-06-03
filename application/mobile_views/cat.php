<?php

function displayPagination($total_pages, $total_result, $results_per_page, $curr_page, $display_range, $sort, $brand_id)
{
	if ($total_pages == 1)
		return;

	if (($total_pages > 1) && ($curr_page != 1))
	{
		print "<li><a href=\"?page=" . ($curr_page - 1) . "&sort=" . $sort. "&band_id=" . $brand_id . "\" title=\"\" class=\"prev\"><span>&nbsp;</span></a></li>";
	}

	$middle_page = ceil($display_range/2);
	$start_page = $curr_page - $middle_page;
	if ($start_page <= 0)
		$start_page = 1;
	$end_page = $start_page + $display_range - 1;
	if ($end_page > $total_pages)
	{
		$end_page = $total_pages;
		$start_page = $end_page - $display_range;
		if ($start_page <= 0)
			$start_page = 1;
	}

	for($i=$start_page;$i<=$end_page;$i++)
	{
		$css = "";
		if ($i == $curr_page)
		{
			$css = "class='active'";
		}
		print "<li " . $css . "><a href=\"?page=" . $i . "&sort=" . $sort. "&band_id=" . $brand_id . "\" title=\"\">" . $i . "</a></li>";
	}
	if (($total_pages > 1) && ($curr_page != $total_pages))
	{
		print "<li><a href=\"?page=" . ($curr_page + 1) . "&sort=" . $sort. "&band_id=" . $brand_id . "\" title=\"\" class=\"next\"><span>&nbsp;</span></a></li>";
	}
}
?>
<div class="p10">
    <div class="filters">
        <h2 class="section-title Rokkitt"><?php print $lang_text["filter_result"]; ?></h2>
        <div class="accordion p10"> 
            <!-- every div is accordion element -->
            <div>
                <strong class="white-grey-gradient"><?php print $lang_text["category"]; ?></strong>
                <div>
                    <ul>
<?php
foreach($cat_result as $key => $value)
{
	print "<li><a href='" . $value["url"] . "' title='" . $value["name"] . "'>" . $value["name"] . " (" . $value["total"] . ")</a></li>";
}
?>
                    </ul>
                </div>
            </div>
            <div>
                <strong class="white-grey-gradient"><?php print $lang_text["brand"]; ?></strong>
                <div>
                    <ul>
<?php
foreach($brand_result as $key => $value)
{
	$brand_url = $_SERVER['REDIRECT_URL'].'?page=1&sort=' . $sort . '&brand_id=' . $value["id"];
	print "<li><a href='" . $brand_url . "' title='" . $value["name"] . "'>" . $value["name"] . " (" . $value["total"] . ")</a></li>";
}
?>
                    </ul>
                </div>
            </div>        
        </div>        
    </div>
</div>

<div class="p10">
    <h2 class="section-title Rokkitt"><?php print $cat_name; ?></h2>
    <div class="product-list">
        <div class="top p10">            
            <form id="" name="" method="post">
                <fieldset>
                    <legend>Sort your products</legend>
                    <label><?php print $lang_text["sort_by"]; ?>:</label>
                    <select name="sort" id="sort" onchange="getQueryString()">
                        <option value="pop_desc" <?php print ($sort == 'pop_desc') ? "selected" : "" ?>><?php print $lang_text["most_popular"]; ?></option>
                        <option value="latest_desc" <?php print ($sort == 'latest_desc') ? "selected" : "" ?>><?php print $lang_text["new_arrival"]; ?></option>
                        <option value="price_asc" <?php print ($sort == 'price_asc') ? "selected" : "" ?>><?php print $lang_text["price_low_to_high"]; ?></option>
						<option value="price_desc" <?php print ($sort == 'price_desc') ? "selected" : "" ?>><?php print $lang_text["price_high_to_low"]; ?></option>
                    </select>
                </fieldset>
            </form>
            <ol class="paging">
<?php displayPagination($total_page, $total_result, $rpp, $curr_page, $display_range, $sort, $brand_id);?>
            </ol>    
        </div>
        <ul class="items">
<?php
	foreach($product_list as $item)
	{
?>
            <li>
                <a href="<?php print $item["prod_url"];?>" title="<?php print $item["prod_name"] ?>" class="img-link">
                    <img src="<?php print $item["image"] ?>" alt="<?php print $item["prod_name"] ?>" width="130"/>
                    <span><?php print (($item["listing_status"] == "I") ? $item["qty"] : "" ). " " . $item["listing_status_text"]; ?></span>
				</a>
                <div>
                    <a href="<?php print $item["prod_url"];?>" title="<?php print $item["prod_name"] ?>">
                        <strong><?php print $item["prod_name"] ?></strong>
                    </a>
                    <span><?php print $lang_text["price"]; ?>: <del><?php print $item["rrp_price"] ?></del></span>
                    <span class="orange"><?php print $lang_text["you_pay"]; ?>: <strong><?php print $item["price"] ?></strong></span>
                    <a href="<?php print base_url() . "cart/add_item/" . $item["sku"]; ?>" title="" class="add-to-basket orange-gradient"><?php print $lang_text["add_basket"]; ?></a>
                </div>
			</li>
<?php
	}
?>
        </ul>
        <div class="p10">            
            <span class="results-count">(<?php print $total_result . " " . $lang_text["products"]; ?>, <?php print $total_page . " " . $lang_text["pages"]; ?>)</span>    
            <ol class="paging right">
<?php displayPagination($total_page, $total_result, $rpp, $curr_page, $display_range, $sort, $brand_id);?>
            </ol>    
            <br class="clear"/>
        </div>
    </div>
</div>
<script language="javascript">
function getQueryString()
{
	url = '?page=<?php print $curr_page;?>&sort=' + document.getElementById('sort').options[document.getElementById('sort').options.selectedIndex].value;
	document.location.href = url;
}
</script>
