<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Shopprice_product_feed_service extends Data_feed_service
{
    protected $id = "Shopprice Product Feed";

    public function __construct()
    {
        parent::Data_feed_service();
        include_once APPPATH . "libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH . "libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());

        $this->set_output_delimiter(chr(9));
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function set_product_srv(Base_service $srv)
    {
        $this->product_srv = $srv;
    }

    public function gen_data_feed($platform_id)
    {
        $platform_id = strtoupper($platform_id);
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $data_feed = $this->get_data_feed($platform_id);

        if ($data_feed) {
            $filename = 'shopprice_product_feed_' . $platform_id . '_' . date('Ymdhis') . '.txt';
            $fp = fopen(DATAPATH . 'feeds/shopprice/' . $platform_id . '/' . $filename, 'w');

            if (fwrite($fp, $data_feed)) {
                $this->ftp_feeds(DATAPATH . 'feeds/shopprice/' . $platform_id . '/' . $filename, "/NZ/valuebaseket_shopprice_nz.txt", $this->get_ftp_name($platform_id));

                header("Content-type: text/csv");
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $data_feed;
            } else {
                $subject = "<DO NOT REPLY> Fails to create Shopprice NZ Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_data_feed($platform_id, $first_line_headling = TRUE)
    {
        $where = array(
            '`pr`.`platform_id`' => $platform_id,
            '`pr`.`listing_status`' => 'L',
            '`sourcing_status` IN' => array('A', 'C', 'L')
        );
        $arr = $this->get_data_list($where);
        if (!$arr) {
            return;
        }

        $new_list = array();
        foreach ($arr as $row) {
            $price_srv = $this->get_price_srv();
            if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku" => $row->get_sku(), "platform_id" => "WEBNZ"), array("limit" => 1))) {
                $price_srv->calc_logistic_cost($prod_obj);
                $price_srv->calculate_profit($prod_obj);
                if ($prod_obj->get_margin() >= 7) {
                    $add = true;
                    $selected = "passed margin rules, so added";
                }

                if ($add) {
                    $new_list[] = $this->process_data_row($row);
                }
            }
        }

        $content = $this->convert($new_list, $first_line_headling);

        return $content;
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_product_srv()->get_shopprice_product_feed_dto($where, array("limit" => -1));
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        if (!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path") . basename($data->get_image_url()))) {
            $data->set_image_url("http://www.valuebasket.com.au/images/product/imageunavailable.jpg");
        }

        $data->set_prod_id($this->string_to_ascii($data->get_sku()));

        $search = array(chr(10), chr(13), chr(9), "<br>");
        $replace = array(' ', ' ', ' ', ' ');

        $detail_desc = htmlentities(strip_tags($data->get_detail_desc()));
        $detail_desc = str_replace($search, $replace, $detail_desc);
        $data->set_detail_desc($detail_desc);

        return $data;
    }

    protected function string_to_ascii($str)
    {
        for ($i = 0; $i < strlen($str); $i++) {
            $new_str .= ord($str[$i]);
        }
        return $new_str;
    }

    protected function get_ftp_name($platform_id)
    {
        return 'GET_NZ_PRODUCT';
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
        return APPPATH . 'data/shopprice_product_feed_xml2csv.txt';
    }

    protected function get_sj_id()
    {
        return "GET_SHOPPRICE_PRODUCT";
    }

    protected function get_sj_name()
    {
        return "Get WEBNZ Shopprice Product Feed Cron Time";
    }

}

/* End of file shopprice_product_feed_service.php */
/* Location: ./system/application/libraries/service/Shopprice_product_feed_service.php */
