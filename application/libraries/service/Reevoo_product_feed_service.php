<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Reevoo_product_feed_service extends Data_feed_service
{
    protected $id = "Reevoo Product Feed";

    public function __construct()
    {
        parent::Data_feed_service();
        $this->set_output_delimiter(chr(9));
    }

    public function gen_data_feed($country_id = null)
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        //Format filename: valuebasket_be_product.csv
        //Country to generate the data feed: BE, FR, ES, PT, IT, AU, NZ, SG, GB

        $data_feed = $this->get_data_feed(TRUE, array('country_id' => $country_id), array());

        //If no country_id is provided, generate for all as before using old format too
        if ($country_id == null)
            $filename = 'reevoo_product_feed_' . date('Ymdhis');
        else
            $filename = strtolower('valuebasket_' . $country_id . '_product');

        $fp = fopen(DATAPATH . 'feeds/reevoo/product/' . $filename . '.csv', 'w');

        if (fwrite($fp, $data_feed)) {
            $this->ftp_feeds(DATAPATH . 'feeds/reevoo/product/' . $filename . '.csv', "/partners/valuebasket/" . $filename . ".csv", $this->get_ftp_name());

            if ($explain_sku == "") {
                header("Content-type: text/csv;charset=utf-8");
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $data_feed;
            }
        } else {
            $subject = "<DO NOT REPLY> Fails to create Reevoo Product Feed File";
            $message = "FILE: " . __FILE__ . "<br>
                         LINE: " . __LINE__;
            $this->error_handler($subject, $message);
        }

    }

    protected function get_ftp_name()
    {
        return 'REEVOO';
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        // combined cateogry and subcategory into product category
        $product_category = $this->get_product_category($data);
        $data->set_product_category($product_category);

        // format the model by removing the brand name from product name
        $model = $this->get_model($data->get_prod_name(), $data->get_brand_name());
        $data->set_model($model);

        // format the product image url
        $image_url = $this->get_image_url($data);
        $data->set_image_url($image_url);

        return $data;
    }

    protected function get_product_category($data)
    {
        $search = array(":", ";", "-", "_", "|", "&");
        $replace = array(" ", " ", " ", " ", " ", "and");
        $cat_name = str_replace($search, $replace, $data->get_cat_name());
        $sub_cat_name = str_replace($search, $replace, $data->get_sub_cat_name());

        $product_category = implode(' > ', array($data->get_cat_name(), $data->get_sub_cat_name()));

        return $product_category;
    }

    protected function get_model($prod_name, $brand_name)
    {
        if (count(explode("(" . $brand_name . ")", $prod_name, 1)) > 1) {
            $arr = explode("(" . $brand_name . ")", $prod_name, 1);
        } else {
            $arr = explode($brand_name, $prod_name);
        }

        foreach ($arr as $str) {
            $new_str[] = trim($str);
        }
        $model = trim(implode(' ', $new_str));

        /*
        $pos = strpos($prod_name, $brand_name, 0);
        $len = strlen($brand_name);

        $start_str = trim(substr($prod_name,0,$pos));
        $end_str = trim(substr($prod_name,$pos+$len));

        $model = implode(' ', array($start_str, $end_str));
        */

        return $model;
    }

    protected function get_image_url($data)
    {
        define('IMG_PATH', "http://www.valuebasket.com/" . $this->get_config_srv()->value_of("prod_img_path"));

        if (file_exists($this->get_config_srv()->value_of("prod_img_path") . $data->get_sku() . "." . $data->get_image())) {
            return IMG_PATH . $data->get_sku() . "." . $data->get_image();
        } else {
            return IMG_PATH . "imageunavailable.jpg";
        }
    }

    public function get_contact_email()
    {
        return 'thomas@eservicesgroup.net';
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_reevoo_product_feed_dto($where['country_id']);
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/reevoo_product_feed_vo2xml.txt';
    }

    protected function get_sj_id()
    {
        return "REEVOO_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "REEVOO Product Feed Cron Time";
    }
}


