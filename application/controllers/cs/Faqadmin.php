<?php
class Faqadmin extends MY_Controller
{
    private $appId = "CS0003";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
    }


    public function index($edit = "", $eid = "", $prod_grp_cd = "")
    {
        $sub_app_id = $this->getAppId() . "01";
        $_SESSION["LISTPAGE"] = ($prod_grp_cd == "" ? base_url() . "marketing/product/?" : current_url()) . $_SERVER['QUERY_STRING'];

        if ($this->input->post("posted")) {
            $wh = [];
            $wh = [
                    'action'=>$this->input->post("action"),
                    'lang_id'=>$this->input->post("lang_id"),
                    'faq_ver'=>$this->input->post("faq_ver")
                  ];
            $this->container['faqadminModel']->save($wh);

            Redirect(base_url() . "cs/faqadmin/?" . $_SERVER["QUERY_STRING"]);
        }

        $where = [];
        $option = [];

        if ($this->input->get("lang_id") != "") {
            $where["l.lang_id LIKE"] = '%' . $this->input->get("lang_id") . '%';
        }

        if ($this->input->get("faq_ver") != "") {
            $where["f.faq_ver LIKE"] = '%' . $this->input->get("lang_id") . '%';
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
            $sort = "lang_id";

        if (empty($order))
            $order = "ASC";

        $option["orderby"] = $sort . " " . $order;


        $data = $this->container['faqadminModel']->getListCnt($where, $option);

        include_once APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;

        unset($where);
        unset($option);

        $data["faq_version"] = ["cveng" => $lang["english"], "cv-fr" => $lang["french"], "cv-de" => $lang["german"], "cv-es" => $lang["espanol"]];
        $data["edit"] = $edit;
        $data["eid"] = $eid;
        $data["notice"] = notice($lang);
        $this->load->view("cs/faqadmin/v_index", $data);

    }

    public function getAppId()
    {
        return $this->appId;
    }

}

