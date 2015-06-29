<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "standard_fr_product_feed_service.php";

class Shopzilla_fr_product_feed_service extends Standard_fr_product_feed_service
{
    protected $id = "Shopzilla FR Product Feed";
    private $prod_srv;

    public function __construct()
    {
        parent::Standard_fr_product_feed_service();
        $this->set_output_delimiter("\t");
    }

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();
        if ($data_feed) {
            $filename = 'shopzilla_fr_product_feed_' . date('Ymdhis') . '.txt';
            $fp = fopen(DATAPATH . 'feeds/shopzilla_fr/' . $filename, 'w');

            if (fwrite($fp, $data_feed)) {
                $this->ftp_feeds(DATAPATH . 'feeds/shopzilla_fr/' . $filename, "/valuebasket_fr_product_feed.txt", $this->get_ftp_name());
            } else {
                $subject = "<DO NOT REPLY> Fails to create Shopzilla FR Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    protected function get_ftp_name()
    {
        return 'SHOPZILLA_FR';
    }

    protected function get_sj_id()
    {
        return "SHOPZILLA_FR_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Shopzilla FR Product Feed Cron Time";
    }
}

/* End of file Standard_fr_product_feed_service.php */
/* Location: ./system/application/libraries/service/Standard_fr_product_feed_service.php */