<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";
DEFINE("DHL_TRACKING_FILENAME", "520543_VB_Valuebasket_Ltd.txt");


class Dhl_shipment_tracking_feed_service extends Data_feed_service
{
    public function __construct(){
        parent::Data_feed_service();
        include_once(APPPATH."libraries/service/External_category_service.php");
        $this->set_ext_cat_srv(new External_category_service());

        include_once(APPPATH."libraries/service/So_shipment_service.php");
        $this->set_shipment_srv(new So_shipment_service());

        include_once(APPPATH."libraries/service/Context_config_service.php");
        $this->set_context_config_srv(new Context_config_service());

        $this->set_output_delimiter(chr(9));
        //$this->set_cat_hash_map();
    }

    public function gen_dhl_shipment_tracking_feed()
    {
        //1. prepare all data
        //2. generate the report and then put in our own server.
        //3. upload to dhl server.

        $feed_content = $this->gen_dhl_tracking_file();

            if($file_location = $this->save_feed($feed_content))
            {
                if(!empty($feed_content)){
                    $remote_file_name = "520543_VB_Valuebasket_Ltd_".date("YmdHis").".txt";
                    $this->ftp_feeds($file_location, "./SIE/".$remote_file_name, "DHL_TRACKING_FEED");
                }
            }
            else
            {
                mail("jerry.lim@eservicesgroup.com","dhl_tracking_feed","failed to upload $file_location to". DHL_TRACKING_FEED);
            }
    }

    public function save_feed($feed_content)
    {
        DEFINE('DHL_TRACKING_FEED', $this->get_context_config_srv()->value_of("dhl_tracking_feed"));
        DEFINE('DHL_TRACKING_FEED_HISTORY',DHL_TRACKING_FEED."/history");

        $file_name = DHL_TRACKING_FILENAME;
        $history_file_name = "dhl_tracking_no".date("Y-m-d_H:i:s").".txt";

        if(file_exists(DHL_TRACKING_FEED))
        {
            if(FALSE === file_put_contents(DHL_TRACKING_FEED."/".$file_name, $feed_content))
            {
                mail("jerry.lim@eservicesgroup.com","dhl_tracking_feed","failed to upload $file_name to". DHL_TRACKING_FEED);
                return false;
            }
            else
            {
                file_put_contents(DHL_TRACKING_FEED_HISTORY."/".$history_file_name, $feed_content);
                return DHL_TRACKING_FEED."/".$file_name;
            }
        }
        else
        {
            mail("jerry.lim@eservicesgroup.com","dhl_tracking_feed","Path No Exists: ". DHL_TRACKING_FEED);
            return false;
        }
    }

