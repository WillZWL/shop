<div class="banner p10">
    <a href="<?php if ($banner_url_para == '') echo 'javascript:;'; else echo $base_url . 'search/search_by_ss' . $banner_url_para; ?>" title="">
        <img src="<?=$cdn_url?>resources/mobile/images/banner/<?=$banner_name?>" alt=""/>
    </a>
</div>
     
<div class="p10">
    <h2 class="section-title Rokkitt left"><?php print $lang_text["index_best_sellers"]; ?></h2>
    <ul class="socials right">
        <li><a href="<?=$twitter_link?>" title="" target="_blank"><img src="<?=$cdn_url?>/resources/mobile/images/twitter.png" alt=""/></a></li>
        <li><a href="<?=$g_plus_link?>" title="" target="_blank"><img src="<?=$cdn_url?>/resources/mobile/images/gplus.png" alt=""/></a></li>
        <li><a href="<?=$facebook_link?>" title="" target="_blank"><img src="<?=$cdn_url?>/resources/mobile/images/fb.png" alt=""/></a></li>
    </ul>
    <br class="clear"/>
</div>

<div class="homepage-slider">       
    <ul>
<?php
	foreach ($best_seller as $key => $item)
	{
?>
        <li>
            <div>
                <a href="<?php print $item["prod_url"]; ?>" title="" class="img-link">
                    <img src="<?php print $item["image"]; ?>" width="120" />
                </a>
                <a href="<?php print $item["prod_url"]; ?>" title=""><strong><?php print $item["prod_name"]; ?></strong></a>
                <del><?php print $item["rrp_price"]; ?></del>
                <strong class="price"><?php print $item["price"]; ?></strong>
                <span><?php print $item["stock_status"]; ?></span>
            </div>
        </li>
<?php
	}
?>
    </ul>    
</div>

<ol class="homepage-slider-pages"><li>&nbsp;</li></ol>
<div class="p10">
    <h2 class="section-title Rokkitt"><?php print $lang_text['reason_heading'];?></h2>
    <div class="accordion"> 
        <!-- every div is accordion element -->
        <div>
            <strong class="white-grey-gradient"><?php print strtoupper($lang_text['about_us']);?></strong>
            <div class="general-text"><?php print $lang_text['about_us_content'];?></div>
        </div>
        
        <div>
            <strong class="white-grey-gradient"><?php print strtoupper($lang_text['reason_heading']);?></strong>
            <div class="general-text">
                <ul>
                    <li><?php print $lang_text['reason1'];?></li>
					<li><?php print $lang_text['reason2'];?></li>
					<li><?php print $lang_text['reason3'];?></li>
					<li><?php print $lang_text['reason4'];?></li>
					<li><?php print $lang_text['reason5'];?></li>
					<li><?php print $lang_text['reason6'];?></li>
                </ul>
            </div>
        </div>        
    </div>
</div>