<?php

class Category extends MY_Controller
{
    private $appId = "MKT0002";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;
        $this->load->view('marketing/category/category_main', $data);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getlayer()
    {
        if ($this->input->get('id') == "") {
            return;
        } else {
            $thisobj = $this->container['categoryModel']->getCatObj($this->input->get('id'));
            if ($thisobj->getLevel() < 3) {
                $where["parent_cat_id"] = $this->input->get('id');
                $option["sortby"] = "name ASC";
                $data = $this->container['categoryModel']->getCatListIndex($where, $option);

            } else {
                $data["category_list"] = $this->container['categoryModel']->getProductBySscat($this->input->get('id'));
                $data["total"] = $this->container['categoryModel']->countProduct($this->input->get('id'));
            }
            $data["thisobj"] = $thisobj;
            $this->load->view('marketing/category/category_rlist', $data);
        }
    }

    public function getnext()
    {
        if ($this->input->get('id') == "" || $this->input->get('level') == "") {
            return;
        } else {
            $list = $this->container['categoryModel']->getlistcnt($this->input->get('level'), $this->input->get('id'), $this->input->get('status'));
            $data["objlist"] = $list;
            $data["level"] = $this->input->get('level');
            $this->load->view('marketing/category/category_llist', $data);
        }
    }

    public function top()
    {
        $sub_app_id = $this->getAppId() . "00";

        $_SESSION["LISTPAGE"] = base_url() . "marketing/category/?" . $_SERVER['QUERY_STRING'];

        $where = [];
        $option = [];


        $where["name"] = $this->input->get("name");
        $where["description"] = $this->input->get("description");
        $where["level"] = $this->input->get("level");
        $where["status"] = $this->input->get("status");
        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = "marketing/category/top/?" . $_SERVER['QUERY_STRING'];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"]) {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data = $this->container['categoryModel']->getCatListIndex($where, $option);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        //$this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);


        $data["refresh"] = $this->input->get("refresh");
        $data["added"] = $this->input->get("added");
        $data["updated"] = $this->input->get("updated");

        $data["showall"] = $this->input->get("showall");
        $data["sortimg"][$sort] = "<img src='" . base_url() . "images/" . $order . ".gif'>";
        $data["xsort"][$sort] = $order == "asc" ? "desc" : "asc";
        $data["searchdisplay"] = "";
        $this->load->view('marketing/category/category_index', $data);
    }

    public function add()
    {
        $data = [];
        $data["added"] = 0;
        if ($this->input->post('add')) {
            $cat_obj = $this->container['categoryModel']->getCatObj();

            $cat_obj->setLevel($this->input->post('level'));
            $cat_obj->setStatus($this->input->post('status'));
            $cat_obj->setName($this->input->post('name'));
            $cat_obj->setDescription($this->input->post('description'));
            $cat_obj->setParentCatId($this->input->post('parent_cat_id'));


            $ret = $this->container['categoryModel']->addCategory($cat_obj);
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "Error " . __LINE__ . ": " . $this->db->_error_message();
            } else {
                unset($_SESSION["NOTICE"]);
                redirect(base_url() . "marketing/category/top/?refresh=1&added=1");

            }
        }