    public function gen_dhl_tracking_file()
    {
        //generate report and then upload to our own server
        //report is on order level, so filter out repeated order

        $order_list = array();
        $feed_content = "";
        if($obj_list = $this->get_shipment_srv()->get_dao()->gen_dhl_shipment_tracking_feed())
        {
            foreach($obj_list as $obj)
            {
                $so_no = $obj->get_so_no();

                //this in_array function is used to filter out repeated order
                //due to the Hs code
                if(in_array($so_no, $order_list))
                {
                    continue;
                }
                else
                {
                    $order_list[] = $so_no;

                    $per_order = array();
                    $tracking_no = $obj->get_tracking_no();

                    $delivery_address = $obj->get_delivery_address();

                    $address_fragments = explode("|", $delivery_address, 3);
                    $final_address = preg_replace('/\s+/', ' ',implode(" ", $address_fragments));

                    $delivery_city = $obj->get_delivery_city();
                    $city_fragments = explode("|", $delivery_city);
                    $final_city = preg_replace('/\s+/', ' ',implode(" ", $city_fragments));

                    $per_order['account_number'] = "520543";
                    $per_order['customer_ref'] = $tracking_no;
                    $per_order['consignee_name'] = mb_substr($obj->get_delivery_name(),0, 30, "UTF-8");
                    //$per_order['consignee_address_1'] = mb_substr($final_address,0, 50, "UTF-8");

                    $per_order['consignee_address_1'] = mb_substr($address_fragments[0],0, 50, "UTF-8");
                    $per_order['consignee_address_2'] = mb_substr($address_fragments[1],0, 50, "UTF-8");
                    $per_order['consignee_address_3'] = mb_substr($address_fragments[2],0, 50, "UTF-8");

                    $per_order['consignee_city'] = mb_substr($final_city,0, 20, "UTF-8");
                    $per_order['consignee_state'] = mb_substr($obj->get_delivery_state(),0, 20, "UTF-8");
                    $per_order['consignee_postal_code'] = mb_substr($obj->get_delivery_postcode(),0, 10, "UTF-8");
                    $per_order['consignee_phone'] = null;
                    $per_order['consignee_email'] = null;
                    $per_order['consignee_country_code'] = mb_substr($obj->get_delivery_country_id(),0, 2, "UTF-8");
                    $per_order['consignee_country_name'] = null;
                    $per_order['country_of_origin'] = "HK";
                    $per_order['generic_goods_description'] = mb_substr($obj->get_cc_desc(),0, 15, "UTF-8");

                    $per_order['total_declared_value'] = $amount =  $obj->get_amount();
                    $per_order['weight'] = null;
                    $per_order['future_use'] = null;
                    $per_order['item_quantity'] = null;
                    $per_order['item_code'] = null;
                    $per_order['item_description'] = null;
                    $per_order['item_value'] = null;
                    $per_order['additional_reference_id'] = $so_no = $obj->get_so_no();
                    $per_order['workshare_indicator'] = null;
                    $per_order['hs_code'] = null;
                    $per_order['customer_ref2'] = null;
                    $per_order['currency'] = $currency_id = $so_no = $obj->get_currency_id();
                    $per_order['dimensional_length'] = null;
                    $per_order['dimensional_width'] = null;
                    $per_order['dimensional_height'] = null;
                    $per_order['product_code'] = "PKW";
                    //$per_order['dimensional_height'] = null;
                    $per_order['material_id'] = null;
                    $per_order['shipper_name'] = null;
                    $per_order['incoterms'] = null;
                    $per_order['freight'] = null;
                    $per_order['insurance'] = null;
                    $per_order['recipient_tax_id'] = null;
                    $per_order['tracking'] = $tracking_no;
                    $per_order['item_description_export'] = null;
                    $per_order['recipient_id_type'] = null;

                    foreach($per_order as $k=>$v)
                    {
                        //remove all pipe character in the filed and remove heading and trailing white space.
                        $per_order[$k] = strtoupper(trim(preg_replace('/\|/', ' ', $v)));
                    }

                    //add pipe character at the end of each line.
                    //$feed_content .= implode("|", $per_order)."|";
                    $feed_content .= implode("|", $per_order);


                    //update the so_shipment to indicate that this shipment has already been included.
                    $sh_no = $obj->get_sh_no();

                    if($so_sh_vo = $this->get_shipment_srv()->get_dao()->get(array("sh_no"=>$sh_no)))
                    {
                        $so_sh_vo->set_courier_feed_sent(1);
                        $this->get_shipment_srv()->get_dao()->update($so_sh_vo);
                    }
                }
                $feed_content .= "\r\n";
            }
        }
        //return utf8_encode($feed_content);
        return $feed_content;
    }



    public function set_shipment_srv($val)
    {
        $this->shipment_srv = $val;
    }

    public function get_shipment_srv()
    {
        return $this->shipment_srv;
    }


    public function set_context_config_srv($val)
    {
        $this->context_config_srv = $val;
    }

    public function get_context_config_srv()
    {
        return $this->context_config_srv;
    }








    public function gen_data_feed($platform_id, $shopping_api = false, $where = array())
    {
        //add $shopping_api flag to return raw data for shopping api usage
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        $this->set_googlebase_platform_id($platform_id);

        $data_feed = $this->get_data_feed($shopping_api, $where);
        if($data_feed)
        {
            if(!$shopping_api)
            {
                foreach($data_feed as $id=>$data)
                {
                    $filename = 'googlebase_product_feed_' . $id . '_' . date('Ymdhis');
                    $fp = fopen(DATAPATH . 'feeds/googlebase/' . $id . '/' . $filename . '.txt', 'w');

                    if(fwrite($fp, $data))
                    {
                        header("Content-type: text/csv");
                        header("Cache-Control: no-store, no-cache");
                        header("Content-Disposition: attachment; filename=\"$filename\"");
                        echo $data;

                        $this->ftp_feeds(DATAPATH . 'feeds/googlebase/' . $id . '/' . $filename . '.txt', "/googlebasefeed_" . strtolower($id) . ".txt", $this->get_ftp_name($id));
                    }
                    else
                    {
                        $subject = "<DO NOT REPLY> Fails to create GoogleBase $id Product Feed File";
                        $message ="FILE: ".__FILE__."<br>
                                     LINE: ".__LINE__;
                        $this->error_handler($subject, $message);
                    }
                }
            }
            else
            {
                return $data_feed;
            }

        }
    }

