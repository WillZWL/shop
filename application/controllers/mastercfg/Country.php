<?php

class Country extends MY_Controller
{
    private $lang_id = "en";
    private $app_id = "MST0012";

    public function __construct()
    {
        parent::__construct();
        $this->load->model("mastercfg/country_model");
        $this->load->model("mastercfg/language_model");
        $this->load->model("mastercfg/currency_model");
        $this->load->library("service/pagination_service");
        $this->load->helper(array("url","notice","object"));
    }

    public function index()
    {
        $sub_id = $this->_get_app_id()."01_".$this->_get_lang_id();

        $_SESSION["clist_page"] = base_url()."mastercfg/country/?".$_SERVER["QUERY_STRING"];

        $where = $option = array();
        if($this->input->get("id") != "")
        {
            $where["id LIKE"] = '%'.$this->input->get("id").'%';
        }

        if($this->input->get("id_3_digit") != "")
        {
            $where["id_3_digit LIKE"] = '%'.$this->input->get("id_3_digit").'%';
        }

        if($this->input->get("name") != "")
        {
            $where["name LIKE"] = '%'.$this->input->get("name").'%';
        }

        if($this->input->get("status") != "")
        {
            $where["status"] = $this->input->get("status");
        }

        if($this->input->get("currency_id") != "")
        {
            $where["currency_id"] = $this->input->get("currency_id");
        }

        if($this->input->get("language_id") != "")
        {
            $where["language_id"] = $this->input->get("language_id");
        }

        if($this->input->get("fc_id") != "")
        {
            $where["fc_id"] = $this->input->get("fc_id");
        }

        if($this->input->get("rma_fc") != "")
        {
            $where["rma_fc"] = $this->input->get("rma_fc");
        }

        if($this->input->get("allow_sell"))
        {
            $where["allow_sell"] = $this->input->get("allow_sell");
        }

        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["clist_page"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"])
        {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "status";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort." ".$order;

        $clist = $this->country_model->get_list_w_rma_fc($where,$option);
        $data["total"] = $this->country_model->get_list_w_rma_fc($where,array("num_rows"=>1));

        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["ar_lang"] = $this->language_model->get_name_w_id_key();
        $data["ar_currency"] = $this->currency_model->get_name_w_id_key();

        include_once APPPATH."language/".$sub_id.".php";
        $data["lang"] = $lang;
        $data["clist"] = $clist;
        $this->load->view("mastercfg/country/v_index",$data);
    }


    public function view($country = "")
    {
        $sub_id = $this->_get_app_id()."02_".$this->_get_lang_id();

        if($this->input->post('posted'))
        {
            $cobj = $this->country_model->get('country',array("id"=>$country));
            $cobj->set_id_3_digit($this->input->post("id_3_digit"));
            $cobj->set_status($this->input->post("status"));
            $cobj->set_currency_id($this->input->post("currency_id"));
            $cobj->set_language_id($this->input->post("language_id"));
            $cobj->set_fc_id(NULL);
            $cobj->set_allow_sell($this->input->post("allow_sell"));
            $cobj->set_url_enable($this->input->post("url_enable"));

            if($this->country_model->update('country',$cobj) === FALSE)
            {
                $_SESSION["NOTICE"] = __LINE__." : ".$this->db->_error_message();
            }
            else
            {
                //continue updating country name in different country
                $error = 0;
                foreach($_POST["langname"]  as $key=>$name)
                {
                    $ceobj = $this->country_model->get('country_ext', array("lang_id"=>$key,"cid"=>$country));
                    if($ceobj)
                    {
                        $ceobj->set_name($name);
                        $action = "update";
                    }
                    else
                    {
                        $ceobj = $this->country_model->get('country_ext');
                        $ceobj->set_cid($country);
                        $ceobj->set_lang_id($key);
                        $ceobj->set_name($name);
                        $action = "insert";
                    }

                    if($this->country_model->$action('country_ext',$ceobj) === FALSE)
                    {
                        $_SESSION["NOTICE"] = __LINE__." : ".$this->db->_error_message();
                        $error++;
                    }
                }

                if($rma_fc_obj = $this->country_model->get('rma_fc',array("cid"=>$country)))
                {
                    $rma_fc_obj->set_rma_fc($this->input->post('rma_fc'));

                    if($this->country_model->update('rma_fc',$rma_fc_obj) === FALSE)
                    {
                        $_SESSION["NOTICE"] = __LINE__." : ".$this->db->_error_message();
                        $error++;
                    }
                }
                else
                {
                    $_SESSION["NOTICE"] = __LINE__." : ".$this->db->_error_message();
                    $error++;
                }

                if(!$error)
                {
                    Redirect(base_url()."mastercfg/country/view/".$country) ;
                }
            }
        }

        if($country == "")
        {
            Redirect(base_url()."mastercfg/country/?".$_SESSION["cquery_string"]);
        }


        $country_vo = $this->country_model->get('country',array("id"=>$country));
        $lang_list = $this->language_model->get_list();
        $name = array();
        foreach($lang_list as $lobj)
        {
            $tmp = $this->country_model->get('country_ext',array('cid'=>$country,'lang_id'=>$lobj->get_id()));
            $name[$lobj->get_id()] = $tmp?$tmp->get_name():"";
        }

        include_once APPPATH."language/".$sub_id.".php";
        $data["lang"] = $lang;
        $data["country_vo"] = $country_vo;
        $data["name"] = $name;
        $data["notice"] = notice($lang);
        $data["ar_lang"] = $this->language_model->get_name_w_id_key();
        $data["ar_currency"] = $this->currency_model->get_name_w_id_key();
        $data["rma_fc_vo"] = $this->country_model->get('rma_fc',array("cid"=>$country));
        $this->load->view("mastercfg/country/v_view",$data);
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


?>