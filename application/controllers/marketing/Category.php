<?php

class Category extends MY_Controller
{
    private $app_id = "MKT0002";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url','notice'));
        $this->load->library('input');
        $this->load->model('marketing/category_model');
        $this->load->model('mastercfg/custom_class_model');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
    }

    public function index()
    {
        $sub_app_id = $this->_get_app_id()."00";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        $this->load->view('marketing/category/category_main', $data);
    }

    public function getlayer()
    {
        if($this->input->get('id') == "")
        {
            return;
        }
        else
        {
            $thisobj = $this->category_model->get_cat_obj($this->input->get('id'));
            if($thisobj->get_level() < 3)
            {
                $where["parent_cat_id"] =  $this->input->get('id');
                $option["sortby"] = "name ASC";
                //$list_obj = $this->category_service->get_list(array("parent_cat_id"=>$this->input->get('id'),));
                $data = $this->category_model->get_cat_list_index($where,$option);

            }
            else
            {
                $data["category_list"] = $this->category_model->get_product_by_sscat($this->input->get('id'));
                $data["total"] = $this->category_model->count_product($this->input->get('id'));
            }
            $data["thisobj"] = $thisobj;
            $this->load->view('marketing/category/category_rlist',$data);
        }
    }

    public function getnext()
    {
        if($this->input->get('id') == "" || $this->input->get('level') == "")
        {
            return;
        }
        else
        {
            $list = $this->category_model->getlistcnt($this->input->get('level'),$this->input->get('id'),$this->input->get('status'));
            $data["objlist"] = $list;
            $data["level"] = $this->input->get('level');
            $this->load->view('marketing/category/category_llist',$data);
        }
    }


    public function top()
    {
        $sub_app_id = $this->_get_app_id()."00";

        $_SESSION["LISTPAGE"] = base_url()."marketing/category/?".$_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();


        $where["name"] = $this->input->get("name");
        $where["description"] = $this->input->get("description");
        $where["level"] = $this->input->get("level");
        $where["status"] = $this->input->get("status");
        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] ="marketing/category/top/?".$_SERVER['QUERY_STRING'];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"])
        {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort." ".$order;

        $data = $this->category_model->get_cat_list_index($where,$option);

        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);


        $data["refresh"] = $this->input->get("refresh");
        $data["added"] = $this->input->get("added");
        $data["updated"] = $this->input->get("updated");

        $data["showall"] = $this->input->get("showall");
        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
