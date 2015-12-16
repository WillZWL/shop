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
            $thisobj = $this->sc['Category']->getDao('Category')->get(['id'=>$this->input->get('id')]);
            if ($thisobj->getLevel() < 3) {
                $where["parent_cat_id"] = $this->input->get('id');
                $option["sortby"] = "name ASC";
                $data = $this->sc['Category']->getCatListIndex($where, $option);

            } else {
                $data["category_list"] = $this->sc['categoryModel']->getProductBySscat($this->input->get('id'));
                $data["total"] = $this->sc['categoryModel']->countProduct($this->input->get('id'));
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
            $list = $this->sc['categoryModel']->getlistcnt($this->input->get('level'), $this->input->get('id'), $this->input->get('status'));
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

        $option['limit'] = ($this->input->get('limit') != '') ? $this->input->get('limit') : '30';
        $option['offset'] = ($this->input->get('per_page') != '') ? $this->input->get('per_page') : '';

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort . " " . $order;

        $data = $this->sc['Category']->getCatListIndex($where, $option);

        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
        $data["lang"] = $lang;

        $config['base_url'] = base_url('marketing/category/top/');
        $config['total_rows'] = $data["total"];
        $config['page_query_string'] = true;
        $config['reuse_query_string'] = true;
        $config['per_page'] = $option['limit'];
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

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
            $cat_obj = $this->sc['Category']->getDao('Category')->get();

            $cat_obj->setLevel($this->input->post('level'));
            $cat_obj->setStatus($this->input->post('status'));
            $cat_obj->setName($this->input->post('name'));
            $cat_obj->setDescription($this->input->post('description'));
            $cat_obj->setParentCatId($this->input->post('parent_cat_id'));


            $ret = $this->sc['categoryModel']->addCategory($cat_obj);
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
            $parent_obj = $this->sc['Category']->getDao('Category')->get(['id'=>$this->input->get('parent')]);
        } else if ($this->input->get('level') == 3) {
            $parent_obj = $this->sc['categoryModel']->getParent(2, $this->input->get('parent'));
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
        define('IMG_PH', $this->sc['ContextConfig']->valueOf("cat_img_path"));
        if (strpos($value, '-1') !== false) {
            $value = str_replace('-1', '', $value);
            $inherit = 1;
        }

        if ($this->input->post('posted')) {
            $this->sc['Category']->getDao('CategoryExtend')->get();
            $data["cat_ext"] = unserialize($_SESSION["cat_ext"]);
            $cat_ext_vo = $this->sc['Category']->getDao('CategoryExtend')->get();

            $stop_sync_name = $this->input->post('stop_sync_name');
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

                    if ($stop_sync_name[$rs_lang_id] != null)
                    {
                        $data["cat_ext"][$value][$rs_lang_id]->setStopSyncName(1);
                    }
                    else
                    {
                        $data["cat_ext"][$value][$rs_lang_id]->setStopSyncName(0);
                    }
                    if (!$this->sc['Category']->getDao('CategoryExtend')->$action($data["cat_ext"][$value][$rs_lang_id])) {
                        $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                    } else {
                        $config['upload_path'] = IMG_PH;
                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $config['overwrite'] = TRUE;
                        $config['is_image'] = TRUE;
                        $this->load->library('upload', $config);

                        if ($_FILES) {
                            if ($_FILES["image_file_" . $rs_lang_id]["name"]) {
                                $config['file_name'] = $value . "_" . $rs_lang_id;
                                $this->upload->initialize($config);

                                if ($this->upload->do_upload("image_file_" . $rs_lang_id)) {
                                    $res = $this->upload->data();
                                    $ext = substr($res["file_ext"], 1);
                                    if (!$cat_cont_obj = $this->sc['categoryModel']->getCatContObj(["cat_id" => $value, "lang_id" => $rs_lang_id])) {
                                        $cat_cont_obj = $this->sc['Category']->getDao('CategoryContent')->get();
                                        $cat_cont_obj->setCatId($value);
                                        $cat_cont_obj->setLangId($rs_lang_id);
                                        $cat_cont_obj->setImage($ext);
                                        if (!$this->sc['Category']->getDao('CategoryContent')->insert($cat_cont_obj)) {
                                            $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                                        }
                                    } else {
                                        $cat_cont_obj->setImage($ext);
                                        if (!$this->sc['Category']->getDao('CategoryContent')->update($cat_cont_obj)) {
                                            $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                                        }
                                    }
                                } else {
                                    $_SESSION["NOTICE"] = $this->upload->display_errors();
                                }
                            }
                        }

                        if ($_FILES) {
                            if (!empty($_FILES["flash_file_" . $rs_lang_id]["name"])) {
                                $config['allowed_types'] = 'swf';
                                $config['file_name'] = $value . "_" . $rs_lang_id;
                                $config['is_image'] = FALSE;
                                $this->upload->initialize($config);

                                if ($this->upload->do_upload("flash_file_" . $rs_lang_id)) {
                                    $res = $this->upload->data();
                                    $ext = substr($res["file_ext"], 1);
                                    if (!$cat_cont_obj = $this->sc['categoryModel']->getCatContObj(["cat_id" => $value, "lang_id" => $rs_lang_id])) {
                                        $cat_cont_obj = $this->sc['Category']->getDao('CategoryContent')->get();
                                        $cat_cont_obj->setCatId($value);
                                        $cat_cont_obj->setLangId($rs_lang_id);
                                        $cat_cont_obj->setFlash($ext);
                                        if (!$this->sc['Category']->getDao('CategoryContent')->insert($cat_cont_obj)) {
                                            $_SESSION["NOTICE"] = "ERROR: " . __LINE__ . " " . $this->db->_error_message();
                                        }
                                    } else {
                                        $cat_cont_obj->setFlash($ext);
                                        if (!$this->sc['Category']->getDao('CategoryContent')->update($cat_cont_obj)) {
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
            }

            $this->sc['categoryModel']->autoload();
            $cat_obj = unserialize($_SESSION["category_edit"]);
            $cat_obj->setName($this->input->post('name'));
            $cat_obj->setDescription($this->input->post('description'));
            $cat_obj->setLevel($this->input->post('level'));
            $cat_obj->setStatus($this->input->post('status'));
            $cat_obj->setPriority($this->input->post('priority') ? $this->input->post('priority') : '');
            $cat_obj->setStopSyncPriority($this->input->post('stop_sync_priority'));
            $cat_obj->setSponsored($this->input->post('sponsored'));
            $cat_obj->setAddColourName($this->input->post('add_colour_name'));
            $cat_obj->setBundleDiscount($this->input->post('bundle_discount'));
            $ccmap = [
                ['country' => 'AU', 'code' => $this->input->post('hscode_AU'), 'duty' => $this->input->post('duty_AU')],
                ['country' => 'BE', 'code' => $this->input->post('hscode_BE'), 'duty' => $this->input->post('duty_BE')],
                ['country' => 'CH', 'code' => $this->input->post('hscode_CH'), 'duty' => $this->input->post('duty_CH')],
                ['country' => 'ES', 'code' => $this->input->post('hscode_ES'), 'duty' => $this->input->post('duty_ES')],
                ['country' => 'FI', 'code' => $this->input->post('hscode_FI'), 'duty' => $this->input->post('duty_FI')],
                ['country' => 'FR', 'code' => $this->input->post('hscode_FR'), 'duty' => $this->input->post('duty_FR')],
                ['country' => 'GB', 'code' => $this->input->post('hscode_GB'), 'duty' => $this->input->post('duty_GB')],
                ['country' => 'HK', 'code' => $this->input->post('hscode_HK'), 'duty' => $this->input->post('duty_HK')],
                ['country' => 'ID', 'code' => $this->input->post('hscode_ID'), 'duty' => $this->input->post('duty_ID')],
                ['country' => 'IE', 'code' => $this->input->post('hscode_IE'), 'duty' => $this->input->post('duty_IE')],
                ['country' => 'IT', 'code' => $this->input->post('hscode_IT'), 'duty' => $this->input->post('duty_IT')],
                ['country' => 'MT', 'code' => $this->input->post('hscode_MT'), 'duty' => $this->input->post('duty_MT')],
                ['country' => 'MY', 'code' => $this->input->post('hscode_MY'), 'duty' => $this->input->post('duty_MY')],
                ['country' => 'NZ', 'code' => $this->input->post('hscode_NZ'), 'duty' => $this->input->post('duty_NZ')],
                ['country' => 'PH', 'code' => $this->input->post('hscode_PH'), 'duty' => $this->input->post('duty_PH')],
                ['country' => 'PL', 'code' => $this->input->post('hscode_PL'), 'duty' => $this->input->post('duty_PL')],
                ['country' => 'PT', 'code' => $this->input->post('hscode_PT'), 'duty' => $this->input->post('duty_PT')],
                ['country' => 'RU', 'code' => $this->input->post('hscode_RU'), 'duty' => $this->input->post('duty_RU')],
                ['country' => 'SG', 'code' => $this->input->post('hscode_SG'), 'duty' => $this->input->post('duty_SG')],
                ['country' => 'TH', 'code' => $this->input->post('hscode_TH'), 'duty' => $this->input->post('duty_TH')],
                ['country' => 'US', 'code' => $this->input->post('hscode_US'), 'duty' => $this->input->post('duty_US')]
            ];
            $cccount = count($ccmap);

            for ($i = 0; $i < $cccount; $i++) {
                $this->sc['CustomClass']->saveCustomClassMapping($ccmap, $i, $value, $this->input->post('name'));
            }

            if ($this->input->post('level') == 1) {
                $cat_obj->setParentCatId(0);
            } else {
                $cat_obj->setParentCatId($this->input->post('subcat'));
            }

            $ret = $this->sc['categoryModel']->updateCategory($cat_obj);
            if ($ret === FALSE) {
                $_SESSION["NOTICE"] = "Update Failed";
            } else {
                unset($_SESSION["category_edit"]);
                unset($_SESSION["cat_ext"]);
                redirect(base_url() . "marketing/category/view/" . $value);
            }
        }
        $cat_obj = $this->sc['Category']->getDao('Category')->get(['id'=>$value]);
        $cat_cont_obj = $this->sc['categoryModel']->getCatContList(["cat_id" => $value]);
        $data["cat_obj"] = $cat_obj;
        $data["cat_cont_obj"] = $cat_cont_obj;
        $data["canadd"] = $canadd;
        $data["canedit"] = $canedit;
        $parent = $cat_obj->getParentCatId();
        $all = '1';
        $data['optionhs'] = $this->sc['CustomClass']->getDao('CustomClassification')->getOption($where);
        $parent_list = "";
        $child_list = "";
        $subcat_list = "";
        $uarr = "";
        $data['upcode'] = [];
        if ($cat_obj->getLevel() == "3") {
            $parent_list = $this->sc['Category']->getDao('Category')->getList(["level" => "2", "id <>" => "0"], ["limit" => -1]);
            $subcat_list = $this->sc['categoryModel']->getParent(3, $cat_obj->getId());
            if ($inherit == 1) {
                $hs_obj = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($parent);
                $data['hs'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($parent);
                $udesc = [];
                for ($i = 0; $i < count($data['hs']); $i++) {
                    $udesc[$i]['code'] = $data['hs'][$i]['code'];
                    $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($parent);
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
                $hs_obj = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($cat_obj->getId());
                $data['hs'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($cat_obj->getId());
                $udesc = [];
                for ($i = 0; $i < count($data['hs']); $i++) {
                    $udesc[$i]['code'] = $data['hs'][$i]['code'];
                    $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($parent);
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
            $parent_list = $this->sc['Category']->getDao('Category')->getList(["level" => "1", "id <>" => "0"], ["limit" => -1]);
            $subcat_list = $this->sc['categoryModel']->getParent(2, $cat_obj->getId());
            if ($inherit == 1) {
                $hs_obj = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($parent);
                $data['hs'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($parent);
                $udesc = [];
                for ($i = 0; $i < count($data['hs']); $i++) {
                    $udesc[$i]['code'] = $data['hs'][$i]['code'];
                    $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($parent);
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
                $hs_obj = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($cat_obj->getId());
                $data['hs'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($cat_obj->getId());
                $udesc = [];
                for ($i = 0; $i < count($data['hs']); $i++) {
                    $udesc[$i]['code'] = $data['hs'][$i]['code'];
                    $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($parent);
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
            $hs_obj = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($cat_obj->getId());
            $data['hs'] = $this->sc['CustomClassificationMapping']->getAllCustomClassMappingBySubCatId($cat_obj->getId());
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

            $child_list = $this->sc['categoryModel']->getlistcount(1, $cat_obj->getId());
        }

        if (empty($data["cat_ext"])) {
            if (($data["cat_ext"] = $this->sc['Category']->getCatExtWithKey(["cat_id" => $value])) === FALSE) {
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
        $data["lang_list"] = $this->sc['Language']->getDao('Language')->getList(["status" => 1], ["limit" => -1]);
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
        $list = $this->sc['categoryModel']->getlistcnt(1, 0, 1);
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
        $selling_platform_obj_list = $this->sc['categoryModel']->getSellingPlatform([], ["orderby" => "name", "limit" => -1]);
        $cat_obj = $this->sc['Category']->getDao('Category')->get(['id'=>$this->input->get("subcat_id")]);
        $ccid_list = $this->sc['categoryModel']->getCustomClassListWithPlatformId($this->input->get('platform'));
        $currency_list = $this->sc['categoryModel']->getCurrencyList();
        $data["sp_list"] = $selling_platform_obj_list;
        $data["cat_obj"] = $cat_obj;
        $data["currency_list"] = $currency_list;
        $data["canedit"] = $canedit ? $canedit : 0;
        $scpv_obj = $this->sc['categoryModel']->getScpvObj(["sub_cat_id" => $this->input->get('subcat_id'), "platform_id" => $this->input->get('platform')]);
        $data["scpv_obj"] = $scpv_obj;
        if (empty($scpv_obj)) {
            $data["action"] = "insert";
            $data["scpv_obj"] = $this->sc['categoryModel']->getScpvObj([]);
            $replace_scpv_obj = $this->sc['categoryModel']->getReplaceScpvObj(["selling_platform_id" => $this->input->get('platform')]);
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
                $scpv_obj = $this->sc['categoryModel']->getScpvObj([]);

            } else {
                $this->sc['categoryModel']->autoloadScpv();
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
                $ret = $this->sc['categoryModel']->insertScpv($scpv_obj);
                if ($ret === FALSE) {
                    $_SESSION["notice"] = "Cannot insert record into database";
                    $d = 0;
                }
            } else {
                $ret = $this->sc['categoryModel']->updateScpv($scpv_obj);
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
            $this->sc['categoryModel']->saveProdSpec($this->input->post('cps_obj'), $cat_id);
            if (!$_SESSION["NOTICE"]) {
                redirect(base_url() . "marketing/category/view_prod_spec/" . $cat_id);
            }
        }
        $data["cat_obj"] = $this->sc['categoryModel']->getCategory(["id" => $cat_id, "level" => 2, "status" => 1]);
        $full_cps_list = $this->sc['categoryModel']->getFullCpsList($cat_id);
        foreach ($full_cps_list AS $obj) {
            $data["full_cps_list"][$obj->getPsgName()][$obj->getPsName()] = $obj;
        }
        $unit_type_list = $this->sc['categoryModel']->getUnitTypeList(["status" => 1], ["orderby" => "name ASC"]);
        foreach ($unit_type_list AS $ut_obj) {
            $ut_array[$ut_obj->getUnitTypeId()] = $this->sc['categoryModel']->getUnitList(["status" => 1, "unit_type_id" => $ut_obj->getUnitTypeId()], ["orderby" => "standardize_value DESC"]);
        }
        $data["ut_array"] = $ut_array;

        $data["cat_id"] = $cat_id;

        include_once APPPATH . "language/" . $this->getAppId() . "05_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view("marketing/category/sc_prod_spec", $data);
    }

}
