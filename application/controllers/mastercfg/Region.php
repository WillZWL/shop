<?php

include_once "region_helper.php";

class Region extends Region_helper
{
    private $app_id = "MST0002";
    private $lang_id = "en";

    public function __construct()
    {
        parent::Region_helper();
        $this->authorization_service->check_access_rights($this->_get_app_id(), "");
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function view($value = "")
    {
        $data = array();
        $data["updated"] = 0;
        $data["editable"] = 1;
        if ($this->input->post('posted')) {
            $error = 0;
            if ($obj = $this->Region_model->get_region()) {

                $obj->set_id($_POST["id"]);
                $obj->set_region_name($_POST["region_name"]);
                $obj->set_type($_POST["region_type"]);
                if ($this->Region_model->update_region($obj)) {
                    //manipulate with countryid

                    $result = $this->Region_model->del_region_country($value);
                    if ($result !== FALSE) {
                        if (!empty($_POST['country'])) {
                            $result2 = $this->Region_model->add_region_country($value, $_POST['country']);
                            if (!$result2) {
                                $error++;
                            }
                        }
                    } else {
                        $error++;
                    }
                } else {
                    $error++;
                }

                if ($error) {
                    //logging service - updated failed
                    $_SESSION["NOTICE"] = "update_failed: " . $this->db->_error_message();
                } else {
                    $data["updated"] = 1;
                }
            }
            Redirect(base_url() . "mastercfg/region/view/" . $value);
        }

        if ($value == "") {
            $this->index();
            return;
        }
        include_once APPPATH . '/language/' . $this->_get_app_id() . '02_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["region_obj"] = $this->Region_model->get_region($value);
        if (empty($data["region_obj"])) {
            $_SESSION["NOTICE"] = "region_not_found";
            $data["region_obj"] = $this->Region_model->get_region();
        }
        $data['country_in'] = $this->Region_model->get_country_in_region($value);
        $data['country_ex'] = $this->Region_model->get_country_ex($this->country_list, $data['country_in']);
        $data['notice'] = notice($lang);
        $this->load->view('mastercfg/region/region_view', $data);
    }

    public function index()
    {
        $_SESSION["notice"] = "";
        $_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];

        $where = array();
        $option = array();

        $where["id"] = $this->input->get("id");
        $where["region_name"] = $this->input->get("region_name");
        $where["region_type"] = $this->input->get("region_type");
        //  $where["status"] = $this->input->get("status");
        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '-1';

        $pconfig['base_url'] = "mastercfg/region/?" . $_SERVER['QUERY_STRING'];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }


        if (empty($sort))
            $sort = "id";

        if (empty($order) || $order == "")
            $order = "asc";


//      $option['sort'] = $sort;
//      $option['order'] = $orders;
        if ($sort == "region_type") {
            $option["orderby"] = "type " . $order;
        } else {
            $option["orderby"] = $sort . " " . $order;
        }

        $data = $this->Region_model->get_region_by_name($where["region_name"], $where["region_type"], $where["id"], $option);

        $sub_app_id = $this->_get_app_id() . "00";

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->initialize($pconfig);

        //$notice = notice();

        $data["showall"] = $this->input->get("showall");
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($where["region_name"]==""&& $where["region_type"] == ""?'style="display:none"':"");
        $data["searchdisplay"] = "";

        $this->load->view('mastercfg/region/region_index', $data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function add()
    {
        $data = array();
        if ($this->input->post('posted')) {
            $obj = $this->Region_model->get_region();
            $obj->set_region_name($_POST["region_name"]);
            $obj->set_type($_POST["region_type"]);
            $retobj = $this->Region_model->add_region($obj);

            if ($retobj !== FALSE) {
                //manipulate with countryid
                if (!$this->Region_model->add_region_country($retobj->get_id(), $_POST["country"])) {
                    //Error Log - adding city failed
                    $_SESSION["notice"] = "Failed to add city to list";
                } else {
                    Redirect(base_url() . "mastercfg/region/");
                }

            } else {
                echo "false";
                //Error Log -adding region failed
                $_SESSION["NOTICE"] = "Failed to add region";
            }
        }

        include_once APPPATH . '/language/' . $this->_get_app_id() . '01_' . $this->_get_lang_id() . '.php';
        $data["lang"] = $lang;
        $data["region_obj"] = $this->Region_model->get_region();
        $data["header"] = 'Create a new region';
        $data['title'] = 'Create a new region';
        $data['country_ex'] = $this->country_list;
        $data["notice"] = notice($lang);

        $this->load->view('mastercfg/region/region_add', $data);
    }

}

?>