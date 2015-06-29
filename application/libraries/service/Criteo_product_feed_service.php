<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Criteo_product_feed_service extends Data_feed_service
{
    protected $id = "Criteo Product Feed";

    public function __construct()
    {
        parent::Data_feed_service();
        include_once APPPATH . "libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH . "libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());
        include_once APPPATH . "libraries/service/Price_service.php";
        $this->set_price_srv(new Price_service());

        $this->set_output_delimiter(',');
    }

    public function set_price_srv(Base_service $srv)
    {
        $this->price_srv = $srv;
    }

    public function set_product_srv(Base_service $srv)
    {
        $this->product_srv = $srv;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        if ($filename = $this->get_data_feed()) {
            if (!copy(DATAPATH . 'feeds/criteo/' . $filename, DATAPATH . 'feeds/criteo/ftp/criteo_product_feed.xml')) {
                $this->log_error_message("File path not valid");
            }
            //$this->ftp_feeds(DATAPATH . 'feeds/criteo/' . $filename, "/vb_data_feed.xml", $this->get_ftp_name());
        }
    }

    public function get_data_feed()
    {
        $filename = $this->get_data_list();

        return $filename;
    }

    protected function get_data_list($where = array(), $option = array())
    {
        $filename = 'vb_data_feed_' . date('Ymdhis') . '.xml';
        $fp = fopen(DATAPATH . 'feeds/criteo/' . $filename, 'w');

        set_time_limit(300);
        $num_rows = $this->get_prod_srv()->get_criteo_product_feed_product_feed_dto(array(), array("num_rows" => 1));
        $offset = 0;
        $arr = array();

        if ($num_rows > 0) {
            $total = ceil($num_rows / 5000);
        }
        $content = '';
        $content .= '<?xml version="1.0"?>' . "\n";
        $content .= '<products>' . "\n";

        for ($i = 0; $i < $total; $i++) {
            if ($arr = $this->get_prod_srv()->get_criteo_product_feed_product_feed_dto(array(), $option = array("orderby" => "pr.sku", "offset" => $i * 5000, "limit" => 5000))) {
                $data = array();
                foreach ($arr as $product_info_dto) {
                    $data = $this->process_data_row($product_info_dto);
                    $content .= $this->gen_xml($data);

                    $data = array();
                    if ($content) {
                        if (fwrite($fp, $content)) {
                            $content = "";
                        } else {
                            $subject = "<DO NOT REPLY> Fails to create CRITEO Product Feed File";
                            $message = "FILE: " . __FILE__ . "<br>
                                         LINE: " . __LINE__;
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

    public function process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        if ($data instanceof Criteo_product_feed_dto) {
            // product image
            $website_domain = $this->get_config_srv()->value_of("website_domain");
            $default_image_path = $this->get_config_srv()->value_of("prod_img_path");
            $image_file_large = $data->get_sku() . "_l." . $data->get_image();
            $image_file_small = $data->get_sku() . "_m." . $data->get_image();

            if (!$data->get_image() || !file_exists($default_image_path . $image_file_large)) {
                $data->set_image_url_large($website_domain . $default_image_path . "imageunavailable_l.jpg");
            } else {
                $data->set_image_url_large($website_domain . $default_image_path . $image_file_large);
            }

            if (!$data->get_image() || !file_exists($default_image_path . $image_file_small)) {
                $data->set_image_url_small($website_domain . $default_image_path . "imageunavailable_m.jpg");
            } else {
                $data->set_image_url_small($website_domain . $default_image_path . $image_file_small);
            }

            // product url
            $prod_name = str_replace(array(" ", "/", "."), "-", $data->get_prod_name());
            $prod_url = $website_domain . "GB/" . $prod_name . "/mainproduct/view/" . $data->get_sku();
            $data->set_prod_url($prod_url);

            // discount percentage 12% as defined by BD
            $discount = 12;
            $data->set_discount($discount);

            // price
            $price = $data->get_price();
            $rrp = $price / (1 - $discount / 100);
            $rrp = number_format($rrp, 2, ".", "");
            $data->set_rrp($rrp);

            // availability
            $data->set_availability("1");
        }

        return $data;
    }

    public function gen_xml($data = NULL)
    {
        $xml_content = '';

        $xml_content .= '<product id="' . $data->get_sku() . '">' . "\n";
        $xml_content .= '<name><![CDATA[' . strip_invalid_xml(htmlspecialchars(trim($data->get_prod_name()))) . ']]></name>' . "\n";
        $xml_content .= '<producturl><![CDATA[' . $data->get_prod_url() . ']]></producturl>' . "\n";
        $xml_content .= '<smallimage><![CDATA[' . $data->get_image_url_small() . ']]></smallimage>' . "\n";
        $xml_content .= '<categoryid1><![CDATA[' . strip_invalid_xml(htmlspecialchars(trim($data->get_cat_name()))) . ']]></categoryid1>' . "\n";
        $xml_content .= '<description><![CDATA[' . strip_invalid_xml(htmlspecialchars(trim($data->get_short_desc()))) . ']]></description>' . "\n";
        $xml_content .= '<instock>' . $data->get_availability() . '</instock>' . "\n";
        $xml_content .= '<price>' . $data->get_price() . '</price>' . "\n";
        $xml_content .= '<bigimage><![CDATA[' . $data->get_image_url_large() . ']]></bigimage>' . "\n";
        $xml_content .= '<retailprice>' . $data->get_rrp() . '</retailprice>' . "\n";
        $xml_content .= '<discount>' . $data->get_discount() . '</discount>' . "\n";
        $xml_content .= '<categoryid2><![CDATA[' . strip_invalid_xml(htmlspecialchars(trim($data->get_sub_cat_name()))) . ']]></categoryid2>' . "\n";
        $xml_content .= '</product>' . "\n";

        return $xml_content;
    }

    public function get_contact_email()
    {
        return 'thomas@eservicesgroup.net';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/criteo_product_feed_xml2csv.txt';
    }

    protected function get_ftp_name()
    {
        return 'CRITEO';
    }

    protected function get_sj_id()
    {
        return "CRITEO_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "CRITEO Product Feed Cron Time";
    }
}

/* End of file criteo_product_feed_service.php */
/* Location: ./system/application/libraries/service/Criteo_product_feed_service.php */