<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Mediaforge_product_feed_service extends Data_feed_service
{
    protected $id = "Mediaforge Product Feed";
    private $prod_srv;
    private $price_srv;

    public function __construct()
    {
        parent::Data_feed_service();
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());

        $this->set_output_delimiter("\t");
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

        $data_feed = $this->get_data_feed(TRUE, $platform_id, $explain_sku);
        switch ($platform_id) {
            case 'WEBFR':
                $country = 'FR';
                $countryname = 'france';
                break;
            case 'WEBES':
                $country = 'ES';
                $countryname = 'spain';
                break;
            case 'WEBNZ':
                $country = 'NZ';
                $countryname = 'new_zealand';
                break; #SBF #2833
            case 'WEBSG':
                $country = 'SG';
                $countryname = 'singapore';
                break; #SBF #2834
            case 'WEBMY':
                $country = 'MY';
                $countryname = 'malaysia';
                break; #SBF #2835
            case 'WEBIT':
                $country = 'IT';
                $countryname = 'italy';
                break; #SBF #2836
            default:
                echo "Please input correct platform_id";
                break;
        }

        if ($data_feed) {
            $filename = 'valuebasket_' . "$countryname" . '_nmerchandis.txt';
            $remotefilename = strtolower('/valuebasket_' . "$countryname" . '_nmerchandis.txt');
            $fp = fopen(DATAPATH . 'feeds/mediaforge/product/' . strtolower($country) . '/' . $filename, 'w');

            if (fwrite($fp, $data_feed)) {
                $this->ftp_feeds(DATAPATH . 'feeds/mediaforge/product/' . strtolower($country) . '/' . $filename, $remotefilename, $this->get_ftp_name() . "_$country");

                if ($explain_sku == "") {
                    header("Cache-Control: no-store, no-cache");
                    header("Content-Disposition: attachment; filename=\"$filename\"");
                    echo $data_feed;
                }
            } else {
                $subject = "<DO NOT REPLY> Fails to create Mediaforge Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_data_feed($first_line_headling = TRUE, $platform_id = "WEBFR", $explain_sku)
    {
        $country = substr($platform_id, -2);

        // common processing to be done here
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();
        $affiliate_id = $this->get_affiliate_id_prefix() . $country;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        switch ($platform_id) {
            // country specific processing to be done here
            case "WEBNZ":
            case "WEBFR":
            case "WEBES":
                return $this->get_data_feed_group_1($first_line_headling, $platform_id, $explain_sku, $override);
                break;

            case "WEBMY":
            case "WEBSG":
                return $this->get_data_feed_group_2($first_line_headling, $platform_id, $explain_sku, $override);
                break;

            case "WEBIT":
                return $this->get_data_feed_group_3($first_line_headling, $platform_id, $explain_sku, $override);
                break;
            // case "WEBES": return $this->get_data_feed_es($first_line_headling, $explain_sku);
        }
    }

    protected function get_affiliate_id_prefix()
    {
        // refer to affiliate table for more details
        // SOME IDs have country suffix
        // SOME don't have.. so take note when checking
        return "MF";
    }

    private function get_data_feed_group_1($first_line_headling = TRUE, $platform_id, $explain_sku, $override = null)
    {
        # this group contains no business logic regarding margins
        $list = $this->get_data_list_w_country(array(), array(), $platform_id);

        if (!$list) {
            return;
        }

        $new_list = array();

        foreach ($list as $row) {
            // $i++;
            // $this->get_price_srv()->calculate_profit($row);

            if ($res = $this->process_data_row($row)) {
                $selected = "not added to output";
                $prod_id = $this->string_to_ascii($row->get_sku());
                $row->set_prod_id($prod_id);

                {
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

                $total = count($new_list);

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
        $content .= "TRL|" . $total;
        return $content;
    }

    public function get_data_list_w_country($where = array(), $option = array(), $platform_id = "WEBFR")
    {
        return $this->get_prod_srv()->get_mediaforge_product_feed_dto(array(), array('limit' => -1), $platform_id);
    }

    protected function string_to_ascii($str)
    {
        for ($i = 0; $i < strlen($str); $i++) {
            $new_str .= ord($str[$i]);
        }
        return $new_str;
    }

    private function get_data_feed_group_2($first_line_headling = TRUE, $platform_id, $explain_sku, $override = null)
    {
        # this group only outputs products with margin >= 7%
        $list = $this->get_data_list_w_country(array(), array(), $platform_id);

        if (!$list) {
            return;
        }

        $new_list = array();

        foreach ($list as $row) {
            if ($res = $this->process_data_row($row)) {
                $add = false;
                $selected = "not added to output";
                $profit_margin = $this->get_margin($platform_id, $res->get_sku(), $res->get_price());

                if (($profit_margin >= 7)) {
                    $prod_id = $this->string_to_ascii($row->get_sku());
                    $row->set_prod_id($prod_id);
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

                $total = count($new_list);

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
        $content .= "TRL|" . $total;
        return $content;
    }

    private function get_margin($country, $sku, $price)
    {
        $json = $this->price_srv->get_profit_margin_json($country, $sku, $price);
        $arr = json_decode($json, true);

        return $arr["get_margin"];
    }

    private function get_data_feed_group_3($first_line_headling = TRUE, $platform_id, $explain_sku, $override = null)
    {
        # this group only outputs products with margin >= 7%
        $list = $this->get_data_list_w_country(array(), array(), $platform_id);

        if (!$list) {
            return;
        }

        $new_list = array();

        foreach ($list as $row) {
            if ($res = $this->process_data_row($row)) {
                $add = false;
                $selected = "not added to output";
                $profit_margin = $this->get_margin($platform_id, $res->get_sku(), $res->get_price());

                if (($profit_margin >= 10)) {
                    $prod_id = $this->string_to_ascii($row->get_sku());
                    $row->set_prod_id($prod_id);
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

                $total = count($new_list);

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
        $content .= "TRL|" . $total;
        return $content;
    }

    protected function get_ftp_name()
    {
        return 'MEDIAFORGE';
    }

    public function get_contact_email()
    {
        return 'itsupport@eservicesgroup.net';
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
        return APPPATH . 'data/mediaforge_product_feed_xml2csv.txt';
    }

    protected function get_sj_id()
    {
        return "MEDIAFORGE_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Mediaforge Product Feed Cron Time";
    }
}

/* End of file Mediaforge_product_feed_service.php */
/* Location: ./system/application/libraries/service/Mediaforge_product_feed_service.php */