    public function get_data_feed($shopping_api = FALSE, $where = array())
    {
        // common processing to be done here

        $country = substr($this->get_googlebase_platform_id(), -2);

        include_once(APPPATH . 'libraries/service/Affiliate_sku_platform_service.php');
        $this->affiliate_sku_platform_service = New Affiliate_sku_platform_service();

        $affiliate_id = $this->get_affiliate_id_prefix() . $country;
        $override = $this->affiliate_sku_platform_service->get_sku_feed_list($affiliate_id);

        $arr = $this->get_data_list($where);
        if (!$arr)
        {
            return;
        }

        $new_list = array();

        foreach ($arr as $row)
        {
            $res = $this->process_data_row($row);
            $add = true;
            $selected = "passed margin rules, so added";

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
                $new_list[] = $res;
        }

        if($new_list)
        {
            foreach ($new_list as $obj)
            {
                $rs[$obj->get_platform_country_id()][] = $obj;
            }

            if($shopping_api)
            {
                return $rs;
            }

            if($rs)
            {
                foreach($rs as $id=>$data_list)
                {
                    $content[$id] = $this->convert($data_list);
                }
            }
        }

        return $content;
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_googlebase_product_feed_dto($this->get_googlebase_platform_id(), $where);
        // $this->get_prod_srv()->get_googlebase_product_feed_dto($this->get_googlebase_platform_id(), $where);
         //var_dump($this->get_prod_srv()->get_dao()->db->last_query());die();
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data))
        {
            return NULL;
        }

        // append country code to sku to form google reference id
        $data->set_google_ref_id(implode('-', array(trim($data->get_platform_country_id()), trim($data->get_sku()))));

        // product type: Category > SubCategory
        $data->set_product_type(implode(' > ', array(trim($data->get_cat_name()), trim($data->get_sub_cat_name()))));

        // product link
        $product_url = $this->get_product_url($data);
        $data->set_product_url($product_url);

        // image link
        $image_url = $this->get_image_url($data);
        $data->set_image_url($image_url);

        // condition
        if($data->get_ex_demo() == 0)
        {
            $data->set_condition("new");
        }
        else
        {
            $data->set_condition("refurbished");
        }

        // availabilty
        $availabilty = $this->get_availabilty($data);
        $data->set_availability($availabilty);

        // price with currency
        $price_w_curr = $this->get_price_w_curr($data);
        $data->set_price_w_curr($price_w_curr);
        $data->set_sale_price($price_w_curr);

        // colour
        if($data->get_colour_id() == "NA")
        {
            $data->set_colour_name("");
            $data->set_item_group_id("");
        }
        else
        {
            $data->set_item_group_id($data->get_prod_grp_cd());
        }

        // shipping text
        $shipping_text = $this->get_shipping_text($data);
        $data->set_shipping($shipping_text);

        #2701 add htmlspecialchars and repleace tab

        $detail_desc = str_replace("\n", "---", $data->get_detail_desc());
        $detail_desc = str_replace("\t", " ", $detail_desc);
        //$data->set_detail_desc((str_replace("\n", "---", $data->get_detail_desc())));
        $data->set_detail_desc(htmlspecialchars($detail_desc));
        $data->set_prod_name(htmlspecialchars($data->get_prod_name()));

        return $data;
    }

    protected function get_url_extension($country_id)
    {   #SBF2701
        //return '.com';
        switch (strtoupper($country_id))
        {
            case 'FR' : $result = '.fr'; break;
            case 'ES' : $result = '.es'; break;
            case 'AU' : $result = '.com.au'; break;
            case 'IT' : $result = '.it'; break;
            case 'BE' : $result = '.be'; break;
            case 'SG' : $result = '.com.sg'; break;
            default : $result = '.com';
        }

        return $result;
    }

    protected function get_affiliate_code($country_id)
    {
        #SBF2701
        switch (strtoupper($country_id))
        {
            case 'FR' : $result = 'AF=GOOFR'; break;
            case 'GB' : $result = 'AF=GOOGB'; break;
            case 'AU' : $result = 'AF=GOOAU'; break;
            case 'ES' : $result = 'AF=GOOES'; break;
            case 'IT' : $result = 'AF=GOOIT'; break;
            case 'BE' : $result = 'AF=GOOBE'; break;
            case 'CH' : $result = 'AF=GOOCH'; break;
            case 'NZ' : $result = 'AF=GOONZ'; break;
            case 'SG' : $result = 'AF=GOOSG'; break;
            case 'MY' : $result = 'AF=GOOMY'; break;
            case 'IE' : $result = 'AF=GOOIE'; break;
            case 'MT' : $result = 'AF=GOOMT'; break;
            case 'PT' : $result = 'AF=GOOPT'; break;
            case 'FI' : $result = 'AF=GOOFI'; break;
            case 'PH' : $result = 'AF=GOOPH'; break;
            case 'RU' : $result = 'AF=GOORU'; break;
            case 'US' : $result = 'AF=GOOUS'; break;

            default : $result = '';
        }

        return $result;
    }

    protected function get_product_url($data)
    {   #SBF2701 replace double quot with '-'
        $prod_name = $data->get_prod_name();
        $search = array(" ", ".", "/", '"');
        $replace = array("-", "", "", "-");
        $prod_name = str_replace($search, $replace, $prod_name);
        $sku = $data->get_sku();
        $country_id = $data->get_platform_country_id();
        $language_id = $data->get_language_id();
        $af_code = $this->get_affiliate_code($country_id);
        $product_url = "http://www.valuebasket" . $this->get_url_extension($country_id) . "/".$language_id."_".$country_id."/".$prod_name."/mainproduct/view/".$sku.($af_code == '' ? '' : '?'.$af_code);

        return $product_url;
    }

    protected function get_image_url($data)
    {
        $country_id = strtolower($data->get_platform_country_id());
        define('IMG_PATH', "http://www.valuebasket" . $this->get_url_extension($country_id) . "/".$this->get_config_srv()->value_of("prod_img_path"));
        if(file_exists("images/product/".$data->get_sku().".".$data->get_image()))
        {
            return IMG_PATH.$data->get_sku().".".$data->get_image();
        }
        else
        {
            return IMG_PATH."imageunavailable.jpg";
        }
    }

    protected function get_price_w_curr($data)
    {
        $price_w_curr = implode(" ", array($data->get_platform_currency_id(), $data->get_price()));

        return $price_w_curr;
    }

    protected function get_shipping_text($data)
    {
        $shipping_text = $data->get_platform_country_id()."::Standard: 0.00 ".$data->get_platform_currency_id();

        return $shipping_text;
    }

    protected function get_availabilty($data)
    {
        $in_stock_text = "in stock";
        $preorder_text = "preorder";
        $out_of_stock_text = "out of stock";
        $arriving_text = "available for order";

        if ($data->get_website_status() == "I")
        {
            if(min($data->get_website_quantity(), $data->get_display_quantity()) <= 0)
            {
                return $out_of_stock_text;
            }
            return $in_stock_text;
        }
        else if ($data->get_website_status() == "P")
        {
            return $preorder_text;
        }
        else if ($data->get_website_status() == "A")
        {
            return $arriving_text;
        }
        else
        {
            return $out_of_stock_text;
        }
    }

    protected function set_cat_hash_map()
    {
        $ext_cat_list = $this->get_ext_cat_srv()->get_list(array("ext_party"=>"GOOGLEBASE", "status"=>1), array("limit"=>-1));
        if($ext_cat_list)
        {
            foreach($ext_cat_list as $ext_cat_obj)
            {
                $rs[$ext_cat_obj->get_country_id()][$ext_cat_obj->get_id()] = $ext_cat_obj->get_ext_name();
            }
        }

        $this->cat_hash_map = $rs;
    }

    public function get_cat_hash_map()
    {
        return $this->cat_hash_map;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/googlebase_product_feed_vo2xml.txt';
    }

    public function get_contact_email()
    {
        return 'nero@eservicesgroup.com';
    }

    protected function get_ftp_name($country='GB')
    {
        #SBF2701
        //return 'GOOGLEBASE';
        switch (strtoupper($country))
        {
            case 'FR' : return 'GOOGLEBASE_FR'; break;
            case 'AU' : return 'GOOGLEBASE_AU'; break;
            case 'ES' : return 'GOOGLEBASE_ES'; break;
            case 'IT' : return 'GOOGLEBASE_IT'; break;
            case 'BE' : return 'GOOGLEBASE_BE'; break;
            default : return 'GOOGLEBASE';
        }
    }

    protected function get_sj_id()
    {
        return "GOOGLEBASE_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "GOOGLEBASE Product Feed Cron Time";
    }

    public function get_ext_cat_srv()
    {
        return $this->ext_cat_srv;
    }

    public function set_ext_cat_srv(Base_service $srv)
    {
        $this->ext_cat_srv = $srv;
    }

    public function get_googlebase_platform_id()
    {
        return $this->googlebase_platform_id;
    }

    public function set_googlebase_platform_id($val)
    {
        $this->googlebase_platform_id = $val;
    }

    protected function get_affiliate_id_prefix()
    {
        // refer to affiliate table for more details
        // SOME IDs have country suffix
        // SOME don't have.. so take note when checking
        return "GOO";
    }
}

/* End of file googlebase_product_feed_service.php */
/* Location: ./system/application/libraries/service/Googlebase_product_feed_service.php */