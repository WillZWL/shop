<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Standard_fr_product_feed_service extends Data_feed_service
{
    protected $id = "Standard FR Product Feed";
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

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();
        if($data_feed)
        {
            $this->del_dir(DATAPATH . 'feeds/standard_fr');
            $filename = 'valuebasket_standard_fr_product_feed.txt';
            $fp = fopen(DATAPATH . 'feeds/standard_fr/' . $filename, 'w');

            if(fwrite($fp, $data_feed))
            {
                if(!copy(DATAPATH . 'feeds/standard_fr/' . $filename, DATAPATH . 'feeds/standard_fr/ftp/valuebasket_fr_product_feed.txt'))
                {
                    $subject = "<DO NOT REPLY> Fails to create Standard FR Product Feed File";
                    $message ="FILE: ".__FILE__."<br>
                                 LINE: ".__LINE__;
                    $this->error_handler($subject, $message);
                }
            }
            else
            {
                $subject = "<DO NOT REPLY> Fails to create Standard FR Product Feed File";
                $message ="FILE: ".__FILE__."<br>
                             LINE: ".__LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_standard_fr_product_feed_dto(array(), array('limit'=>-1));
    }

    public function get_data_feed($first_line_headling = TRUE)
    {
        $list = $this->get_data_list();

        if (!$list)
        {
            return;
        }

        $new_list = array();
        foreach ($list as $row)
        {
            if($res = $this->process_data_row($row))
            {
                if ((($res->get_price() >= 200) && ($res->get_price() < 400) && ($res->get_margin() >= 10)) ||
                    (($res->get_price() >= 400) && ($res->get_price() < 800) && ($res->get_margin() >= 8)) ||
                    (($res->get_price() >= 800) && ($res->get_price() < 1200) && ($res->get_margin() >= 7)) ||
                    (($res->get_price() >= 1200) && ($res->get_margin() >= 6)))
                {
                    $new_list[] = $res;
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

        $this->get_price_srv()->calculate_profit($data);

        $org_price = $this->get_price_srv()->calc_website_product_rrp($data->get_price(), $data->get_fixed_rrp(), $data->get_rrp_factor());
        $data->set_org_price($org_price);

        return $data;
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/standard_fr_product_feed_xml2csv.txt';
    }

    public function get_contact_email()
    {
        return 'shing-alert@eservicesgroup.com';
    }

    protected function get_ftp_name()
    {
        return 'STANDARD_FR';
    }

    protected function get_sj_id()
    {
        return "STANDARD_FR_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Standard FR Product Feed Cron Time";
    }
}

/* End of file Standard_fr_product_feed_service.php */
/* Location: ./system/application/libraries/service/Standard_fr_product_feed_service.php */