        include_once APPPATH . "language/" . $this->getAppId() . "03_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["level"] = $this->input->get('level');
        $data["parent"] = $this->input->get('parent');
        $data["notice"] = notice($lang);
        if ($this->input->get('level') == 2) {
            $parent_obj = $this->container['categoryModel']->getCatObj($this->input->get('parent'));
        } else if ($this->input->get('level') == 3) {
            $parent_obj = $this->container['categoryModel']->getParent(2, $this->input->get('parent'));
        } else {
            $parent_obj = "";
        }
        $data['parent_obj'] = $parent_obj;
        $this->load->view("marketing/category/category_add", $data);
    }

    public function view($value)
    {
        $data = [];
        $canadd = 1;
        $canedit = 1;
        $inherit = 0;
        define('IMG_PH', $this->container['contextConfigService']->valueOf("cat_img_path"));
        if (strpos($value, '-1') !== false) {
            $value = str_replace('-1', '', $value);
            $inherit = 1;
        }

        if ($this->input->post('posted')) {
            $this->container['categoryService']->getCategoryExtendDao()->get();
            $data["cat_ext"] = unserialize($_SESSION["cat_ext"]);
            $cat_ext_vo = $this->container['categoryService']->getCategoryExtendDao()->get();

            foreach ($_POST["lang_name"] as $rs_lang_id => $rs_name) {
                if ($rs_name) {
                    if (empty($data["cat_ext"][$value][$rs_lang_id])) {
                        $data["cat_ext"][$value][$rs_lang_id] = clone $cat_ext_vo;
                        $data["cat_ext"][$value][$rs_lang_id]->setCatId($value);
                        $data["cat_ext"][$value][$rs_lang_id]->setLangId($rs_lang_id);
                        $action = "insert";
                    } else {
                        $action = "update";
                    }

                    $data["cat_ext"][$value][$rs_lang_id]->setName($rs_name);
                    if (!$this->container['categoryService']->getCategoryExtendDao()->$action($data["cat_ext"][$value][$rs_lang_id])) {
                        $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                    } else {
                        $config['upload_path'] = IMG_PH;
                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $config['overwrite'] = TRUE;
                        $config['is_image'] = TRUE;
                        $this->load->library('upload', $config);

                        if ($_FILES["image_file_" . $rs_lang_id]["name"]) {
                            $config['file_name'] = $value . "_" . $rs_lang_id;
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload("image_file_" . $rs_lang_id)) {
                                $res = $this->upload->data();
                                $ext = substr($res["file_ext"], 1);
                                if (!$cat_cont_obj = $this->container['categoryModel']->getCatContObj(array("cat_id" => $value, "lang_id" => $rs_lang_id))) {
                                    $cat_cont_obj = $this->container['categoryService']->getCategoryContentDao()->get();
                                    $cat_cont_obj->setCatId($value);
                                    $cat_cont_obj->setLangId($rs_lang_id);
                                    $cat_cont_obj->setImage($ext);
                                    if (!$this->container['categoryService']->getCategoryContentDao()->insert($cat_cont_obj)) {
                                        $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                                    }
                                } else {
                                    $cat_cont_obj->setImage($ext);
                                    if (!$this->container['categoryService']->getCategoryContentDao()->update($cat_cont_obj)) {
                                        $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                                    }
                                }
                            } else {
                                $_SESSION["NOTICE"] = $this->upload->display_errors();
                            }
                        }

                        if (!empty($_FILES["flash_file_" . $rs_lang_id]["name"])) {
                            $config['allowed_types'] = 'swf';
                            $config['file_name'] = $value . "_" . $rs_lang_id;
                            $config['is_image'] = FALSE;
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload("flash_file_" . $rs_lang_id)) {
                                $res = $this->upload->data();
                                $ext = substr($res["file_ext"], 1);
                                if (!$cat_cont_obj = $this->container['categoryModel']->getCatContObj(array("cat_id" => $value, "lang_id" => $rs_lang_id))) {
                                    $cat_cont_obj = $this->container['categoryService']->getCategoryContentDao()->get();
                                    $cat_cont_obj->setCatId($value);
                                    $cat_cont_obj->setLangId($rs_lang_id);
                                    $cat_cont_obj->setFlash($ext);
                                    if (!$this->container['categoryService']->getCategoryContentDao()->insert($cat_cont_obj)) {
                                        $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                                    }
                                } else {
                                    $cat_cont_obj->setFlash($ext);
                                    if (!$this->container['categoryService']->getCategoryContentDao()->update($cat_cont_obj)) {
                                        $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                                    }
                                }
                            } else {
                                $_SESSION["NOTICE"] = $this->upload->display_errors();
                            }
                        }
                    }
                }
            }

            $this->container['categoryModel']->autoload();
            $cat_obj = unserialize($_SESSION["category_edit"]);
            $cat_obj->setName($this->input->post('name'));
            $cat_obj->setDescription($this->input->post('description'));
            $cat_obj->setLevel($this->input->post('level'));
            $cat_obj->setStatus($this->input->post('status'));
            $cat_obj->setPriority($this->input->post('priority') ? $this->input->post('priority') : '');
            $cat_obj->setAddColourName($this->input->post('add_colour_name'));
            $cat_obj->setBundleDiscount($this->input->post('bundle_discount'));
            $ccmap = array(
                array('country' => 'AU', 'code' => $this->input->post('hscode_AU')),
                array('country' => 'BE', 'code' => $this->input->post('hscode_BE')),
                array('country' => 'CH', 'code' => $this->input->post('hscode_CH')),
                array('country' => 'ES', 'code' => $this->input->post('hscode_ES')),
                array('country' => 'FI', 'code' => $this->input->post('hscode_FI')),
                array('country' => 'FR', 'code' => $this->input->post('hscode_FR')),
                array('country' => 'GB', 'code' => $this->input->post('hscode_GB')),
                array('country' => 'HK', 'code' => $this->input->post('hscode_HK')),
                array('country' => 'ID', 'code' => $this->input->post('hscode_ID')),
                array('country' => 'IE', 'code' => $this->input->post('hscode_IE')),
                array('country' => 'IT', 'code' => $this->input->post('hscode_IT')),
                array('country' => 'MT', 'code' => $this->input->post('hscode_MT')),
                array('country' => 'MY', 'code' => $this->input->post('hscode_MY')),
                array('country' => 'NZ', 'code' => $this->input->post('hscode_NZ')),
                array('country' => 'PH', 'code' => $this->input->post('hscode_PH')),
                array('country' => 'PL', 'code' => $this->input->post('hscode_PL')),
                array('country' => 'PT', 'code' => $this->input->post('hscode_PT')),
                array('country' => 'RU', 'code' => $this->input->post('hscode_RU')),
                array('country' => 'SG', 'code' => $this->input->post('hscode_SG')),
                array('country' => 'TH', 'code' => $this->input->post('hscode_TH')),
                array('country' => 'US', 'code' => $this->input->post('hscode_US'))
            );
            $cccount = count($ccmap);

            for ($i = 0; $i < $cccount; $i++) {
                $cc_obj = $this->container['customClassModel']->getCustomClass(array('country_id' => $ccmap[$i]['country'], 'code' => $ccmap[$i]['code']));

                if ($cc_obj) {
                    $ccm_obj = $this->container['customClassModel']->getCustomClassMapping(array('sub_cat_id' => $value, 'country_id' => $ccmap[$i]['country']));
                    $ccm_dao = $this->container['customClassModel']->customClassificationMappingService->getDao();
                    $ccm_vo = $ccm_dao->get();

                    if (!$ccm_obj) {
                        $action = "insert_ccm";
                        $ccm_obj = clone($ccm_vo);
                        $ccm_obj->setSubCatId($value);
                        $ccm_obj->setCountryId($ccmap[$i]['country']);
                        $ccm_obj->setCustomClassId($cc_obj->getId());
                    } else {
                        $action = "update_ccm";
                        $ccm_obj->setCustomClassId($cc_obj->getId());
                    }

                    if ($this->container['customClassModel']->$action($ccm_obj) === FALSE) {
                        $error_message = __LINE__ . "category.php " . $action . " Error. " . $ccm_dao->db->_error_message();
                        $_SESSION["NOTICE"] = $error_message;
                    }

                } else {
                    $cc_dao = $this->container['customClassModel']->customClassService->getDao();
                    $cc_vo = $cc_dao->get();
                    $action = "add_cc";
                    $cc_obj = clone($cc_vo);
                    $cc_obj->setCountryId($ccmap[$i]['country']);
                    $cc_obj->setCode($ccmap[$i]['code']);
                    $cc_obj->setDescription($this->input->post('name'));
                    $cc_obj->setDutyPcent($ccmap[$i]['duty']);

                    if ($this->container['customClassModel']->$action($cc_obj) === FALSE) {
                        $error_message = __LINE__ . "category.php " . $action . " Error. " . $cc_dao->db->_error_message();
                        $_SESSION["NOTICE"] = $error_message;
                    }

                    $ccm_obj = $this->container['customClassModel']->getCustomClassMapping(array('sub_cat_id' => $value, 'country_id' => $ccmap[$i]['country']));
                    $ccm_dao = $this->container['customClassModel']->customClassificationMappingService->getDao();
                    $ccm_vo = $ccm_dao->get();
                    if (!$ccm_obj) {
                        $action = "insert_ccm";
                        $ccm_obj = clone($ccm_vo);
                        $ccm_obj->setSubCatId($value);
                        $ccm_obj->setCountryId($ccmap[$i]['country']);
                        $ccm_obj->setCustomClassId($cc_obj->getId());
                    } else {
                        $action = "update_ccm";
                        $ccm_obj->setCustomClassId($cc_obj->getId());
                    }

                    if ($this->container['customClassModel']->$action($ccm_obj) === FALSE) {
                        $error_message = __LINE__ . "category.php " . $action . " Error. " . $ccm_dao->db->_error_message();
                        $_SESSION["NOTICE"] = $error_message;
                    }

                }
            }

            if ($this->input->post('level') == 1) {
                $cat_obj->setParentCatId(0);
            } else {
                $cat_obj->setParentCatId($this->input->post('subcat'));
            }

            $ret = $this->container['categoryModel']->updateCategory($cat_obj);
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "Update Failed";
            } else {
                unset($_SESSION["category_edit"]);
                unset($_SESSION["cat_ext"]);
                redirect(base_url() . "marketing/category/view/" . $value);
            }
        }
        $cat_obj = $this->container['categoryModel']->getCatObj($value);
        $cat_cont_obj = $this->container['categoryModel']->getCatContList(array("cat_id" => $value));
        $data["cat_obj"] = $cat_obj;
        $data["cat_cont_obj"] = $cat_cont_obj;
        $data["canadd"] = $canadd;
        $data["canedit"] = $canedit;
        $parent = $cat_obj->getParentCatId();
        $all = '1';
        $data['optionhs'] = $this->container['customClassModel']->getCustomClassOption($all);

        $parent_list = "";
        $child_list = "";
        $subcat_list = "";
        $uarr = "";
        $data['upcode'] = [];
        if ($cat_obj->getLevel() == "3") {
            $parent_list = $this->container['categoryModel']->categoryService->getList(array("level" => "2", "id <>" => "0"), array("limit" => -1));
            $subcat_list = $this->container['categoryModel']->getParent(3, $cat_obj->getId());
            if ($inherit == 1) {
                $hs_obj = $this->container['customClassModel']->getCustomClassByCatSubId($parent);
                $data['hs'] = $this->container['customClassModel']->getCustomClassByCatSubId($parent);
                $udesc = [];
                for ($i = 0; $i < count($data['hs']); $i++) {
                    $udesc[$i]['code'] = $data['hs'][$i]['code'];
                    $udesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->container['customClassModel']->getCustomClassByCatSubId($parent);
                $updesc = [];
                for ($i = 0; $i < count($data['parcode']); $i++) {
                    $updesc[$i]['code'] = $data['parcode'][$i]['code'];
                    $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['upcode'] = $this->arrayUnique($updesc);

                for ($i = 0; $i < count($data['hs']); $i++) {
                    $uarr .= '"' . $data['hs'][$i]['country_id'] . '"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;
            } else {
                $hs_obj = $this->container['customClassModel']->getCustomClassByCatSubId($cat_obj->getId());
                $data['hs'] = $this->container['customClassModel']->getCustomClassByCatSubId($cat_obj->getId());
                $udesc = [];
                for ($i = 0; $i < count($data['hs']); $i++) {
                    $udesc[$i]['code'] = $data['hs'][$i]['code'];
                    $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->container['customClassModel']->getCustomClassByCatSubId($parent);
                $updesc = [];
                for ($i = 0; $i < count($data['parcode']); $i++) {
                    $updesc[$i]['code'] = $data['parcode'][$i]['code'];
                    $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['upcode'] = $this->arrayUnique($updesc);

                for ($i = 0; $i < count($data['hs']); $i++) {
                    $uarr .= '"' . $data['hs'][$i]['country_id'] . '"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;
            }

        } else if ($cat_obj->getLevel() == "2") {
            $parent_list = $this->container['categoryModel']->categoryService->getList(array("level" => "1", "id <>" => "0"), array("limit" => -1));
            $subcat_list = $this->container['categoryModel']->getParent(2, $cat_obj->getId());
            if ($inherit == 1) {
                $hs_obj = $this->container['customClassModel']->getCustomClassByCatSubId($parent);
                $data['hs'] = $this->container['customClassModel']->getCustomClassByCatSubId($parent);
                $udesc = [];
                for ($i = 0; $i < count($data['hs']); $i++) {
                    $udesc[$i]['code'] = $data['hs'][$i]['code'];
                    $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->container['customClassModel']->getCustomClassByCatSubId($parent);
                $updesc = [];
                for ($i = 0; $i < count($data['parcode']); $i++) {
                    $updesc[$i]['code'] = $data['parcode'][$i]['code'];
                    $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['upcode'] = $this->arrayUnique($updesc);

                for ($i = 0; $i < count($data['hs']); $i++) {
                    $uarr .= '"' . $data['hs'][$i]['country_id'] . '"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;
            } else {
                $hs_obj = $this->container['customClassModel']->getCustomClassByCatSubId($cat_obj->getId());
                $data['hs'] = $this->container['customClassModel']->getCustomClassByCatSubId($cat_obj->getId());
                $udesc = [];
                for ($i = 0; $i < count($data['hs']); $i++) {
                    $udesc[$i]['code'] = $data['hs'][$i]['code'];
                    $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->container['customClassModel']->getCustomClassByCatSubId($parent);
                $updesc = [];
                for ($i = 0; $i < count($data['parcode']); $i++) {
                    $updesc[$i]['code'] = $data['parcode'][$i]['code'];
                    $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['upcode'] = $this->arrayUnique($updesc);

                for ($i = 0; $i < count($data['hs']); $i++) {
                    $uarr .= '"' . $data['hs'][$i]['country_id'] . '"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;
            }
        } else {
            $hs_obj = $this->container['customClassModel']->getCustomClassByCatSubId($cat_obj->getId());
            $data['hs'] = $this->container['customClassModel']->getCustomClassByCatSubId($cat_obj->getId());
            $udesc = [];
            for ($i = 0; $i < count($data['hs']); $i++) {
                $udesc[$i]['code'] = $data['hs'][$i]['code'];
                $udesc[$i]['description'] = $data['hs'][$i]['description'];
            }
            $data['ucode'] = $this->arrayUnique($udesc);

            for ($i = 0; $i < count($data['hs']); $i++) {
                $uarr .= '"' . $data['hs'][$i]['country_id'] . '"';
                $uarr .= ', ';
            }
            $data['psarr'] = $uarr;

            $child_list = $this->container['categoryModel']->getlistcount(1, $cat_obj->getId());
        }

        if (empty($data["cat_ext"])) {
            if (($data["cat_ext"] = $this->container['categoryService']->getCatExtWithKey(array("cat_id" => $value))) === FALSE) {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            } else {
                $_SESSION["cat_ext"] = serialize($data["cat_ext"]);
            }
        }

        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["parent_list"] = $parent_list;
        $data["child_list"] = $child_list;
        $data["subcat_list"] = $subcat_list;
        $data["lang_list"] = $this->container['languageService']->getList(array("status" => 1), array("limit" => -1));
        $data["cat_id"] = $value;
        $_SESSION["category_edit"] = serialize($cat_obj);
        $this->load->view("marketing/category/category_view", $data);
    }

    public function arrayUnique($array, $preserveKeys = false)
    {
        // Unique Array for return
        $arrayRewrite = [];
        // Array with the md5 hashes
        $arrayHashes = [];
        foreach ($array as $key => $item) {
            // Serialize the current element and create a md5 hash
            $hash = md5(serialize($item));
            // If the md5 didn't come up yet, add the element to
            // to arrayRewrite, otherwise drop it
            if (!isset($arrayHashes[$hash])) {
                // Save the current element hash
                $arrayHashes[$hash] = $hash;
                // Add element to the unique Array
                if ($preserveKeys) {
                    $arrayRewrite[$key] = $item;
                } else {
                    $arrayRewrite[] = $item;
                }
            }
        }
        return $arrayRewrite;
    }

    public function left()
    {
        $list = $this->container['categoryModel']->getlistcnt(1, 0, 1);
        $data["objlist"] = $list;
        $data["level"] = 1;
        $data["status"] = 1;
        $this->load->view('marketing/category/category_left', $data);
    }

    public function view_scpv()
    {
        $canedit = 1;
        $data = [];
        $data['type'] = $this->input->post('type') ? $this->input->post('type') : "";
        $selling_platform_obj_list = $this->container['categoryModel']->getSellingPlatform([], array("orderby" => "name", "limit" => -1));
        $cat_obj = $this->container['categoryModel']->getCatObj($this->input->get("subcat_id"));
        $ccid_list = $this->container['categoryModel']->getCustomClassListWithPlatformId($this->input->get('platform'));
        $currency_list = $this->container['categoryModel']->getCurrencyList();
        $data["sp_list"] = $selling_platform_obj_list;
        $data["cat_obj"] = $cat_obj;
        $data["currency_list"] = $currency_list;
        $data["canedit"] = $canedit ? $canedit : 0;
        $scpv_obj = $this->container['categoryModel']->getScpvObj(array("sub_cat_id" => $this->input->get('subcat_id'), "platform_id" => $this->input->get('platform')));
        $data["scpv_obj"] = $scpv_obj;
        if (empty($scpv_obj)) {
            $data["action"] = "insert";
            $data["scpv_obj"] = $this->container['categoryModel']->getScpvObj(array());
            $replace_scpv_obj = $this->container['categoryModel']->getReplaceScpvObj(array("selling_platform_id" => $this->input->get('platform')));
            if ($replace_scpv_obj) {
                $data["scpv_obj"]->setCurrencyId($replace_scpv_obj->getPlatformCurrencyId());
            }
        } else {
            $_SESSION["scpv_obj"] = serialize($scpv_obj);
            $data["action"] = "update";
        }
        $_SESSION["scpv_obj"] = serialize($scpv_obj);
        include_once APPPATH . "language/" . $this->getAppId() . "04_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view("marketing/category/scpv_view", $data);
    }

    public function update_scpv()
    {
        if ($this->input->post('posted')) {
            if ($this->input->post('type') == "insert") {
                $scpv_obj = $this->container['categoryModel']->getScpvObj(array());

            } else {
                $this->container['categoryModel']->autoloadScpv();
                $scpv_obj = unserialize($_SESSION["scpv_obj"]);

            }
            $d = 1;
            $scpv_obj->setSubCatId($this->input->post('subcat_id'));
            $scpv_obj->setPlatformId($this->input->post('platform'));
            $scpv_obj->setFixedFee($this->input->post('fixed_fee'));
            $scpv_obj->setProfitMargin($this->input->post('profit_margin'));
            $scpv_obj->setCurrencyId($this->input->post('currency'));
            $scpv_obj->setDlvryChrg($this->input->post('dlvry_chrg'));
            $scpv_obj->setPlatformCommission($this->input->post('commission'));
            if ($this->input->post('type') == "insert") {
                $ret = $this->container['categoryModel']->insertScpv($scpv_obj);
                if ($ret === FALSE) {
                    $_SESSION["notice"] = "Cannot insert record into database";
                    $d = 0;
                }
            } else {
                $ret = $this->container['categoryModel']->updateScpv($scpv_obj);
                if ($ret === FALSE) {
                    $_SESSION["notice"] = "Cannot update current record";
                    $d = 0;
                }
            }
            unset($_SESSION["spcv_obj"]);
            redirect(base_url() . "marketing/category/view_scpv/?subcat_id=" . $this->input->post('subcat_id') . '&platform=' . $this->input->post('platform') . "&dtype=" . $this->input->post('type') . "&d=" . $d);
        }
    }

    public function view_prod_spec($cat_id = "")
    {
        if ($this->input->post('posted')) {
            foreach ($this->input->post('cps_obj') AS $ps_id => $cps_array) {
                $cps_obj = $this->container['categoryModel']->getCps(array('cat_id' => $cat_id, 'ps_id' => $ps_id));
                if ($cps_obj) {
                    $cps_action = "update_cps";
                } else {
                    $cps_action = "insert_cps";
                    $cps_obj = $this->container['categoryModel']->getCps();
                    $cps_obj->setPsId($ps_id);
                    $cps_obj->setCatId($cat_id);
                    $cps_obj->setUnitId($cps_array['unit_id']);
                }
                $cps_obj->setPriority($cps_array['priority']);
                $cps_obj->setStatus($cps_array['status']);
                if ($this->container['categoryModel']->$cps_action($cps_obj) === FALSE) {
                    $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
                }
            }
            if (!$_SESSION["NOTICE"]) {
                redirect(base_url() . "marketing/category/view_prod_spec/" . $cat_id);
            }
        }
        $data["cat_obj"] = $this->container['categoryModel']->getCategory(array("id" => $cat_id, "level" => 2, "status" => 1));
        $full_cps_list = $this->container['categoryModel']->getFullCpsList($cat_id);
        foreach ($full_cps_list AS $obj) {
            $data["full_cps_list"][$obj->getPsgName()][$obj->getPsName()] = $obj;
        }
        $unit_type_list = $this->container['categoryModel']->getUnitTypeList(array("status" => 1), array("orderby" => "name ASC"));
        foreach ($unit_type_list AS $ut_obj) {
            $ut_array[$ut_obj->getUnitTypeId()] = $this->container['categoryModel']->getUnitList(array("status" => 1, "unit_type_id" => $ut_obj->getUnitTypeId()), array("orderby" => "standardize_value DESC"));
        }
        $data["ut_array"] = $ut_array;

        $data["cat_id"] = $cat_id;

        include_once APPPATH . "language/" . $this->getAppId() . "05_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view("marketing/category/sc_prod_spec", $data);
    }

}

?>