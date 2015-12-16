<?php
include_once "FreightHelper.php";

class Freight extends FreightHelper
{
    private $appId = "MST0009";

    public function __construct()
    {
        parent::__construct();
        $this->sc['Authorization']->checkAccessRights($this->getAppId(), "");
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function index($cat_type = "freight", $cat_id = "")
    {
        $sub_app_id = $this->getAppId() . "00";

        $where = array();
        $option = array();

        if ($this->input->get("weight") != "") {
            $where["weight"] = $this->input->get("weight");
        }

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '20';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        if (empty($sort)) {
            $sort = $cat_type == "weight" ? "weight" : "id";
        }

        if (empty($order)) {
            $order = "asc";
        }

        $option["orderby"] = $sort . " " . $order;

        if ($this->input->get("declared_pcent") != "") {
            $where["declared_pcent"] = $this->input->get("declared_pcent");
        }

        if ($this->input->get("bulk_admin_chrg") != "") {
            $where["bulk_admin_chrg"] = $this->input->get("bulk_admin_chrg");
        }

        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/freight/index/{$cat_type}/{$cat_id}?" . $_SERVER['QUERY_STRING'];

        if ($this->input->get("name") != "") {
            $where["name like"] = "%" . $this->input->get("name") . "%";
        }

        if ($this->input->get("declared_pcent") != "") {
            $where["declared_pcent"] = $this->input->get("declared_pcent");
        }

        if ($this->input->get("bulk_admin_chrg") != "") {
            $where["bulk_admin_chrg"] = $this->input->get("bulk_admin_chrg");
        }

        $data["objlist"] = $this->sc['freightModel']->getFreightCatList($where, $option);
        $data["total"] = $this->sc['freightModel']->getFreightCatTotal($where, $option);

        $data["searchdisplay"] = "";

        if (empty($_SESSION["freightCatVo"])) {
            if (($freightCatVo = $this->sc['freightModel']->getFreightCat()) === FALSE) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            } else {
                $_SESSION["freightCatVo"] = serialize($freightCatVo);
            }
        }

        if (empty($_SESSION["freightCatObj"][$cat_id])) {
            if (($data["freightCatObj"] = $this->sc['freightModel']->getFreightCat(array("id" => $cat_id))) === FALSE) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
            } else {
                unset($_SESSION["freightCatObj"]);
                $_SESSION["freightCatObj"][$cat_id] = serialize($data["freightCatObj"]);
            }
        }

