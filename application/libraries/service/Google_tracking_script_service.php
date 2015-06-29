<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "multipage_tracking_script_service.php";

#SBF#2096
class Google_tracking_script_service extends Multipage_tracking_script_service
{
    public function get_fixed_code()
    {
        $id = "";
        $ret_code = "";
        switch ($this->get_country_id()) {
            case "AU":
                $id = "998162196";
                $label = "UyGqCKzhwAQQlP762wM";
                break;
            case "FR":
                $id = "999266961";
                $label = "jzhzCP_fwgQQkbW-3AM";
                break;
            case "GB":
                $id = "1017948130";
                $label = "wA76COabkAQQ4s-y5QM";
                break;
            case "MY":
                $id = "998162196";
                $label = "qv7WCJz1nAQQlP762wM";
                break;
            case "NZ":
                $id = "998162196";
                $label = "qv7WCJz1nAQQlP762wM";
                break;
            case "SG":
                $id = "998162196";
                $label = "qv7WCJz1nAQQlP762wM";
                break;
            default:
                break;
        }

        if ($id != "") {
            // this portion appears in all pages
            $ret_code = <<<javascript
<div style="display:none">
<!-- Google Code for Remarketing tag -->
<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = $id;
    var google_conversion_label = "$label";
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/{$id}/?value=0&amp;label={$label}&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
</div>
javascript;
        }

        return $ret_code;
    }

    public function get_variable_code($page_type, $param)
    {
        $ret_code = "";
        switch ($this->get_country_id()) {
            #2096 - implements AU only
            case "AU":
                switch ($page_type) {
                    case "home_page":
                        return $this->home_page($param);
                        break;
                    case "category_page":
                        return $this->category_page($param);
                        break;
                    case "product_page":
                        return $this->product_page($param);
                        break;
                    case "cart_page":
                        return $this->cart_page($param);
                        break;
                    case "payment_success":
                        return $this->payment_success_code($param);
                        break;
                }
                break;
        }
    }

    private function home_page($param_list)
    {
        $ret_code = <<<javascript
            <script type="text/javascript">
                var google_tag_params =
                {
                    prodid: '',
                    pvalue: '',
                    pcat:   '''',
                    pname:  '',
                    pagetype: 'home'
                }
            </script>
javascript;
        return $ret_code;
    }

    private function category_page($param_list)
    {
        $ret_code = <<<javascript
            <script type="text/javascript">
                var google_tag_params =
                {
                    prodid: '',
                    pvalue: '',
                    pcat:   '{$param_list["pcat"]}',
                    pname:  '',
                    pagetype: 'category'
                }
            </script>
javascript;
        return $ret_code;
    }

    private function product_page($param_list)
    {
        $ret_code = <<<javascript
            <script type="text/javascript">
                var google_tag_params =
                {
                    prodid: '{$param_list["prodid"]}',
                    pvalue: '{$param_list["pvalue"]}',
                    pcat:   '{$param_list["pcat"]}',
                    pname:  '{$param_list["pname"]}',
                    pagetype: 'product'
                }
            </script>
javascript;
        return $ret_code;
    }

    private function cart_page($param_list)
    {
        $ret_code = <<<javascript
            <script type="text/javascript">
                var google_tag_params =
                {
                    prodid: {$param_list["prodid"]},
                    pvalue: {$param_list["pvalue"]},
                    pcat:   {$param_list["pcat"]},
                    pname:  {$param_list["pname"]},
                    pagetype: 'cart'
                }
            </script>
javascript;
        return $ret_code;
    }

    private function payment_success_code($param_list)
    {
        $ret_code = <<<javascript
            <script type="text/javascript">
                var google_tag_params =
                {
                    prodid: {$param_list["prodid"]},
                    pvalue: {$param_list["pvalue"]},
                    pcat:   {$param_list["pcat"]},
                    pname:  {$param_list["pname"]},
                    pagetype: 'purchase'
                }
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

