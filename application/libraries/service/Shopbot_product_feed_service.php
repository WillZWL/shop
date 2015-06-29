<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Shopbot_product_feed_service extends Data_feed_service
{
    protected $id = "Shopbot Product Feed";
    protected $platform_id = "WEBAU";

    public function __construct(){
        parent::Data_feed_service();
        include_once APPPATH."libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH."libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());

        $this->set_output_delimiter(',');
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
            $filename = 'shopbot_product_feed.csv';
            $fp = fopen(DATAPATH . 'feeds/shopbot/' . $filename, 'w');

            if(fwrite($fp, $data_feed))
            {
                $this->ftp_feeds(DATAPATH . 'feeds/shopbot/' . $filename, "/ProductFeed.csv", $this->get_ftp_name());

                header("Content-type: text/csv");
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $data_feed;

            }
            else
            {
                $subject = "<DO NOT REPLY> Fails to create Shopbot Product Feed File";
                $message ="FILE: ".__FILE__."<br>
                             LINE: ".__LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_shopbot_product_feed_dto(array("pr.platform_id"=>$this->platform_id), array("limit"=>-1));
    }

    public function get_data_feed($first_line_headling = FALSE)
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
            if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku"=>$row->get_sku(), "platform_id"=>$this->platform_id), array("limit"=>1)))
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
                    switch($override[$this->platform_id][$row->get_sku()])  // lock to WEBAU
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

        $search = array(chr(10), chr(13));
        $replace = array( " ", " ");
        $detail_desc = str_replace($search, $replace, $data->get_detail_desc());
        $detail_desc = trim($detail_desc);
        $data->set_detail_desc($detail_desc);

        if(!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path").basename($data->get_image_url())))
        {
            $data->set_image_url("http://www.valuebasket.com.au/images/product/imageunavailable.jpg");
        }

        $data->set_rrp($this->get_price_srv()->calc_website_product_rrp($data->get_price(), $data->get_fixed_rrp(), $data->get_rrp_factor()));
        $data->set_availability('Y');
        return $data;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/shopbot_product_feed_xml2csv.txt';
    }

    public function get_contact_email()
    {
        return 'shing-alert@eservicesgroup.com';
    }

    protected function get_ftp_name()
    {
        return 'Shopbot';
    }

    protected function get_sj_id()
    {
        return "SHOPBOT_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Shopbot Product Feed Cron Time";
    }

    protected function get_affiliate_id_prefix()
    {
        return "SB";
    }
}

/* End of file shopbot_product_feed_service.php */
/* Location: ./system/application/libraries/service/Shopbot_product_feed_service.php */