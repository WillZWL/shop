<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Sli_product_feed_service extends Data_feed_service
{
    protected $id = "SLI Product Feed";

    public function __construct(){
        parent::Data_feed_service();
        include_once APPPATH."libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH."libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());

        $this->set_output_delimiter(',');
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function set_product_srv(Base_service $srv)
    {
        $this->product_srv = $srv;
    }

    public function gen_data_feed($where, $option, $lang_id)
    {
        $this->load_language($lang_id);
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        if($filename = $this->get_data_feed($where, $option))
        {
            $this->ftp_feeds(DATAPATH . 'feeds/sli/' .$where["pbv.language_id"]."/". $filename, "/vb_data_feed_".$where["pbv.language_id"].".xml", $this->get_ftp_name());
        }
    }

    protected function get_data_list($where = array(), $option = array())
    {
        $filename = 'vb_data_feed_'.date('Ymdhis').'_'.$where["pbv.language_id"].'.xml';
        $fp = fopen(DATAPATH . 'feeds/sli/' .$where["pbv.language_id"]."/". $filename, 'w');

        set_time_limit(300);
        $num_rows = $this->get_prod_srv()->get_sli_product_feed_product_info_dto(array(), array("num_rows"=>1));
        $offset = 0;
        $arr = array();

        if($num_rows > 0)
        {
            $total = ceil($num_rows/5000);
        }
        $content = '';
        $content .= '<?xml version="1.0"?>' . "\n";
        $content .= '<products>' . "\n";

        for($i=0; $i < $total; $i++)
        {
            if($arr = $this->get_prod_srv()->get_sli_product_feed_product_info_dto($where, $option = array("orderby"=>"pr.sku", "offset"=>$i*5000, "limit"=>5000)))
            {
                foreach ($arr as $product_info_dto)
                {
                    $prod_list = $this->get_prod_srv()->get_sli_product_feed_price_info_dto(array_merge($where, array("p.sku"=>$product_info_dto->get_sku())), array("limit"=>-1));
                    $data[$product_info_dto->get_sku()]['product_info_dto'] = $product_info_dto;
                    foreach($prod_list as $price_info_dto)
                    {
                        $arr = array();
                        $product_info_dto = $this->process_data_row($product_info_dto);
                        $price_info_dto = $this->process_data_row($price_info_dto);
                        $data[$price_info_dto->get_sku()]['price_info_dto'][] = $price_info_dto;
                    }

                    $content .= $this->gen_xml($data);
                    $data = array();
                    if($content)
                    {
                        if(fwrite($fp, $content))
                        {
                            $content = "";
                        }
                        else
                        {
                            $subject = "<DO NOT REPLY> Fails to create SLI Product Feed File";
                            $message ="FILE: ".__FILE__."<br>
                                         LINE: ".__LINE__;
                            $this->error_handler($subject, $message);
                        }
                    }
                }
            }
        }
        $content = '</products>' . "\n";
        fwrite($fp, $content);

        return $filename;
    }

    public function get_data_feed($where, $option)
    {
        $filename = $this->get_data_list($where, $option);

        return $filename;
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data))
        {
            return NULL;
        }

        if($data instanceof Sli_product_feed_product_info_dto)
        {
            if(!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path").basename($data->get_image_url())))
            {
                $data->set_image_url("http://www.valuebasket.com/images/product/imageunavailable.jpg");
            }
        }

        if($data instanceof Sli_product_feed_price_info_dto)
        {
            if($data->get_price() > 0)
            {
                $rrp = $this->get_price_srv()->calc_website_product_rrp($data->get_price(), $data->get_fixed_rrp(), $data->get_rrp_factor());
            }
            else
            {
                $rrp = 0;
            }
            $data->set_rrp($rrp);
        }

        return $data;
    }

    public function gen_xml($data_list = NULL)
    {
        $lang = $this->lang;
        $website_status = array("I"=>"In Stock", "O"=>"Out of Stock", "P"=>"Pre-Order", "A"=>"Arriving");

        $xml_content = '';

        $prev_sku = "";
        foreach($data_list as $data)
        {
            $xml_content .= '<product>' . "\n";
            $xml_content .= '<sku>' . $data['product_info_dto']->get_sku() .  '</sku>' . "\n";
            $xml_content .= '<product_name>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_prod_name()))) .  '</product_name>' . "\n";
            $xml_content .= '<image_url>' . $data['product_info_dto']->get_image_url() .  '</image_url>' . "\n";
            $xml_content .= '<cat_name>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_cat_name()))) .  '</cat_name>' . "\n";
            $xml_content .= '<sub_cat_name>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_sub_cat_name()))) .  '</sub_cat_name>' . "\n";
            $xml_content .= '<brand_name>'. strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_brand_name()))) .  '</brand_name>' . "\n";
            $xml_content .= '<short_desc>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_short_desc()))) .  '</short_desc>' . "\n";
            $xml_content .= '<detail_desc>' . strip_invalid_xml(htmlspecialchars(trim($data['product_info_dto']->get_detail_desc()))) .  '</detail_desc>' . "\n";
            $xml_content .= '<mpn>' . $data['product_info_dto']->get_mpn() .  '</mpn>' . "\n";
            $xml_content .= '<ean>' . $data['product_info_dto']->get_ean() .  '</ean>' . "\n";
            $xml_content .= '<upc>' . $data['product_info_dto']->get_upc() .  '</upc>' . "\n";
            $xml_content .= '<is_bundle>' . ($data['product_info_dto']->get_inbundle()?"Y":"N") . '</is_bundle>' . "\n";

            $xml_content .= '<platforms>' . "\n";
            foreach($data['price_info_dto'] as $price_info_dto)
            {
                if($price_info_dto->get_website_status() == 'O')
                {
                    $quantity = 0;
                }
                else
                {
                    $quantity = $price_info_dto->get_quantity();
                }

                $xml_content .= '<platform>' . "\n";
                $xml_content .= '<country_id>' . $price_info_dto->get_country_id() .  '</country_id>' . "\n";
                $xml_content .= '<product_url><![CDATA[' . $price_info_dto->get_product_url() .  ']]></product_url>' . "\n";
                $xml_content .= '<currency>' . $price_info_dto->get_currency_id() .  '</currency>' . "\n";
                $xml_content .= '<price>' . $price_info_dto->get_price() .  '</price>' . "\n";
                $xml_content .= '<rrp>' . $price_info_dto->get_rrp() .  '</rrp>' . "\n";

                if ($this->get_price_srv()->is_display_saving_message($price_info_dto->get_country_id()) == 'T')
                {
                    $xml_content .= '<discount_text>' . $lang['save'] . number_format(($price_info_dto->get_rrp() == 0 ? 0 : ($price_info_dto->get_rrp() - $price_info_dto->get_price()) / $price_info_dto->get_rrp() * 100), 0) . '%</discount_text>' . "\n";
                }

                $xml_content .= '<website_status>' . $website_status[$price_info_dto->get_website_status()] .  '</website_status>' . "\n";
                $xml_content .= '<quantity>' . $quantity .  '</quantity>' . "\n";
                $xml_content .= '</platform>' . "\n";
            }
            $xml_content .= '</platforms>' . "\n";
            $xml_content .= '</product>' . "\n";
        }

        return $xml_content;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/sli_product_feed_xml2csv.txt';
    }

    public function get_contact_email()
    {
        return 'thomas@eservicesgroup.net';
    }

    protected function get_ftp_name()
    {
        return 'SLI';
    }

    protected function get_sj_id()
    {
        return "SLI_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "SLI Product Feed Cron Time";
    }
}
