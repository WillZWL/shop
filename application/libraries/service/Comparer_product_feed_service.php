<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Comparer_product_feed_service extends Data_feed_service
{
    protected $id = "Comparer Product Feed";
    private $prod_srv;
    private $price_srv;

    public function __construct()
    {
        parent::Data_feed_service();
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());

        $this->set_output_delimiter("\t");
    }

    public function gen_data_feed($country, $explain_sku = "")
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed(TRUE, $country, $explain_sku);
        if ($data_feed) {
            $this->del_dir(DATAPATH . "feeds/comparer/$country");
            $this->del_dir(DATAPATH . "feeds/comparer/ftp/$country");
            $filename = 'valuebasket_comparer_' . "$country" . '.txt';
            $fp_wdate = fopen(DATAPATH . "feeds/comparer/$country/" . $filename, 'w');
            $remotefilename = strtolower('/valuebasket_comparer_' . "$country" . '.txt');
            $fp_nodate = fopen(DATAPATH . "feeds/comparer/ftp/$country/" . $remotefilename, 'w');

            if (fwrite($fp_nodate, $data_feed) AND fwrite($fp_wdate, $data_feed)) {
                if ($explain_sku == "") {
                    header("Content-type: text/csv");
                    header("Cache-Control: no-store, no-cache");
                    header("Content-Disposition: attachment; filename=\"$filename\"");
                    echo $data_feed;
                }
            } else {
                $subject = "<DO NOT REPLY> Fails to create Comparer Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_data_feed($first_line_headling = TRUE, $country = "FR", $explain_sku)
    {
        // common processing to be done here
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();
        $affiliate_id = $this->get_affiliate_id_prefix() . $country;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        switch ($country) {
            // country specific processing to be done here
            case "BE":
            case "FR":
                return $this->get_data_feed_group_1($first_line_headling, $country, $explain_sku, $override);
        }
    }

    protected function get_affiliate_id_prefix()
    {
        return "CR";
    }

    private function get_data_feed_group_1($first_line_headling = TRUE, $country, $explain_sku, $override = null)
    {
        // group_1 refers to the same business logic regarding price & margin
        $list = $this->get_data_list_w_country(array(), array(), $country);

        if (!$list) {
            return;
        }

        $new_list = array();

        foreach ($list as $row) {
            $i++;
            $this->get_price_srv()->calculate_profit($row);

            if ($res = $this->process_data_row($row)) {
                $add = false;
                $selected = "not added to output";
                if ($country == "FR" || $country == "BE") {
                    if (($res->get_price() > 200) && ($res->get_margin() > 7)) {
                        $add = true;
                        $selected = "added to output";
                    }
                } else {
                    if
                    (
                        (($res->get_price() >= 200) && ($res->get_price() < 400) && ($res->get_margin() >= 12)) ||
                        (($res->get_price() >= 400) && ($res->get_price() < 800) && ($res->get_margin() >= 12)) ||
                        (($res->get_price() >= 800) && ($res->get_price() < 1200) && ($res->get_margin() >= 12)) ||
                        (($res->get_price() >= 1200) && ($res->get_margin() >= 12))
                    ) {
                        $add = true;
                        $selected = "added to output";
                    }
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
                    $rrp = $this->get_price_srv()->calc_website_product_rrp($res->get_price(), $res->get_fixed_rrp(), $res->get_rrp_factor());
                    $res->set_rrp($rrp);
                    $new_list[] = $res;
                }

                if ($explain_sku != "") {
                    if (strtoupper($res->get_sku()) == strtoupper($explain_sku)) {
                        var_dump("$explain_sku $selected");
                        echo "<pre>";
                        var_dump($res);
                    }
                }
            }
        }

        $content = $this->convert($new_list, $first_line_headling);
        return $content;
    }

    private function get_data_list_w_country($where = array(), $option = array(), $country = "FR")
    {
        return $this->get_prod_srv()->get_comparer_product_feed_dto(array(), array('limit' => -1), $country);
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
        return 'ping@eservicesgroup.com';
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return '';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/comparer_product_feed_xml2csv.txt';
    }

    protected function get_ftp_name()
    {
        return 'COMPARER';
    }

    protected function get_sj_id()
    {
        return "COMPARER_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Comparer Product Feed Cron Time";
    }
}

/* End of file Comparer_product_feed_service.php */
/* Location: ./system/application/libraries/service/Comparer_product_feed_service.php */