//      $data["searchdisplay"] = ($where["name"]=="" && $where["description"]=="" && $where["level"]=="" && $where["status"]=="")?'style="display:none"':"";
        $data["searchdisplay"] = "";
        $this->load->view('marketing/category/category_index', $data);
    }

    public function add()
    {
        $data = array();
        $data["added"] = 0;
        if($this->input->post('add'))
        {
            $cat_obj = $this->category_model->get_cat_obj();

            $cat_obj->set_level($this->input->post('level'));
            $cat_obj->set_status($this->input->post('status'));
            $cat_obj->set_name($this->input->post('name'));
            $cat_obj->set_description($this->input->post('description'));
            $cat_obj->set_parent_cat_id($this->input->post('parent_cat_id'));


            $ret = $this->category_model->add_category($cat_obj);
            if($ret === FALSE)
            {
/*
                if($this->input->get('level')==2)
                {
                    $cat = "category";
                }
                else if($this->input->get('level') == 2)
                {
                    $cat = "subcategory";
                }
                else
                {
                    $cat = "sub sub category";
                }
                $_SESSION["NOTICE"] = "Fail to add new $cat";
*/
                $_SESSION["NOTICE"] = "Error ".__LINE__.": ".$this->db->_error_message();
            }
            else
            {
                unset($_SESSION["NOTICE"]);
                redirect(base_url()."marketing/category/top/?refresh=1&added=1");

            }
        }

        include_once APPPATH."language/".$this->_get_app_id()."03_".$this->_get_lang_id().".php";
        $data["lang"] = $lang;
        $data["level"] = $this->input->get('level');
        $data["parent"] = $this->input->get('parent');
        $data["notice"] = notice($lang);
        if($this->input->get('level') == 2)
        {
            $parent_obj = $this->category_model->get_cat_obj($this->input->get('parent'));
        }
        else if ($this->input->get('level') == 3)
        {
            //$parentobj = $this->category_model->get_cat_obj($this->input->get('parent'));
            $parent_obj = $this->category_model->get_parent(2,$this->input->get('parent'));
        }
        else
        {
            $parent_obj = "";
        }
        $data['parent_obj'] = $parent_obj;
        $this->load->view("marketing/category/category_add",$data);
    }

    public function view($value)
    {
        $data = array();
        $canadd = 1;
        $canedit = 1;
        define('IMG_PH', $this->context_config_service->value_of("cat_img_path"));
        if(strpos($value, '-1')!==false){
            $value = str_replace('-1', '', $value);
            $inherit = 1;
        }

        if($this->input->post('posted'))
        {
            $this->category_service->get_ext_dao()->include_vo();
            $data["cat_ext"]=unserialize($_SESSION["cat_ext"]);
            $cat_ext_vo = $this->category_service->get_ext_dao()->get();

            foreach ($_POST["lang_name"] as $rs_lang_id=>$rs_name)
            {
                if ($rs_name)
                {
                    if (empty($data["cat_ext"][$value][$rs_lang_id]))
                    {
                        $data["cat_ext"][$value][$rs_lang_id] = clone $cat_ext_vo;
                        $data["cat_ext"][$value][$rs_lang_id]->set_cat_id($value);
                        $data["cat_ext"][$value][$rs_lang_id]->set_lang_id($rs_lang_id);
                        $action = "insert";
                    }
                    else
                    {
                        /*
                        if ($data["cat_ext"][$value][$rs_lang_id]->get_name() == $rs_name)
                        {
                            continue;
                        }
                        */
                        $action = "update";
                    }

                    $data["cat_ext"][$value][$rs_lang_id]->set_name($rs_name);
                    if (!$this->category_service->get_ext_dao()->$action($data["cat_ext"][$value][$rs_lang_id]))
                    {
                        $_SESSION["NOTICE"] = "ERROR: ".__LINE__." ".$this->db->_error_message();
                    }
                    else
                    {
                        $config['upload_path'] = IMG_PH;
                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $config['overwrite'] = TRUE;
                        $config['is_image'] = TRUE;
                        $this->load->library('upload', $config);

                        if($_FILES["image_file_".$rs_lang_id]["name"])
                        {
                            $config['file_name'] = $value."_".$rs_lang_id;
                            $this->upload->initialize($config);

                            //@unlink(IMG_PH."cat_".$value."_".$v->get_id().".".$cat_cont_obj->get_image());

                            if($this->upload->do_upload("image_file_".$rs_lang_id))
                            {
                                $res = $this->upload->data();
                                $ext = substr($res["file_ext"], 1);
                                if(!$cat_cont_obj = $this->category_model->get_cat_cont_obj(array("cat_id"=>$value, "lang_id"=>$rs_lang_id)))
                                {
                                    $cat_cont_obj = $this->category_service->get_cc_dao()->get();
                                    $cat_cont_obj->set_cat_id($value);
                                    $cat_cont_obj->set_lang_id($rs_lang_id);
                                    $cat_cont_obj->set_image($ext);
                                    if (!$this->category_service->get_cc_dao()->insert($cat_cont_obj))
                                    {
                                        $_SESSION["NOTICE"] = "ERROR: ".__LINE__." ".$this->db->_error_message();
                                    }
                                }
                                else
                                {
                                    $cat_cont_obj->set_image($ext);
                                    if (!$this->category_service->get_cc_dao()->update($cat_cont_obj))
                                    {
                                        $_SESSION["NOTICE"] = "ERROR: ".__LINE__." ".$this->db->_error_message();
                                    }
                                }
                            }
                            else
                            {
                                $_SESSION["NOTICE"] = $this->upload->display_errors();
                            }
                        }

                        if(!empty($_FILES["flash_file_".$rs_lang_id]["name"]))
                        {
                            $config['allowed_types'] = 'swf';
                            $config['file_name'] = $value."_".$rs_lang_id;
                            $config['is_image'] = FALSE;
                            $this->upload->initialize($config);

                            if($this->upload->do_upload("flash_file_".$rs_lang_id))
                            {
                                $res = $this->upload->data();
                                $ext = substr($res["file_ext"], 1);
                                if(!$cat_cont_obj = $this->category_model->get_cat_cont_obj(array("cat_id"=>$value, "lang_id"=>$rs_lang_id)))
                                {
                                    $cat_cont_obj = $this->category_service->get_cc_dao()->get();
                                    $cat_cont_obj->set_cat_id($value);
                                    $cat_cont_obj->set_lang_id($rs_lang_id);
                                    $cat_cont_obj->set_flash($ext);
                                    if (!$this->category_service->get_cc_dao()->insert($cat_cont_obj))
                                    {
                                        $_SESSION["NOTICE"] = "ERROR: ".__LINE__." ".$this->db->_error_message();
                                    }
                                }
                                else
                                {
                                    $cat_cont_obj->set_flash($ext);
                                    if (!$this->category_service->get_cc_dao()->update($cat_cont_obj))
                                    {
                                        $_SESSION["NOTICE"] = "ERROR: ".__LINE__." ".$this->db->_error_message();
                                    }
                                }
                            }
                            else
                            {
                                $_SESSION["NOTICE"] = $this->upload->display_errors();
                            }
                        }
                    }
                }
            }

            $this->category_model->__autoload();
            $cat_obj = unserialize($_SESSION["category_edit"]);
            $cat_obj->set_name($this->input->post('name'));
            $cat_obj->set_description($this->input->post('description'));
            $cat_obj->set_level($this->input->post('level'));
            $cat_obj->set_status($this->input->post('status'));
            $cat_obj->set_priority($this->input->post('priority')?$this->input->post('priority'):'');
            $cat_obj->set_add_colour_name($this->input->post('add_colour_name'));
            $cat_obj->set_bundle_discount($this->input->post('bundle_discount'));
//"AU", "BE", "CH", "ES", "FI", "FR", "GB", "HK", "ID", "IE", "IT", "MT", "MY", "NZ", "PH", "PL", "PT", "RU", "SG", "TH", "US"
            $ccmap = array(
            array('country'=>'AU', 'code'=>$this->input->post('hscode_AU')),
            array('country'=>'BE', 'code'=>$this->input->post('hscode_BE')),
            array('country'=>'CH', 'code'=>$this->input->post('hscode_CH')),
            array('country'=>'ES', 'code'=>$this->input->post('hscode_ES')),
            array('country'=>'FI', 'code'=>$this->input->post('hscode_FI')),
            array('country'=>'FR', 'code'=>$this->input->post('hscode_FR')),
            array('country'=>'GB', 'code'=>$this->input->post('hscode_GB')),
            array('country'=>'HK', 'code'=>$this->input->post('hscode_HK')),
            array('country'=>'ID', 'code'=>$this->input->post('hscode_ID')),
            array('country'=>'IE', 'code'=>$this->input->post('hscode_IE')),
            array('country'=>'IT', 'code'=>$this->input->post('hscode_IT')),
            array('country'=>'MT', 'code'=>$this->input->post('hscode_MT')),
            array('country'=>'MY', 'code'=>$this->input->post('hscode_MY')),
            array('country'=>'NZ', 'code'=>$this->input->post('hscode_NZ')),
            array('country'=>'PH', 'code'=>$this->input->post('hscode_PH')),
            array('country'=>'PL', 'code'=>$this->input->post('hscode_PL')),
            array('country'=>'PT', 'code'=>$this->input->post('hscode_PT')),
            array('country'=>'RU', 'code'=>$this->input->post('hscode_RU')),
            array('country'=>'SG', 'code'=>$this->input->post('hscode_SG')),
            array('country'=>'TH', 'code'=>$this->input->post('hscode_TH')),
            array('country'=>'US', 'code'=>$this->input->post('hscode_US'))
             );
            $cccount = count($ccmap);

            for($i=0;$i<$cccount;$i++){
                $cc_obj = $this->custom_class_model->get_cc(array('country_id'=>$ccmap[$i]['country'], 'code'=>$ccmap[$i]['code']));
                //var_dump($this->db->last_query()); die();
                //var_dump($cc_obj); die();
                if($cc_obj){
                    $ccm_obj = $this->custom_class_model->get_ccm(array('sub_cat_id'=>$value, 'country_id'=>$ccmap[$i]['country']));
                    $ccm_dao = $this->custom_class_model->custom_classification_mapping_service->get_dao();
                    $ccm_vo = $ccm_dao->get();
                    //if no record we add new record
                    if(!$ccm_obj)
                    {
                        $action = "insert_ccm";
                        //echo "<pre>"; var_dump($ccm_vo); die();
                        $ccm_obj = clone($ccm_vo);
                        $ccm_obj->set_sub_cat_id($value);
                        $ccm_obj->set_country_id($ccmap[$i]['country']);
                        $ccm_obj->set_custom_class_id($cc_obj->get_id());
                    }
                    else
                    {
                        // if record found then we update
                        $action = "update_ccm";
                        $ccm_obj->set_custom_class_id($cc_obj->get_id());
                    }

                    if($this->custom_class_model->$action($ccm_obj) === FALSE)
                    {
                        $error_message = __LINE__ . "category.php ".$action." Error. ".$ccm_dao->db->_error_message();
                        $_SESSION["NOTICE"] = $error_message;
                    }

                    // $cc_dao = $this->custom_class_model->custom_class_service->get_dao();
                    // $cc_vo = $cc_dao->get();
                    // $action = "update_cc";
                    //  //echo "<pre>"; var_dump($cc_vo); die();
                    //  // $cc_obj = clone($cc_vo);
                    //  // $cc_obj->set_country_id($ccmap[$i]['country']);
                    //  // $cc_obj->set_code($ccmap[$i]['code']);
                    //  // $cc_obj->set_description($this->input->post('name'));
                    //  $cc_obj->set_duty_pcent($ccmap[$i]['duty']);
                    //  // $ccm_obj->set_custom_class_id($cc_obj->get_id());

                    // if($this->custom_class_model->$action($cc_obj) === FALSE)
                    // {
                    //  $error_message = __LINE__ . "category.php ".$action." Error. ".$ccm_dao->db->_error_message();
                    //  $_SESSION["NOTICE"] = $error_message;
                    // }

                }else{
                    $cc_dao = $this->custom_class_model->custom_class_service->get_dao();
                    $cc_vo = $cc_dao->get();
                    $action = "add_cc";
                        //echo "<pre>"; var_dump($cc_vo); die();
                        $cc_obj = clone($cc_vo);
                        $cc_obj->set_country_id($ccmap[$i]['country']);
                        $cc_obj->set_code($ccmap[$i]['code']);
                        $cc_obj->set_description($this->input->post('name'));
                        $cc_obj->set_duty_pcent($ccmap[$i]['duty']);
                        // $ccm_obj->set_custom_class_id($cc_obj->get_id());

                    if($this->custom_class_model->$action($cc_obj) === FALSE)
                    {
                        $error_message = __LINE__ . "category.php ".$action." Error. ".$cc_dao->db->_error_message();
                        $_SESSION["NOTICE"] = $error_message;
                    }

                    $ccm_obj = $this->custom_class_model->get_ccm(array('sub_cat_id'=>$value, 'country_id'=>$ccmap[$i]['country']));
                    $ccm_dao = $this->custom_class_model->custom_classification_mapping_service->get_dao();
                    $ccm_vo = $ccm_dao->get();
                    //if no record we add new record
                    if(!$ccm_obj)
                    {
                        $action = "insert_ccm";
                        //echo "<pre>"; var_dump($ccm_vo); die();
                        $ccm_obj = clone($ccm_vo);
                        $ccm_obj->set_sub_cat_id($value);
                        $ccm_obj->set_country_id($ccmap[$i]['country']);
                        $ccm_obj->set_custom_class_id($cc_obj->get_id());
                    }
                    else
                    {
                        // if record found then we update
                        $action = "update_ccm";
                        $ccm_obj->set_custom_class_id($cc_obj->get_id());
                    }

                    if($this->custom_class_model->$action($ccm_obj) === FALSE)
                    {
                        $error_message = __LINE__ . "category.php ".$action." Error. ".$ccm_dao->db->_error_message();
                        $_SESSION["NOTICE"] = $error_message;
                    }

                }
            }

            if($this->input->post('level') == 1)
            {
                $cat_obj->set_parent_cat_id(0);
            }
            else
            {
                $cat_obj->set_parent_cat_id($this->input->post('subcat'));
            }

            $ret = $this->category_model->update_category($cat_obj);
            if($ret === FALSE)
            {
                $_SESSION["NOTICE"] = "Update Failed";
            }
            else
            {
                unset($_SESSION["category_edit"]);
                unset($_SESSION["cat_ext"]);
                //Tommy
                //redirect(base_url()."marketing/category/top/?refresh=1&updated=1");
                redirect(base_url()."marketing/category/view/".$value);
            }
        }
        $cat_obj = $this->category_model->get_cat_obj($value);
        $cat_cont_obj = $this->category_model->get_cat_cont_list(array("cat_id"=>$value));
        $data["cat_obj"] = $cat_obj;
        $data["cat_cont_obj"] = $cat_cont_obj;
        $data["canadd"] = $canadd;
        $data["canedit"] = $canedit;
        $parent = $cat_obj->get_parent_cat_id();
        $all = '1';
        $data['optionhs'] = $this->custom_class_model->get_cc_option($all);

        $parent_list = "";
        $child_list = "";
        $subcat_list = "";
        if($cat_obj->get_level() == "3")
        {
            $parent_list = $this->category_model->category_service->get_list(array("level"=>"2","id <>"=>"0"), array("limit" =>-1));
            $subcat_list = $this->category_model->get_parent(3,$cat_obj->get_id());
            if($inherit == 1){
                $hs_obj = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                $data['hs'] = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                $udesc = array();
                    for ($i=0; $i<count($data['hs']);$i++) {
                        $udesc[$i]['code'] = $data['hs'][$i]['code'];
                        $udesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                $updesc = array();
                    for ($i=0; $i<count($data['parcode']);$i++) {
                        $updesc[$i]['code'] = $data['parcode'][$i]['code'];
                        $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['upcode'] = $this->arrayUnique($updesc);

                for ($i=0; $i<count($data['hs']);$i++) {
                    $uarr .= '"'.$data['hs'][$i]['country_id'].'"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;
            }else{
                $hs_obj = $this->custom_class_model->get_cc_by_cat_sub_id($cat_obj->get_id());
                $data['hs'] = $this->custom_class_model->get_cc_by_cat_sub_id($cat_obj->get_id());
                $udesc = array();
                    for ($i=0; $i<count($data['hs']);$i++) {
                        $udesc[$i]['code'] = $data['hs'][$i]['code'];
                        $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                $updesc = array();
                    for ($i=0; $i<count($data['parcode']);$i++) {
                        $updesc[$i]['code'] = $data['parcode'][$i]['code'];
                        $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['upcode'] = $this->arrayUnique($updesc);

                for ($i=0; $i<count($data['hs']);$i++) {
                    $uarr .= '"'.$data['hs'][$i]['country_id'].'"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;
            }
            //$hs_obj = $this->custom_class_model->get_cc_by_cat_sub_id($cat_obj->get_id());
            //$data['hs'] = $this->custom_class_model->get_cc_by_cat_sub_id($cat_obj->get_id());
            //$par_obj = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
        }
        else if ($cat_obj->get_level() == "2")
        {
            $parent_list = $this->category_model->category_service->get_list(array("level"=>"1","id <>"=>"0"), array("limit" => -1));
            $subcat_list = $this->category_model->get_parent(2,$cat_obj->get_id());
            if($inherit == 1){
                $hs_obj = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                $data['hs'] = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                $udesc = array();
                    for ($i=0; $i<count($data['hs']);$i++) {
                        $udesc[$i]['code'] = $data['hs'][$i]['code'];
                        $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode'] = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                $updesc = array();
                    for ($i=0; $i<count($data['parcode']);$i++) {
                        $updesc[$i]['code'] = $data['parcode'][$i]['code'];
                        $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['upcode'] = $this->arrayUnique($updesc);

                for ($i=0; $i<count($data['hs']);$i++) {
                    $uarr .= '"'.$data['hs'][$i]['country_id'].'"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;
            }else{
                $hs_obj = $this->custom_class_model->get_cc_by_cat_sub_id($cat_obj->get_id());
                $data['hs'] = $this->custom_class_model->get_cc_by_cat_sub_id($cat_obj->get_id());
                $udesc = array();
                    for ($i=0; $i<count($data['hs']);$i++) {
                        $udesc[$i]['code'] = $data['hs'][$i]['code'];
                        $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                $data['parcode']  = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                $updesc = array();
                    for ($i=0; $i<count($data['parcode']);$i++) {
                        $updesc[$i]['code'] = $data['parcode'][$i]['code'];
                        $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                }
                $data['upcode'] = $this->arrayUnique($updesc);

                for ($i=0; $i<count($data['hs']);$i++) {
                    $uarr .= '"'.$data['hs'][$i]['country_id'].'"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;
            }
        }
        else
        {
                $hs_obj = $this->custom_class_model->get_cc_by_cat_sub_id($cat_obj->get_id());
                $data['hs'] = $this->custom_class_model->get_cc_by_cat_sub_id($cat_obj->get_id());
                $udesc = array();
                    for ($i=0; $i<count($data['hs']);$i++) {
                        $udesc[$i]['code'] = $data['hs'][$i]['code'];
                        $udesc[$i]['description'] = $data['hs'][$i]['description'];
                }
                $data['ucode'] = $this->arrayUnique($udesc);

                for ($i=0; $i<count($data['hs']);$i++) {
                    $uarr .= '"'.$data['hs'][$i]['country_id'].'"';
                    $uarr .= ', ';
                }
                $data['psarr'] = $uarr;

                // $data['parcode']  = $this->custom_class_model->get_cc_by_cat_sub_id($parent);
                // $updesc = array();
                //  for ($i=0; $i<count($data['parcode']);$i++) {
          //            $updesc[$i]['code'] = $data['parcode'][$i]['code'];
          //            $updesc[$i]['description'] = $data['parcode'][$i]['description'];
                // }
                //$data['upcode'] = $this->arrayUnique($updesc);

            $child_list = $this->category_model->getlistcount(1,$cat_obj->get_id());
            // $hs_obj = $this->custom_class_model->get_cc_for_main_cat_id($cat_obj->get_id());
            // $data['hs'] = $this->custom_class_model->get_cc_for_main_cat_id($cat_obj->get_id());
            //do nothing
        }

        if (empty($data["cat_ext"]))
        {
            if (($data["cat_ext"] = $this->category_service->get_cat_ext_w_key(array("cat_id"=>$value))) === FALSE)
            {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            }
            else
            {
                $_SESSION["cat_ext"] = serialize($data["cat_ext"]);
            }
        }

        include_once APPPATH."language/".$this->_get_app_id()."01_".$this->_get_lang_id().".php";
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $data["parent_list"] = $parent_list;
        $data["child_list"] = $child_list;
        $data["subcat_list"] = $subcat_list;
        $data["lang_list"] = $this->language_service->get_list(array("status"=>1), array("limit"=>-1));
        $data["cat_id"] = $value;
        $_SESSION["category_edit"] = serialize($cat_obj);
        $this->load->view("marketing/category/category_view",$data);
    }

    public function arrayUnique($array, $preserveKeys = false)
    {
        // Unique Array for return
        $arrayRewrite = array();
        // Array with the md5 hashes
        $arrayHashes = array();
        foreach($array as $key => $item) {
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
        $list = $this->category_model->getlistcnt(1,0,1);
        $data["objlist"] = $list;
        $data["level"] = 1;
        $data["status"] = 1;
        $this->load->view('marketing/category/category_left',$data);
    }

    public function view_scpv()
    {
        $canedit = 1;
        $data = array();
        $selling_platform_obj_list = $this->category_model->get_selling_platform(array(), array("orderby"=>"name", "limit"=>-1));
        $cat_obj = $this->category_model->get_cat_obj($this->input->get("subcat_id"));
        $ccid_list = $this->category_model->get_custom_class_list_w_platform_id($this->input->get('platform'));
        $currency_list = $this->category_model->get_currency_list();
        $data["sp_list"] = $selling_platform_obj_list;
        $data["cat_obj"] = $cat_obj;
        $data["currency_list"]= $currency_list;
        $data["canedit"] = $canedit;
        $scpv_obj = $this->category_model->get_scpv_obj(array("sub_cat_id"=>$this->input->get('subcat_id'), "platform_id"=>$this->input->get('platform')));
        $data["scpv_obj"] = $scpv_obj;
        if(empty($scpv_obj))
        {
            $data["action"] = "insert";
            $data["scpv_obj"] = $this->category_model->get_scpv_obj(array());
            $replace_scpv_obj = $this->category_model->get_replace_scpv_obj(array("selling_platform_id"=>$this->input->get('platform')));
            if($replace_scpv_obj)
            {
                $data["scpv_obj"]->set_currency_id($replace_scpv_obj->get_platform_currency_id());
            }
        }
        else
        {
            $_SESSION["scpv_obj"] = serialize($scpv_obj);
            $data["action"] = "update";
        }
        $_SESSION["scpv_obj"] = serialize($scpv_obj);
        include_once APPPATH."language/".$this->_get_app_id()."04_".$this->_get_lang_id().".php";
        $data["lang"] = $lang;
        $this->load->view("marketing/category/scpv_view",$data);
    }

    public function update_scpv()
    {
        if($this->input->post('posted'))
        {
            if($this->input->post('type') == "insert")
            {
                $scpv_obj = $this->category_model->get_scpv_obj(array());

            }
            else
            {
                $this->category_model->__autoload_scpv();
                $scpv_obj = unserialize($_SESSION["scpv_obj"]);

            }
            $d = 1;
            $scpv_obj->set_sub_cat_id($this->input->post('subcat_id'));
            $scpv_obj->set_platform_id($this->input->post('platform'));
            $scpv_obj->set_fixed_fee($this->input->post('fixed_fee'));
            $scpv_obj->set_profit_margin($this->input->post('profit_margin'));
            $scpv_obj->set_currency_id($this->input->post('currency'));
            $scpv_obj->set_dlvry_chrg($this->input->post('dlvry_chrg'));
            $scpv_obj->set_platform_commission($this->input->post('commission'));
            if($this->input->post('type') == "insert")
            {
                $ret = $this->category_model->insert_scpv($scpv_obj);
                if($ret === FALSE)
                {
                    $_SESSION["notice"] = "Cannot insert record into database";
                    $d = 0;
                }
            }
            else
            {
                $ret = $this->category_model->update_scpv($scpv_obj);
                if($ret === FALSE)
                {
                    $_SESSION["notice"] = "Cannot update current record";
                    $d = 0;
                }
            }
            unset($_SESSION["spcv_obj"]);
            redirect(base_url()."marketing/category/view_scpv/?subcat_id=".$this->input->post('subcat_id').'&platform='.$this->input->post('platform')."&dtype=".$this->input->post('type')."&d=".$d);
        }
    }

    public function view_prod_spec($cat_id = "")
    {
        if($this->input->post('posted'))
        {
            foreach($this->input->post('cps_obj') AS $ps_id=>$cps_array)
            {
                $cps_obj = $this->category_model->get_cps(array('cat_id'=>$cat_id, 'ps_id'=>$ps_id));
                if($cps_obj)
                {
                    $cps_action = "update_cps";
                }
                else
                {
                    $cps_action = "insert_cps";
                    $cps_obj = $this->category_model->get_cps();
                    $cps_obj->set_ps_id($ps_id);
                    $cps_obj->set_cat_id($cat_id);
                    $cps_obj->set_unit_id($cps_array['unit_id']);
                }
                $cps_obj->set_priority($cps_array['priority']);
                $cps_obj->set_status($cps_array['status']);
                if($this->category_model->$cps_action($cps_obj) === FALSE)
                {
                    $_SESSION["NOTICE"] = "Error: ".__LINE__.": ".$this->db->_error_message();
                }
            }
            if(!$_SESSION["NOTICE"])
            {
                redirect(base_url()."marketing/category/view_prod_spec/".$cat_id);
            }
        }
        $data["cat_obj"] = $this->category_model->get_category(array("id"=>$cat_id, "level"=>2, "status"=>1));
        $full_cps_list = $this->category_model->get_full_cps_list($cat_id);
        foreach($full_cps_list AS $obj)
        {
            $data["full_cps_list"][$obj->get_psg_name()][$obj->get_ps_name()] = $obj;
        }
        $unit_type_list = $this->category_model->get_unit_type_list(array("status"=>1), array("orderby"=>"name ASC"));
        foreach($unit_type_list AS $ut_obj)
        {
            $ut_array[$ut_obj->get_id()] = $this->category_model->get_unit_list(array("status"=>1, "unit_type_id"=>$ut_obj->get_id()), array("orderby"=>"standardize_value DESC"));
        }
        $data["ut_array"] = $ut_array;

/*      var_dump($full_cps_list);
        $psg_list= $this->category_model->get_prod_spec_group_list(array("status"=>1), array("orderby"=>"priority DESC"));
        foreach($psg_list AS $psg_obj)
        {
            $psg_id = $psg_obj->get_id();
            $no_of_row_psl = $this->category_model->get_no_of_row_psl(array("status"=>1 ,"psg_id"=>$psg_id));
            if($no_of_row_psl)
            {
                $data["prod_spec_list"][$psg_id] = $this->category_model->get_prod_spec_list(array("cat_id"=>$cat_id, "psg_id"=>$psg_id), array("orderby"=>"status DESC, psg_id, priority DESC"));
            }
        }
        $data["prod_spec_group_list"] = $psg_list;
        $data["unit_type_list"] = $this->category_model->get_unit_type_list(array("status"=>1), array("orderby"=>"name ASC"));
*/
        $data["cat_id"] = $cat_id;

        $data["lang"] = $lang;
        include_once APPPATH."language/".$this->_get_app_id()."05_".$this->_get_lang_id().".php";

        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view("marketing/category/sc_prod_spec",$data);
    }

    /*
    public function edit_prod_spec()
    {
        $cmd = $this->input->post("cmd");
        if($cmd == "add")
        {
            $cat_id = $this->input->post("cat_id");
            unset($_SESSION["NOTICE"]);

            $ps_obj = $this->category_model->get_prod_spec();
            set_value($ps_obj, $_POST);
            $ps_obj->set_status(1);
            if (!$this->category_model->add_prod_spec($ps_obj))
            {
                $_SESSION["NOTICE"] = $this->db->_error_message();
            }
            redirect(base_url()."marketing/category/view_prod_spec/".$cat_id);
        }
        elseif($cmd == "update")
        {
            unset($_SESSION["NOTICE"]);
            $ps_id = $this->input->post("ps_id");
            $cat_id = $this->input->post("cat_id");
            $ps_obj = $this->category_model->get_prod_spec(array("id"=>$ps_id));
            if($ps_obj)
            {
                $ps_obj->set_name($this->input->post("name"));
                $ps_obj->set_status($this->input->post("status"));
                $ps_obj->set_priority($this->input->post("priority"));
                if (!$this->category_model->update_prod_spec($ps_obj))
                {
                    var_dump($this->db->_error_message());
                    $_SESSION["NOTICE"] = $this->db->_error_message();
                }
                redirect(base_url()."marketing/category/view_prod_spec/".$cat_id);
            }
            else
            {
                $_SESSION["NOTICE"] = "Product Specification Not Exist.";
            }
        }
    }
    */

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

/*
    public function js_unitlist(){
        $unit_type_list = $this->category_model->get_unit_type_list(array("status"=>1), array("orderby"=>"name ASC"));
        foreach($unit_type_list AS $ut_obj)
        {
            $unit_list = $this->category_model->get_unit_list(array("status"=>1, "unit_type_id"=>$ut_obj->get_id()), array("orderby"=>"standardize_value ASC"));
            foreach($unit_list AS $u_obj)
            {
                $jsunitlist[$ut_obj->get_id()][] = "'".$u_obj->get_id()."':'".$u_obj->get_unit_name()."'";
            }
        }
        foreach ($jsunitlist as $utid=>$jsuname)
        {
            $jsunit[] = "'".$utid."': {".(implode(", ", $jsuname))."}";
        }
        $js = "unitlist = {".implode(", ", $jsunit)."};";
        $js .= "
            function ChangeUnit(val, obj)
            {
                obj.length = 1;
                for (var i in unitlist[val]){
                    obj.options[obj.options.length]=new Option(unitlist[val][i], i);
                }
            }";
        echo $js;
    }
    */
}

?>