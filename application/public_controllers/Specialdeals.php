<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Specialdeals extends PUB_Controller
{

    private $lang_id = "en";

    public function Specialdeals()
    {
        parent::PUB_Controller();
        $this->load->helper(array('url'));
        $this->load->model("marketing/festive_deal_model");
    }

    public function index($festive="")
    {
        $time = date("Y-m-d H:i:s");
        $result = $this->festive_deal_model->get("dao",array("link_name"=>$festive,"start_date <= "=>$time,"end_date >= "=>$time, "display"=>'Y'));
        if(!$result)
        {
            Redirect(base_url()."specialdeals/goto_current/");
        }
        else
        {
            $imagepath = base_url()."images/festive_deal/";

            $data["banner"] = $imagepath.$result->get_banner_file();
            $data["banner_link"] = $result->get_banner_link();

            $content = array();

            $section_list = $this->festive_deal_model->get_list("fds_dao",array("fd_id"=>$result->get_id()),array("orderby"=>"display_order ASC"));

            if(count((array)$section_list))
            {
                foreach($section_list as $fds_obj)
                {
                    $content[] = $this->festive_deal_model->get_fd_detail($fds_obj, $imagepath);
                }
            }
            $data["content"] = $content;
            $data["festive"] = $festive;
            $data["error"] = 0;
            $this->load_view("specialdeals.php",$data);
        }
    }

    public function view_products($festive="",$link_id="")
    {
        $ret = $this->festive_deal_model->verify_festive_link_id($festive,$link_id);

        if(!count($ret))
        {
            Redirect(base_url()."specialdeals/goto_current/");
        }
        else
        {
            $sc_obj = $this->festive_deal_model->get("fdsc_dao",array("id"=>$link_id));
            $data["banner"] = $sc_obj->get_banner();
            $data["content"] = $this->festive_deal_model->get_fdsc_detail($ret, PLATFORM, $this->_get_lang_id());
            $data["festive"] = $festive;
            $data["errors"] = 0;
            $this->load_view("deals.php",$data);
        }
    }

    public function goto_current()
    {
        $data["message"] = "Sorry but this special deal is no longer available, you may wish to check out our other deals.";
        $data["error"] = 1;
        $time = date("Y-m-d H:i:s");
        $obj = $this->festive_deal_model->get("dao",array("end_date >="=>$time, "start_date <="=>$time, "display"=>'Y'));
        if($obj)
        {
            $data["link"] = base_url()."specialdeals/index2/".$obj->get_link_name();
        }
        else
        {
            $data["link"] = base_url();
        }
        $this->load_view("specialdeals.php",$data);
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }
}


?>
