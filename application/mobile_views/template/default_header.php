        <div class="header">
            <a href="/<?php print get_lang_id() . "_" . PLATFORMCOUNTRYID . "/"; ?>" title="" class="logo"><img src="<?php print $cdn_url . '/resources/mobile/images/logo.png' ?>" alt=""/></a>
            <button type="button" class="toggle-menu"><span>&nbsp;</span></button>
            <ul class="category-nav">
                <li class="nav-title"><?php print $lang_text["default_text_browse_category"];?></li>
<?php
	include_once(VIEWPATH . "template/menu/" . get_lang_id() . "/menu_" . strtolower(PLATFORMID) . ".html");
?>
            </ul>
        </div>
        <div class="quick-nav">
            <ul>
                <li><a href="<?php print $base_url . "display/view/faq"; ?>" title="" class="item-1">&nbsp;</a></li>
                <li><a href="<?php print $base_url . "myaccount"; ?>" title="" class="item-2">&nbsp;</a></li>
                <li><a href="<?php print $base_url . "display/view/shipping"; ?>" title="" class="item-3">&nbsp;</a></li>
                <li><a href="<?php print $base_url . "contact"; ?>" title="" class="item-4">&nbsp;</a></li>
                <li><a href="<?php print $base_url . "display/view/warranty"; ?>" title="" class="item-5">&nbsp;</a></li>
            </ul>
        </div>
        
        <div class="p10">
            <div class="product-search">
                <form id="searchform" name="searchform" action="<?php print $searchAction;?>" method="GET">
                    <fieldset>
                        <legend>Product search form</legend>
                        <input type="text" name="q" autocomplete="off" onfocus="this.value=''" value="" placeholder="<?php print $lang_text["default_text_find_your_product"];?>"/>
                    </fieldset>
                </form>
                <a href="<?php print base_url() . "review_order";?>" title="" class="basket-link orange-gradient">
                    <span class="basket-icon">&nbsp;</span>
                    <span class="items-count"><?php print $cart["item"];?></span>
                </a>
                <br class="clear"/>
            </div>
        </div>