        // $data["origin_country_list"] = $this->sc['freightModel']->getOriginCountryList();

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $config['base_url'] = base_url("mastercfg/freight/index/{$cat_type}/{$cat_id}");
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["cmd"] = ($cat_id == "") ? $this->input->post("cmd") : "edit";
        $data["cat_id"] = $cat_id;
        $data["cat_type"] = $cat_type;
        $view_file = 'mastercfg/freight/freight_index_v';
        $this->load->view($view_file, $data);
    }

    public function add()
    {
        $sub_app_id = $this->getAppId() . "01";
        $cat_type = $this->input->post("cat_type");

        if ($this->input->post("posted")) {
            if (isset($_SESSION["freightCatVo"])) {
                $data["freight_cat"] = unserialize($_SESSION["freightCatVo"]);

                $_POST["status"] = 1;
                set_value($data["freight_cat"], $_POST);

                $name = $data["freight_cat"]->getName();
                $proc = $this->sc['freightModel']->getFreightCat(array("name" => $name));
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "freight_cat_existed";
                } else {

                    if ($newobj = $this->sc['freightModel']->addFreightCat($data["freight_cat"])) {
                        if ($objlist = $this->sc['freightModel']->getFccNearestAmount($newobj->getId(), $data["freight_cat"]->getWeight())) {
                            foreach ($objlist as $obj) {
                                $obj->setFcatId($newobj->getId());
                                $this->sc['freightModel']->addFcc($obj);
                            }
                        }

                        unset($_SESSION["freightCatVo"]);
                        redirect(base_url() . "mastercfg/freight/index/" . $cat_type . "?" . $_SERVER['QUERY_STRING']);
                    } else {
                        $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                    }
                }
            }
        }

        $this->index($cat_type);
    }

    public function view($origin_country = "")
    {
        $cat_type = "";
        if ($origin_country) {
            $sub_app_id = $this->getAppId() . "02";
            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;
            if ($this->input->post("posted")) {
                $this->sc['freightModel']->saveFreightCatCharge($_POST["value"], $origin_country);
                if (empty($_SESSION["NOTICE"])) {
                    redirect(current_url() . "?" . $_SERVER['QUERY_STRING']);
                }
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            if (empty($sort)) {
                $sort = $cat_type == "weight" ? "cat_name" : "weight";
            }

            if (empty($order)) {
                $order = "asc";
            }

            $option["orderby"] = $sort . " " . $order;

            $full_list = $this->sc['freightModel']->getFullFreightCatChargeList(array("origin_country" => $origin_country), array("orderby" => "fcat_id ASC", "limit" => -1));
            $data["objlist"] = $full_list["value_list"];
            $data["key_freight_list"] = $full_list["key_list"]["frieght_cat_arr"];
            $data["key_country_list"] = $full_list["key_list"]["dest_country_arr"];

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            // $data["origin_country_list"] = $this->sc['freightModel']->getOriginCountryList();
            $data["origin_country"] = $origin_country;
            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
            $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
            $this->load->view('mastercfg/freight/freight_detail_v', $data);
        }
    }

    public function region($courier_id = "")
    {
        if ($courier_id) {
            $sub_app_id = $this->getAppId() . "02";

            $courier = $this->sc['freightModel']->getCourier(array("id" => $courier_id));
            $data["objlist"] = $this->sc['freightModel']->getCourierRegionCountry(array("courier_id" => $courier_id));

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            $data["cmd"] = "view";
            $this->load->view('mastercfg/freight/freight_region_v', $data);
        }
    }

    public function edit($cat_id)
    {
        $sub_app_id = $this->getAppId() . "02";
        $cat_type = $this->input->post("cat_type");

        if ($this->input->post("posted")) {
            unset($_SESSION["NOTICE"]);
            if ($cat_type == "freight") {
                if (isset($_SESSION["freightCatObj"][$cat_id])) {
                    $data["freight_cat"] = unserialize($_SESSION["freightCatObj"][$cat_id]);

                    if ($data["freight_cat"]->getName() != $_POST["name"]) {
                        $proc = $this->sc['freightModel']->getFreightCat(array("name" => $_POST["name"]));
                        if (!empty($proc)) {
                            $_SESSION["NOTICE"] = "freight_cat_existed";
                        }
                    }
                    if (empty($_SESSION["NOTICE"])) {
                        set_value($data["freight_cat"], $_POST);

                        if ($this->sc['freightModel']->updateFreightCat($data["freight_cat"])) {
                            unset($_SESSION["freightCatObj"]);
                            redirect(base_url() . "mastercfg/freight/index/" . $cat_type . "?" . $_SERVER['QUERY_STRING']);
                        } else {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        }
                    }
                }
            } else {
                if (isset($_SESSION["weight_cat_obj"][$cat_id])) {
                    $data["weight_cat"] = unserialize($_SESSION["weight_cat_obj"][$cat_id]);

                    if ($data["weight_cat"]->getWeight() != $_POST["weight"]) {
                        $proc = $this->sc['freightModel']->getWeightCat(array("weight" => $_POST["weight"]));
                        if (!empty($proc)) {
                            $_SESSION["NOTICE"] = "weight_cat_existed";
                        }
                    }
                    if (empty($_SESSION["NOTICE"])) {
                        set_value($data["weight_cat"], $_POST);

                        if ($this->sc['freightModel']->updateWeightCat($data["weight_cat"])) {
                            unset($_SESSION["weight_cat_obj"]);
                            redirect(base_url() . "mastercfg/freight/index/" . $cat_type . "?" . $_SERVER['QUERY_STRING']);
                        } else {
                            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->_error_message();
                        }
                    }
                }
            }
        }
        $this->index($cat_type, $_POST["id"]);

    }

    public function delete($id = "")
    {

    }
}



