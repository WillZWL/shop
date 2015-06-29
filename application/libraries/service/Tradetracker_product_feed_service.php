<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Tradetracker_product_feed_service extends Data_feed_service
{
    protected $id = "Tradetracker Product Feed";
    private $prod_srv;
    private $price_srv;

    public function __construct()
    {
        parent::Data_feed_service();
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());
        $this->set_output_delimiter(",");
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function gen_data_feed($platform_id, $explain_sku = "")
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed(TRUE, $platform_id, $explain_sku, $override);
        if($data_feed)
        {
            // $filename = 'valuebasket_tradetracker_' . "$platform_id" . '.csv';
            // $fp = fopen(DATAPATH . "feeds/tradetracker/$platform_id/" . $filename, 'w');
            $this->del_dir(DATAPATH . "feeds/tradetracker/ftp/$platform_id");
            $remotefilename = strtolower('/valuebasket_tradetracker_' . "$platform_id" . '.csv');
            $fp_nodate = fopen(DATAPATH . "feeds/tradetracker/ftp/$platform_id/" . $remotefilename, 'w');

            // 2014-10-20 - only write to the ftp folder
            // if(fwrite($fp, $data_feed) && fwrite($fp_nodate, $data_feed))
            if(fwrite($fp_nodate, $data_feed))
            {
                switch ($platform_id)
                {
                    case "WEBBE":
                        // $this->ftp_feeds(DATAPATH . "feeds/tradetracker/{$platform_id}/" . $filename, $remotefilename, $this->get_ftp_name() . "_$platform_id");
                        break;

                    default:
                        break;
                }

                if ($explain_sku == "")
                {
                    header("Content-type: text/csv");
                    header("Cache-Control: no-store, no-cache");
                    header("Content-Disposition: attachment; filename=\"$remotefilename\"");
                    echo $data_feed;
                }
            }
            else
            {
                $subject = "<DO NOT REPLY> Fails to create Tradetracker Product Feed File";
                $message ="FILE: ".__FILE__."<br>
                             LINE: ".__LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    protected function get_data_list_w_country($where = array(), $option = array(), $platform_id)
    {
        return $this->get_prod_srv()->get_tradetracker_product_feed_dto(array(), array('limit'=>-1), $platform_id);
    }

    public function get_data_feed($first_line_headling = TRUE, $platform_id = "WEBBE", $explain_sku)
    {
        $country_id = substr($platform_id, -2);

        // common processing to be done here
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();
        $affiliate_id = $this->get_affiliate_id_prefix() . $country_id;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        switch ($platform_id)
        {
            // country specific processing to be done here
            case "WEBBE":
                return $this->get_data_feed_margin_group_a($first_line_headling, $platform_id, $explain_sku, $override);
                break;
        }
    }

    private function get_margin($platform_id, $sku, $price)
    {
        $json          = $this->price_srv->get_profit_margin_json($platform_id, $sku, $price);
        $arr           = json_decode($json, true);

        return $arr["get_margin"];
    }

    private function get_data_feed_margin_group_a($first_line_headling = TRUE, $platform_id, $explain_sku, $override = null)
    {
        $list = $this->get_data_list_w_country(array(), array(), $platform_id);
        if (!$list)
        {
            return;
        }

        $new_list = array();
        foreach ($list as $row)
        {
            $sku = $row->get_sku();
            $price = $row->get_price();
            $profit_margin = $this->get_margin($platform_id, $sku, $price);
            $row->set_margin($profit_margin);
            // $this->get_price_srv()->calculate_profit($row);
            if($res = $this->process_data_row($row))
            {
                $add = false;
                $selected = " --> NOT ADDED TO FINAL OUTPUT";
                if ((($res->get_price() >= 20) && ($res->get_price() < 200) && ($res->get_margin() >= 10)) ||
                    (($res->get_price() >= 200) && ($res->get_price() < 400) && ($res->get_margin() >= 10)) ||
                    (($res->get_price() >= 400) && ($res->get_price() < 800) && ($res->get_margin() >= 8)) ||
                    (($res->get_price() >= 800) && ($res->get_price() < 1200) && ($res->get_margin() >= 7)) ||
                    (($res->get_price() >= 1200) && ($res->get_margin() >= 6)))
                {
                    $add = true;
                    $selected = "passed margin rules, so added";
                }

                if ($override != null)
                {
                    switch($override[$row->get_platform_id()][$row->get_sku()])
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
                    $new_list[] = $res;
                }

                if ($explain_sku != "")
                {
                    if (strtoupper($res->get_sku()) == strtoupper($explain_sku))
                    {
                        var_dump(strtoupper($explain_sku) . $selected);
                        echo "<pre>";
                        var_dump($res);
                    }
                }
            }
        }
        // set_time_limit(300); // make sure we don't timeout for another 5 minutes

        $content = $this->convert($new_list, $first_line_headling);
        return $content;
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
        return APPPATH . 'data/tradetracker_product_feed_xml2csv.txt';
    }

    public function get_contact_email()
    {
        return 'itsupport@eservicesgroup.net';
    }

    protected function get_ftp_name()
    {
        return 'TRADETRACKER';
    }

    protected function get_sj_id()
    {
        return "TRADETRACKER_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Tradetracker Product Feed Cron Time";
    }

    protected function get_affiliate_id_prefix()
    {
        // refer to affiliate table for more details
        // SOME IDs have country suffix
        // SOME don't have.. so take note when checking
        return "TT";
    }
}