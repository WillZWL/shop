<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Priceme_product_feed_service extends Data_feed_service
{
    protected $id = "PriceMe Product Feed";
    private $profit_margin;

    public function __construct()
    {
        parent::Data_feed_service();
        include_once APPPATH . "libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH . "libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());

        $this->set_output_delimiter(chr(9));
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function set_product_srv(Base_service $srv)
    {
        $this->product_srv = $srv;
    }

    public function gen_data_feed($platform_id = 'WEBHK', $gen_csv = 0, $explain_sku = "")
    {
        if ($this->init($platform_id)) {
            define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

            $data_feed = $this->get_data_feed(TRUE, $platform_id, $explain_sku);
            if ($data_feed) {
                $this->del_dir(DATAPATH . $this->local_file_path);
                $filename = 'priceme_product_feed_' . $platform_id . '.txt';
                $fp = fopen(DATAPATH . $this->local_file_path . $filename, 'w');

                if (fwrite($fp, $data_feed)) {
                    // if($platform_id=='WEBHK' || $platform_id=='WEBMY' || $platform_id=='WEBSG' || $platform_id=='WEBAU')
                    {
                        @$this->ftp_feeds(DATAPATH . $this->local_file_path . $filename, $this->upload_file_path, $this->get_ftp_name());
                    }

                    if ($gen_csv) {
                        $this->gen_csv($filename);
                    }
                    if ($explain_sku == "") {
                        header("Cache-Control: no-store, no-cache");
                        header("Content-Disposition: attachment; filename=\"$filename\"");
                        echo $data_feed;
                    }
                } else {
                    $subject = "<DO NOT REPLY> Fails to create PriceMe Product Feed File";
                    $message = "FILE: " . __FILE__ . "<br>
                                 LINE: " . __LINE__;
                    $this->error_handler($subject, $message);
                }
            }
        }
    }

    public function init($platform_id)
    {
        switch ($platform_id) {
            case "WEBHK":
                $this->platform_id = "WEBHK";
                $this->id = "PriceMe HK Product Feed";
                $this->country_w_language = 'en_HK';
                $this->sj_id = 'PRICEME_HK_PRODUCT_FEED';
                $this->sj_name = "PriceMe HK Product Feed Cron Time";
                $this->base_url = "http://www.valuebasket.com/";
                $this->ftp_name = "PRICEME";
                $this->local_file_path = "feeds/priceme/HK/";
                $this->upload_file_path = "HK/valuebasket.txt";
                $this->profit_margin = 5;
                return TRUE;
                break;
            case "WEBMY":
                $this->platform_id = "WEBMY";
                $this->id = "PriceMe MY Product Feed";
                $this->country_w_language = 'en_MY';
                $this->sj_id = 'PRICEME_MY_PRODUCT_FEED';
                $this->sj_name = "PriceMe MY Product Feed Cron Time";
                $this->base_url = "http://www.valuebasket.com/";
                $this->ftp_name = "PRICEME";
                $this->local_file_path = "feeds/priceme/MY/";
                $this->upload_file_path = "MY/valuebasket.txt";
                $this->profit_margin = 5;
                return TRUE;
                break;
            case "WEBSG":
                $this->platform_id = "WEBSG";
                $this->id = "PriceMe SG Product Feed";
                $this->country_w_language = 'en_SG';
                $this->sj_id = 'PRICEME_SG_PRODUCT_FEED';
                $this->sj_name = "PriceMe SG Product Feed Cron Time";
                $this->base_url = "http://www.valuebasket.com.sg/";
                $this->ftp_name = "PRICEME";
                $this->local_file_path = "feeds/priceme/SG/";
                $this->upload_file_path = "SG/valuebasket.txt";
                $this->profit_margin = 5;
                return TRUE;
                break;
            case "WEBAU":
                $this->platform_id = "WEBAU";
                $this->id = "PriceMe AU Product Feed";
                $this->country_w_language = 'en_AU';
                $this->sj_id = 'PRICEME_HK_PRODUCT_FEED';
                $this->sj_name = "PriceMe HK Product Feed Cron Time";
                $this->base_url = "http://www.valuebasket.com.au/";
                $this->ftp_name = "PRICEME";
                $this->local_file_path = "feeds/priceme/AU/";
                $this->upload_file_path = "AU/valuebasket.txt";
                $this->profit_margin = 5;
                return TRUE;
                break;

// for additional countries in future, please add below here and use get_data_list_w_country
            case "WEBNZ":
                $this->platform_id = "WEBNZ";
                $this->id = "PriceMe NZ Product Feed";
                $this->country_w_language = 'en_NZ';
                $this->sj_id = 'PRICEME_NZ_PRODUCT_FEED';
                $this->sj_name = "PriceMe NZ Product Feed Cron Time";
                $this->base_url = "http://www.valuebasket.co.nz/";
                $this->ftp_name = "PRICEME";
                $this->local_file_path = "feeds/priceme/NZ/";
                $this->upload_file_path = "NZ/valuebasket_priceme_nz.txt";
                $this->profit_margin = 6;
                return TRUE;
                break;

            case "WEBPH":
                $this->platform_id = "WEBPH";
                $this->id = "PriceMe PH Product Feed";
                $this->country_w_language = 'en_PH';
                $this->sj_id = 'PRICEME_PH_PRODUCT_FEED';
                $this->sj_name = "PriceMe PH Product Feed Cron Time";
                $this->base_url = "http://www.valuebasket.com.ph/";
                $this->ftp_name = "PRICEME";
                $this->local_file_path = "feeds/priceme/PH/";
                $this->upload_file_path = "PH/valuebasket_priceme_ph.txt";
                $this->profit_margin = 5;
                return TRUE;
                break;
            default:
                return FALSE;
        }
    }

    public function get_data_feed($first_line_headling = TRUE, $platform_id = "WEBHK", $explain_sku)
    {
        // common processing to be done here
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();

        $affiliate_id = $this->get_affiliate_id_prefix() . substr($platform_id, -2);
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        switch ($platform_id) {
            case "WEBHK":
            case "WEBAU":
                return $this->get_data_feed_default($first_line_headling, $override);

            case "WEBPH":
            case "WEBMY":
            case "WEBSG":
            case "WEBNZ":
                return $this->get_data_feed_group_1($first_line_headling, $platform_id, $explain_sku, $override);
        }
    }

    protected function get_affiliate_id_prefix()
    {
        return "PM";
    }

    private function get_data_feed_default($first_line_headling = TRUE, $override = null)
    {
        $arr = $this->get_data_list();
        if (!$arr) {
            return;
        }

        $new_list = array();

        foreach ($arr as $row) {
            $price_srv = $this->get_price_srv();
            if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku" => $row->get_sku(), "platform_id" => $this->platform_id), array("limit" => 1))) {
                $add = false;
                $price_srv->calc_logistic_cost($prod_obj);
                $price_srv->calculate_profit($prod_obj);
                if ($prod_obj->get_margin() >= $this->profit_margin && $row->get_price() >= 150) // OVERRIDE START: always copy from this line
                {
                    $add = true;
                    $selected = " --> ADDED TO OUTPUT";
                } else {
                    $selected = " --> NOT ADDED TO OUTPUT";
                    $add = false;
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

                if ($add) // OVERRIDE END: copy until this line
                {
                    $new_list[] = $this->priceme_process_data_row($row);
                }
            }
        }

        $content = $this->convert($new_list, $first_line_headling);

        return $content;
    }

    protected function get_data_list($where = array(), $option = array())
    {
        $obj_list = $this->get_prod_srv()->get_priceme_product_feed_dto(array("p.sku NOT IN ('10391-AA-BK','10391-AA-WH','10392-AA-BK','10392-AA-WH','10636-AA-BK','11752-AA-BK')" => null, "pr.platform_id" => $this->platform_id), array("limit" => -1));
        foreach ($obj_list AS $obj) {
            $obj->set_product_url($this->base_url . $this->country_w_language . "/" . str_replace(array(" ", "/", "."), "-", $obj->get_prod_name()) . '/mainproduct/view/' . $obj->get_sku());
        }

        return $obj_list;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function priceme_process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        if (!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path") . basename($data->get_image_url()))) {
            $data->set_image_url($this->base_url . "images/product/imageunavailable.jpg");
        }

        $data->set_prod_id($this->string_to_ascii($data->get_sku()));

        $search = array(chr(10), chr(13), chr(9), "<br>");
        $replace = array(' ', ' ', ' ', ' ');

        $detail_desc = htmlentities(strip_tags($data->get_detail_desc()));
        $detail_desc = str_replace($search, $replace, $detail_desc);
        $data->set_detail_desc($detail_desc);

        return $data;
    }

#   SBF #2238 PriceMe NZ, SBF #2500 PriceMe PH

    protected function string_to_ascii($str)
    {
        for ($i = 0; $i < strlen($str); $i++) {
            $new_str .= ord($str[$i]);
        }
        return $new_str;
    }

    private function get_data_feed_group_1($first_line_headling = TRUE, $platform_id, $explain_sku, $override = null)
    {
        $list = $this->get_data_list_w_country(array(), array(), $platform_id);

        if (!$list) {
            return;
        }

        $new_list = array();
        foreach ($list as $row) {
            $this->get_price_srv()->calculate_profit($row);

            if ($res = $this->process_data_row($row)) {
                if ($res->get_margin() >= 6) // OVERRIDE START: always copy from this line
                {
                    $add = true;
                    $selected = " --> ADDED TO OUTPUT";
                } else {
                    $selected = " --> NOT ADDED TO OUTPUT";
                    $add = false;
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

                if ($add) // OVERRIDE END: copy until this line
                {
                    $new_list[] = $res;
                }

                if ($explain_sku != "") {
                    // var_dump($res);die();
                    if (strtoupper($res->get_sku()) == strtoupper($explain_sku)) {
                        var_dump(strtoupper($explain_sku) . " " . $selected);
                        echo "<pre>";
                        var_dump($res);
                    }
                }
            }
        }

        $content = $this->convert($new_list, $first_line_headling);
        return $content;
    }

    public function get_data_list_w_country($where = array(), $option = array(), $platform_id)
    {
        return $this->get_prod_srv()->get_priceme_product_feed_w_country_dto(array(), array('limit' => -1), $platform_id);
    }

    protected function get_ftp_name()
    {
        return 'PRICE_ME';
    }

    public function gen_csv($filename)
    {
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Expires: ' . gmdate('D, d M Y H:i:s', gmmktime() - 3600) . ' GMT');
        header("Content-Length: " . filesize(DATAPATH . $this->local_file_path . $filename));
        $fp = fopen(DATAPATH . $this->local_file_path . $filename, "r");
        fpassthru($fp);
    }

    public function get_contact_email()
    {
        return 'itsupport@eservicesgroup.net';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/priceme_product_feed_xml2csv.txt';
    }

    protected function get_sj_id()
    {
        return $this->sj_id;
    }

    protected function get_sj_name()
    {
        return $this->sj_name;
    }
}

/* End of file priceme_product_feed_service.php */
/* Location: ./system/application/libraries/service/Priceme_product_feed_service.php */