<?php

class Cron_sourcing_list extends MY_Controller
{

    private $app_id = "SUP0003";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('object'));
        $this->load->model('order/so_model');
        $this->load->model('marketing/product_model');
        $this->load->model('supply/supplier_model');
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->set_config_srv(new Context_config_service());
    }

    public function set_config_srv(Base_service $srv)
    {
        $this->config_srv = $srv;
    }

    public function index()
    {
        $src_list = $this->so_model->so_service->get_soid_dao()->get_gen_sourcing_list_with_priority();
        if ($src_list) {
            $sl_vo = $this->product_model->product_service->get_sl_dao()->get();
            $cur_date = date("Y-m-d");
            foreach ($src_list as $obj) {
                $sl_obj = clone $sl_vo;
                set_value($sl_obj, $obj);
                $sl_obj->set_list_date($cur_date);
                $sl_obj->set_required_qty($obj->get_required_qty() - $obj->get_inventory());
                /*set prioritized qty = required qty in 2012-05-17, requested by Fiona*/
                /*
                                $prioritized = $obj->get_prioritized_qty();
                                if (!empty($prioritized))
                                    $sl_obj->set_prioritized_qty($obj->get_prioritized_qty() - $obj->get_inventory());
                */
                $sl_obj->set_prioritized_qty($obj->get_required_qty() - $obj->get_inventory());
                if ($this->product_model->product_service->get_sl_dao()->insert($sl_obj) === FALSE) {
                    echo $this->db->last_query();
                    echo "\n";
                    echo $this->db->_error_message();
                    echo "\n";
                }
            }
            $this->product_model->product_service->get_sl_dao()->sp_copy_prev_sl_data($cur_date);
        }
    }

    public function cps_sourcing_list()
    {
        $where = array("item_sku not in ('16908-AA-NA', '14651-AA-NA')" => null);
        $src_list = $this->so_model->so_service->get_soid_dao()->get_cps_sourcing_list($where);
        if ($src_list) {
            # before create sourcing list, set all previous requests to status = 0
            $status = $this->supplier_model->inactive_cpssl_status();
            $cpssl_vo = $this->supplier_model->get("cpssl_dao");
            $cur_date = date("Y-m-d");
            $ts = date("Y-m-d_His");
            foreach ($src_list as $obj) {
                $cpssl_obj = clone $cpssl_vo;
                $cpssl_obj->set_list_date($cur_date);
                $obj->set_required_qty($obj->get_required_qty() - $obj->get_inventory());
                set_value($cpssl_obj, $obj);
                $cpssl_obj->set_status(1);
                if ($obj->get_inventory() == 0) {
                    $cpssl_obj->set_required_info($obj->get_order_info());
                } else {
                    $required_info = $result = array();
                    $prioritized_order = $this->sort_order($obj->get_order_info());
                    $inv = $obj->get_inventory();
                    foreach ($prioritized_order AS $so_no => $qty) {
                        if ($inv > 0 && $inv >= $qty) {
                            $inv = $inv - $qty;
                            $required_info[$so_no] = 0;
                        } else {
                            $required_info[$so_no] = $qty;
                        }
                    }
                    if ($inv > 0) {
                        foreach ($required_info AS $so_no => $qty) {
                            if ($inv > 0 && $qty > 0) {
                                $required_info[$so_no] = $qty - $inv;
                            }
                        }
                    }

                    foreach ($required_info AS $so_no => $qty) {
                        $result[$so_no] = $so_no . "::" . $required_info[$so_no];
                    }
                    $info = implode("||", $result);
                    $cpssl_obj->set_required_info($info);
                    $cpssl_obj->set_avg_cost($obj->get_avg_cost());
                }

                $this->supplier_model->add("cpssl_dao", $cpssl_obj);

                // CSI#3093 we might want to check if the above statement failed
                // then perform an update instead
                if ($this->supplier_model->db->affected_rows() <= 0)
                    $this->supplier_model->update("cpssl_dao", $cpssl_obj);

                // if (stripos($obj->get_order_info(),"318607") !== false)
                // var_dump($this->supplier_model->db->last_query());

            }
            $this->gen_cps_sourcing_xml($cur_date, $ts);
        }
    }

    public function sort_order($order_info = "")
    {
        $order = $temp = $result = array();

        $order_info = explode("||", $order_info);
        if ($order_info) {
            foreach ($order_info AS $info) {
                $temp = explode("::", $info);
                $list[$temp[0]] = $temp[1];
                $order[$temp[0]] = $this->so_model->get_priority_score($temp[0]);
            }
            arsort($order);
            foreach ($order AS $so_no => $score) {
                $result[$so_no] = $list[$so_no];
            }
        }
        return $result;
    }

    public function gen_cps_sourcing_xml($list_date, $timestamp = "")
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        if ($timestamp == "")
            $timestamp = $list_date;

        $cps_sourcing_list = $this->supplier_model->get_list("cpssl_dao", array("list_date" => $list_date, "status" => 1), array("limit" => -1));
        if ($cps_sourcing_list) {
            $cps_list = null;#array();
            foreach ($cps_sourcing_list AS $obj) {
                if ($obj->get_required_qty() > 0) {
                    $required_info = explode("||", $obj->get_required_info());
                    foreach ($required_info AS $info) {
                        list($so_no, $qty) = explode("::", $info);
                        if ($qty > 0) {
                            $cps_list[$so_no]["information"][] = array("sku" => $obj->get_item_sku(), "qty" => $qty, 'avg_cost' => $obj->get_avg_cost());
                        }
                    }
                }
            }


            $so_no_array = null;
            foreach ($cps_list as $so_no => $value)
                $so_no_array[] = $so_no;

            $list = $this->so_model->get_so_priority_score_info($so_no_array);

            $filename = 'vb_cps_sourcing_list_' . $timestamp . '.xml';
            $default_name = 'vb_cps_sourcing_list.xml';
            $filepath = DATAPATH . 'sourcing_list/';
            $fp = fopen($filepath . $filename, 'w');

            $starttime = -1;
            $count = 0;

            $content = '';
            $content .= '<?xml version="1.0"?>' . "\n";
            $content .= '<orders>' . "\n";
            // foreach($cps_list AS $so_no=>$information)
            foreach ($list AS $k => $data) {
                $so_no = $data["so_no"];

                $so = $this->so_model->get("dao", array("so_no" => $so_no));
                if ($starttime == -1) $starttime = time();

                $delivery_country_id = $data["delivery_country_id"];
                $information = $cps_list[$so_no];
                $priority_score = $this->so_model->get_priority_score($so_no, array($data["order_create_date"], $data["biz_type"], $data["conv_site_id"], $data["order_margin"]));

                echo "\r\n" . (time() - $starttime) . " sec(s) #{$count}: SO#$so_no @ $priority_score";
                $count++;

                $content .= '<order>' . "\n";
                $content .= '<bundle/>' . "\n";
                $content .= "<retailer_order_reference>$so_no</retailer_order_reference>\n";
                $content .= "<purchased_date>{$data["order_create_date"]}</purchased_date>\n";
                $content .= "<name>" . htmlspecialchars($so->get_delivery_name()) . "</name>\n";
                $content .= "<address>" . htmlspecialchars($so->get_delivery_address()) . "</address>\n";
                $content .= "<postcode>{$so->get_delivery_postcode()}</postcode>\n";
                $content .= "<city>" . htmlspecialchars($so->get_delivery_city()) . "</city>\n";
                $content .= "<state>{$so->get_delivery_state()}</state>\n";
                $content .= "<country>{$delivery_country_id}</country>\n";
                $content .= "<score>$priority_score</score>\n";
                $content .= "<amount>\n";
                $content .= "   <currency></currency>\n";
                $content .= "   <delivery></delivery>\n";
                $content .= "   <total></total>\n";
                $content .= "</amount>\n";
                $content .= "<skus>\n";
                $master_sku = "";
                foreach ($information["information"] AS $prod_arr) {
                    $is_clearance = $this->get_product_clearance($prod_arr["sku"]) ? "TRUE" : "FALSE";
                    $content .= "<sku>\n";
                    $content .= "   <price></price>\n";
                    $content .= "   <retailer_sku>{$prod_arr["sku"]}</retailer_sku>\n";
                    $content .= "   <master_sku>{$this->get_master_sku($prod_arr["sku"])}</master_sku>\n";
                    $content .= "   <quantity>{$prod_arr["qty"]}</quantity>\n";
                    $content .= "   <is_clearance>{$is_clearance}</is_clearance>\n";
                    $content .= "   <cost>{$prod_arr["avg_cost"]}</cost>\n";
                    $content .= "   <qtyallocated/>\n";
                    $content .= "   <sourcingcomment/>\n";
                    $content .= "</sku>\n";
                }
                $content .= '</skus>' . "\n";
                $content .= '</order>' . "\n";
            }
            $content .= '</orders>' . "\n";

            if ($content) {
                if (fwrite($fp, $content)) {
                    if (!copy($filepath . $filename, $filepath . $default_name)) {
                        $subject = "<DO NOT REPLY> Fails to create Default CPS Sourcing List";
                        $message = "FILE: " . __FILE__ . "<br>
                                     LINE: " . __LINE__;
                        $this->error_handler($subject, $message);
                    }
                } else {
                    $subject = "<DO NOT REPLY> Fails to create Daily CPS Sourcing List";
                    $message = "FILE: " . __FILE__ . "<br>
                                 LINE: " . __LINE__;
                    $this->error_handler($subject, $message);
                }
            }
        }
    }

    public function get_config_srv()
    {
        return $this->config_srv;
    }

    public function get_product_clearance($sku)
    {
        return $this->so_model->get_product_clearance($sku);
    }

    public function get_master_sku($sku)
    {
        return $this->supplier_model->get_master_sku(array("sku" => $sku, "ext_sys" => "WMS", "status" => 1));
    }

    public function error_handler($subject = '', $msg = '', $is_dead = false)
    {
        //echo $msg;
        $subject = $subject ? $subject : 'CPS Sourcing List Error';

        if ($subject) {
            mail($this->get_contact_email(), $subject, $msg, 'From: thomas@eservicesgroup.net');
        }

        if ($is_dead) {
            exit;
        }
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function retrieve_xml()
    {
        define('DATAPATH', $this->get_config_srv()->value_of("data_path"));
        // $content = file_get_contents(DATAPATH . 'sourcing_list/vb_cps_sourcing_list.xml');
        header('Content-type: application/xml');
        // echo $content;
        readfile(DATAPATH . 'sourcing_list/vb_cps_sourcing_list.xml');
    }

    public function get_score($so_no)
    {
        var_dump($this->so_model->get_priority_score($so_no));
    }
}

/* End of file cron_sourcing_list.php */
/* Location: ./app/controllers/supply/cron_sourcing_list.php */