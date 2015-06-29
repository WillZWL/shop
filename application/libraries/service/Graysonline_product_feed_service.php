<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Data_feed_service.php";

class Graysonline_product_feed_service extends Data_feed_service
{
    protected $id = "Graysonline Product Feed";

    public function __construct()
    {
        parent::Data_feed_service();
        include_once APPPATH . "libraries/service/Price_website_service.php";
        $this->set_price_srv(new Price_website_service());
        include_once APPPATH . "libraries/service/Product_service.php";
        $this->set_product_srv(new Product_service());

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

    public function gen_data_feed()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();
        if ($data_feed) {
            $filename = 'graysonline_product_feed_' . date('Ymdhis') . '.csv';
            $fp = fopen(DATAPATH . 'feeds/graysonline/' . $filename, 'w');

            if (fwrite($fp, $data_feed)) {
                //$this->ftp_feeds(DATAPATH . 'feeds/graysonline/' . $filename, "/ProductFeed.csv", $this->get_ftp_name());
                //$this->attach_file(DATAPATH . 'feeds/graysonline/' . $filename);
            } else {
                $subject = "<DO NOT REPLY> Fails to create Graysonline Product Feed File";
                $message = "FILE: " . __FILE__ . "<br>
                             LINE: " . __LINE__;
                $this->error_handler($subject, $message);
            }
        }
    }

    public function get_data_feed($first_line_headling = TRUE)
    {
        $arr = $this->get_data_list();

        if (!$arr) {
            return;
        }

        $new_list = array();

        foreach ($arr as $row) {
            if ($res = $this->process_data_row($row)) {
                $new_list[] = $res;
            }
        }
        $content = $this->convert($new_list, $first_line_headling);

        return $content;
    }

    protected function get_data_list($where = array(), $option = array())
    {
        return $this->get_prod_srv()->get_graysonline_product_feed_dto(array(), array("limit" => -1));
    }

    public function process_data_row($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }
        $price_srv = $this->get_price_srv();
        if ($prod_obj = $this->get_product_srv()->get_dao()->get_product_overview(array("sku" => $data->get_sku(), "platform_id" => "WEBAU"), array("limit" => 1))) {
            $price_srv->calc_logistic_cost($prod_obj);
            $price_srv->calculate_profit($prod_obj);
        }

        $supplier_cost = $prod_obj->get_supplier_cost() * 1;
        $website_price = $data->get_price() * 1;
        $logistic_cost = $prod_obj->get_logistic_cost() * 1;
        $payment_charge = $prod_obj->get_payment_charge() * 1;

        //#2387 Product Feed: 3% deduction on profit margin for GraysOnline
        //skip sub-category (accessorise) because they are cheap and low profit margin already

        $sub_cat_id = $data->get_sub_cat_id();

        /*
        $skip_dub_category = array(
        'Camera Accessories',
        'iPad Accessories',
        'iPhone Accessories',
        'Lens Holders',
        'Mac Accessories',
        'Mobile Phones Accessories',
        'Tablets Accessories',
        'WaterProof Cases',
        'Lens Accessories',
        'Tripods',
        'Headphone Accessories',
        'Gadgets & Toys',
        'Flash Guns',
        'Storage Device',
        'Flash Memories',
        'AV Accessories',
        'Bluetooth Headsets',
        'Camera Bags',
        'Controllers',
        'Extended Warranty',
        'External Battery Pack',
        'Filters',
        'Gadgets',
        'Lightning Accessories',
        'Power Accessories',
        'Keyboards'
        );*/

        $skip_sub_category_id = array(16, 17, 18, 19, 20, 21, 28, 29, 30, 34, 40, 41, 49, 51, 52, 55, 60, 61, 75, 248, 376, 393, 419, 422, 472, 491, 503, 538, 567);

        if (in_array($sub_cat_id, $skip_sub_category_id)) {
            $cost = $website_price - $logistic_cost - $payment_charge;
        } else {
            $cost = number_format(($website_price - $logistic_cost - $payment_charge) * (1 - 0.03), 2, '.', '');
        }

        /*
        if($cost > 1000)
        {
            return false;
        }
        */

        $data->set_supplier_cost($supplier_cost);
        $data->set_suggested_selling_price(ceil($website_price * 1.02));
        $data->set_cost($cost);

        $logistic_cost = $prod_obj->get_logistic_cost();
        $data->set_shipping(number_format($logistic_cost * 1.1, 2, ".", ""));

