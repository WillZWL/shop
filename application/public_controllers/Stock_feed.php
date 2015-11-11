<?php
if ($GLOBALS["URI"]->segments[2] == "xml_skype_feed") {
    DEFINE ('PLATFORM_TYPE', 'SKYPE');
}

class Stock_feed extends PUB_Controller
{

    private $lang_id = "en";

    public function Stock_feed()
    {
        parent::PUB_Controller(array("get_currency_list" => 0));
        $this->load->model('website/cart_session_model');
        $this->load->model('website/stock_feed_model');
        $this->load->library('service/context_config_service');
        $this->load->helper(array('price'));
    }

    public function index()
    {
        if ($_SERVER['HTTPS'] != "on") {
            $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            header("Location: $url");
            exit;
        }

        // Status flag:
        $LoginSuccessful = false;

        // Check username and password:
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $Username = $_SERVER['PHP_AUTH_USER'];
            $Password = $_SERVER['PHP_AUTH_PW'];

            $cred = "$Username|$Password";

            switch ($cred) {
                # all INACTIVE accounts here
                case 'rubbish|rubbish':
                    break;
                # all ACTIVE accounts here
                case 'nextag|nextag%15':
                case 'shopzilla|shopzilla!!8991':
                case 'touslesprix|tous(#22':
                case 'become.eu|become#(73':
                case 'twenga|twenga%@36':
                case 'cherchons|cherchons#^#4':
                case 'icomparateur|compar#5374':
                case 'shopricer|shopricer@^90':
                case 'leguide|leguide%&833':
                case 'achetezfacile|achetez41#':
                    $LoginSuccessful = true;
                    break;
            }
        }

