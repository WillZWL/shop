<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";
include_once APPPATH . "helpers/price_helper.php";

class Googlebase_product_feed_service extends Data_feed_service
{
    protected $id = "GoogleBase Product Feed";
    protected $cat_hash_map;

    private $googlebase_platform_id; // defines which platform to generate feed

    public function __construct()
    {
        parent::Data_feed_service();
        include_once(APPPATH . "libraries/service/External_category_service.php");
        $this->set_ext_cat_srv(new External_category_service());

        $this->set_output_delimiter(chr(9));
        //$this->set_cat_hash_map();
    }

    public function set_ext_cat_srv(Base_service $srv)
    {
        $this->ext_cat_srv = $srv;
    }

    public function gen_data_feed($platform_id, $shopping_api = false, $where = array())
    {
        //add $shopping_api flag to return raw data for shopping api usage
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $this->set_googlebase_platform_id($platform_id);

        $data_feed = $this->get_data_feed($shopping_api, $where);
        if ($data_feed) {
            if (!$shopping_api) {
                foreach ($data_feed as $id => $data) {
                    $filename = 'googlebase_product_feed_' . $id . '_' . date('Ymdhis');
                    $fp = fopen(DATAPATH . 'feeds/googlebase/' . $id . '/' . $filename . '.txt', 'w');

                    if (fwrite($fp, $data)) {
                        header("Content-type: text/csv");
                        header("Cache-Control: no-store, no-cache");
                        header("Content-Disposition: attachment; filename=\"$filename\"");
                        echo $data;

                        $this->ftp_feeds(DATAPATH . 'feeds/googlebase/' . $id . '/' . $filename . '.txt', "/googlebasefeed_" . strtolower($id) . ".txt", $this->get_ftp_name($id));
                    } else {
                        $subject = "<DO NOT REPLY> Fails to create GoogleBase $id Product Feed File";
                        $message = "FILE: " . __FILE__ . "<br>
                                     LINE: " . __LINE__;
                        $this->error_handler($subject, $message);
                    }
                }
            } else {
                return $data_feed;
            }

        }
    }

    public function get_data_feed($shopping_api = FALSE, $where = array())
    {
        // common processing to be done here

        $country = substr($this->get_googlebase_platform_id(), -2);

        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();

        $affiliate_id = $this->get_affiliate_id_prefix() . $country;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        $arr = $this->get_data_list($where);
        if (!$arr) {
            return;
        }

        $new_list = array();

        foreach ($arr as $row) {
            $res = $this->process_data_row($row);
            $add = true;
            $selected = "passed margin rules, so added";

            if ($override != null) {
                switch ($override[$row->get_platform_id()][$row->get_sku()]) {
                    case 1: # exclude
                        $add = false;
                        $selected = "always exclude";
                        break;

                    case 2: # include
                        $add = true;
                        $selected = "always include";
                        break;
                }
            }

            if ($add)
                $new_list[] = $res;
        }

        if ($new_list) {
            foreach ($new_list as $obj) {
                $rs[$obj->get_platform_country_id()][] = $obj;
            }

            if ($shopping_api) {
                return $rs;
            }

            if ($rs) {
                foreach ($rs as $id => $data_list) {
                    $content[$id] = $this->convert($data_list);
                }
            }
        }

        return $content;
    }

    public function get_googlebase_platform_id()
    {
        return $this->googlebase_platform_id;
    }

    public function set_googlebase_platform_id($val)
    {
        $this->googlebase_platform_id = $val;
    }

    protected function get_affiliate_id_prefix()
    {
        // refer to affiliate table for more details
        // SOME IDs have country suffix
        // SOME don't have.. so take note when checking
        return "GOO";
    }

