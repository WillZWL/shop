<?php

class On_sale extends MY_Controller
{

    private $appId = "MKT0073";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'directory', 'notice'));
        $this->load->model('marketing/on_sale_model');
        $this->load->library('service/pagination_service');
    }

    public function main()
    {
        if ($this->input->get('level') == "" || $this->input->get('catid') == "") {
            $this->index();
            exit;
        }
        if ($this->input->get('platform')) {
            $data["display"] = 1;
        } else {
            $data["display"] = 0;
        }

        $sub_app_id = $this->getAppId() . "01";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["catid"] = $this->input->get('catid');
        $data["level"] = $this->input->get('level');
        $data["platform"] = $this->input->get('platform');
        $data["platform_id_list"] = $this->on_sale_model->get_platform_id_list(array("type" => "WEBSITE"), array("orderby" => "id ASC"));
        $this->load->view('marketing/on_sale/cl_index', $data);
    }

    public function index()
    {
        $where = array();
        $option = array();

        $_SESSION["LISTPAGE"] = base_url() . "marketing/on_sale/?" . $_SERVER['QUERY_STRING'];

        $where["name"] = $this->input->get("name");
        $where["description"] = $this->input->get("description");
        $where["level"] = $this->input->get("level");
        $where["status"] = $this->input->get("status");
        $where["manual"] = $this->input->get("manual");
        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "name";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;


        $data = $this->on_sale_model->get_cat_list_index($where, $option);

        if ($data["list"] === FALSE) {
            $_SESSION["NOTICE"] = "list_error";
        } else {
            unset($_SESSION["NOTICE"]);
        }
        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["refresh"] = $this->input->get("refresh");
        $data["added"] = $this->input->get("added");
        $data["updated"] = $this->input->get("updated");

        $data["showall"] = $this->input->get("showall");
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = ($where["name"] == "" && $where["description"] == "" && $where["level"] == "" && $where["status"] == "" && $where["manual"]) ? 'style="display:none"' : "";

        $sub_app_id = $this->getAppId() . "04";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('marketing/on_sale/cl_list_index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function view()
    {
        $cat = $this->input->get('cat_id');
        $scat = $this->input->get('sub_cat_id');
        $sscat = $this->input->get('sub_sub_cat_id');
        if ($cat == "") {
            $this->index();
            exit;
        }

        $key = $cat;
        $level = 1;
        if ($scat != "") {
            if ($sscat != "") {
                $level = 3;
                $key = $sscat;
            } else {
                $level = 2;
                $key = $scat;
            }
        }
        $data["level"] = $level;
        $data["catid"] = $key;
        $this->load->view('marketing/on_sale/cl_view', $data);
    }

    public function view_left()
    {
        $where = array();
        $option = array();
        $sub_app_id = $this->getAppId() . "02";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        if (($sku = $this->input->get("sku")) != "" || ($prod_name = $this->input->get("name")) != "") {
            $data["search"] = 1;
            if ($sku != "") {
                $where["sku"] = $sku;
            }

            if ($prod_name != "") {
                $where["name"] = $prod_name;
            }
            $where["platform_id"] = $this->input->get('platform');
            $where["listing_status"] = "1";
            $where["weblist"] = "1";
            switch ($this->input->get('level')) {
                case "1":
                    if ($this->input->get('cat') != 0) {
                        $where["cat_id"] = $this->input->get('cat');
                    }
                    break;

                case "2":
                    $where["sub_cat_id"] = $this->input->get('cat');
                    break;

                case "3":
                    $where["sub_sub_cat_id"] = $this->input->get('cat');
                    break;

                default:
                    break;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $pconfig['base_url'] = current_url() . "?" . $_SERVER['QUERY_STRING'];
            $option["limit"] = $pconfig['per_page'] = $limit;

            if ($option["limit"]) {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "sku";

            if (empty($order))
                $order = "asc";

            $option["orderby"] = $sort . " " . $order;

            $data["objlist"] = $this->on_sale_model->get_product_list($where, $option);
            $data["total"] = $this->on_sale_model->get_product_list_total($where);

            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->msg_br = TRUE;
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        }
        $this->load->view('marketing/on_sale/cl_view_left', $data);
    }

    public function view_right($catid, $platform_id)
    {
        $limit = $data["limit"] = $this->on_sale_model->get_list_limit();

        if ($catid == "") {
            $this->index();
            exit;
        }
        if ($this->input->post('posted')) {
            $err = 0;

            $input = $this->input->post('cat');
            $this->on_sale_model->trans_start();

            $ret = $this->on_sale_model->delete_cl(array("catid" => $catid, "platform_id" => $platform_id, "type" => "CL", "mode" => "M"));
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "update_failed";
            }

            foreach ($input as $key => $v) {
                if ($v != "") {
                    $action = "insert";
                    $obj = $this->on_sale_model->get_vo();
                    $obj->set_catid($catid);
                    $obj->set_platform_id($platform_id);
                    $obj->set_type('CL');
                    $obj->set_rank($key);
                    $obj->set_selection($v);
                    $obj->set_mode('M');
                    $ret = $this->on_sale_model->insert($obj);

                    if ($ret === FALSE) {
                        $_SESSION["NOTICE"] = "update_failed";
                        $err++;
                        break;
                    } else {
                        unset($_SESSION["NOTICE"]);
                    }
                }
            }
            if (!$err) {
                $this->on_sale_model->trans_complete();
            }
        } elseif ($_POST['for_all_platform_use']) {
            $platform_id_list = $this->on_sale_model->get_platform_id_list(array("type" => "WEBSITE"), array("orderby" => "id ASC"));
            $err = 0;

            $input = $this->input->post('cat');
            $this->on_sale_model->trans_start();

            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "update_failed";
            }

            foreach ($platform_id_list as $obj) {
                $temp_platform_id = $obj->get_id();
                $ret = $this->on_sale_model->delete_cl(array("catid" => $catid, "platform_id" => $temp_platform_id, "type" => "CL", "mode" => "A"));
                foreach ($input as $key => $v) {
                    if ($v != "") {
                        $action = "insert";
                        $obj = $this->on_sale_model->get_vo();
                        $obj->set_catid($catid);
                        $obj->set_platform_id($temp_platform_id);
                        $obj->set_type('CL');
                        $obj->set_rank($key);
                        $obj->set_selection($v);
                        $obj->set_mode('A');
                        $ret = $this->on_sale_model->insert($obj);

                        if ($ret === FALSE) {
                            $_SESSION["NOTICE"] = "update_failed";
                            $err++;
                            break;
                        } else {
                            unset($_SESSION["NOTICE"]);
                        }
                    }
                }
            }
            if (!$err) {
                $this->on_sale_model->trans_complete();
            }

        }


        $sub_app_id = $this->getAppId() . "03";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $count = $this->on_sale_model->get_count($catid, 'M', $platform_id);
        $cnt = 0;

        if ($count === FALSE) {
            $this->index();
            exit;
        }

        if (!$count) {
            for ($i = 1; $i <= $limit; $i++) {
                $obj = $this->on_sale_model->get_on_sale($catid, $i, $platform_id);
                //echo $this->db->last_query()."  ".$this->db->_error_message();

                $value[$i] = $lang["not_assigned"];
                $name[$i] = "";
            }
        } else {
            $lists = $this->on_sale_model->on_sale_service->get_dao()->get_manual_item_by_rank($catid, "CL", $platform_id, "M");
//          echo $this->db->last_query();
//          var_dump($lists);
            $value = array();
            $name = array();

            foreach ($lists as $product_list) {
                $value[$cnt + 1] = $product_list->get_sku();
                $name[$cnt + 1] = $product_list->get_name();
                $cnt++;
            }

            for ($i = $cnt + 1; $i <= $limit; $i++) {
                $value[$i] = $lang["not_assigned"];
                $name[$i] = "";
            }
        }

        $count = $this->on_sale_model->get_count($catid, 'A', $platform_id);
        $acnt = 0;

        if ($count === FALSE) {
            $this->index();
            exit;
        }

        if (!$count) {
            for ($i = 1; $i <= $limit; $i++) {
                $obj = $this->on_sale_model->get_on_sale($catid, $i, $platform_id);
                //echo $this->db->last_query()."  ".$this->db->_error_message();

                $avalue[$i] = $lang["not_assigned"];
                $aname[$i] = "";
            }
        } else {
            $list = $this->on_sale_model->get_list_w_name($catid, 'A', 'CL', $platform_id);
            for ($i = 1; $i <= $limit; $i++) {
                $obj = $this->on_sale_model->get_on_sale($catid, $i, $platform_id);
                //echo $this->db->last_query();
                //echo "   ".$this->on_sale_model->_error_message();
                if (isset($list[$i])) {
                    $avalue[$i] = $list[$i]->get_selection();
                    $aname[$i] = $list[$i]->get_name();
                    $acnt++;
                } else {
                    $avalue[$i] = $lang["not_assigned"];
                    $aname[$i] = "";
                }
            }
        }

        $data["aname"] = $aname;
        $data["avalue"] = $avalue;
        $data["name"] = $name;
        $data["value"] = $value;

        $oname = $name;
        $ovalue = $value;

        if ($cnt < $limit) {
            foreach ($avalue as $key => $val) {
                if ($cnt < $limit && !in_array($val, $ovalue)) {
                    $ovalue[++$cnt] = $val;
                    $oname[$cnt] = $aname[$key];
                }
            }
        }

        $data["oname"] = $oname;
        $data["ovalue"] = $ovalue;

        $data["notice"] = notice($lang);
        $this->load->view('marketing/on_sale/cl_view_right', $data);
    }

}

?>