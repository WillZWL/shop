<?php

class Top_view_video extends MY_Controller
{

    private $appId = "MKT0041";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'directory', 'notice'));
        $this->load->model('marketing/top_view_video_model');
        $this->load->library('service/pagination_service');
    }

    public function main()
    {
        if ($this->input->get('level') == "" || $this->input->get('catid') == "") {
            $this->index();
            exit;
        }
        if ($this->input->get('platform') && $this->input->get('type') && $this->input->get('src')) {
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
        $data["type"] = $this->input->get('type');
        $data["src"] = $this->input->get('src');
        $data["platform_id_list"] = $this->top_view_video_model->get_platform_id_list(array(), array("orderby" => "id ASC"));
        $this->load->view('marketing/top_view_video/tv_index', $data);
    }

    public function index()
    {
        $where = array();
        $option = array();

        $_SESSION["LISTPAGE"] = base_url() . "marketing/top_view_video/?" . $_SERVER['QUERY_STRING'];

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

        if (empty($sort)) {
            $sort = "name";
        }

        if (empty($order)) {
            $order = "asc";
        }

        $option["orderby"] = $sort . " " . $order;

        $data = $this->top_view_video_model->get_cat_list_index($where, $option);

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
        $this->load->view('marketing/top_view_video/tv_list_index', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
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
            $option["selling_platform"] = $this->input->get('platform');
            $where["video_type"] = $this->input->get('type');
            $where["video_src"] = $this->input->get('src');
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

            $data["objlist"] = $this->top_view_video_model->get_video_list($where, $option);

            $option = array();
            $option["selling_platform"] = $this->input->get('platform');
            $data["total"] = $this->top_view_video_model->get_video_list_total($where, $option);

            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->msg_br = TRUE;
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        }
        $this->load->view('marketing/top_view_video/tv_view_left', $data);
    }

    public function view_right($catid, $platform_id, $type, $src)
    {
        $limit = $data["limit"] = $this->top_view_video_model->get_list_limit();

        if ($catid == "") {
            $this->index();
            exit;
        }
        $data["catid"] = $catid;
        $data["platform_id"] = $platform_id;
        $data["video_type"] = $type;
        $data["video_src"] = $src;

        if ($this->input->post('posted')) {
            $err = 0;

            $input = $this->input->post('cat');
            $sku = $this->input->post('sku');
            $language = $this->input->post('language');

            $this->top_view_video_model->trans_start();

            $ret = $this->top_view_video_model->delete_bs(array("catid" => $catid, "platform_id" => $platform_id, "listing_type" => "TV", "video_type" => $type, "mode" => "M", "src" => $src));
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "update_failed";
            }

            foreach ($input as $key => $v) {
                if ($v != "") {
                    $action = "insert";
                    $obj = $this->top_view_video_model->get_vo();
                    $obj->set_catid($catid);
                    $obj->set_platform_id($platform_id);
                    $obj->set_listing_type('TV');
                    $obj->set_video_type($type);
                    $obj->set_src($src);
                    $obj->set_rank($key);
                    $obj->set_sku($sku[$key]);
                    $obj->set_lang_id($language[$key]);
                    $obj->set_mode('M');
                    $obj->set_ref_id($v);
                    $ret = $this->top_view_video_model->insert($obj);

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
                $this->top_view_video_model->trans_complete();
            }
        }

        $sub_app_id = $this->getAppId() . "03";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $count = $this->top_view_video_model->get_count($catid, 'M', $platform_id, $type, $src);
        $cnt = 0;

        if ($count === FALSE) {
            $this->index();
            exit;
        }

        if (!$count) {
            for ($i = 1; $i <= $limit; $i++) {
                $obj = $this->top_view_video_model->get_top_view_video($catid, $i, $type, $platform_id, $src);
                //echo $this->db->last_query()."  ".$this->db->_error_message();

                $sku[$i] = "";
                $value[$i] = $lang["not_assigned"];
                $name[$i] = $lang["not_assigned"];
            }
        } else {
            $list = $this->top_view_video_model->get_list_w_name($catid, 'M', 'TV', $type, $platform_id, $src);
            //echo $this->db->last_query();

            for ($i = 1; $i <= $limit; $i++) {
                $obj = $this->top_view_video_model->get_top_view_video($catid, $i, $type, $platform_id, $src);
                //echo $this->db->last_query();
                //echo "   ".$this->top_view_video_model->_error_message();
                if (isset($list[$i])) {
                    $sku[$i] = $list[$i]->get_sku();
                    $name[$i] = $list[$i]->get_name();
                    $value[$i] = $list[$i]->get_ref_id();
                    $cnt++;
                } else {
                    $sku[$i] = "";
                    $name[$i] = $lang["not_assigned"];
                    $value[$i] = $lang["not_assigned"];
                }
            }
        }

        $count = $this->top_view_video_model->get_count($catid, 'A', $platform_id, $type, $src);
        $acnt = 0;

        if ($count === FALSE) {
            $this->index();
            exit;
        }
        if (!$count) {
            for ($i = 1; $i <= $limit; $i++) {

                $obj = $this->top_view_video_model->get_top_view_video($catid, $i, $type, $platform_id, $src);
                //echo $this->db->last_query()."  ".$this->db->_error_message();

                $asku[$i] = "";
                $aname[$i] = $lang["not_assigned"];
                $avalue[$i] = $lang["not_assigned"];

            }
        } else {
            $list = $this->top_view_video_model->get_list_w_name($catid, 'A', 'TV', $type, $platform_id, $src);
            //echo $this->db->last_query();
            for ($i = 1; $i <= $limit; $i++) {
                $obj = $this->top_view_video_model->get_top_view_video($catid, $i, $type, $platform_id, $src);
                //echo $this->db->last_query();
                //echo "   ".$this->top_view_video_model->_error_message();
                if (isset($list[$i])) {
                    $asku[$i] = $list[$i]->get_sku();
                    $aname[$i] = $list[$i]->get_name();
                    $avalue[$i] = $list[$i]->get_ref_id();
                    $acnt++;
                } else {
                    $asku[$i] = "";
                    $aname[$i] = $lang["not_assigned"];
                    $avalue[$i] = $lang["not_assigned"];
                }
            }
        }

        $data["asku"] = $asku;
        $data["aname"] = $aname;
        $data["avalue"] = $avalue;
        $data["sku"] = $sku;
        $data["name"] = $name;
        $data["value"] = $value;

        $osku = $sku;
        $oname = $name;
        $ovalue = $value;

        if ($cnt < $limit) {
            foreach ($avalue as $key => $val) {
                if ($cnt < $limit && !in_array($val, $ovalue)) {
                    $ovalue[++$cnt] = $val;
                    $oname[$cnt] = $aname[$key];
                    $osku[$cnt] = $asku[$key];
                }
            }
        }

        $data["osku"] = $osku;
        $data["oname"] = $oname;
        $data["ovalue"] = $ovalue;
        $data["notice"] = notice($lang);

        $this->load->view('marketing/top_view_video/tv_view_right', $data);
    }

}

?>