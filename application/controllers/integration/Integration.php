<?php
use ESG\Panther\Models\Integration\IntegrationModel as newIntegrationModel;

class Integration extends MY_Controller
{
    private $appId = "INT0001";
    private $lang_id = "en";
    private $notification_email = "itsupport@eservicesgroup.net";

    public function __construct()
    {
        parent::__construct();
/*
        $this->load->model('integration/integration_model');
        $this->load->helper(array('url', 'notice', 'object', 'operator', 'lang'));
        $this->load->library('service/pagination_service');
        $this->load->library('encrypt');
*/
    }

    public function sendOrderToCybsDecisionManager($debug = 0)
    {
        $integrationModel = new newIntegrationModel();
        $integrationModel->sendOrderToCybsDecisionManager($debug);
        print "Done";
    }
/*
    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";
        //$this->authorization_service->check_access_rights($sub_app_id, "List");

        $_SESSION["LISTPAGE"] = base_url() . "integration/integration/?" . $_SERVER['QUERY_STRING'];

        $_SESSION['int_query'] = $_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();
        $where['b.listed'] = 1;

        if ($this->input->get("id")) {
            $where["b.id LIKE "] = '%' . $this->input->get("id") . '%';
        }

        if ($this->input->get("func_name")) {
            $where["func_name LIKE "] = '%' . $this->input->get("func_name") . '%';
        }

        if ($this->input->get("remark")) {
            $where["remark LIKE "] = '%' . $this->input->get("remark") . '%';
        }

        if ($this->input->get("status")) {
            $where["status"] = $this->input->get("status");
        }

        if ($this->input->get("create_on")) {
            fetch_operator($where, "create_on", $this->input->get("create_on"));
        }

        if ($this->input->get("end_time")) {
            fetch_operator($where, "end_time", $this->input->get("end_time"));
        }

        if ($this->input->get("duration")) {
            fetch_operator($where, "duration", $this->input->get("duration"));
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort . " " . $order;

        $data["objlist"] = $this->integration_model->get_batch_list($where, $option);
        $data["total"] = $this->integration_model->get_batch_list_total($where);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($where["id"]=="" && $where["integrationname"]=="" && $where["email"]=="" && $where["roles"]=="")?'style="display:none"':"";
        $data["searchdisplay"] = "";
        $this->load->view('integration/integration_index_v', $data);
    }
*/
    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
/*
    public function view($func, $batch_id)
    {
        $sub_app_id = $this->getAppId() . "01";
        //$this->authorization_service->check_access_rights($sub_app_id, "List");

        if ($func && $batch_id) {
            if (in_array($func, array("amuk_order", "amfr_order"))) {
                $this->view_amazon($func, $batch_id);
                return;
            } elseif (strpos($func, "tracking_info") !== FALSE) {
                $func = "tracking_info";
            }

            $dao_arr = array("ixtens_reprice_amuk" => "ip_dao", "ixtens_reprice_amfr" => "ip_dao", "dpd_process" => "iss_dao", "metapack_process" => "iss_dao", "invetory" => "", "tracking_info" => "", "youtube_video" => "video_dao");
            $sort_arr = array("ixtens_reprice_amuk" => "sku", "ixtens_reprice_amfr" => "sku", "dpd_process" => "id", "metapack_process" => "id", "inventory" => "trans_id", "tracking_info" => "trans_id", "youtube_video" => "trans_id");

            $_SESSION["LISTPAGE"] = base_url() . "integration/integration/view/{$func}/{$batch_id}?" . $_SERVER['QUERY_STRING'];

            $where = array();
            $option = array();

            $where["batch_id"] = $batch_id;
            if ($this->input->get("sku")) {
                $where["sku LIKE "] = '%' . $this->input->get("sku") . '%';
            }

            if ($this->input->get("price")) {
                fetch_operator($where, "price", $this->input->get("price"));
            }

            if ($this->input->get("sh_no")) {
                $where["sh_no LIKE"] = '%' . $this->input->get("sh_no") . '%';
            }

            if ($this->input->get("tracking_no")) {
                $where["tracking_no LIKE"] = '%' . $this->input->get("tracking_no") . '%';
            }

            if ($this->input->get("courier_id")) {
                $where["courier_id LIKE"] = '%' . $this->input->get("courier_id") . '%';
            }

            if ($this->input->get("ship_method")) {
                $where["ship_method LIKE"] = '%' . $this->input->get("ship_method") . '%';
            }

            if ($this->input->get("batch_status")) {
                $where["batch_status"] = $this->input->get("batch_status");
            }

            if ($this->input->get("failed_reason")) {
                $where["failed_reason LIKE "] = '%' . $this->input->get("failed_reason") . '%';
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $option["limit"] = $pconfig['per_page'] = $limit;
            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = $sort_arr[$func];

            if (empty($order))
                $order = "asc";

            if ($sort) {
                $option["orderby"] = $sort . " " . $order;
            }

            switch ($func) {
                case "inventory":
                    $data["objlist"] = $this->integration_model->get_iinv_list($dao_arr[$func], $where, $option);
                    $data["total"] = $this->integration_model->get_iinv_num_rows($dao_arr[$func], $where);
                    break;

                case "tracking_info":
                    $data["objlist"] = $this->integration_model->get_itinfo_list($dao_arr[$func], $where, $option);
                    $data["total"] = $this->integration_model->get_itinfo_num_rows($dao_arr[$func], $where);
                    break;

                case "youtube_video":
                    if ($this->input->get("view_count")) {
                        fetch_operator($where, "view_count", $this->input->get("view_count"));
                    }
                    $option['groupby'] = array("batch_id", "sku", "ref_id", "view_count", "batch_status", "failed_reason");
                    $data["objlist"] = $this->integration_model->get_iyoutube_list($dao_arr[$func], $where, $option);
                    $data["total"] = count($data["objlist"]);
                    break;
                default:
                    $data["objlist"] = $this->integration_model->get_list($dao_arr[$func], $where, $option);
                    $data["total"] = $this->integration_model->get_num_rows($dao_arr[$func], $where);
            }

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
            $data["lang"] = $lang;

            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            //      $data["searchdisplay"] = ($where["id"]=="" && $where["integrationname"]=="" && $where["email"]=="" && $where["roles"]=="")?'style="display:none"':"";
            $data["searchdisplay"] = "";
            $data["func"] = $func;
            if (in_array($func, array("ixtens_reprice_amuk", "ixtens_reprice_amfr"))) {
                $this->load->view("integration/integration_ixtens_reprice_pg_v", $data);
            } else {
                $this->load->view("integration/integration_{$func}_v", $data);
            }
        }
    }

    public function view_amazon($func, $batch_id)
    {
        $sub_app_id = $this->getAppId() . "02";

        $_SESSION["LISTPAGE"] = base_url() . "integration/integration/view_amazon/{$func}/{$batch_id}?" . $_SERVER['QUERY_STRING'];

        if ($this->input->post('edit')) {
            $obj = $this->integration_model->get("iso_dao", array("batch_id" => $batch_id, "trans_id" => $this->input->post('trans')));
            $obj->set_platform_order_id($this->input->post("porder_id"));
            $obj->set_batch_status('I');
            $ret = $this->integration_model->update("iso_dao", $obj);
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            }
        }

        $where = array();
        $option = array();

        $where["batch_id"] = $batch_id;
        if ($this->input->get("trans_id")) {
            $where["trans_id"] = $this->input->get("trans_id");
        }

        if ($this->input->get("platform_order_id")) {
            $where["platform_order_id LIKE"] = '%' . $this->input->get("platform_order_id") . '%';
        }

        if ($this->input->get("batch_status")) {
            $where["batch_status"] = $this->input->get("batch_status");
        }

        if ($this->input->get("failed_reason")) {
            $where["failed_reason LIKE "] = '%' . $this->input->get("failed_reason") . '%';
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "trans_id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data["objlist"] = $this->integration_model->get_list("iso_dao", $where, $option);
        $data["total"] = $this->integration_model->get_num_rows("iso_dao", $where);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["func"] = $func;
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        //      $data["searchdisplay"] = ($where["id"]=="" && $where["integrationname"]=="" && $where["email"]=="" && $where["roles"]=="")?'style="display:none"':"";
        $data["searchdisplay"] = "";

        $this->load->view("integration/integration_" . $func . "_v", $data);
    }

    public function examine_client($batch_id = "", $trans_id = "")
    {
        $sub_app_id = $this->getAppId() . "03";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if ($batch_id == "" || $trans_id == "") {
            exit;
        }

        if ($this->input->post("edit")) {
            $obj = $client_info = $this->integration_model->get("ic_dao", array("trans_id" => $trans_id, "batch_id" => $batch_id));
            $obj->set_email($this->input->post('email'));
            $obj->set_forename($this->input->post('forename'));
            $obj->set_surname($this->input->post('surname'));
            $obj->set_companyname($this->input->post('companyname'));
            $obj->set_address_1($this->input->post('address_1'));
            $obj->set_address_2($this->input->post('address_2'));
            $obj->set_address_3($this->input->post('address_3'));
            $obj->set_city($this->input->post('city'));
            $obj->set_state($this->input->post('state'));
            $obj->set_postcode($this->input->post('postcode'));
            $obj->set_country_id($this->input->post('country_id'));
            $obj->set_tel_3($this->input->post('tel'));
            $obj->set_mobile($this->input->post('mobile'));
            $obj->set_batch_status("I");
            $obj->set_failed_reason("Edit for re-process");

            $ret = $this->integration_model->update("ic_dao", $obj);
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "update_batch_failed";
            }
        }
        $data["edit"] = 0;
        $data["obj"] = $client_info = $this->integration_model->get("ic_dao", array("trans_id" => $trans_id, "batch_id" => $batch_id));
        if (in_array($client_info->get_batch_status(), array("F", "I"))) {
            $data["edit"] = 1;
        }
        $data["notice"] = notice($lang);

        $this->load->view("integration/examine_client", $data);

    }

    public function examine_order($batch_id = "", $trans_id = "")
    {
        $sub_app_id = $this->getAppId() . "04";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        if ($batch_id == "" || $trans_id == "") {
            exit;
        }

        if ($this->input->post("edit")) {
            $obj = $this->integration_model->get('isoi_dao', array('so_trans_id' => $trans_id, 'batch_id' => $batch_id, 'line_no' => $this->input->post('ln')));
            $obj->set_prod_sku($this->input->post('prod_sku'));
            $obj->set_batch_status('I');

            $ret = $this->integration_model->update("isoi_dao", $obj);
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "update_batch_failed";
            }
        }
        $data["batch_id"] = $batch_id;
        $data["trans_id"] = $trans_id;
        $data["edit"] = 0;
        $data["obj_list"] = $this->integration_model->get_list("isoi_dao", array("so_trans_id" => $trans_id, "batch_id" => $batch_id));
        $data["obj_id_list"] = $this->integration_model->get_list("isoid_dao", array("trans_so_no" => $trans_id, "batch_id" => $batch_id));
        $this->load->view("integration/examine_order", $data);

    }

    public function reprocess_amazon($batch_id = "", $trans_id = "", $platform = "")
    {
        if ($batch_id == "" || $trans_id == "" || $platform == "") {
            redirect(current_url());
        } else {
            $ret = $this->integration_model->reprocess_amazon($batch_id, $trans_id, $platform);

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            }

            $ret = $this->integration_model->check_and_update_batch_status($batch_id);

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            }


            if (isset($_SESSION["LISTPAGE"])) {
                redirect($_SESSION["LISTPAGE"]);
            } else {
                redirect(current_url());
            }
        }
    }

    public function investigated($func = "", $batch_id = "")
    {
        $where["batch_id"] = $batch_id;
        switch ($func) {
            case "ixtens_repice_pg":
                $where["sku"] = $this->input->get("sku");
                $dao = "ip_dao";
                break;
        }

        if (($integration_vo = $this->integration_model->get($dao, $where)) === FALSE) {
            $_SESSION["NOTICE"] = $this->db->_error_message();
        } else {
            if (empty($integration_vo)) {
                $_SESSION["NOTICE"] = "data_not_found";
            } else {
                $integration_vo->set_batch_status("I");
                if (!$this->integration_model->update($dao, $integration_vo)) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"])) {
            redirect($_SESSION["LISTPAGE"]);
        } else {
            redirect(current_url());
        }

    }

    public function reprocess_ebay($batch_id, $trans_id)
    {
        return $this->integration_model->reprocess_ebay($batch_id, $trans_id);
    }

    public function auto_pricing_by_sku($sku, $platform_type = "WEBSITE")
    {
        $price_list = $this->integration_model->get_platform_price_list(array("sp.type" => $platform_type, "sku" => $sku, "auto_price" => "Y"));
        if ($price_list) {
            $title = '[VB] AUTO PRICE BY SKU';
            $message = "PLATFORM=>SKU\n";
            foreach ($price_list AS $price_obj) {
                $this->integration_model->update_auto_price($price_obj->get_platform_id(), $price_obj->get_sku());
                $message .= $price_obj->get_platform_id() . "=>" . $price_obj->get_sku() . "\n";
            }
            //mail($this->notification_email, $title, $message);
        }
        echo "OK";
    }

    public function auto_pricing_to_all_platform()
    {
        $selling_platform_list = $this->integration_model->selling_platform_service->get_dao()->get_list(array("id like 'WEB%'" => null, "status" => 1), array("limit" => -1));
        foreach ($selling_platform_list as $platform) {
            $this->auto_pricing_by_platform($platform->get_id());
        }
    }

    public function auto_pricing_by_platform($platform_id)
    {
        $price_list = $this->integration_model->get_platform_price_list(array("p.platform_id" => $platform_id, "auto_price" => "Y"), array("limit" => -1));
        if ($price_list) {
            $title = '[VB] AUTO PRICE BY PLATFORM';
            $message = "PLATFORM=>SKU\n";
            foreach ($price_list AS $price_obj) {
                $this->integration_model->update_auto_price($price_obj->get_platform_id(), $price_obj->get_sku());
                $message .= $price_obj->get_platform_id() . "=>" . $price_obj->get_sku() . "\n";
            }
            // mail($this->notification_email, $title, $message);
        }
    }

    public function refresh_all_platform_margin()
    {
        // return TRUE;
        include_once(APPPATH . "libraries/service/price_margin_service.php");
        $price_margin_service = new Price_margin_service();
        $result = $price_margin_service->refresh_all_platform_margin();

        if (isset($result["error_message"])) {
            mail($this->notification_email, "VB Error updating Price Margin", $result["error_message"]);
        }
    }

    public function refresh_all_platform_margin_by_sku($sku = "")
    {
        // return TRUE;
        include_once(APPPATH . "libraries/service/price_margin_service.php");
        $price_margin_service = new Price_margin_service();
        $result = $price_margin_service->refresh_all_platform_margin(array(), $sku);

        if (isset($result["error_message"])) {
            mail($this->notification_email, "VB Error updating Price Margin", $result["error_message"]);
        }
    }
*/
}



