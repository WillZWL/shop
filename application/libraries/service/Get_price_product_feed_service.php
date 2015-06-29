<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Get_price_product_feed_service extends Data_feed_service
{
    protected $id = "GetPrice Product Feed";

    public function __construct(){
        parent::Data_feed_service();
        include_once APPPATH."libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH."libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());

        $this->set_output_delimiter(chr(9));
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function set_product_srv(Base_service $srv)
    {
        $this->product_srv = $srv;
    }

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();
        if($data_feed)
        {
            $filename = 'get_price_product_feed.txt';
            $fp = fopen(DATAPATH . 'feeds/get_price/' . $filename, 'w');

            if(fwrite($fp, $data_feed))
            {
                $this->ftp_feeds(DATAPATH . 'feeds/get_price/' . $filename, "/valuebasket.txt", $this->get_ftp_name());
            }
            else
            {
                $subject = "<DO NOT REPLY> Fails to create GetPrice Product Feed File";
                $message ="FILE: ".__FILE__."<br>
                             LINE: ".__LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_get_price_product_feed_dto(array("p.sku NOT IN ('10391-AA-BK','10391-AA-WH','10392-AA-BK','10392-AA-WH','10636-AA-BK','11752-AA-BK', '10652-AA-BK', '10652-AA-BN', '10652-AA-RD', '10652-AA-SL', '10652-AA-WH', '10657-AA-GP')"=>null), array("limit"=>-1));
    }

    public function get_data_feed($first_line_headling = TRUE)
    {
        $arr = $this->get_data_list();
        if (!$arr)
        {
            return;
        }

        $new_list = array();

        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();
        $affiliate_id = $this->get_affiliate_id_prefix();# . $country; # no country for this feed
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        foreach ($arr as $row)
        {
            $price_srv = $this->get_price_srv();
            if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku"=>$row->get_sku(), "platform_id"=>"WEBAU"), array("limit"=>1)))
            {
                $price_srv->calc_logistic_cost($prod_obj);
                $price_srv->calculate_profit($prod_obj);
                if($prod_obj->get_margin() > 5 && $row->get_price() > 40)
                {
                    $add = true;
                    $selected = "passed margin rules, so added";
                }

                if ($override != null)
                {
                    // switch($override[$row->get_platform_id()][$row->get_sku()])
                    switch($override["WEBAU"][$row->get_sku()]) // lock to WEBAU
                    {
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
                {
                    $new_list[] = $this->process_data_row($row);
                }
            }
        }

        $content = $this->convert($new_list, $first_line_headling);

        return $content;
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data))
        {
            return NULL;
        }

        if(!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path").basename($data->get_image_url())))
        {
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
        for($i=0; $i < strlen($str); $i++)
        {
            $new_str .= ord($str[$i]);
        }
        return $new_str;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/get_price_product_feed_xml2csv.txt';
    }

    public function get_contact_email()
    {
        return 'steven@eservicesgroup.net';
    }

    protected function get_ftp_name()
    {
        return 'GET_PRICE';
    }

    protected function get_sj_id()
    {
        return "GET_PRICE_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "GetPrice Product Feed Cron Time";
    }

    protected function get_affiliate_id_prefix()
    {
        return "GP";
    }
}

/* End of file get_price_product_feed_service.php */
/* Location: ./system/application/libraries/service/Get_price_product_feed_service.php */