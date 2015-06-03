<?php
function generate_upselling_content($tbswrapper, $data)
{
	if ($data['has_ra'] === FALSE)
	{
		return '';
	}

	$tbswrapper->tbsLoadTemplate('resources/template/upselling.html', '', '', $data['lang_text']);

	$checkout["url"] = base_url()."review_order";
	$tbswrapper->tbsMergeField('checkout', $checkout);
	$tbswrapper->tbsMergeField('need_checkout_button', (isset($data["need_checkout_button"]) ? $data["need_checkout_button"] : TRUE));
	$tbswrapper->tbsMergeField('prod_name', $data["prod_name"]);
	$tbswrapper->tbsMergeField('parent_sku', $data["parent_sku"]);
	
	$show_top_bar = isset($data["need_show_topbar"]) ? $data["need_show_topbar"] : TRUE;
	$show_id;
	$show_type;
	

	$default_warranty_image = $data['lang_text']['default_warranty_image'];
	//$tbswrapper->tbsMergeField('warranty_title', $warranty_title);
	$tbswrapper->tbsMergeField('default_warranty_css', $default_warranty_css);
	$tbswrapper->tbsMergeField('default_warranty_text', $default_warranty_text);

	$raw_heading = array();
	if($data["warranty"])
	{
		//$scat_id = 1;
		//$warranty_heading[$key]["name"] = $data['lang_text']['warranty'];
		//$tbswrapper->tbsMergeBlock('warranty_heading', $warranty_heading);

		$wIndex = 1;
		foreach($data["warranty"] AS $key => $val)
		{	
			if($val['wlist']){
				$raw_heading[$key]["index"] = $wIndex++;
				$raw_heading[$key]["name"] = $val['group_name'];
				$raw_heading[$key]["group_id"] = $val['id'];
				$raw_heading[$key]["display"] = "none";
				if($show_top_bar && !isset($show_id) && !isset($show_type))
				{
					$show_type = "rw_";
					$show_id = $val['id'];
				}
			}
		}
		
		$tbswrapper->tbsMergeBlock('raw_heading', $raw_heading);
		
		foreach($data["warranty"] AS $key => $temp)
		{	
			$wgid = $temp['id'];
			if($temp['wlist']){
				foreach($temp['wlist'] as $key => $value)
				{
					if($value)
					{
						${"rw_".$wgid}[$key]["sku"] = $value["sku"];
						${"rw_".$wgid}[$key]["prod_name"] = $value["prod_name"];
						${"rw_".$wgid}[$key]["listing_status"] = $value["listing_status"];
						${"rw_".$wgid}[$key]["stock_status"] =  $value["stock_status"];
						
						if ($value["stock_status_id"] == "O")
						{
							${"rw_".$wgid}[$key]["css_stock_status"] =  "out_stock";
							${"rw_".$wgid}[$key]["checkbox_disabled"] =  "disabled";
							${"rw_".$wgid}[$key]["price_text"] =  $data['lang_text']["status_out_stock"];
							${"rw_".$wgid}[$key]["price_css"] = "outstock";
						}
						else
						{
							${"rw_".$wgid}[$key]["css_stock_status"] =  "in_stock";
							${"rw_".$wgid}[$key]["checkbox_disabled"] =  "";
							${"rw_".$wgid}[$key]["price_text"] =  platform_curr_format(PLATFORMID, $value["price"]);
							${"rw_".$wgid}[$key]["price_css"] =  "";
						}
						${"rw_".$wgid}[$key]["price"] = platform_curr_format(PLATFORMID, $value["price"]);
						${"rw_".$wgid}[$key]["rrp_price"] = platform_curr_format(PLATFORMID, $value["rrp_price"]);
						${"rw_".$wgid}[$key]["prod_url"] = $value["prod_url"];
						${"rw_".$wgid}[$key]["short_desc"] = $value["short_desc"];
						${"rw_".$wgid}[$key]["image"] = get_image_file($value["image_ext"], "m", $value["sku"]);
						if(${"rw_".$wgid}[$key]["listing_status"] == 'I')
						{
							${"rw_".$wgid}[$key]["add_cart"] = base_url()."cart/add_item/".$value["sku"];
						}

						if($_SESSION["ra_items"][PLATFORMID][$data["parent_sku"]][$value["sku"]] > 0)
						{
							${"rw_".$wgid}[$key]["checked"] = "checked";
						}
						else
						{
							${"rw_".$wgid}[$key]["checked"] = "";
						}
						if($key == 0)
						{
							${"rw_".$wgid}[$key]["img"] = '<td style="vertical-align:top" rowspan="' . count($temp['wlist']) . '"><a id="rw_fb_'.$wgid.'" href="'.base_cdn_url().get_image_file($value["image_ext"], "m", $value["sku"]).'" class="fancybox" title=""><img id="rw_img_'.$wgid.'" src="'.base_cdn_url().get_image_file($value["image_ext"], "m", $value["sku"]).'" alt="" style="width:120px;height:120px" /></a></td>';
						}
						else
						{
							${"rw_".$wgid}[$key]["img"] = '';
						}
					}
				}
				$tbswrapper->tbsMergeBlock("rw_".$wgid, ${"rw_".$wgid});
			}
		}
	}
	else
	{
		$tbswrapper->tbsMergeBlock('raw_heading', $raw_heading);
	}

	$ra_heading = array();
	if($data["ra_group"])
	{
		foreach($data["ra_group"] AS $key=>$val)
		{
			$ra_heading[$key]["index"] = $key + 1;
			$ra_heading[$key]["name"] = $val['group_name'];
			$ra_heading[$key]["group_id"] = $val['group_id'];
			$ra_heading[$key]["display"] = "none";
			
			if($show_top_bar && !isset($show_id) && !isset($show_type))
			{
				$show_type = "ra_";
				$show_id = $val['group_id'];
			}
		}

		$tbswrapper->tbsMergeBlock('ra_heading', $ra_heading);

		foreach($data["ra_group"] AS $key=>$val)
		{
			$group_id = $val['group_id'];

			if($data["ra_item"][$group_id])
			{
				foreach($data["ra_item"][$group_id] AS $key=>$value)
				{
					${"rag_".$group_id}[$key]["sku"] = $value["sku"];
					${"rag_".$group_id}[$key]["prod_name"] = $value["prod_name"];
					${"rag_".$group_id}[$key]["listing_status"] = $value["listing_status"];
					${"rag_".$group_id}[$key]["stock_status"] =  $value["stock_status"];
					
					if ($value["stock_status_id"] == "O")
					{
						${"rag_".$group_id}[$key]["css_stock_status"] =  "out_stock";
						${"rag_".$group_id}[$key]["checkbox_disabled"] =  "disabled";
						${"rag_".$group_id}[$key]["price_text"] =  $data['lang_text']["status_out_stock"];
						${"rag_".$group_id}[$key]["price_css"] = "outstock";
					}
					else
					{
						${"rag_".$group_id}[$key]["css_stock_status"] =  "in_stock";
						${"rag_".$group_id}[$key]["checkbox_disabled"] =  "";
						${"rag_".$group_id}[$key]["price_text"] =  platform_curr_format(PLATFORMID, $value["price"]);
						${"rag_".$group_id}[$key]["price_css"] =  "";
					}
					${"rag_".$group_id}[$key]["price"] = platform_curr_format(PLATFORMID, $value["price"]);
					${"rag_".$group_id}[$key]["rrp_price"] = platform_curr_format(PLATFORMID, $value["rrp_price"]);
					${"rag_".$group_id}[$key]["prod_url"] = $value["prod_url"];
					${"rag_".$group_id}[$key]["short_desc"] = $value["short_desc"];
					${"rag_".$group_id}[$key]["image"] = get_image_file($value["image_ext"], "m", $value["sku"]);
					if(${"rag_".$group_id}[$key]["listing_status"] == 'I')
					{
						${"rag_".$group_id}[$key]["add_cart"] = base_url()."cart/add_item/".$value["sku"];
					}

					if($_SESSION["ra_items"][PLATFORMID][$data["parent_sku"]][$value["sku"]] > 0)
					{
						${"rag_".$group_id}[$key]["checked"] = "checked";
					}
					else
					{
						${"rag_".$group_id}[$key]["checked"] = "";
					}
					if($key == 0)
					{
						${"rag_".$group_id}[$key]["img"] = '<td style="vertical-align:top" rowspan="' . count($data["ra_item"][$group_id]) . '"><a id="ra_fb_'.$group_id.'" href="'.base_cdn_url().get_image_file($value["image_ext"], "m", $value["sku"]).'" class="fancybox" title=""><img id="ra_img_'.$group_id.'" src="'.base_cdn_url().get_image_file($value["image_ext"], "m", $value["sku"]).'" alt="" style="width:120px;height:120px" /></a></td>';
					}
					else
					{
						${"rag_".$group_id}[$key]["img"] = '';
					}
				}
				$tbswrapper->tbsMergeBlock("rag_".$group_id, ${"rag_".$group_id});
			}
		}
	}
	else
	{
		$tbswrapper->tbsMergeBlock('ra_heading', $ra_heading);
	}

	$tbswrapper->tbsMergeField('show_id', $show_id);
	$tbswrapper->tbsMergeField('show_type', $show_type);
	$tbswrapper->tbsMergeField('need_show_topbar', (isset($data["need_show_topbar"]) ? $data["need_show_topbar"] : TRUE));
	
	$tbswrapper->tbsMergeField('cdn_url', base_cdn_url());
	return $tbswrapper->tbsRender();
}
?>