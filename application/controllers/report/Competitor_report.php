<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");

class Competitor_report extends MY_Controller
{

    public $overview_path;
    public $default_country_id;

    //must set to public for view
    private $app_id = "RPT0045";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->report_path = 'report/competitor_report';
        $this->load->model($this->report_path . '_model', 'competitor_report_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator'));

        $this->load->library('service/affiliate_sku_platform_service');
        $this->load->library('service/sku_mapping_service');
        $this->load->library('service/price_service');
        $this->load->library('service/competitor_service');
        $this->load->library('service/competitor_map_service');
    }


    public function index($country_id = "", $nodata = 0)
    {
        $sub_app_id = $this->_get_app_id() . "00";

        if ($nodata) {
            echo "<script>alert('No data available for your selection!');</script>";
        }

        if ($country_id) {
            $data["country_id"] = $country_id;
            $data['all_competitors_list'] = $this->competitor_service->get_list(array("country_id" => $country_id));
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $data["notice"] = notice($lang);
        $data["country_list"] = $this->competitor_report_model->get_sell_country_list();

        $this->load->view('report/competitor_report', $data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function query()
    {
        if ($this->input->post("is_query")) {
            if ($this->input->post("country_id")) {
                $this->country_id = $this->input->post("country_id");
                $where["c.country_id"] = $this->country_id;
                $this->platform_id = "WEB" . $this->input->post("country_id");
                $where["pr.platform_id"] = $this->platform_id;
            }

            if ($this->input->post("competitor_id")) {
                $competitor_id = $this->input->post("competitor_id");
                $where["c.id"] = $competitor_id;
            }

            if ($this->input->post("cat_id")) {
                $cat_id = $this->input->post("cat_id");
                $where["p.cat_id"] = $this->input->post("cat_id");
            }

            if ($this->input->post("sub_cat_id")) {
                $sub_cat_id = $this->input->post("sub_cat_id");
                $where["p.sub_cat_id"] = $this->input->post("sub_cat_id");
            }

            if ($this->input->post("brand_id")) {
                $brand_id = $this->input->post("brand_id");
                $where["p.brand_id"] = $this->input->post("brand_id");
            }

            if ($this->input->post("price_listing_status")) {
                $price_listing_status = $this->input->post("price_listing_status");
                $where["pr.listing_status"] = $this->input->post("price_listing_status");
            }

            if ($data = $this->competitor_map_service->get_dao()->get_competitor_rpt_data($where, $competitor_id)) {
                $this->generate_report($data, $competitor_id);
            } else {
                header("Location: " . base_url() . "report/competitor_report/index/{$this->country_id}/1");
            }
        }
    }

    private function generate_report($data, $competitor_id = "")
    {
        $country_id = $this->country_id;
        $data_content = array();

        if ($competitor_id) {
            # if only one competitor chosen
            foreach ($data as $data_obj) {
                $competitor_name = $data_obj->competitor_name;
                $platform_id = $data_obj->platform_id;
                $ext_sku = $data_obj->ext_sku;
                $sku = $data_obj->sku;
                $ext_name = $data_obj->note_1;  #merchant name if competitor_name is actually a price comparison site

                $name = $data_obj->name;
                $vb_price = number_format($data_obj->price, '2', '.', '');
                $profit_margin = $this->get_margin($this->platform_id, $sku, $vb_price);
                $profit_margin = number_format($profit_margin, '2', '.', '');
                $competitor_price = number_format($data_obj->competitor_price, '2', '.', '');
                $stock_status = $data_obj->note_2;
                $status = $this->get_stock_status_name($stock_status);

                #sbf 445 - add more info
                $sourcefile_timestamp = $data_obj->sourcefile_timestamp;    # timestamp of spider crawling file uploaded on ftp
                $auto_price = $data_obj->auto_price;
                $auto_price_type = $this->get_auto_price_type($auto_price);


                $data_content[] = array($platform_id, $ext_sku, $sku, $name, $vb_price, $profit_margin, $competitor_price, $ext_name, $status, $auto_price_type, $sourcefile_timestamp);
            }

            $headers = array('platform_id', 'master_sku', 'sku', 'product_name', 'vb_selling_price', 'profit_margin(%)', 'competitor_price', 'ext_competitor_name', 'stock_status', 'Auto price type', 'Spider\'s last upload time');
            // $headers = array_merge($headers, (array)$competitor_name);
            array_unshift($data_content, $headers);
        } else {
            if ($competitor_list = $this->competitor_service->get_list(array("country_id" => $country_id, "status" => 1))) {
                # get a list of all active competitors in the country
                foreach ($competitor_list as $competitor_obj) {
                    # to append new header with current competitor name
                    $competitors = array_merge((array)$competitors, (array)$competitor_obj->get_competitor_name());
                }

                foreach ($data as $data_obj) {
                    $platform_id = $data_obj->platform_id;
                    $ext_sku = $data_obj->ext_sku;
                    $sku = $data_obj->sku;
                    // $listing_status = $data_obj->listing_status;
                    $name = $data_obj->name;
                    $vb_price = number_format($data_obj->price, '2', '.', '');
                    $profit_margin = $this->get_margin($this->platform_id, $sku, $vb_price);
                    $profit_margin = number_format($profit_margin, '2', '.', '');

                    $content = array($platform_id, $ext_sku, $sku, $name, $vb_price, $profit_margin);

                    # explode the data from group_concat
                    $competitor_arr = explode(',', $data_obj->competitor_name);
                    $competitor_price_arr = explode(',', $data_obj->competitor_price);

                    foreach ($competitors as $competitor) {
                        /*
                            determine if current row has info for the right competitor column
                            if in right column, insert competitor price, else, leave blank

                                    | comp_1 | comp_2 | comp_3 | comp_4 |
                            sku1234 |        |  79.90 |        |  80.99 |
                        */

                        $match = FALSE;

                        foreach ($competitor_arr as $key => $competitor_name) {
                            if ($competitor == $competitor_name) {
                                $match = TRUE;
                                $competitor_price = number_format($competitor_price_arr[$key], '2', '.', '');
                                $content = array_merge($content, (array)$competitor_price);
                            }
                        }

                        if ($match === FALSE) {
                            $competitor_price = "";
                            $content = array_merge($content, (array)$competitor_price);
                        }
                    }

                    $data_content[] = $content;
                }

                $headers = array('platform_id', 'master_sku', 'sku', 'product_name', 'vb_selling_price', 'profit_margin(%)');
                $headers = array_merge($headers, (array)$competitors);

                array_unshift($data_content, $headers);
            }
        }

        $this->generate_csv($data_content);
    }

    private function get_margin($platform_id, $sku, $price)
    {
        $json = $this->price_service->get_profit_margin_json($platform_id, $sku, $price);
        $arr = json_decode($json, true);

        return $arr["get_margin"];
    }

    private function get_stock_status_name($stock_status = "")
    {
        switch ($stock_status) {
            case 0:
                $status = "In Stock";
                break;

            case 1:
                $status = "Out of Stock";
                break;

            case 2:
                $status = "Pre-order";
                break;

            case 3:
                $status = "Arriving";
                break;

            default:
                $status = "";
                break;
        }

        return $status;
    }

    private function get_auto_price_type($auto_price = "")
    {
        switch ($auto_price) {
            case 'N':
                $type = "No Action";
                break;

            case 'Y':
                $type = "Auto Price";
                break;

            case 'C':
                $type = "Competitor Reprice";
                break;

            default:
                $type = "";
                break;
        }

        return $type;
    }

    function generate_csv($data)
    {
        header("Content-type: text/csv");
        header("Cache-Control: no-store, no-cache");
        header("Content-Disposition: attachment; filename=\"export_competitor_prices.csv\"");

        foreach ($data as $key => $value) {
            $lines .= implode(',', $value) . "\r\n";
        }

        echo $lines;
    }
}
