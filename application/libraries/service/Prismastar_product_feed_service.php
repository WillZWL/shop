<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";
include_once APPPATH . "helpers/price_helper.php";

class Prismastar_product_feed_service extends Data_feed_service
{
    protected $id = "Prismastar Product Feed";
    protected $cat_hash_map;

    private $prismastar_platform_id; // defines which platform to generate feed

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

    public function gen_data_feed($platform_id)
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $this->set_prismastar_platform_id($platform_id);

        $data_feed = $this->get_data_feed();
        if ($data_feed) {
            foreach ($data_feed as $id => $data) {
                $this->del_dir(DATAPATH . 'feeds/prismastar/' . $id);
                $filename = 'prismastar_product_feed_' . $id . '_' . date('Ymdhis');
                $fp = fopen(DATAPATH . 'feeds/prismastar/' . $id . '/' . $filename . '.txt', 'w');

                if (fwrite($fp, $data)) {
                    $this->ftp_feeds(DATAPATH . 'feeds/prismastar/' . $id . '/' . $filename . '.txt', "/ProductFeed_$id.txt", $this->get_ftp_name());
                } else {
                    $subject = "<DO NOT REPLY> Fails to create Prismastar $id Product Feed File";
                    $message = "FILE: " . __FILE__ . "<br>
                                 LINE: " . __LINE__;
                    $this->error_handler($subject, $message);
                }
            }
        }
    }

    public function get_data_feed()
    {
        $arr = $this->get_data_list();

        if (!$arr) {
            return;
        }

        $new_list = array();

        foreach ($arr as $row) {
            $new_list[] = $this->process_data_row($row);
        }

        if ($new_list) {
            foreach ($new_list as $obj) {
                $rs[$obj->get_platform_country_id()][] = $obj;
            }

            if ($rs) {
                foreach ($rs as $id => $data_list) {
                    $content[$id] = $this->convert($data_list);
                }
            }
        }

        return $content;
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_prismastar_product_feed_dto($this->get_prismastar_platform_id());
    }

    public function get_prismastar_platform_id()
    {
        return $this->prismastar_platform_id;
    }

    public function set_prismastar_platform_id($val)
    {
        $this->prismastar_platform_id = $val;
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

        $search = array(chr(9), chr(10), chr(13));
        $replace = array(" ", ";", ";");

        $data->set_detail_desc(str_replace($search, $replace, $data->get_detail_desc()));
        $data->set_contents(str_replace($search, $replace, $data->get_contents()));
        $data->set_feature(str_replace($search, $replace, $data->get_feature()));

        return $data;
    }

    protected function get_product_url($data)
    {
        $prod_name = $data->get_prod_name();
        $search = array(" ", ".", "/");
        $replace = array("-", "", "");
        $prod_name = str_replace($search, $replace, $prod_name);
        $sku = $data->get_sku();
        $country_id = $data->get_platform_country_id();
        $language_id = $data->get_language_id();
        $product_url = "http://www.valuebasket.com/" . $language_id . "_" . $country_id . "/" . $prod_name . "/mainproduct/view/" . $sku;

        return $product_url;
    }

    protected function get_image_url($data)
    {
        $country_id = strtolower($data->get_platform_country_id());
        define('IMG_PATH', "http://www.valuebasket.com/" . $this->get_config_srv()->value_of("prod_img_path"));
        if (file_exists("images/product/" . $data->get_sku() . "." . $data->get_image())) {
            return IMG_PATH . $data->get_sku() . "." . $data->get_image();
        } else {
            return IMG_PATH . "imageunavailable.jpg";
        }
    }

    protected function get_availabilty($data)
    {
        if ($data->get_website_status() != "I") {
            if ($data->get_website_status() == "A")
                return "Available Soon";
            elseif ($data->get_website_status() == "P")
                return "Pre Order";
            else
                return "out of stock";
        } else {
            if (min($data->get_website_quantity(), $data->get_display_quantity()) <= 0) {
                return "out of stock";
            }
        }
        return "in stock";
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

    protected function get_ftp_name()
    {
        return 'PRISMASTAR';
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
        return 'steven@eservicesgroup.net';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/prismastar_product_feed_vo2xml.txt';
    }

    protected function get_sj_id()
    {
        return "PRISMASTAR_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "PRISMASTAR Product Feed Cron Time";
    }
}


