<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";


# SBF #2275 Add OMG SG product feed
class Omg_product_feed_service extends Data_feed_service
{
    protected $id = "Omg Product Feed";
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


    public function gen_data_feed($country)
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed(TRUE, $country);
        if($data_feed)
        {
            #keep date-stamped files hidden frm ftp folder
            $this->del_dir(DATAPATH . 'feeds/omg/' . $country);
            $this->del_dir(DATAPATH . 'feeds/omg/ftp/' . $country);
            $filename = 'valuebasket_omg_' . "$country" . '_' . date('Ymdhis') . '.csv';
            $fp_wdate = fopen(DATAPATH . 'feeds/omg/' . $country . '/' . $filename, 'w');
            $remotefilename = strtolower('/valuebasket_omg_' . "$country" . '.csv');
            $fp_nodate = fopen(DATAPATH . 'feeds/omg/ftp/' . $country . '/' . $remotefilename, 'w');

            if(fwrite($fp_nodate, $data_feed) AND fwrite($fp_wdate, $data_feed))
            {
                if($country == 'SG' or $country == 'MY')
                {
                    $this->ftp_feeds(DATAPATH . 'feeds/omg/'. $country . '/'. $filename, $remotefilename, $this->get_ftp_name() . "_$country");

                }
                header("Cache-Control: no-store, no-cache");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $data_feed;
            }
            else
            {
                $subject = "<DO NOT REPLY> Fails to create Omg Product Feed File";
                $message ="FILE: ".__FILE__."<br>
                             LINE: ".__LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_data_list_w_country($where = array(), $option = array(),  $country)
    {
        return $this->get_prod_srv()->get_omg_product_feed_dto(array(), array('limit'=>-1), $country);
    }

    public function get_data_feed($first_line_headling = TRUE, $country = "SG")
    {
        // common processing to be done here
        switch ($country)
        {
            // country specific processing to be done here
            case "MY":
            case "SG": return $this->get_data_feed_group_1($first_line_headling, $country);
                break;
        }
    }

    private function get_data_feed_group_1($first_line_headling = TRUE, $country)
    {
        # only output items with margin >= 7
        $list = $this->get_data_list_w_country(array(), array(), $country);

        if (!$list)
        {
            return;
        }

        $new_list = array();
        foreach ($list as $row)
        {
            // $this->get_price_srv()->calculate_profit($row);

            if($res = $this->process_data_row($row))
            {
                $profit_margin = $this->get_margin("WEB$country", $res->get_sku(), $res->get_price());
                if (($profit_margin >= 7))
                {
                    # next 3 lines will calculate & add % discount into column in feed
                    $rrp = $this->get_price_srv()->calc_website_product_rrp($res->get_price(), $res->get_fixed_rrp(), $res->get_rrp_factor());
                    $discount_amt = $rrp - $res->get_price();
                    if($discount_amt && $discount_amt !== 0)
                    {
                        $discount = number_format($discount_amt) / $rrp * 100;
                    }
                    else
                    {
                        $discount = 0;
                    }
                    $res->set_discount($discount);
                    $res->set_rrp($rrp);
                    $new_list[] = $res;
                }
            }
        }
        $content = $this->convert($new_list, $first_line_headling);
        return $content;
    }

    private function get_margin($country, $sku, $price)
    {
        $json          = $this->price_srv->get_profit_margin_json($country, $sku, $price);
        $arr           = json_decode($json, true);

        return $arr["get_margin"];
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
        return APPPATH . 'data/omg_product_feed_xml2csv.txt';
    }

    public function get_contact_email()
    {
        return 'itsupport@eservicesgroup.net';
    }

    protected function get_ftp_name()
    {
        return 'OMG';
    }

    protected function get_sj_id()
    {
        return "OMG_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Omg Product Feed Cron Time";
    }
}


