<?php

class SellerSetting extends MY_Controller
{
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'object', 'directory', 'notice'));
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    // TODO set best seller for each category
    public function index($platform_id = '', $catid = 0)
    {
        $type = $this->getType();
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
        if ($platform_id) {
            $where['ll.platform_id'] = $platform_id;
            $where['ll.type'] = $type;
            $where['ll.catid'] = 0;
            $seller_list = $this->sc['LandpageListing']->getLandpageList($where, ['limit' => -1]);
            if ($seller_list) {
                $data['seller_list'] = $seller_list;
            }
            $data["platform_id"] = $platform_id;
        }
        $sub_app_id = $this->getAppId().'01';
        include_once APPPATH.'language/'.$sub_app_id.'_'.$this->getLangId().'.php';
        $data['lang'] = $lang;
        $data["notice"] = notice($lang, TRUE);
        $data['selling_platform'] = $this->sc['SellingPlatform']->getList(['status' => 1, 'type' => 'WEBSITE'], ['limit' => -1]);
        $data['catid'] = $catid;
        $data['handle'] = $this->getHandle();
        $data['limit'] = $this->getLimit();
        $this->load->view('marketing/seller_setting/index', $data);
    }

    public function prodList($line = "", $platform_id = "")
    {
        if ($platform_id == "") {
            show_404();
        }
        $sub_app_id = $this->getAppId() . "00";
        $_SESSION["LISTPAGE"] = current_url() . "?" . $_SERVER['QUERY_STRING'];
        $where = [];
        $option = [];
        $where["p.status"] = 2;
        $where['pr.listing_status'] = 'L';
        $where['p.website_status <>'] = 'O';
        $where["pr.platform_id"] = $platform_id;
        $submit_search = 0;
        if ($this->input->get("sku") != "") {
            $where["p.sku LIKE "] = "%" . $this->input->get("sku") . "%";
            $submit_search = 1;
        }
        if ($this->input->get("name") != "") {
            $where["p.name LIKE "] = "%" . $this->input->get("name") . "%";
            $submit_search = 1;
        }
        if ($this->input->get("cat_id") != "") {
            $where["p.cat_id"] = $this->input->get("cat_id");
            $submit_search = 1;
        }
        if ($this->input->get("sub_cat_id") != "") {
            $where["p.sub_cat_id"] = $this->input->get("sub_cat_id");
            $submit_search = 1;
        }
        if ($this->input->get("sub_sub_cat_id") != "") {
            $where["p.sub_sub_cat_id"] = $this->input->get("sub_sub_cat_id");
            $submit_search = 1;
        }
        if ($this->input->get("brand_id") != "") {
            $where["p.brand_id"] = $this->input->get("brand_id");
            $submit_search = 1;
        }
        if ($this->input->get("website_status") != "") {
            if ($this->input->get("website_status") == "I") {
                $where["p.website_status"] = "I";
                $where["p.website_quantity >"] = "0";
            } elseif ($this->input->get("website_status") == "O") {
                $where["((p.website_status = 'I' && p.website_quantity <1) OR p.website_status = 'O')"] = null;
            } else {
                $where["p.website_status"] = $this->input->get("website_status");
            }
            $submit_search = 1;
        }
        $sort = $this->input->get("sort");
        $order = $this->input->get("order");
        $limit = '20';
        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }
        if (!empty($sort) && !empty($order)) {
            $option["orderby"] = $sort . " " . $order;
        }
        if ($this->input->get("search")) {
            $option["show_name"] = 1;
            $data["objlist"] = $this->sc['Product']->getProductOverview($where, $option);
            $data["total"] = $this->sc['Product']->getProductOverview($where, ["num_rows" => 1]);
        }
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $pconfig['total_rows'] = $data['total'];
        $data["notice"] = notice($lang);
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["line"] = $line;
        $data['handle'] = $this->getHandle();
        $this->load->view('marketing/seller_setting/prod_list_v', $data);
    }

    public function update()
    {
        $platform_id = $this->input->post('platform_id');
        $catid = $this->input->post('catid');
        $item_list = $this->input->post('item');
        $type = $this->getType();
        $handle = $this->getHandle();
        if ($platform_id && $item_list) {
            $i = 0;
            foreach ($item_list as $k => $item) {
                $sku = $item['sku'];
                $rank = $item['rank'];
                $mode = $item['mode'];
                $item_arr = [
                                'catid' => $catid,
                                'platform_id' => $platform_id,
                                'type' => $type,
                                'mode' => $mode,
                                'rank' => $rank
                            ];
                $where = ['catid' => $catid, 'platform_id' => $platform_id, 'rank' => $rank, 'type'=>$type];
                if ($sku) {
                    $prod_obj = $this->sc['Product']->getDao('Product')->get(['sku'=>$sku]);
                    if ($prod_obj) {
                        $landpage_obj = $this->sc['LandpageListing']->getDao('LandpageListing')->get($where);
                        if ($landpage_obj) {
                            $landpage_obj->setMode($mode);
                            $landpage_obj->setSelection($sku);
                            $res = $this->sc['LandpageListing']->getDao('LandpageListing')->update($landpage_obj, $where);
                        } else {
                            $item_arr['sku'] = $sku;
                            $res = $this->insertItem($item_arr);
                        }
                    } else {
                        $_SESSION["NOTICE"] = 'Sku not exists in Panther';
                    }
                } elseif ($mode == 'A') {
                    $item_arr['sku'] = '';
                    $landpage_obj = $this->sc['LandpageListing']->getDao('LandpageListing')->get($where);
                    if (!$landpage_obj) {
                        $res = $this->insertItem($item_arr);
                    }
                }
                if ($res === false) {
                    $i++;
                }
            }
            if ($i > 0) {
                $_SESSION["NOTICE"] = 'Update FALSE';
            } else {
                $_SESSION["NOTICE"] = 'Update Success';
                redirect(base_url()."marketing/$handle/index/$platform_id");
            }
        } else {
            redirect(base_url().'marketing/$handle');
        }
    }

    public function insertItem($item)
    {
        $landpage_vo = $this->sc['LandpageListing']->getDao('LandpageListing')->get();
        $landpage_obj = clone $landpage_vo;
        $landpage_obj->setCatid($item['catid']);
        $landpage_obj->setPlatformId($item['platform_id']);
        $landpage_obj->setType($item['type']);
        $landpage_obj->setMode($item['mode']);
        $landpage_obj->setRank($item['rank']);
        $landpage_obj->setSelection($item['sku']);
        return  $this->sc['LandpageListing']->getDao('LandpageListing')->insert($landpage_obj);
    }

    public function autoUpdate($platform_id)
    {
        $type = $this->getType();
        $handle = $this->getHandle();
        $res = $this->sc['LandpageListing']->updateByPlatformAndType($platform_id, $type);
        if ($res) {
            $_SESSION["NOTICE"] = $res;
        }
        redirect(base_url()."marketing/$handle/index/$platform_id");
    }
}
?>