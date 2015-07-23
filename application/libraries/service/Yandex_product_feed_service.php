<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Yandex_product_feed_service extends Data_feed_service
{
    protected $id = "Yandex Product Feed";
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

    public function get_yandex_xml($platform_id)
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $filename = strtolower('valuebasket_yandex_' . "$platform_id" . '.xml');
        $fp = fopen(DATAPATH . "feeds/yandex/ftp/$platform_id/" . $filename, 'r');

        $content = file_get_contents(DATAPATH . "feeds/yandex/ftp/$platform_id/" . $filename);

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
            $this->del_dir(DATAPATH . 'feeds/yandex/' . "$platform_id");
            $this->del_dir(DATAPATH . "feeds/yandex/ftp/$platform_id");

            $filename = 'valuebasket_yandex_' . "$platform_id" . '_' . date('Ymdhis') . '.xml';
            $fp_w_date = fopen(DATAPATH . 'feeds/yandex/' . "$platform_id/$filename", 'w');
            // no time stamp
            $remotefilename = strtolower('/valuebasket_yandex_' . "$platform_id" . '.xml');
            $fp = fopen(DATAPATH . "feeds/yandex/ftp/$platform_id" . $remotefilename, 'w');

            if (fwrite($fp_w_date, $data_feed) && fwrite($fp, $data_feed)) {
                // {
                //  $this->ftp_feeds(DATAPATH . 'feeds/yandex/' . $filename, $remotefilename, $this->get_ftp_name() . "_$platform_id");
                // }

                if ($explain_sku == "") {
                    header("Content-type: text/xml");
                    header("Cache-Control: no-store, no-cache");
                    header("Content-Disposition: attachment; filename=\"$filename\"");
                    echo $data_feed;
                }
            }
            // else
            // {
            //  $subject = "<DO NOT REPLY> Fails to create Yandex Product Feed File";
            //  $message ="FILE: ".__FILE__."<br>
            //               LINE: ".__LINE__;
            //  $this->error_handler($subject, $message);
            // }
        }
    }

    public function get_data_feed($first_line_heading = TRUE, $platform_id = "WEBRU", $explain_sku)
    {
        // common processing to be done here
        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();

        $country_id = strtoupper(substr($platform_id, -2));
        $affiliate_id = $this->get_affiliate_id_prefix() . $country_id;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        switch ($platform_id) {
            // country specific processing to be done here
            case "WEBRU":
                return $this->get_data_feed_group_1($first_line_heading, $platform_id, $explain_sku, $override);
                break;
        }
    }

    protected function get_affiliate_id_prefix()
    {
        return "YM";
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
                $profit_margin = $this->get_margin($platform_id, $sku, $price);

                $selected = "not added to output";
                $add = false;
                if ($profit_margin > 10) {
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

        $content = $this->convert_to_yml($new_list, $platform_id);
        return $content;
    }

    public function get_data_list_w_country($where = array(), $option = array(), $platform_id = "WEBRU")
    {
        return $this->get_prod_srv()->get_yandex_product_feed_dto(array(), array('limit' => -1), $platform_id);
    }

    private function get_margin($platform_id, $sku, $price)
    {
        $json = $this->price_srv->get_profit_margin_json($platform_id, $sku, $price);
        $arr = json_decode($json, true);

        return $arr["get_margin"];
    }

    private function convert_to_yml($list = array(), $platform_id = "WEBRU")
    {
        // xml format in Yandex standard
        // http://help.yandex.ru/partnermarket/yml/about-yml.xml
        $date = date('Y-m-d H:i', $this->now_date);
        $lang_id = strtolower(substr($platform_id, -2));
        $country_id = strtoupper(substr($platform_id, -2));
        $search = array('"', '&');
        $replace = array('&quot;', '&amp;');

        switch ($platform_id) {
            case 'WEBRU':
                $currid = 'RUR';
                break;

            default:
                $currid = 'RUR';
                break;
        }

        $implementation = new DOMImplementation();
        $dtd = $implementation->createDocumentType('yml_catalog', '', 'shops.dtd');

        // $dom = new DOMDocument('1.0');
        $dom = $implementation->createDocument('', '', $dtd);
        $dom->encoding = 'windows-1251';
        $dom->formatOutput = true;

        ###### DEBUG LINE BELOW ######
        // header("Content-Type: text/plain");  # for readability during debug

        $root = $dom->createElement('yml_catalog');
        $root = $dom->appendChild($root);
        $date_attr = $dom->createAttribute("date");
        $root->appendChild($date_attr);
        $text = $dom->createTextNode($date);
        $date_attr->appendChild($text);

        $shop = $dom->createElement('shop');
        $root->appendChild($shop);
        $shop_name = $dom->createElement('name');
        $shop->appendChild($shop_name);
        $text = $dom->createTextNode("valuebasket");
        $shop_name->appendChild($text);

        $shop_co = $dom->createElement('company');
        $shop->appendChild($shop_co);
        $text = $dom->createTextNode("valuebasket.ru");
        $shop_co->appendChild($text);

        $shop_co = $dom->createElement('url');
        $shop->appendChild($shop_co);
        $text = $dom->createTextNode("http://www.valuebasket.ru");
        $shop_co->appendChild($text);

        $curr = $dom->createElement('currencies');
        $shop->appendChild($curr);
        $curr_child = $dom->createElement('currency');
        $curr->appendChild($curr_child);
        $curr_attr = $dom->createAttribute('id');
        $curr_child->appendChild($curr_attr);
        $text = $dom->createTextNode($currid);
        $curr_attr->appendChild($text);

        $rate_attr = $dom->createAttribute('rate');
        $curr_child->appendChild($rate_attr);
        $text = $dom->createTextNode(1);
        $rate_attr->appendChild($text);

        $cat = $dom->createElement('categories');
        $shop->appendChild($cat);

        if ($cat_list = $this->get_cat_srv()->get_menu_list_data($lang_id, $platform_id)) {
            foreach ($cat_list["menu"] as $arr) {
                # only main cat here
                $catid = $arr["cat_id"];
                $catname = $arr["display_name"];

                # sbf #4991 - put back mobile phone
                // if($catid == 4)
                // {
                //  # sbf#3918 - remove mobile phone
                //  continue;
                // }

                $cat_child = $dom->createElement('category');
                $cat->appendChild($cat_child);
                $cat_attr = $dom->createAttribute('id');
                $cat_child->appendChild($cat_attr);
                $text = $dom->createTextNode($catid);
                $cat_attr->appendChild($text);

                $text = $dom->createTextNode($catname);
                $cat_child->appendChild($text);
            }
        }

        $local_del_cost = $dom->createElement('local_delivery_cost');
        $shop->appendChild($local_del_cost);
        $text = $dom->createTextNode(0);
        $local_del_cost->appendChild($text);

        if ($list) {
            $offer = $dom->createElement('offers');
            $shop->appendChild($offer);

            foreach ($list as $obj) {
                switch ($obj->warranty_in_month) {
                    case '0':
                        $sellerwarranty = "false";
                        break;

                    case '6':
                        $sellerwarranty = "P6M";
                        break;

                    case '12':
                        $sellerwarranty = "P1Y";
                        break;

                    case '18':
                        $sellerwarranty = "P18M";
                        break;

                    case '24':
                        $sellerwarranty = "P2Y";
                        break;

                    case '36':
                        $sellerwarranty = "P3Y";
                        break;

                    default:
                        $sellerwarranty = "true";
                        break;
                }

                $skustr = str_replace('-', '', $obj->sku);
                $proddesc = str_replace($search, $replace, $obj->detail_desc);
                $prodname = str_replace($search, $replace, $obj->prod_name);
                // $modeltext = trim(str_replace($search, $replace, $obj->model));

                // yandex's own category
                if (!($yandex_cat = $this->get_cat_mapping_dao()->get_yandex_cat_by_subcatid($obj->sub_cat_id, $lang_id, $country_id)->ext_name)) {
                    $yandex_cat = "Все товары";  // all products
                }

                $offer_child = $dom->createElement("offer");
                $offer->appendChild($offer_child);
                $offer_attr_id = $dom->createAttribute("id");
                $offer_child->appendChild($offer_attr_id);
                $text = $dom->createTextNode($skustr);
                $offer_attr_id->appendChild($text);

                $offer_attr_type = $dom->createAttribute("type");
                $offer_child->appendChild($offer_attr_type);
                $text = $dom->createTextNode('vendor.model');
                $offer_attr_type->appendChild($text);

                $offer_attr_avail = $dom->createAttribute("available");
                $offer_child->appendChild($offer_attr_avail);
                $text = $dom->createTextNode('false');
                $offer_attr_avail->appendChild($text);

                $url = $dom->createElement("url");
                $offer_child->appendChild($url);
                $text = $dom->createTextNode($obj->prod_url);
                $url->appendChild($text);

                $price = $dom->createElement("price");
                $offer_child->appendChild($price);
                $text = $dom->createTextNode($obj->price);
                $price->appendChild($text);

                $currencyid = $dom->createElement("currencyId");
                $offer_child->appendChild($currencyid);
                $text = $dom->createTextNode($currid);
                $currencyid->appendChild($text);

                $categoryid = $dom->createElement("categoryId");
                $offer_child->appendChild($categoryid);
                $text = $dom->createTextNode($obj->cat_id);
                $categoryid->appendChild($text);

                $market_category = $dom->createElement("market_category");
                $offer_child->appendChild($market_category);
                $text = $dom->createTextNode($yandex_cat);
                $market_category->appendChild($text);

                $picture = $dom->createElement("picture");
                $offer_child->appendChild($picture);
                $text = $dom->createTextNode($obj->image_url);
                $picture->appendChild($text);

                $store = $dom->createElement("store");
                $offer_child->appendChild($store);
                $text = $dom->createTextNode('false');
                $store->appendChild($text);

                $pickup = $dom->createElement("pickup");
                $offer_child->appendChild($pickup);
                $text = $dom->createTextNode('false');
                $pickup->appendChild($text);

                $delivery = $dom->createElement("delivery");
                $offer_child->appendChild($delivery);
                $text = $dom->createTextNode('true');
                $delivery->appendChild($text);

                $local_del_cost = $dom->createElement("local_delivery_cost");
                $offer_child->appendChild($local_del_cost);
                $text = $dom->createTextNode('0');
                $local_del_cost->appendChild($text);

                # in type = "vendor.model", don't use product name
                // $name = $dom->createElement("name");
                // $offer_child->appendChild($name);
                // $text = $dom->createTextNode($prodname);
                // $name->appendChild($text);

                $vendor = $dom->createElement("vendor");
                $offer_child->appendChild($vendor);
                $text = $dom->createTextNode($obj->brand_name);
                $vendor->appendChild($text);

                $model = $dom->createElement("model");
                $offer_child->appendChild($model);
                $text = $dom->createTextNode($prodname);
                $model->appendChild($text);

                $description = $dom->createElement("description");
                $offer_child->appendChild($description);
                $text = $dom->createTextNode($proddesc);
                $description->appendChild($text);

                $sales_notes = $dom->createElement("sales_notes");
                $offer_child->appendChild($sales_notes);
                $text = $dom->createTextNode("Яндекс.Деньги, Qiwi, Visa, Mastercard, Paypal и др");
                $sales_notes->appendChild($text);

                $seller_warranty = $dom->createElement("seller_warranty");
                $offer_child->appendChild($seller_warranty);
                $text = $dom->createTextNode($sellerwarranty);
                $seller_warranty->appendChild($text);

                // echo "<pre>"; var_dump($obj); die();
            }
        }
        // echo $dom->saveXML(); die();
        $xml = $dom->saveXML();
        return $xml;
    }

    public function get_cat_srv()
    {
        return $this->cat_srv;
    }

    public function set_cat_srv(Base_service $srv)
    {
        $this->cat_srv = $srv;
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
        return APPPATH . 'data/yandex_product_feed_xml2csv.txt';
    }

    protected function get_ftp_name()
    {
        return 'YANDEX';
    }

    protected function get_sj_id()
    {
        return "YANDEX_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Yandex Product Feed Cron Time";
    }
}
