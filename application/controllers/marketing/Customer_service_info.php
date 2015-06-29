<?php
class Customer_service_info extends MY_Controller
{
    private $app_id = "MKT0054";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url','directory','notice','object','image'));
        $this->load->model('marketing/customer_service_info_model');
        $this->load->library('service/platform_biz_var_service');
    }

    function index($lang_id="", $country_id="")
    {
        $sub_app_id = $this->_get_app_id()."00";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");

        if(!empty($country_id) && $lang_id == "ALL")
        {
            $platform_list = $this->customer_service_info_model->get_platform_list_w_country_id($country_id);
        }
        elseif($country_id == "" && !empty($lang_id))
        {
            $platform_list = $this->customer_service_info_model->get_platform_list_w_lang_id($lang_id);
        }

        if($this->input->post('posted'))
        {
            if($country_id)
            {
                foreach($platform_list as $plat_type)
                {
                    foreach($plat_type as $plat_obj)
                    {
                        $action = "update";
                        if(!$csi_obj = $this->customer_service_info_model->get(array("platform_id"=>$plat_obj->get_id())))
                        {
                            $csi_obj = $this->customer_service_info_model->get();
                            $csi_obj->set_platform_id($plat_obj->get_id());
                            $csi_obj->set_type($plat_obj->get_type());
                            $action = "insert";
                        }
                        $pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$plat_obj->get_id()));
                        $csi_obj->set_lang_id($pbv_obj->get_language_id());
                        $csi_obj->set_title($this->input->post('title'));
                        $csi_obj->set_content($this->input->post('content'));
                        $csi_obj->set_short_text($_POST['short_text'][strtolower($plat_obj->get_type())]);
                        $csi_obj->set_long_text($_POST['long_text'][strtolower($plat_obj->get_type())]);
                        $csi_obj->set_short_text_status(1);
                        $csi_obj->set_long_text_status(1);

                        if(!$this->customer_service_info_model->{$action}($csi_obj))
                        {
                            $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                        }
                    }
                }
            }
            elseif($lang_id)
            {
                $plat_arr = array("SKYPE", "WEBSITE");
                foreach($plat_arr as $type)
                {
                    $action = "update";
                    if(!$csi_obj = $this->customer_service_info_model->get(array("lang_id"=>$lang_id, "platform_id IS NULL"=>NULL, "type"=>$type)))
                    {
                        $csi_obj = $this->customer_service_info_model->get();
                        $csi_obj->set_platform_id(null);
                        $action = "insert";
                    }
                    $csi_obj->set_lang_id($lang_id);
                    $csi_obj->set_title($this->input->post('title'));
                    $csi_obj->set_content($this->input->post('content'));
                    $csi_obj->set_short_text_status(1);
                    $csi_obj->set_long_text_status(1);
                    $csi_obj->set_type($type);
                    $csi_obj->set_short_text($_POST['short_text'][strtolower($type)]);
                    $csi_obj->set_long_text($_POST['long_text'][strtolower($type)]);

                    if(!$this->customer_service_info_model->{$action}($csi_obj))
                    {
                        $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                    }
                }

                foreach($platform_list as $plat_type)
                {
                    foreach($plat_type as $plat_obj)
                    {
                        $action = "update";
                        if(!$csi_obj = $this->customer_service_info_model->get(array("platform_id"=>$plat_obj->get_id())))
                        {
                            $csi_obj = $this->customer_service_info_model->get();
                            $csi_obj->set_platform_id($plat_obj->get_id());
                            $csi_obj->set_type($plat_obj->get_type());
                            $action = "insert";
                        }
                        $csi_obj->set_lang_id($lang_id);
                        $csi_obj->set_title($this->input->post('title'));
                        $csi_obj->set_content($this->input->post('content'));
                        $csi_obj->set_short_text($_POST['short_text'][strtolower($plat_obj->get_type())]);
                        $csi_obj->set_long_text($_POST['long_text'][strtolower($plat_obj->get_type())]);
                        $csi_obj->set_short_text_status(1);
                        $csi_obj->set_long_text_status(1);

                        if(!$this->customer_service_info_model->{$action}($csi_obj))
                        {
                            $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                        }
                    }
                }
            }
        }

        if($country_id)
        {
            foreach($platform_list as $plat_type)
            {
                foreach($plat_type as $plat_obj)
                {
                    $data['csi_obj'][$plat_obj->get_type()] = $this->customer_service_info_model->get(array("platform_id"=>$plat_obj->get_id()));
                }
            }
        }
        elseif($lang_id)
        {
            $data['csi_obj']['WEBSITE'] = $this->customer_service_info_model->get(array("lang_id"=>$lang_id, "platform_id IS NULL"=>NULL, "type"=>"WEBSITE"));
            $data['csi_obj']['SKYPE'] = $this->customer_service_info_model->get(array("lang_id"=>$lang_id, "platform_id IS NULL"=>NULL, "type"=>"SKYPE"));
        }

        $data["language_id"] = $lang_id;
        $data["country_id"] = $country_id;

        $data["platform_list"] = $this->customer_service_info_model->get_platform_list_w_country_id($country_id);
        $data["country_list"] = $this->customer_service_info_model->get_country_list(array("allow_sell"=>1, "status"=>1), array("orderby"=>"name ASC", "limit"=>-1));
        $data["lang_list"] = $this->customer_service_info_model->get_language_list(array("status"=>1), array("orderby"=>"name ASC"));
        $data["lang"] = $lang;

        $this->load->view("marketing/customer_service_info/cs_info_v.php", $data);
    }

    public function status()
    {
        if($this->input->post('posted'))
        {
            $country_list = $this->customer_service_info_model->get_country_list(array("allow_sell"=>1, "status"=>1), array("orderby"=>"name ASC", "limit"=>-1));
            foreach($country_list as $c_obj)
            {
                $platform_list = $this->customer_service_info_model->get_platform_list_w_country_id($c_obj->get_id());
                foreach($platform_list as $plat_type)
                {
                    foreach($plat_type as $plat_obj)
                    {
                        $action = "update";
                        if(!$csi_obj = $this->customer_service_info_model->get(array("platform_id"=>$plat_obj->get_id())))
                        {
                            $csi_obj = $this->customer_service_info_model->get();
                            $csi_obj->set_platform_id($plat_obj->get_id());
                            $csi_obj->set_type($plat_obj->get_type());
                            $csi_obj->set_title('');
                            $csi_obj->set_content('');
                            $csi_obj->set_short_text('');
                            $csi_obj->set_long_text('');
                            $action = "insert";
                        }
                        $pbv_obj = $this->platform_biz_var_service->get(array("selling_platform_id"=>$plat_obj->get_id()));
                        $csi_obj->set_lang_id($pbv_obj->get_language_id());
                        $csi_obj->set_type($plat_obj->get_type());

                        if($plat_obj->get_type()=='WEBSITE')
                        {
                            $st_status = $_POST["st_status_w"][$pbv_obj->get_language_id()][$c_obj->get_id()]?1:0;
                            $lt_status = $_POST["lt_status_w"][$pbv_obj->get_language_id()][$c_obj->get_id()]?1:0;
                        }
                        elseif($plat_obj->get_type()=='SKYPE')
                        {
                            $st_status = $_POST["st_status_s"][$pbv_obj->get_language_id()][$c_obj->get_id()]?1:0;
                            $lt_status = $_POST["lt_status_s"][$pbv_obj->get_language_id()][$c_obj->get_id()]?1:0;
                        }

                        $csi_obj->set_short_text_status($st_status);
                        $csi_obj->set_long_text_status($lt_status);

                        if(!$this->customer_service_info_model->{$action}($csi_obj))
                        {

                            $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                        }
                    }
                }
            }

            $lang_list = $this->customer_service_info_model->get_language_list(array("status"=>1), array("orderby"=>"name ASC"));
            foreach($lang_list as $lang_obj)
            {
                $plat_arr = array("SKYPE", "WEBSITE");
                foreach($plat_arr as $type)
                {
                    $action = "update";
                    if(!$csi_obj = $this->customer_service_info_model->get(array("lang_id"=>$lang_obj->get_id(), "type"=>$type, "platform_id IS NULL"=>NULL)))
                    {
                        $csi_obj = $this->customer_service_info_model->get();
                        $csi_obj->set_platform_id(null);
                        $csi_obj->set_title('');
                        $csi_obj->set_content('');
                        $csi_obj->set_short_text('');
                        $csi_obj->set_long_text('');
                        $action = "insert";
                    }
                    $csi_obj->set_lang_id($lang_obj->get_id());
                    $csi_obj->set_type($type);

                    if($type=='WEBSITE')
                    {
                        $st_status = $_POST["st_status_we"][$lang_obj->get_id()]?1:0;
                        $lt_status = $_POST["lt_status_we"][$lang_obj->get_id()]?1:0;
                    }
                    elseif($type=='SKYPE')
                    {
                        $st_status = $_POST["st_status_sk"][$lang_obj->get_id()]?1:0;
                        $lt_status = $_POST["lt_status_sk"][$lang_obj->get_id()]?1:0;
                    }

                    $csi_obj->set_short_text_status($st_status);
                    $csi_obj->set_long_text_status($lt_status);

                    if(!$this->customer_service_info_model->{$action}($csi_obj))
                    {
                        $_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
                    }
                }
            }
        }
        include_once APPPATH."language/".$this->_get_app_id()."01_".$this->_get_lang_id().".php";

        $data['country_list'] = $this->customer_service_info_model->get_country_list(array("allow_sell"=>1, "status"=>1), array("orderby"=>"name ASC", "limit"=>-1));
        $country_list = $this->customer_service_info_model->get_country_language_list();

        foreach($country_list as $c_obj)
        {
            $data['country_list_by_country_id'][$c_obj->get_id()] = $c_obj;
            $data['country_list_by_lang'][$c_obj->get_language_id()][] = $c_obj;
        }

        foreach($country_list as $c_obj)
        {
            $platform_list = $this->customer_service_info_model->get_platform_list_w_country_id($c_obj->get_id());
            foreach($platform_list as $plat_type)
            {
                foreach($plat_type as $plat_obj)
                {
                    $data['csi_obj'] = $this->customer_service_info_model->get_list(array("platform_id"=>$plat_obj->get_id(), "(type='WEBSITE' OR type='SKYPE')"=>NULL), array("limit"=>-1));
                    foreach($data['csi_obj'] as $obj)
                    {
                        if($obj->get_type()=='WEBSITE')
                        {
                            $data['st_status_w'][$c_obj->get_id()] = ($obj->get_short_text_status())?1:0;
                            $data['lt_status_w'][$c_obj->get_id()] = ($obj->get_long_text_status())?1:0;
                        }
                        elseif($obj->get_type()=='SKYPE')
                        {
                            $data['st_status_s'][$c_obj->get_id()] = ($obj->get_short_text_status())?1:0;
                            $data['lt_status_s'][$c_obj->get_id()] = ($obj->get_long_text_status())?1:0;
                        }
                    }
                }
            }
        }

        $lang_list = $this->customer_service_info_model->get_language_list(array("status"=>1), array("orderby"=>"name ASC"));
        foreach($lang_list as $lang_obj)
        {
            $data['csi_lang_obj'] = $this->customer_service_info_model->get_list(array("platform_id is NULL"=>NULL, "(type='WEBSITE' OR type='SKYPE')"=>NULL, "lang_id"=>$lang_obj->get_id()), array("limit"=>-1));
            foreach($data['csi_lang_obj'] as $obj)
            {
                if($obj->get_type()=='WEBSITE')
                {
                    $data['st_status_we'][$lang_obj->get_id()] = ($obj->get_short_text_status())?1:0;
                    $data['lt_status_we'][$lang_obj->get_id()] = ($obj->get_long_text_status())?1:0;
                }
                elseif($obj->get_type()=='SKYPE')
                {
                    $data['st_status_sk'][$lang_obj->get_id()] = ($obj->get_short_text_status())?1:0;
                    $data['lt_status_sk'][$lang_obj->get_id()] = ($obj->get_long_text_status())?1:0;
                }
            }
        }

        $platform_list_s = $this->platform_biz_var_service->get_list(array("(selling_platform_id LIKE 'WS%')"=>null), array("limit"=>-1));
        $platform_list_w = $this->platform_biz_var_service->get_list(array("(selling_platform_id LIKE 'WEB%')"=>null), array("limit"=>-1));

        foreach($platform_list_s as $obj)
        {
            $data['pbv']['SKYPE'][$obj->get_language_id()][$obj->get_platform_country_id()] = 1;
        }
        foreach($platform_list_w as $obj)
        {
            $data['pbv']['WEBSITE'][$obj->get_language_id()][$obj->get_platform_country_id()] = 1;
        }

        $data["lang"] = $lang;
        $data["notice"] = notice($lang);

        foreach($lang_list as $obj)
        {
            $data['lang_list'][$obj->get_id()] = $obj;
        }

        $this->load->view('marketing/customer_service_info/cs_info_status.php',$data);
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}
