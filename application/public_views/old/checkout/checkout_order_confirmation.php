<div id="content">
<h5 class="side_title"><?=$data['lang_text']['confirmation_title']?></h5>
    <div id="shopping_cart">
        <div class="silver_box" id="payment_result">
            <p class="rokkit_12">
                <?=$data['lang_text']['para_details1']?><br>
                <?=$data['lang_text']['para_details2']?> <?=$data['so_no']?>
                <br><br>
            </p>
            <p>
                <?=$data['lang_text']['para_text1']?> <a href="mailto:<?=$data['client_email']?>"><?=$data['client_email']?></a>
                <?=$data['lang_text']['para_text2']?>
            </p>
            <p>
            <?=$data['lang_text']['order_review_text1']?><br><br>
            <p>
                <div style="float:left;width:400px;">
                    <b><?=$data['lang_text']['order_review_shipping_address']?></b><br><br>
                    <?=$data['client_name']?><br>
                    <?=$data['address']?><br>
                    <?=$data['city']?> <?=$data['postcode']?> <br>
                    <?=$data['country_name']?><br><br>
                    <b><?=$data['lang_text']['telephone']?></b><br><br>
                    <?=$data['telephone']?><br><br>
                </div>
                <div style="float:left;">
                    <b><?=$data['lang_text']['order_review_items_ordered']?></b><br><br>
                    <?=$data['item_detail']?><br>
                </div>
                <div class="clear"></div>
            </p>
            <p>
            <?=$data['lang_text']['payment_instruction1']?><br><br>
            <?=$data['lang_text']['payment_instruction2']?><br><br>
            <?=$data['lang_text']['payment_instruction3']?><br><br>
            <?=$data['lang_text']['payment_instruction4']?><br><br>
            <?=$data['lang_text']['payment_instruction5']?><br><br>
            <?=$data['lang_text']['enquiry']?><br><br>
            <?=$data['lang_text']['thank_you']?><br><br>
            </p>
        </div>
    </div>
</div>