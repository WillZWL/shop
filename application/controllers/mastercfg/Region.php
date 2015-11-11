<?php
include_once "RegionHelper.php";

use ESG\Panther\Models\Mastercfg\RegionModel;
use ESG\Panther\Service\PaginationService;

class Region extends RegionHelper
{
    private $appId = "MST0002";
    private $langId = "en";

    public function __construct()
    {
        parent::__construct();
        $this->authorization_service->check_access_rights($this->getAppId(), "");
        $this->regionModel = new RegionModel;
        $this->paginationService = new PaginationService;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function view($value = "")
    {
        $data = [];
        $data["updated"] = 0;
        $data["editable"] = 1;
        if ($this->input->post('posted')) {
            $error = 0;
            if ($obj = $this->regionModel->getRegion($this->input->post("id"))) {
                $obj->setId($this->input->post("id"));
                $obj->setRegionName($this->input->post("region_name"));
                $obj->setType($this->input->post("region_type"));
                if ($this->regionModel->updateRegion($obj)) {
                    $result = $this->regionModel->delRegionCountry($value);
                    if ($result !== FALSE) {
                        if (!empty($this->input->post('country'))) {
                            $result2 = $this->regionModel->addRegionCountry($value, $this->input->post('country'));
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
        include_once APPPATH . '/language/' . $this->getAppId() . '02_' . $this->getLangId() . '.php';
        $data["lang"] = $lang;
        $data["region_obj"] = $this->regionModel->getRegion($value);
        if (empty($data["region_obj"])) {
            $_SESSION["NOTICE"] = "region_not_found";
            $data["region_obj"] = $this->regionModel->getRegion();
        }
        $data['country_in'] = $this->regionModel->getCountryInRegion($value);
        $data['country_ex'] = $this->regionModel->getCountryEx($this->country_list, $data['country_in']);
        $data['notice'] = notice($lang);
        $data["id"] = $value;
        $this->load->view('mastercfg/region/region_view', $data);
    }

    public function index()
    {
        $_SESSION["notice"] = "";
        $_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];

        $where = [];
        $option = [];

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

        $data = $this->regionModel->getRegionByName($where["region_name"], $where["region_type"], $where["id"], $option);

        $subAppId = $this->getAppId() . "00";

        include_once(APPPATH . "language/" . $subAppId . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->paginationService->initialize($pconfig);

        $data["notice"] = notice();

        $data["showall"] = $this->input->get("showall");
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
//      $data["searchdisplay"] = ($where["region_name"]==""&& $where["region_type"] == ""?'style="display:none"':"");
        $data["searchdisplay"] = "";

        $this->load->view('mastercfg/region/region_index', $data);
    }

    public function getLangId()
    {
        return $this->langId;
    }

    public function add()
    {
        $data = [];
        if ($this->input->post('posted')) {
            $obj = $this->regionModel->getRegion();
            $obj->setId(0);
            $obj->setRegionName($this->input->post("region_name"));
            $obj->setType($this->input->post("region_type"));
            $retobj = $this->regionModel->add_region($obj);
            if ($retobj !== FALSE) {
                if (!$this->regionModel->addRegionCountry($retobj->getId(), $this->input->post("country"))) {
                    $_SESSION["notice"] = "Failed to add city to list";
                } else {
                    Redirect(base_url() . "mastercfg/region/");
                }

            } else {
                echo "false";
                $_SESSION["NOTICE"] = "Failed to add region";
            }
        }

        include_once APPPATH . '/language/' . $this->getAppId() . '01_' . $this->getLangId() . '.php';
        $data["lang"] = $lang;
        $data["region_obj"] = $this->regionModel->getRegion();
        $data["header"] = 'Create a new region';
        $data['title'] = 'Create a new region';
        $data['country_ex'] = $this->country_list;
        $data["notice"] = notice($lang);

        $this->load->view('mastercfg/region/region_add', $data);
    }

}
