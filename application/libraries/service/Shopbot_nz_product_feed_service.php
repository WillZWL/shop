<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "shopbot_product_feed_service.php";

class Shopbot_nz_product_feed_service extends Shopbot_product_feed_service
{
    protected $id = "Shopbot NZ Product Feed";
    protected $platform_id = "WEBNZ";

    public function __construct()
    {
        parent::Shopbot_product_feed_service();
    }

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();

        if ($data_feed) {
            $filename = 'shopbot_nz_product_feed.csv';
            $fp = fopen(DATAPATH . 'feeds/shopbot/nz/' . $filename, 'w');

            if (fwrite($fp, $data_feed)) {
                $this->ftp_feeds(DATAPATH . 'feeds/shopbot/nz/' . $filename, "/ProductFeed.csv", $this->get_ftp_name());
                header("Content-type: text/csv");
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $data_feed;
            } else {
                $subject = "<DO NOT REPLY> Fails to create Shopbot NZ Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    protected function get_ftp_name()
    {
        return 'Shopbot_NZ';
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_shopbot_nz_product_feed_dto(array("pr.platform_id" => $this->platform_id), array("limit" => -1));
    }

    protected function get_sj_id()
    {
        return "SHOPBOT_NZ_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Shopbot New Zealand Product Feed Cron Time";
    }

    protected function get_affiliate_id_prefix()
    {
        return "SBNZ";
    }
}