        // Login passed successful?
        if (!$LoginSuccessful) {

            /*
            ** The user gets here if:
            **
            ** 1. The user entered incorrect login data (three times)
            **     --> User will see the error message from below
            **
            ** 2. Or the user requested the page for the first time
            **     --> Then the 401 headers apply and the "login box" will
            **         be shown
            */

            // The text inside the realm section will be visible for the
            // user in the login box
            header('WWW-Authenticate: Basic realm="Please Login"');
            header('HTTP/1.0 401 Unauthorized');

            print "Login failed!\n";

        } else {
            // The user entered the correct login data, put
            // your confidential data in here:

            header("Content-type: text/tab-separated-values; charset=utf-8");
            header('Content-Disposition: attachment; filename=valuebasket_product_feed.txt');
            echo file_get_contents("/var/data/valuebasket.com/feeds/shopping_com_fr/ftp/shopping_product_feed.txt");
        }

    }

    public function xml_stock_feed($country_id = null)
    {
        $skus = explode(",", $this->input->get("skus"));
        if (count($skus)) {
            if (!empty($country_id)) {
                $platform_id = "WEB" . $country_id;
                if ($list = $this->product_model->get_listing_info_list($skus, $platform_id)) {
                    foreach ($list AS $obj) {
                        if ($obj) {
                            $data['data_list'][$obj->get_sku()][$country_id] = $obj;
                        }
                    }
                }
            } else {
                if ($sp_list = $this->stock_feed_model->get_platform_list_w_allow_sell_country("WEBSITE")) {
                    foreach ($sp_list AS $key => $result) {
                        if ($list = $this->product_model->get_listing_info_list($skus, $result["platform_id"])) {
                            foreach ($list AS $obj) {
                                if ($obj) {
                                    $data['data_list'][$obj->get_sku()][$result{"country_id"}] = $obj;
                                }
                            }
                        }
                    }
                } else {
                    $this->load_view('stock_feed.php', $data);
                }
            }

            $this->load_view('stock_feed.php', $data);
        }
    }

    public function xml_skype_feed($sku = "", $promotion_code = "")
    {
        $accesscontrol = false;
        if ($accesscontrol) {
            $allow_ip = array('204.9.163.153',
                '113.28.59.81',
                '50.16.218.94',
                '192.168.0.1',
                '61.8.203.2', // SG office
            );
            if (!in_array($_SERVER['REMOTE_ADDR'], $allow_ip)) {
                show_404();
            }
        }

        if ($sku) {
            $_SESSION["cart"][PLATFORMID][$sku] = 1;
            $clist[0] = array("sku" => $sku);
            if ($ar_obj = $this->product_model->product_service->get_skype_page_info($clist, PLATFORMID, get_lang_id())) {
                $data["obj"] = $ar_obj[$sku];
                if ($promotion_code) {
                    $data["promotion_code"] = $promotion_code;
                    if ($promo_obj = $this->cart_session_service->promo_cd_svc->get(array("code" => $promotion_code))) {
                        if ($promo_obj->get_disc_type() == "FD" && $promo_obj->get_disc_level_value() != "All") {
                            $this->cart_session_service->set_delivery_mode($promo_obj->get_disc_level_value());
                        }
                    }
                }
                $this->cart_session_service->set_del_country_id(PLATFORMCOUNTRYID);
                $data["cart"] = $this->cart_session_service->get_detail(PLATFORMID, 1, 0, 0, 0, 0, 0, 0, $promotion_code, 1);
                $data["cart"]["cart"] = $data["cart"]["cart"][0];
                $data["cat_obj"] = $this->cart_session_model->product_model->category_service->get_dao()->get(array("id" => $data["obj"]->get_sub_cat_id()));
                $data["promo_text"] = NULL;

                if (!$data['promo_text'] = $this->get_promo_text("SKYPE", get_lang_id(), PLATFORMID, $sku)) {
                    $data['promo_text'] = $this->get_promo_text("SKYPE", 'en', PLATFORMID, $sku);
                }

                $cart = $data['cart'];
                if ($promotion_code) {
                    $sku_display = $cart["cart"]["sku"] . "/" . $promotion_code;
                    $url_append = "add_promote/{$cart["cart"]["sku"]}/{$promotion_code}";
                } else {
                    $sku_display = $cart["cart"]['sku'];
                    $url_append = "add/{$cart["cart"]["sku"]}";
                }

                $data['sku'] = $sku_display;
                $data['sku_display'] = $sku_display;
                $data['url_append'] = $url_append;
                $data['prod_name'] = $cart["cart"]["name"];


                $n_search = array("  ", " ");
                $n_replace = array(" ", "-");

                $prod_url = "https://" . $_SERVER['HTTP_HOST'] . "/"
                    . str_replace($n_search, $n_replace, parse_url_char(str_replace('<br />', ' ', $cart['cart']['name'])))
                    . "/product_skype/$url_append";
                $data['prod_url'] = $prod_url;


                $delivery_charge = $cart["dc_default"]["charge"];
                if ($cart["promo"]["valid"]) {
                    if (isset($cart["promo"]["disc_amount"])) {
                        $promo_disc_amount = $cart["promo"]["disc_amount"];
                    } else if ($cart["promo"]["free_delivery"]) {
                        $delivery_charge = $cart["dc"][$cart["promo"]["promotion_code_obj"]->get_disc_level_value()]["charge"];
                    }
                }

                // Price Calculation
                $data['platform_id'] = PLATFORMID;
                $data['currency_id'] = PLATFORMCURR;
                $price = platform_curr_round(PLATFORMID, $cart["cart"]["total"]);
                $data['price'] = $price;
                $promotion_price = platform_curr_round(PLATFORMID, $cart["cart"]["total"] - $promo_disc_amount);
                $data['promotion_price'] = $promotion_price;
                $bundle_price = (platform_curr_round(PLATFORMID, ($cat_obj ? $cart["cart"]["total"] * (100 - $cat_obj->get_bundle_discount()) / 100 : $cart["cart"]["total"]) - $promo_disc_amount)) == "0.00" ? "FREE" : (platform_curr_round(PLATFORMID, ($cat_obj ? $cart["cart"]["total"] * (100 - $cat_obj->get_bundle_discount()) / 100 : $cart["cart"]["total"]) - $promo_disc_amount));
                $data['bundle_price'] = $bundle_price;
                $data['shipping_cost'] = platform_curr_round(PLATFORMID, $delivery_charge);

                // Status Determination for the cache saving
                $data['listing_status'] = $ar_obj[$sku]->get_listing_status();
            }
            unset($_SESSION["cart"]);
        }

        $this->load->view('stock_skype_feed.php', $data);

        return $data;
    }

    public function get_promo_text($platform_type = "Skype", $lang_id = "", $platform_id = "", $sku = "")
    {
        $this->load->library('service/promotion_text_service');
        $res = $this->promotion_text_service->get_promo_text($platform_type, $lang_id, $platform_id, $sku);

        return $res['promo_text'];
    }

}
