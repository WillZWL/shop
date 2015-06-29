<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Skapiec_product_feed_service extends Data_feed_service
{
    protected $id = "Skapiec Product Feed";
    private $prod_srv;
    private $price_srv;
    private $cat_srv;
    private $now_date;

    public function __construct()
    {
        parent::Data_feed_service();
        include_once(APPPATH . 'libraries/service/Price_service.php');
        $this->set_price_srv(New Price_service());
        include_once(APPPATH . 'libraries/service/Category_service.php');
        $this->set_cat_srv(New Category_service());
        include_once(APPPATH . 'libraries/dao/Category_mapping_dao.php');
        $this->set_cat_mapping_dao(New Category_mapping_dao());

        $this->set_output_delimiter("\t");
    }

    public function set_cat_mapping_dao(Base_dao $srv)
    {
        $this->cat_mapping_dao = $srv;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function get_cat_srv()
    {
        return $this->cat_srv;
    }

    public function set_cat_srv(Base_service $srv)
    {
        $this->cat_srv = $srv;
    }

    public function get_skapiec_xml($platform_id)
    {
        /* NOTE: As of 2014-11-12 SBF#5185. SKAPIEC is using CENEO's product feed format */
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $filename = strtolower('valuebasket_skapiec_' . "$platform_id" . '.xml');
        $fp = fopen(DATAPATH . "feeds/skapiec/ftp/$platform_id/" . $filename, 'r');

        $content = file_get_contents(DATAPATH . "feeds/skapiec/ftp/$platform_id/" . $filename);

        header("Content-type: text/xml");
        // header("Cache-Control: no-store, no-cache");
        // header("Content-Disposition: attachment; filename=\"$filename\"");
        echo $content;
    }

    public function gen_data_feed($platform_id, $explain_sku = "")
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $this->now_date = time();
        $platform_id = strtoupper($platform_id);

        $data_feed = $this->get_data_feed(TRUE, $platform_id, $explain_sku);
        if ($data_feed) {
            $remotefilename = strtolower('/valuebasket_skapiec_' . "$platform_id" . '.xml');
            $fp = fopen(DATAPATH . "feeds/skapiec/ftp/$platform_id" . $remotefilename, 'w');

            if (fwrite($fp, $data_feed)) {
                // {
                //  $this->ftp_feeds(DATAPATH . 'feeds/skapiec/' . $filename, $remotefilename, $this->get_ftp_name() . "_$platform_id");
                // }

                if ($explain_sku == "") {
                    header("Content-type: text/xml");
                    header("Cache-Control: no-store, no-cache");
                    header("Content-Disposition: attachment; filename=\"$remotefilename\"");
                    echo $data_feed;
                }
            } else {
                $subject = "Failed to create Skapiec Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                mail($this->get_contact_email(), $subject, $message);
            }
        }
    }

    public function get_data_feed($first_line_heading = TRUE, $platform_id = "WEBPL", $explain_sku)
    {
        // common processing to be done here
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();

        $country_id = substr($platform_id, -2);
        $affiliate_id = $this->get_affiliate_id_prefix() . $country_id;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        switch ($platform_id) {
            // country specific processing to be done here
            case "WEBPL":
                return $this->get_data_feed_group_1($first_line_heading, $platform_id, $explain_sku, $override);
                break;
        }
    }

    protected function get_affiliate_id_prefix()
    {
        return "SKA";
    }

    private function get_data_feed_group_1($first_line_heading = TRUE, $platform_id, $explain_sku, $override = null)
    {
        //this is grouped by business logic regarding price & margin
        $list = $this->get_data_list_w_country(array(), array(), $platform_id);

        if (!$list) {
            return;
        }

        $new_list = array();
        foreach ($list as $row) {
            if ($res = $this->process_data_row($row)) {
                // echo "<pre>"; var_dump($res);die();
                $sku = $res->sku;
                $price = $res->price;

                $profit_info = $this->get_margin($platform_id, $sku, $price);

                $margin = $profit_info["get_margin"];
                $profit = $profit_info["get_profit"];   // absolute profit in local currency

                $selected = "not added to output";
                $add = false;
                if ($margin > 7 && $profit > 60) {
                    $add = true;
                    $selected = "added to output";
                }

                if ($override != null) {
                    switch ($override[$res->platform_id][$res->sku]) {
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
                    // $rrp = $this->get_price_srv()->calc_website_product_rrp($res->get_price(), $res->get_fixed_rrp(), $res->get_rrp_factor());
                    // $res->set_rrp($rrp);
                    $new_list[] = $res;
                }

                if ($explain_sku != "") {
                    if (strtoupper($res->sku) == strtoupper($explain_sku)) {
                        var_dump("$explain_sku $selected");
                        echo "<pre>";
                        var_dump($res);
                    }
                }
            }
        }

        $content = $this->convert_to_xml($new_list, $platform_id);
        return $content;
    }

    public function get_data_list_w_country($where = array(), $option = array(), $platform_id = "WEBPL")
    {
        return $this->get_prod_srv()->get_skapiec_product_feed_dto(array(), array('limit' => -1), $platform_id);
    }

    private function get_margin($platform_id, $sku, $price)
    {
        $json = $this->price_srv->get_profit_margin_json($platform_id, $sku, $price);
        $arr = json_decode($json, true);

        return $arr;
    }

    private function convert_to_xml($list = array(), $platform_id = "WEBPL")
    {
        // sbf #4462
        $dom = new DOMDocument('1.0');
        $dom->encoding = 'UTF-8';
        $dom->formatOutput = true;

        ###### DEBUG LINE BELOW ######
        // header("Content-Type: text/plain");  # for readability during debug

        $lang_id = strtolower(substr($platform_id, -2));
        $country_id = strtoupper(substr($platform_id, -2));

        $offers = $dom->createElement('offers');
        $offers = $dom->appendChild($offers);
        $xmlns = $dom->createAttribute("xmlns:xsi");
        $offers->appendChild($xmlns);
        $text = $dom->createTextNode("http://www.w3.org/2001/XMLSchema-instance");
        $xmlns->appendChild($text);

        $version = $dom->createAttribute("version");
        $offers->appendChild($version);
        $text = $dom->createTextNode("1");
        $version->appendChild($text);

        if ($list) {
            foreach ($list as $obj) {
                $avail_option = $this->get_avail_option($obj->delivery_scenarioid);

                // ceneo's own category -- SKAPIEC is using Ceneo feed
                $ceneo_cat = "";
                if ($obj->sub_cat_id != "") {
                    if (!($ceneo_cat = $this->get_cat_mapping_dao()->get_external_catmapping("CENEO", 2, $obj->sub_cat_id, $lang_id, $country_id)->ext_name))
                        $ceneo_cat = "";
                }

                $group = $dom->createElement('group');
                $offers->appendChild($group);
                $name = $dom->createAttribute("name");
                $group->appendChild($name);
                $text = $dom->createTextNode("other");
                $name->appendChild($text);

                $offer = $dom->createElement('o');
                $group->appendChild($offer);
                $id = $dom->createAttribute("id");
                $offer->appendChild($id);
                $text = $dom->createTextNode($obj->sku);
                $id->appendChild($text);

                $url = $dom->createAttribute("url");
                $offer->appendChild($url);
                $text = $dom->createTextNode($obj->prod_url);
                $url->appendChild($text);

                $price = $dom->createAttribute("price");
                $offer->appendChild($price);
                $text = $dom->createTextNode($obj->price);
                $price->appendChild($text);

                $avail = $dom->createAttribute("avail");
                $offer->appendChild($avail);
                $text = $dom->createTextNode($avail_option);
                $avail->appendChild($text);

                $basket = $dom->createAttribute("basket");
                $offer->appendChild($basket);
                $text = $dom->createTextNode(0);
                $basket->appendChild($text);

                $stock = $dom->createAttribute("stock");
                $offer->appendChild($stock);
                $text = $dom->createTextNode($obj->website_quantity);
                $stock->appendChild($text);

                $cat = $dom->createElement("cat");
                $offer->appendChild($cat);
                // $catcdata = $dom->ownerDocument->createCDATASection($ceneo_cat);
                // $cat->appendChild($catcdata);
                $text = $cat->appendChild(new DOMCdataSection($ceneo_cat));

                $name = $dom->createElement("name");
                $offer->appendChild($name);
                $text = $name->appendChild(new DOMCdataSection($obj->prod_name));

                $imgs = $dom->createElement('imgs');
                $offer->appendChild($imgs);
                $main = $dom->createElement("main");
                $imgs->appendChild($main);
                $mainurl = $dom->createAttribute("url");
                $main->appendChild($mainurl);
                $text = $dom->createTextNode($obj->image_url);
                $mainurl->appendChild($text);
                $img = $dom->createElement("i");
                $imgs->appendChild($img);
                $smallimgurl = $dom->createAttribute("url");
                $img->appendChild($smallimgurl);
                $text = $dom->createTextNode($obj->image_url);
                $smallimgurl->appendChild($text);

                # temporarily don't show desc
                // $desc = $dom->createElement("desc");
                // $offer->appendChild($desc);
                //  $text = $desc->appendChild(new DOMCdataSection($obj->detail_desc));

                $attrs = $dom->createElement('attrs');
                $offer->appendChild($attrs);
                $attribute = $dom->createElement('a');
                $attrs->appendChild($attribute);
                $name = $dom->createAttribute("name");
                $attribute->appendChild($name);
                $text = $dom->createTextNode("Manufacturer");
                $name->appendChild($text);
                $text = $attribute->appendChild(new DOMCdataSection($obj->brand_name));

                $attribute = $dom->createElement('a');
                $attrs->appendChild($attribute);
                $name = $dom->createAttribute("name");
                $attribute->appendChild($name);
                $text = $dom->createTextNode("Manufacturer’s code");
                $name->appendChild($text);
                $text = $attribute->appendChild(new DOMCdataSection($obj->mpn));

                // $attribute = $dom->createElement('a');
                // $attrs->appendChild($attribute);
                //  $name = $dom->createAttribute("name");
                //  $attribute->appendChild($name);
                //  $text = $dom->createTextNode("EAN");
                //  $name->appendChild($text);
                //  $text = $attribute->appendChild(new DOMCdataSection($obj->sku));

                // echo "<pre>"; var_dump($obj); die();
            }
        }

        // header('Content-Type: text/xml');
        // echo $dom->saveXML(); die();
        $xml = $dom->saveXML();
        return $xml;
    }

    private function get_avail_option($delivery_scenarioid = "")
    {
        switch ($delivery_scenarioid) {
            case 1:
            case 2:
            case 5:
                $avail = 7;
                break;

            case 3:
                $avail = 14;
                break;

            case 4:
                $avail = 3;
                break;

            default:
                $avail = 7;
                break;
        }

        return $avail;
    }

    public function get_cat_mapping_dao()
    {
        return $this->cat_mapping_dao;
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
        return APPPATH . 'data/skapiec_product_feed_xml2csv.txt';
    }

    protected function get_ftp_name()
    {
        return 'SKAPIEC';
    }

    protected function get_sj_id()
    {
        return "SKAPIEC_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Skapiec Product Feed Cron Time";
    }
}

/* End of file Skapiec_product_feed_service.php */
/* Location: ./system/application/libraries/service/Skapiec_product_feed_service.php */