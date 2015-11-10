<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Shopping_com_fr_product_feed_service extends Data_feed_service
{
    protected $id = "Shopping Product Feed";
    private $prod_srv;
    private $price_srv;

    public function __construct()
    {
        parent::Data_feed_service();
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());

        $this->set_output_delimiter("\t");
    }

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();
        if ($data_feed) {
            $filename = 'valuebasket_shopping_com_fr_' . date('Ymdhis') . '.txt';
            $fp = fopen(DATAPATH . 'feeds/shopping_com_fr/' . $filename, 'w');

            if (fwrite($fp, $data_feed)) {
                header("Content-type: text/csv");
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $data_feed;

                if (!copy(DATAPATH . 'feeds/shopping_com_fr/' . $filename, DATAPATH . 'feeds/shopping_com_fr/ftp/shopping_product_feed.txt')) {
                    $subject = "<DO NOT REPLY> Fails to create Shopping.com FR Product Feed File";
                    $message = "FILE: " . __FILE__ . "<br>
                                 LINE: " . __LINE__;
                    $this->error_handler($subject, $message);
                }
            } else {
                $subject = "<DO NOT REPLY> Fails to create Shopping.com FR Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_data_feed($first_line_headling = TRUE)
    {
        // common processing to be done here
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();
        $affiliate_id = $this->get_affiliate_id_prefix() . $country;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        $list = $this->get_data_list();

        if (!$list) {
            return;
        }

        $new_list = array();
        foreach ($list as $row) {
            if ($res = $this->process_data_row($row)) {
                $add = false;
                if ((($res->get_price() >= 400) && ($res->get_price() < 800) && ($res->get_margin() >= 10)) ||
                    (($res->get_price() >= 800) && ($res->get_price() < 1200) && ($res->get_margin() >= 9)) ||
                    (($res->get_price() >= 1200) && ($res->get_margin() >= 8))
                ) {
                    $add = true;
                    $selected = "passed margin rules, so added";
                }

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

                if ($add) {
                    $new_list[] = $res;
                }
            }
        }

        $content = $this->convert($new_list, $first_line_headling);
        return $content;
    }

    protected function get_affiliate_id_prefix()
    {
        return "SH";
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_shopping_com_fr_product_feed_dto(array(), array('limit' => -1));
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        $this->get_price_srv()->calculate_profit($data);

        $org_price = $this->get_price_srv()->calc_website_product_rrp($data->get_price(), $data->get_fixed_rrp(), $data->get_rrp_factor());
        $data->set_org_price($org_price);

        return $data;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function get_contact_email()
    {
        return 'shing-alert@eservicesgroup.com';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/shopping_com_fr_product_feed_xml2csv.txt';
    }

    protected function get_ftp_name()
    {
        return 'SHOPPING_COM_FR';
    }

    protected function get_sj_id()
    {
        return "SHOPPING_COM_FR_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Shopping.com FR Product Feed Cron Time";
    }
}


