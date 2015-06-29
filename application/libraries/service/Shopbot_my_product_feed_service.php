<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "apac_product_feed_service.php";

class Shopbot_my_product_feed_service extends Apac_product_feed_service
{
    protected $id = "Shopbot MY Product Feed";
    protected $platform_id = "WEBMY";

    public function __construct(){
        parent::Apac_product_feed_service();
        include_once APPPATH."libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH."libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());

        $this->set_output_delimiter(',');
    }


    public function gen_data_feed($platform_id = "WEBMY")
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $data_feed = $this->get_data_feed($platform_id);
        //echo "<pre>"; var_dump($data_feed); die();
        if($data_feed)
        {
            //$filename = 'shopbot_nz_product_feed_' . date('Ymdhis') . '.csv';
            $filename = 'shopbot_my_product_feed.csv';
            $fp = fopen(DATAPATH . 'feeds/shopbot/my/'. $filename, 'w');

            if(fwrite($fp, $data_feed))
            {
                $this->ftp_feeds(DATAPATH .  'feeds/shopbot/my/' . $filename, "/ProductFeed.csv", $this->get_ftp_name($platform_id));
                header("Content-type: text/csv");
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $data_feed;
            }
            else
            {
                $subject = "<DO NOT REPLY> Fails to create Shopbot MY Product Feed File";
                $message ="FILE: ".__FILE__."<br>
                             LINE: ".__LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_affiliate_data($platform_id)
    {
        switch($platform_id)
        {
            case "WEBMY":
                $data['domain'] = "www.valuebasket.com";
                $data['locale'] = "en_MY";
                $data['af'] = "SBMY";
                break;
            default:
                $data['domain'] = "www.valuebasket.com";
                $data['locale'] = "en_AU";
                $data['af'] = "SBAU";
        }
        return $data;
    }

    protected function get_ftp_name()
    {
        return 'Shopbot_MY';
    }

    protected function get_sj_id()
    {
        return "SHOPBOT_MY_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Shopbot Malaysia Product Feed Cron Time";
    }

    protected function get_affiliate_id_prefix()
    {
        return "SBMY";
    }
}

/* End of file shopbot_nz_product_feed_service.php */
/* Location: ./system/application/libraries/service/Shopbot_nz_product_feed_service.php */