    protected function get_data_list($where = array(), $option = array())
    {
        // $this->get_prod_srv()->get_googlebase_product_feed_dto($this->get_googlebase_platform_id(), $where);
        // var_dump($this->get_prod_srv()->get_dao()->db->last_query());die();
        return $this->get_prod_srv()->get_googlebase_product_feed_dto($this->get_googlebase_platform_id(), $where);
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        // append country code to sku to form google reference id
        $data->set_google_ref_id(implode('-', array(trim($data->get_platform_country_id()), trim($data->get_sku()))));

        // product type: Category > SubCategory
        $data->set_product_type(implode(' > ', array(trim($data->get_cat_name()), trim($data->get_sub_cat_name()))));

        // product link
        $product_url = $this->get_product_url($data);
        $data->set_product_url($product_url);

        // image link
        $image_url = $this->get_image_url($data);
        $data->set_image_url($image_url);

        // condition
        if ($data->get_ex_demo() == 0) {
            $data->set_condition("new");
        } else {
            $data->set_condition("refurbished");
        }

        // availabilty
        $availabilty = $this->get_availabilty($data);
        $data->set_availability($availabilty);

        // price with currency
        $price_w_curr = $this->get_price_w_curr($data);
        $data->set_price_w_curr($price_w_curr);
        $data->set_sale_price($price_w_curr);

        // colour
        if ($data->get_colour_id() == "NA") {
            $data->set_colour_name("");
            $data->set_item_group_id("");
        } else {
            $data->set_item_group_id($data->get_prod_grp_cd());
        }

        // shipping text
        $shipping_text = $this->get_shipping_text($data);
        $data->set_shipping($shipping_text);

        #2701 add htmlspecialchars and repleace tab

        $detail_desc = str_replace("\n", "---", $data->get_detail_desc());
        $detail_desc = str_replace("\t", " ", $detail_desc);
        //$data->set_detail_desc((str_replace("\n", "---", $data->get_detail_desc())));
        $data->set_detail_desc(htmlspecialchars($detail_desc));
        $data->set_prod_name(htmlspecialchars($data->get_prod_name()));

        return $data;
    }

    protected function get_product_url($data)
    {   #SBF2701 replace double quot with '-'
        $prod_name = $data->get_prod_name();
        $search = array(" ", ".", "/", '"');
        $replace = array("-", "", "", "-");
        $prod_name = str_replace($search, $replace, $prod_name);
        $sku = $data->get_sku();
        $country_id = $data->get_platform_country_id();
        $language_id = $data->get_language_id();
        $af_code = $this->get_affiliate_code($country_id);
        $product_url = "http://www.valuebasket" . $this->get_url_extension($country_id) . "/" . $language_id . "_" . $country_id . "/" . $prod_name . "/mainproduct/view/" . $sku . ($af_code == '' ? '' : '?' . $af_code);

        return $product_url;
    }

    protected function get_affiliate_code($country_id)
    {
        #SBF2701
        switch (strtoupper($country_id)) {
            case 'FR' :
                $result = 'AF=GOOFR';
                break;
            case 'GB' :
                $result = 'AF=GOOGB';
                break;
            case 'AU' :
                $result = 'AF=GOOAU';
                break;
            case 'ES' :
                $result = 'AF=GOOES';
                break;
            case 'IT' :
                $result = 'AF=GOOIT';
                break;
            case 'BE' :
                $result = 'AF=GOOBE';
                break;
            case 'CH' :
                $result = 'AF=GOOCH';
                break;
            case 'NZ' :
                $result = 'AF=GOONZ';
                break;
            case 'SG' :
                $result = 'AF=GOOSG';
                break;
            case 'MY' :
                $result = 'AF=GOOMY';
                break;
            case 'IE' :
                $result = 'AF=GOOIE';
                break;
            case 'MT' :
                $result = 'AF=GOOMT';
                break;
            case 'PT' :
                $result = 'AF=GOOPT';
                break;
            case 'FI' :
                $result = 'AF=GOOFI';
                break;
            case 'PH' :
                $result = 'AF=GOOPH';
                break;
            case 'RU' :
                $result = 'AF=GOORU';
                break;
            case 'US' :
                $result = 'AF=GOOUS';
                break;

            default :
                $result = '';
        }

        return $result;
    }

