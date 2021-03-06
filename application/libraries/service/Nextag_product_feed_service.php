<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Nextag_product_feed_service extends Data_feed_service
{
    protected $id = "Nextag Product Feed";
    private $prod_srv;
    private $price_srv;

    public function __construct()
    {
        parent::Data_feed_service();
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());
        $this->set_output_delimiter("\t");
    }

    public function gen_data_feed($platform_id, $explain_sku = "")
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        switch ($platform_id) {
            case 'WEBES':
                $country_id = 'ES';
                break;

            default:
                $country_id = '';
                break;
        }

        $data_feed = $this->get_data_feed(TRUE, $platform_id, $explain_sku);
        if ($data_feed) {
            # with timestamp, for our records
            $filename = 'valuebasket_nextag_' . "$platform_id" . '.txt';
            $fp = fopen(DATAPATH . "feeds/nextag/$platform_id/" . $filename, 'w');

            # no timestamp, for nextag
            $remotefilename = strtolower('/valuebasket_nextag_' . "$platform_id" . '.txt');
            $fp_nodate = fopen(DATAPATH . "feeds/nextag/ftp/$platform_id/" . $remotefilename, 'w');

            if (fwrite($fp, $data_feed) && fwrite($fp_nodate, $data_feed)) {
                // $this->ftp_feeds(DATAPATH . "feeds/nextag/$platform_id/".$filename, $remotefilename, $this->get_ftp_name()."_$country_id");

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

    public function get_data_feed($first_line_headling = TRUE, $platform_id = "WEBES", $explain_sku)
    {
        // common processing to be done here
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();

        $affiliate_id = $this->get_affiliate_id_prefix() . $platform_id;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        switch ($platform_id) {
            // country specific processing to be done here
            case "WEBES":
                return $this->get_data_feed_group_1($first_line_headling, $platform_id, $explain_sku, $override);
                break;
        }
    }

    protected function get_affiliate_id_prefix()
    {
        return "NX";
    }

    private function get_data_feed_group_1($first_line_headling = TRUE, $platform_id, $explain_sku, $override = null)
    {
        //this is grouped by business logic regarding price & margin

        $list = $this->get_data_list_w_platform(array(), array(), $platform_id);

        if (!$list) {
            return;
        }

        $new_list = array();
        foreach ($list as $row) {
            // $this->get_price_srv()->calculate_profit($row);
            if ($res = $this->process_data_row($row)) {
                $profit_margin = $this->get_margin($platform_id, $res->get_sku(), $res->get_price());

                $selected = "not added to output";
                $add = false;
                if
                (
                    (($res->get_price() >= 100) && ($res->get_price() < 400) && ($profit_margin >= 12)) ||
                    (($res->get_price() >= 400) && ($res->get_price() < 800) && ($profit_margin >= 12)) ||
                    (($res->get_price() >= 800) && ($res->get_price() < 1200) && ($profit_margin >= 12)) ||
                    (($res->get_price() >= 1200) && ($profit_margin >= 12))
                ) {
                    $add = true;
                    $selected = "added to output";
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

    public function get_data_list_w_platform($where = array(), $option = array(), $platform_id = "WEBES")
    {
        return $this->get_prod_srv()->get_nextag_product_feed_dto(array(), array('limit' => -1), $platform_id);
    }

    private function get_margin($country, $sku, $price)
    {
        $json = $this->price_srv->get_profit_margin_json($country, $sku, $price);
        $arr = json_decode($json, true);

        return $arr["get_margin"];
    }


    // SBF #3585 group 1 is ES

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
        return 'itsupport@eservicesgroup.net';
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return "";
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/nextag_product_feed_xml2csv.txt';
    }

    protected function get_ftp_name()
    {
        return 'NEXTAG';
    }

    protected function get_sj_id()
    {
        return "NEXTAG_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Nextag Product Feed Cron Time";
    }
}


