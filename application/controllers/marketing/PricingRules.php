<?php
DEFINE("PLATFORM_TYPE", "WEBSITE");



class PricingRules extends MY_Controller
{
    //must set to public for view
    public $default_platform_id;
    private $appId = "MKT0086";
    private $lang_id = "en";

    //private $pricingRulesModel;

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
        $_SESSION["LISTPAGE"] = base_url() . "marketing/pricingRules/?" . $_SERVER['QUERY_STRING'];

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

        $data = $this->sc['pricingRulesModel']->getPricingRulesList($where, $option);

        include_once(APPPATH . "language/" . $this->getAppId() . "00" . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('marketing/pricingRules/index');
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
        $this->load->view('marketing/pricing_rules/pricing_rules_index_v', $data);
    }

    public function add()
    {
        if ($this->input->post("posted")) {
            if (isset($_SESSION["PricingRulesVo"])) {
                $this->sc['pricingRulesModel']->includePricingRulesVo();
                $data["pricingrule"] = unserialize($_SESSION["PricingRulesVo"]);

                set_value($data["pricingrule"], $_POST);

                //$proc = $this->sc['pricingRulesModel']->getPricingRule(["country_id" => $data["pricingrule"]->getCountryId()]);
                if (!$this->checkRule($data["pricingrule"]) == 0)
				{
                    $_SESSION["NOTICE"] = "pricing_rule_existed";
                }
                else
                {
                    if ((double)$this->input->post('min_margin') < 0)
                    {
                        $_SESSION["NOTICE"] = "min_margin_negative";
                    }

					$data["pricingrule"]->setId(0);
					if ($new_obj = $this->sc['pricingRulesModel']->addPricingRules($data["pricingrule"])) {
						unset($_SESSION["PricingRulesVo"]);
						$id = $new_obj->getId();
						redirect(base_url() . "marketing/pricingRules/view/" . $id);
					} else {
						$_SESSION["NOTICE"] = $this->db->error();
					}
                }
            }
        }

        include_once(APPPATH . "language/" . $this->getAppId() . "01" . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
		$data["country_list"] = $this->sc['Country']->getCountryLanguageList();

        if (empty($data["pricingrule"])) {
            if (($data["pricingrule"] = $this->sc['pricingRulesModel']->getPricingRule()) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->error();
            } else {
                $_SESSION["PricingRulesVo"] = serialize($data["pricingrule"]);
            }
        }

        $data["notice"] = notice($lang);
        $data["cmd"] = "add";
        $this->load->view('marketing/pricing_rules/pricing_rules_detail_v', $data);
    }

    public function view($id = "")
    {
        if ($id) {
            global $data;

            if ($this->input->post("posted") && $this->input->post("cmd") == "edit") {

                if (isset($_SESSION["PricingRulesVo"])) {
                    $this->sc['pricingRulesModel']->includePricingRulesVo();
                    $data["pricingrule"] = unserialize($_SESSION["PricingRulesVo"]);

                    if ($data["pricingrule"]->getId() != $_POST["id"]) {
                        $proc = $this->sc['pricingRulesModel']->getPricingRule(["id" => $id]);
                        if (!empty($proc)) {
                            $_SESSION["NOTICE"] = "pricing_rule_existed";
                        }
                    } else {
						set_value($data["pricingrule"], $_POST);

                        if ($this->checkRule($data["pricingrule"]) == 0)
						{
                            if ((double)$this->input->post('min_margin') < 0)
                            {
                                $_SESSION["NOTICE"] = "min_margin_negative";
                            }

							if ($this->sc['pricingRulesModel']->updatePricingRules($data["pricingrule"])) {
								unset($_SESSION["PricingRulesVo"]);
								redirect(base_url() . "marketing/pricingRules/view/" . $id);
							} else {
								$_SESSION["NOTICE"] = $this->db->error();
							}
						}
						else
						{
							$_SESSION["NOTICE"] = "pricing_rule_existed";
						}
                    }
                }
            }

            include_once(APPPATH . "language/" . $this->getAppId() . "02" . "_" . $this->getLangId() . ".php");
			$data["lang"] = $lang;
			$data["country_list"] = $this->sc['Country']->getCountryLanguageList();

            if (empty($data["pricingrule"])) {
                if (($data["pricingrule"] = $this->sc['pricingRulesModel']->getPricingRule(["id" => $id])) === FALSE) {
                    $_SESSION["NOTICE"] = $this->db->error();
                } else {
                    $_SESSION["PricingRulesVo"] = serialize($data["pricingrule"]);
                }
            }

            $data["notice"] = notice($lang);
            $data["cmd"] = "edit";
            $this->load->view('marketing/pricing_rules/pricing_rules_detail_v', $data);
        }
    }

	public function checkRule($pricingrule)
	{
		$where['country_id'] = $this->input->post('country_id');
		$where['(' . $this->input->post('range_min') . ' between range_min and range_max or ' . $this->input->post('range_max') . ' between range_min and range_max)'] = null;

		$where_days = " (";
		if ($this->input->post('monday') == 1)
			$where_days .= " monday = 1 or";

		if ($this->input->post('tuesday') == 1)
			$where_days .= " tuesday = 1 or";

		if ($this->input->post('wednesday') == 1)
			$where_days .= " wednesday = 1 or";

		if ($this->input->post('thursday') == 1)
			$where_days .= " thursday = 1 or";

		if ($this->input->post('friday') == 1)
			$where_days .= " friday = 1 or";

		if ($this->input->post('saturday') == 1)
			$where_days .= " saturday = 1 or";

		if ($this->input->post('sunday') == 1)
			$where_days .= " sunday = 1 or";

		$where_days = substr($where_days, 0, -2);
		$where_days .= ")";

		if ($pricingrule->getId() != "")
		{
			$where['id <>'] = $pricingrule->getId();
		}

        $where[$where_days] = null;

		$data = $this->sc['pricingRulesModel']->getExistingRule($where);

		return $data["existing"];
	}
}