    protected function get_url_extension($country_id)
    {   #SBF2701
        //return '.com';
        switch (strtoupper($country_id)) {
            case 'FR' :
                $result = '.fr';
                break;
            case 'ES' :
                $result = '.es';
                break;
            case 'AU' :
                $result = '.com.au';
                break;
            /*          case 'IT' : $result = '.it'; break;*/
            case 'BE' :
                $result = '.be';
                break;
            case 'SG' :
                $result = '.com.sg';
                break;
            case 'PL' :
                $result = '.pl';
                break;
            default :
                $result = '.com';
        }

        return $result;
    }

    protected function get_image_url($data)
    {
        $country_id = strtolower($data->get_platform_country_id());
        define('IMG_PATH', "http://www.valuebasket" . $this->get_url_extension($country_id) . "/" . $this->get_config_srv()->value_of("prod_img_path"));
        if (file_exists("images/product/" . $data->get_sku() . "." . $data->get_image())) {
            return IMG_PATH . $data->get_sku() . "." . $data->get_image();
        } else {
            return IMG_PATH . "imageunavailable.jpg";
        }
    }

    protected function get_availabilty($data)
    {
        $in_stock_text = "in stock";
        $preorder_text = "preorder";
        $out_of_stock_text = "out of stock";
        $arriving_text = "available for order";

        if ($data->get_website_status() == "I") {
            if (min($data->get_website_quantity(), $data->get_display_quantity()) <= 0) {
                return $out_of_stock_text;
            }
            return $in_stock_text;
        } else if ($data->get_website_status() == "P") {
            return $preorder_text;
        } else if ($data->get_website_status() == "A") {
            return $arriving_text;
        } else {
            return $out_of_stock_text;
        }
    }

    protected function get_price_w_curr($data)
    {
        $price_w_curr = implode(" ", array($data->get_platform_currency_id(), $data->get_price()));

        return $price_w_curr;
    }

    protected function get_shipping_text($data)
    {
        $shipping_text = $data->get_platform_country_id() . "::Standard: 0.00 " . $data->get_platform_currency_id();

        return $shipping_text;
    }

    protected function get_ftp_name($country = 'GB')
    {
        #SBF2701
        //return 'GOOGLEBASE';
        switch (strtoupper($country)) {
            case 'FR' :
                return 'GOOGLEBASE_FR';
                break;
            case 'AU' :
                return 'GOOGLEBASE_AU';
                break;
            case 'ES' :
                return 'GOOGLEBASE_ES';
                break;
            case 'IT' :
                return 'GOOGLEBASE_IT';
                break;
            case 'BE' :
                return 'GOOGLEBASE_BE';
                break;
            default :
                return 'GOOGLEBASE';
        }
    }

    public function get_cat_hash_map()
    {
        return $this->cat_hash_map;
    }

    protected function set_cat_hash_map()
    {
        $ext_cat_list = $this->get_ext_cat_srv()->get_list(array("ext_party" => "GOOGLEBASE", "status" => 1), array("limit" => -1));
        if ($ext_cat_list) {
            foreach ($ext_cat_list as $ext_cat_obj) {
                $rs[$ext_cat_obj->get_country_id()][$ext_cat_obj->get_id()] = $ext_cat_obj->get_ext_name();
            }
        }

        $this->cat_hash_map = $rs;
    }

    public function get_ext_cat_srv()
    {
        return $this->ext_cat_srv;
    }

    public function get_contact_email()
    {
        return 'nero@eservicesgroup.com';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/googlebase_product_feed_vo2xml.txt';
    }

    protected function get_sj_id()
    {
        return "GOOGLEBASE_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "GOOGLEBASE Product Feed Cron Time";
    }
}


