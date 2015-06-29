<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "apac_product_feed_service.php";

class Price_panda_product_feed_service extends Apac_product_feed_service
{
    protected $id = "Price Panda Product Feed";

    public function __construct()
    {
        parent::Apac_product_feed_service();
        include_once APPPATH . "libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH . "libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());

        $this->set_output_delimiter(';');
    }

    /*
    */
    public function gen_data_feed($platform_id = "WEBSG")
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $data_feed = $this->get_data_feed($platform_id);
        //echo "<pre>"; var_dump($data_feed); die();
        if ($data_feed) {
            $filename = 'price_panda_product_feed.txt';
            $fp = fopen(DATAPATH . "feeds/price_panda/{$platform_id}/" . $filename, 'w');

            if (fwrite($fp, $data_feed)) {
                $this->ftp_feeds(DATAPATH . "feeds/price_panda/{$platform_id}/" . $filename, "/valuebasket.txt", $this->get_ftp_name($platform_id));
            } else {
                $subject = "<DO NOT REPLY> Fails to create Price Panda Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    protected function get_ftp_name($platform_id)
    {
        switch ($platform_id) {
            case "WEBSG":
                return 'PRICE_PANDA_SG';
                break;
            case "WEBMY":
                return 'PRICE_PANDA_MY';
                break;
        }
        return false;
    }

    public function get_affiliate_data($platform_id)
    {
        switch ($platform_id) {
            case "WEBSG":
                $data['domain'] = "www.valuebasket.com.sg";
                $data['locale'] = "en_SG";
                $data['af'] = "PPSG";
                break;
            case "WEBMY":
                $data['domain'] = "www.valuebasket.com";
                $data['locale'] = "en_MY";
                $data['af'] = "PPMY";
                break;
            default:
                $data['domain'] = "www.valuebasket.com";
                $data['locale'] = "en_MY";
                $data['af'] = "PPMY";
        }
        return $data;
    }

    protected function get_sj_id()
    {
        return "PRICE_PANDA_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Price Panda Product Feed Cron Time";
    }
}

/* End of file price_panda_product_feed_service.php */
/* Location: ./system/application/libraries/service/Price_panda_product_feed_service.php */