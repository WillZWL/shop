<?php
    header('Content-type: application/xml');
    echo "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">\n";
?>
<?php
$status = array("I"=>"In Stock","O"=>"Out Of Stock","A"=>"In Stock with Supplier","P"=>"Pre-order");
if($obj)
{
    $quantity = $obj->get_display_quantity()?min($obj->get_website_quantity(),$obj->get_display_quantity()):$obj->get_website_quantity();
    $in_stock = ($quantity > 0 && $obj->get_website_status() == 'I' && $obj->get_sourcing_status() != 'O' && $obj->get_prod_status() == 2 && $listing_status == 'L')?"true":"false";
?>
    <product>
        <id/>
        <sku><?=$sku_display?></sku>
        <name><?=$prod_name?></name>
        <product_url><?=$prod_url?></product_url>
        <shop_logo>http://<?=$_SERVER['HTTP_HOST']?>/images/chatandvision-skype.jpg</shop_logo>
        <price currency="<?=PLATFORMCURR?>"><?=$price?></price>
        <promotion_price currency="<?=PLATFORMCURR?>"><?=$promotion_price?></promotion_price>
        <bundle_price currency="<?=PLATFORMCURR?>"><?=$bundle_price?></bundle_price>
        <shipping_cost currency="<?=PLATFORMCURR?>"><?=$shipping_cost?></shipping_cost>
        <stock>
            <qty><?=$quantity?></qty>
            <in_stock><?=$in_stock?></in_stock>
        </stock>
        <promo_text><?=$promo_text?></promo_text>
    </product>
<?php
}
?>
