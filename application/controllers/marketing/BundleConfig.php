<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");


class BundleConfig extends MY_Controller
{
    //must set to public for view
    public $default_platform_id;
    private $appId = "MKT0087";
    private $lang_id = "en";

    //private $bundleConfigModel;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAppId()
    {

        return $this->appId;
    }

    public function index($offset = 0)
    {
        $_SESSION["LISTPAGE"] = base_url() . "marketing/bundleConfig/?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];

        if ($this->input->get("country_id") != "") {
            $where["country_id LIKE "] = "%" . $this->input->get("country_id") . "%";
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        if (empty($sort))
            $sort = "country_id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $option["limit"] = $limit;
        $option["offset"] = $offset;

        $data = $this->sc['bundleConfigModel']->getBundleConfigList($where, $option);

        include_once(APPPATH . "language/" . $this->getAppId() . "00" . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('marketing/bundleConfig/index');
        $config['total_rows'] = $data["total"];
        $config['per_page'] = $limit;

		//var_dump($data);

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $data["notice"] = notice($lang);

        $data["country_list"] = $this->sc['Country']->getCountryLanguageList();

        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('marketing/bundle_config/bundle_config_index_v', $data);
    }

    public function add()
    {
        if ($this->input->post("posted")) {
            if (isset($_SESSION["BundleConfigVo"])) {
                $this->sc['bundleConfigModel']->includeBundleConfigVo();
                $data["bundleconfig"] = unserialize($_SESSION["BundleConfigVo"]);

                set_value($data["bundleconfig"], $_POST);

                if (!$this->checkConfig($data["bundleconfig"]) == 0)
				{
                    $_SESSION["NOTICE"] = "bundle_existed";
                }
                else
                {
					$data["bundleconfig"]->setId(0);
					if ($new_obj = $this->sc['bundleConfigModel']->addBundleConfig($data["bundleconfig"])) {
						unset($_SESSION["BundleConfigVo"]);
						$id = $new_obj->getId();
						redirect(base_url() . "marketing/bundleConfig/view/" . $id);
					} else {
						$_SESSION["NOTICE"] = $this->db->error();
					}
                }
            }
        }

        include_once(APPPATH . "language/" . $this->getAppId() . "01" . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
		$data["country_list"] = $this->sc['Country']->getCountryLanguageList();

        if (empty($data["bundleconfig"])) {
            if (($data["bundleconfig"] = $this->sc['bundleConfigModel']->getBundleConfig()) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->error();
            } else {
                $_SESSION["BundleConfigVo"] = serialize($data["bundleconfig"]);
            }
        }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('marketing/bundle_config/bundle_config_detail_v', $data);
    }

    public function view($id = "")
    {
        if ($id) {
            global $data;

            if ($this->input->post("posted") && $this->input->post("cmd") == "edit") {

                if (isset($_SESSION["BundleConfigVo"])) {
                    $this->sc['bundleConfigModel']->includeBundleConfigVo();
                    $data["bundleconfig"] = unserialize($_SESSION["BundleConfigVo"]);

                    if ($data["bundleconfig"]->getId() != $_POST["id"]) {
                        $proc = $this->sc['bundleConfigModel']->getBundleConfig(["id" => $id]);
                        if (!empty($proc)) {
                            $_SESSION["NOTICE"] = "bundle_existed";
                        }
                    } else {
						set_value($data["bundleconfig"], $_POST);

                        if ($this->checkConfig($data["bundleconfig"]) == 0)
						{
							if ($this->sc['bundleConfigModel']->updatebundleConfig($data["bundleconfig"])) {
								unset($_SESSION["BundleConfigVo"]);
								redirect(base_url() . "marketing/bundleConfig/view/" . $id);
							} else {
								$_SESSION["NOTICE"] = $this->db->error();
							}
						}
						else
						{
							$_SESSION["NOTICE"] = "bundle_existed";
						}
                    }
                }
            }

            include_once(APPPATH . "language/" . $this->getAppId() . "02" . "_" . $this->getLangId() . ".php");
			$data["lang"] = $lang;
			$data["country_list"] = $this->sc['Country']->getCountryLanguageList();

            if (empty($data["bundleconfig"])) {
                if (($data["bundleconfig"] = $this->sc['bundleConfigModel']->getBundleConfig(["id" => $id])) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->error();
                } else {
                    $_SESSION["BundleConfigVo"] = serialize($data["bundleconfig"]);
                }
            }

            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('marketing/bundle_config/bundle_config_detail_v', $data);
        }
    }

	public function checkConfig($bundleconfig)
	{
		return false;
	}
}


