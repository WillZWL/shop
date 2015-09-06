<?php
use AtomV2\Models\Mastercfg\BrandModel;
use AtomV2\Service\PaginationService;

class Brand extends MY_Controller
{
    private $appId = "MST0006";

    public function __construct()
    {
        parent::__construct();
        $this->brandModel = new BrandModel;
        $this->paginationService = new PaginationService;
    }

    public function index($offset = 0)
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "mastercfg/brand/?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];

        if ($this->input->get("brand_name") != "") {
            $where["b.brand_name LIKE "] = "%" . $this->input->get("brand_name") . "%";
        }
        if ($this->input->get("regions") != "") {
            $where["b.regions"] = "%" . $this->input->get("regions") . "%";
        }
        if ($this->input->get("status") != "") {
            $where["b.status"] = $this->input->get("status");
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        if (empty($sort))
            $sort = "brand_name";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        $data = $this->brandModel->getBrandList($where, $option);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('mastercfg/brand/index');
        $config['total_rows'] = $data["total"];
        $config['per_page'] = $limit;

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('mastercfg/brand/brand_index_v', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function add()
    {

        $sub_app_id = $this->getAppId() . "01";

        if ($this->input->post("posted")) {
            if (isset($_SESSION["brand_vo"])) {
                $this->brandModel->includeBrandVo();
                $data["brand"] = unserialize($_SESSION["brand_vo"]);

                $_POST["status"] = 1;
                set_value($data["brand"], $_POST);

                $proc = $this->brandModel->getBrand(["brand_name" => $data["brand"]->getBrandName()]);
                if (!empty($proc)) {
                    $_SESSION["NOTICE"] = "brand_existed";
                } else {
                    $data["brand"]->setId(0);
                    if ($new_obj = $this->brandModel->addBrand($data["brand"])) {
                        unset($_SESSION["brand_vo"]);
                        $id = $new_obj->getId();
                        redirect(base_url() . "mastercfg/brand/view/" . $id);
                    } else {
                        $_SESSION["NOTICE"] = $this->db->error();
                    }
                }
            }
        }

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        if (empty($data["brand"])) {
            if (($data["brand"] = $this->brandModel->getBrand()) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->error();
            } else {
                $_SESSION["brand_vo"] = serialize($data["brand"]);
            }
        }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('mastercfg/brand/brand_detail_v', $data);
    }

    public function view($id = "")
    {
        if ($id) {
            $sub_app_id = $this->getAppId() . "02";
            global $data;

            if ($this->input->post("posted") && $this->input->post("cmd") == "edit") {

                if (isset($_SESSION["brand_vo"])) {
                    $this->brandModel->includeBrandVo();
                    $data["brand"] = unserialize($_SESSION["brand_vo"]);

                    if ($data["brand"]->getId() != $_POST["id"]) {
                        $proc = $this->brandModel->getBrand(["id" => $id]);
                        if (!empty($proc)) {
                            $_SESSION["NOTICE"] = "brand_existed";
                        }
                    } else {
                        set_value($data["brand"], $_POST);

                        if ($this->brandModel->updateBrand($data["brand"])) {
                            unset($_SESSION["brand_vo"]);
                            redirect(base_url() . "mastercfg/brand/view/" . $id);
                        } else {
                            $_SESSION["NOTICE"] = $this->db->error();
                        }
                    }
                }
            }

            include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
            $data["lang"] = $lang;

            if (empty($data["brand"])) {
                if (($data["brand"] = $this->brandModel->getBrand(["id" => $id])) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->error();
                } else {
                    $_SESSION["brand_vo"] = serialize($data["brand"]);
                }
            }

            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('mastercfg/brand/brand_detail_v', $data);
        }
    }

    public function del_region()
    {
        if ($this->input->post("posted") && ($brand_id = $this->input->post("brand_id"))) {
            foreach ($_POST["check"] as $cur_brand) {
                if ($this->brandModel->delBrandRegion(["brand_id" => $brand_id, "sales_region_id" => $_POST["del_sales_region_id"][$cur_brand], "src_region_id" => $_POST["del_src_region_id"][$cur_brand]]) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->error();
                }
            }
            redirect(base_url() . "mastercfg/brand/view/" . $brand_id);
        }
    }

    public function js_brandlist()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->brandModel->getBrandList(["status" => 1], ["orderby" => "brand_name ASC", "limit" => -1]);
        foreach ($objlist["brandlist"] as $obj) {
            $sid = str_replace("'", "\'", $obj->getId());
            $name = str_replace("'", "\'", $obj->getBrandName());
            $slist[] = "'" . $sid . "':'" . $name . "'";
        }
        $js = "brandlist = {" . implode(", ", $slist) . "};";
        $js .= "
            function InitBrand(obj)
            {
                for (var i in brandlist){
                    obj.options[obj.options.length]=new Option(brandlist[i], i);
                }
            }";
        echo $js;
    }
}