        $supplier_status = $data->get_supplier_status();
        $lead_day = $data->get_lead_day();
        $last_week_updated = $data->get_last_week_updated();

        switch ($supplier_status) {
            case "A": // readily available
                if ($lead_day <= 1) {
                    $sourcing_status = "In Stock";
                } elseif ($lead_day <= 4) {
                    $sourcing_status = "Low Stock";
                } else {
                    $sourcing_status = "Back Order";
                }
                break;

            case "C": // stock constrain
                if ($last_week_updated) {
                    $sourcing_status = "Stock coming 1-2 Week";
                } else {
                    $sourcing_status = "Out of Stock";
                }
                break;

            case "O": // out of stock
                $sourcing_status = "Out of Stock";
                break;

            case "D": // discontinued
                $sourcing_status = "Out of Stock";
                break;

            default:
                $sourcing_status = "Out Of Stock";
        }
        $data->set_sourcing_status($sourcing_status);

        $search = array(chr(10), chr(13));
        $replace = array(" ", " ");
        $detail_desc = str_replace($search, $replace, $data->get_detail_desc());
        $detail_desc = trim($detail_desc);
        $data->set_detail_desc($detail_desc);

        $specification = str_replace($search, $replace, $data->get_specification());
        $specification = trim($specification);
        $data->set_specification($specification);

        if (!$data->get_image_url() || !file_exists($this->get_config_srv()->value_of("prod_img_path") . basename($data->get_image_url()))) {
            $data->set_image_url("http://www.valuebasket.com/images/product/imageunavailable.jpg");
        }

        return $data;
    }

    public function get_price_srv()
    {
        return $this->price_srv;
    }

    public function get_product_srv()
    {
        return $this->product_srv;
    }

    public function download_csv()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));

        $data_feed = $this->get_data_feed();
        if ($data_feed) {
            $filename = 'graysonline_product_feed_' . date('Ymdhis') . '.csv';
            $fp = fopen(DATAPATH . 'feeds/graysonline/' . $filename, 'w');
            if (fwrite($fp, $data_feed)) {
                header("Content-Type: text/csv");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                header('Expires: ' . gmdate('D, d M Y H:i:s', gmmktime() - 3600) . ' GMT');
                header("Content-Length: " . filesize(DATAPATH . 'feeds/graysonline/' . $filename));
                $fp = fopen(DATAPATH . 'feeds/graysonline/' . $filename, "r");
                fpassthru($fp);
            }
        }
    }

    public function attach_file($file)
    {
        //add From: header
        $headers = "From: itsupport@eservicesgroup.net\r\n";

        //specify MIME version 1.0
        $headers .= "MIME-Version: 1.0\r\n";

        //unique boundary
        $boundary = uniqid("HTMLDEMO");

        //tell e-mail client this e-mail contains//alternate versions
        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

        $fp = @fopen($file, "rb");
        $data = @fread($fp, filesize($file));
        $data = chunk_split(base64_encode($data));
        @fclose($fp);

        //HTML version of message
        $body .= "--$boundary\r\n" .
            "Content-Type: octet-stream; name= \"graysonline_product_feed.csv\" charset=ISO-8859-1\r\n" .
            "Content-Description: " . basename($file) . "\n" .
            "Content-Disposition: attachment;\n" . " filename=\"" . basename($file) . "\"; size=" . filesize($file) . ";\n" .
            "Content-Transfer-Encoding: base64\r\n\r\n" . $data . "\r\n";

        //send message
        mail("steven@eservicesgroup.net", "[VB] Graysonline Product Feed", $body, $headers);
    }

    public function get_contact_email()
    {
        return 'shing-alert@eservicesgroup.com';
    }

    protected function get_default_vo2xml_mapping()
    {
        return '';
    }

    protected function get_default_xml2csv_mapping()
    {
        return APPPATH . 'data/graysonline_product_feed_xml2csv.txt';
    }

    protected function get_ftp_name()
    {
        return 'GRAYSONLINE';
    }

    protected function get_sj_id()
    {
        return "GRAYSONLINE_PRODUCT_FEED";
    }

    protected function get_sj_name()
    {
        return "Graysonline Product Feed Cron Time";
    }
}

/* End of file graysonline_product_feed_service.php */
/* Location: ./system/application/libraries/service/Graysonline_product_feed_service.php */