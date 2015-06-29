<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Multipage_tracking_script_service.php";

#SBF#2247
class Adroll_tracking_script_service extends Multipage_tracking_script_service
{
    public function get_fixed_code()
    {
        $ret_code = "";
        switch ($this->get_country_id()) {
            default:
                // this portion appears in all tradedoubler javascripts
                $ret_code = <<<javascript
<script type="text/javascript">
adroll_adv_id = "RVF3GHANHVB3TFXIDZC5BD";
adroll_pix_id = "DBCLPVGVPFGEZLFMA3JYWH";
(function () {
var oldonload = window.onload;
window.onload = function(){
__adroll_loaded=true;
var scr = document.createElement("script");
var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
scr.setAttribute('async', 'true');
scr.type = "text/javascript";
scr.src = host + "/j/roundtrip.js";
((document.getElementsByTagName('head') || [null])[0] ||
document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
if(oldonload){oldonload()}};
}());
</script>
javascript;
                break;
        }
        return $ret_code;
    }

    public function get_variable_code($page_type, $param)
    {
        $ret_code = "";
        switch ($this->get_country_id()) {
            default:
                switch ($page_type) {
                    case "payment_success":
                        return $this->payment_success_code($param);
                        break;
                }
                break;
        }
    }

    private function payment_success_code($param_list)
    {
        // $sku["id"] = "";
        // $sku["price"] = "";
        // $sku["currency"] = "";
        // $sku["name"] = "";
        // $sku["qty"] = "";
        // $product_list[] = $sku;
        // $param_list["product_list"] = $product_list
        // $param_list["order_id"] = ""
        // $param_list["order_value"] = ""
        // $param_list["currency"] = ""

        // $param_list["product_list"][0]["price"];

        $price = $param_list['price'];
        unset($param_list['price']);
        $json = json_encode($param_list);

        $ret_code = <<<javascript
            <script type="text/javascript">
                adroll_conversion_value_in_dollars = $price
            </script>
            <script type="text/javascript">
                adroll_custom_data = $json
            </script>
javascript;
        return $ret_code;
    }

    private function test()
    {
        $a = new Tradedoubler_tracking_script_service();
        $a->set_country_id("FR");

        echo $a->get_fixed_code();
        echo "***************************************************\r\n";
        echo $a->get_variable_code("homepage");

        $param["id"] = "1234-AA-NA";
        $param["price"] = "999.87";
        $param["currency"] = "SGD";
        $param["name"] = "my phone";
        $param_list[] = $param;
        $param_list[] = $param;
        // echo $a->get_variable_code("category", $param_list);

        $param["productId"] = "1234-AA-NA";
        $param["category"] = "phone";
        $param["brand"] = "nokia";
        $param["productName"] = "my phone";
        $param["productDescription"] = "very power phone";
        $param["price"] = "998.81";
        $param["currency"] = "SGD";
        $param["url"] = "http://www.google.com";
        $param["imageUrl"] = "http://www.google.com/logo.png";
        // echo $a->get_variable_code("product", $param);

        $param_list = null;
        $param["id"] = "1234-AA-NA";
        $param["price"] = "999.87";
        $param["currency"] = "SGD";
        $param["name"] = "my phone";
        $param["qty"] = "9";
        $param_list[] = $param;
        $param_list[] = $param;
        // echo $a->get_variable_code("basket", $param_list);

        $param_list = null;
        $product_list = null;
        $param["id"] = "1234-AA-NA";
        $param["price"] = "999.87";
        $param["currency"] = "SGD";
        $param["name"] = "my phone";
        $param["qty"] = "9";
        $product_list[] = $param;
        $product_list[] = $param;
        $param_list["product_list"] = $product_list;
        $param_list["order_id"] = "5678";
        $param_list["order_value"] = "999.11";
        $param_list["currency"] = "USD";

        echo $a->get_variable_code("payment_success", $param_list);

        die();
    }
}

