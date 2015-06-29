<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Shopping_com_product_feed_service extends Data_feed_service
{
    protected $id = "Shopping.com Product Feed";

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

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();
        if($data_feed)
        {
            $filename = 'shopping_com_product_feed_' . date('Ymdhis') . '.txt';
            $fp = fopen(DATAPATH . 'feeds/shopping_com/' . $filename, 'w');

            if(fwrite($fp, $data_feed))
            {
                $this->ftp_feeds(DATAPATH . 'feeds/shopping_com/' . $filename, "/ProductFeed.txt", $this->get_ftp_name());
            }
            else
            {
                $subject = "<DO NOT REPLY> Fails to create Shopping.com Product Feed File";
                $message ="FILE: ".__FILE__."<br>
                             LINE: ".__LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_shopping_com_product_feed_dto();
    }

    public function get_data_feed($first_line_headling = TRUE)
    {
        $arr = $this->get_data_list();

        if (!$arr)
        {
            return;
        }

        $new_list = array();

        foreach ($arr as $row)
        {
            $price_srv = $this->get_price_srv();
            if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku"=>$row->get_sku(), "platform_id"=>"WEBGB"), array("limit"=>1)))
            {
                $p_svc = $price_srv->get_price_service_from_dto($prod_obj);
                $price_srv->calc_freight_cost($prod_obj, $p_svc, $prod_obj->get_platform_currency_id());
                $price_srv->calc_cost($prod_obj);
                $price_srv->calculate_profit($prod_obj);
                if($prod_obj->get_profit() > 30)
                {
                    $new_list[] = $this->process_data_row($row);
                }
            }
        }
        $content = $this->convert($new_list, $first_line_headling);

        return $content;
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data))
        {
            return NULL;
        }

        if(!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path").basename($data->get_image_url())))
        {
            $data->set_image_url("http://www.valuebasket.com/images/product/imageunavailable.jpg");
        }

        return $data;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/shopping_com_product_feed_xml2csv.txt';
    }

    public function get_contact_email()
    {
        return 'steven@eservicesgroup.net';
    }

    protected function get_ftp_name()
    {
        return 'SHOPPING_COM';
    }

    protected function get_sj_id()
    {
        return "SHOPPING_COM_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Shopping.com Product Feed Cron Time";
    }
}

/* End of file shopping_com_product_feed_service.php */
/* Location: ./system/application/libraries/service/Shopping_com_product_feed_service.php */