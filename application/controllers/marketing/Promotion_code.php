<?php

class Promotion_code extends MY_Controller
{

    private $appId = "MKT0017";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
    }

    public function index($offset = 0)
    {
        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];

        $submit_search = 0;

        if ($this->input->get("code") != "") {
            $where["code LIKE "] = "%" . $this->input->get("code") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("description") != "") {
            $where["description LIKE "] = "%" . $this->input->get("description") . "%";
            $submit_search = 1;
        }

        if ($this->input->get("expire_date") != "") {
            fetch_operator($where, "expire_date", $this->input->get("expire_date"));
            $submit_search = 1;
        }

        if ($this->input->get("no_taken") != "") {
            fetch_operator($where, "no_taken", $this->input->get("no_taken"));
            $submit_search = 1;
        }

        if ($this->input->get("status") != "") {
            $where["status"] = $this->input->get("status");
            $submit_search = 1;
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        if (empty($sort)) {
            $sort = "expire_date desc, create_on";
        }

        if (empty($order)) {
            $order = "desc";
        }

        $option["orderby"] = $sort . " " . $order;

        $data["objlist"] = $this->sc['PromotionCode']->getDao('PromotionCode')->getList($where, $option);
        $data["total"] = $this->sc['PromotionCode']->getDao('PromotionCode')->getNumRows($where);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('marketing/promotion_code/index');
        $config['total_rows'] = $data["total"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();


        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('marketing/promotion_code/promotion_code_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function add()
    {

        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["promotion_code_vo"])) {
                $data["promotion_code"] = unserialize($_SESSION["promotion_code_vo"]);
                set_value($data["promotion_code"], $_POST);
                foreach ($_POST["relevant_prod"] as $d_product) {
                    if ($d_product != "") {
                        $relevant_prod[] = $d_product;
                    }
                }
                if ($_POST['disc_type'] == 'FD') {
                    $disc_level_value = $_POST['disc_level_value']['FD'];
                } else {
                    switch ($_POST["disc_level"]) {
                        case "SCAT":
                            $disc_level_value = $_POST["disc_level_value"]["CAT"] . "," . $_POST["disc_level_value"][$_POST["disc_level"]];
                            break;
                        case "SSCAT":
                            $disc_level_value = $_POST["disc_level_value"]["CAT"] . "," . $_POST["disc_level_value"]["SCAT"] . "," . $_POST["disc_level_value"][$_POST["disc_level"]];
                            break;
                        case "PD":
                            $disc_level_value = trim(@implode(",", $_POST["disc_level_value"][$_POST["disc_level"]]), ',');
                            break;
                        default:
                            $disc_level_value = $_POST["disc_level_value"][$_POST["disc_level"]];
                    }
                }
                foreach ($_POST["week_day"] as $day) {
                    $week_day[] = $day;
                }

                foreach ($_POST["redemption_prod_value"] as $prod_value) {
                    $redemption_prod_value[] = $prod_value;
                }

                foreach ($_POST["free_item_sku"] as $item_sku) {
                    $free_item_sku[] = $item_sku;
                }

                $data["promotion_code"]->setWeekDay(implode(",", $week_day));
                $data["promotion_code"]->setRedemptionProdValue(implode(",", $redemption_prod_value));
                    $data["promotion_code"]->setFreeItemSku(implode(",", $free_item_sku));
                $data["promotion_code"]->setDiscLevelValue($disc_level_value);
                $data["promotion_code"]->setRelevantProd(trim(@implode(",", $relevant_prod), ','));
                if (substr($prefix = rtrim($this->input->post("prefix")), -1) == "%") {
                    $new_promotion_code = substr($prefix, 0, -1) . hash("crc32", mktime());
                } else {
                    $new_promotion_code = $prefix;
                }
                $data["promotion_code"]->setCode($new_promotion_code);
                if ($new_obj = $this->sc['PromotionCode']->getDao('PromotionCode')->insert($data["promotion_code"])) {
                    unset($_SESSION["promotion_code_vo"]);
                    redirect($_SESSION["LISTPAGE"]);
                } else {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        if (empty($data["promotion_code"])) {
            if (($data["promotion_code"] = $this->sc['PromotionCode']->getDao('PromotionCode')->get()) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                $_SESSION["promotion_code_vo"] = serialize($data["promotion_code"]);
            }
        }

        $data["country_list"] = $this->sc['Region']->getSellCountryList();
        $data['delivery_option_list'] = $this->sc['Courier']->getDao('Courier')->getList(['weight_type' => 'CH']);
        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('marketing/promotion_code/promotion_code_detail_v', $data);
    }

    public function view($code = "")
    {
        if ($code) {
            $sub_app_id = $this->getAppId() . "02";

            if ($this->input->post("posted")) {
                unset($_SESSION["NOTICE"]);
                if ($data["promotion_code"] = $this->sc['PromotionCode']->getDao('PromotionCode')->get(["code" => $code])) {
                    set_value($data["promotion_code"], $_POST);
                    foreach ($_POST["relevant_prod"] as $d_product) {
                        if ($d_product != "") {
                            $relevant_prod[] = $d_product;
                        }
                    }
                    if ($_POST['disc_type'] == 'FD') {
                        $disc_level_value = $_POST['disc_level_value']['FD'];
                    } else {
                        switch ($_POST["disc_level"]) {
                            case "SCAT":
                                $disc_level_value = $_POST["disc_level_value"]["CAT"] . "," . $_POST["disc_level_value"][$_POST["disc_level"]];
                                break;
                            case "SSCAT":
                                $disc_level_value = $_POST["disc_level_value"]["CAT"] . "," . $_POST["disc_level_value"]["SCAT"] . "," . $_POST["disc_level_value"][$_POST["disc_level"]];
                                break;
                            case "PD":
                                $disc_level_value = trim(@implode(",", $_POST["disc_level_value"][$_POST["disc_level"]]), ',');
                                break;
                            default:
                                $disc_level_value = $_POST["disc_level_value"][$_POST["disc_level"]];
                        }
                    }

                    foreach ($_POST["week_day"] as $day) {
                        $week_day[] = $day;
                    }

                    foreach ($_POST["redemption_prod_value"] as $prod_value) {
                        $redemption_prod_value[] = $prod_value;
                    }

                    foreach ($_POST["free_item_sku"] as $item_sku) {
                        $free_item_sku[] = $item_sku;
                    }

                    $data["promotion_code"]->setWeekDay(implode(",", $week_day));
                    $data["promotion_code"]->setRedemptionProdValue(implode(",", $redemption_prod_value));
                    $data["promotion_code"]->setFreeItemSku(implode(",", $free_item_sku));
                    $data["promotion_code"]->setDiscLevelValue($disc_level_value);
                    $data["promotion_code"]->setRelevantProd(trim(@implode(",", $relevant_prod), ','));

                    if ($this->sc['PromotionCode']->getDao('PromotionCode')->update($data["promotion_code"])) {
                        unset($_SESSION["promotion_code_obj"]);
                        redirect(base_url() . "marketing/promotion_code/view/" . $code);
                    } else {
                        $_SESSION["NOTICE"] = $this->db->_error_message();
                    }
                }
            }

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            if (empty($data["promotion_code"])) {
                if (($data["promotion_code"] = $this->sc['PromotionCode']->getDao('PromotionCode')->get(["code" => $code])) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                } else {
                    unset($_SESSION["promotion_code_obj"]);
                    $_SESSION["promotion_code_obj"][$code] = serialize($data["promotion_code"]);
                }
            }

            $data["country_list"] = $this->sc['Region']->getSellCountryList();
            $data['delivery_option_list'] = $this->sc['Courier']->getDao('Courier')->getList(['weight_type' => 'CH']);
            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('marketing/promotion_code/promotion_code_detail_v', $data);
        }
    }
}


