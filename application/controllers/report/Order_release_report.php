<?php
class Order_release_report extends MY_Controller
{
    private $app_id = "RPT0038";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/release_order_report_model');
        $this->load->helper(array('url','notice'));
        $this->load->library('input');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->_get_app_id()."00";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        return $lang;
    }

    public function index()
    {
        $data["title"] = "Compensation Report";

        include_once APPPATH.'/language/'.$this->_get_app_id().'00_'.$this->_get_lang_id().'.php';
        $data["lang"] = $lang;


        if($_POST["display_report"] == 1 || $_GET['per_page'] ||$_GET["search"])
        {
            if($_POST["order_release_start_date"] && $_POST["order_release_end_date"])
            {
                $data['start_date'] = $_POST["order_release_start_date"];
                $data['end_date'] = $_POST["order_release_end_date"];
                $_SESSION["start_date"] = $data['start_date'];
                $_SESSION["end_date"] = $data['end_date'];
            }
            else
            {
                $data['start_date'] = $_SESSION["start_date"];
                $data['end_date'] = $_SESSION["end_date"];
            }

            $where = array();
            $option = array();

            $where["roh.create_on >="] = $_SESSION["start_date"] . " 00:00:00";
            $where["roh.create_on <="] = $_SESSION["end_date"] . " 23:59:59";

            $sort = $this->input->get('sort');
            if($sort == "")
            {
                $sort = "roh.so_no";
            }
            $data["sort"] = $sort;

            $order = $this->input->get('order');
            if (empty($order))
            {
                $order = "asc";
            }
            $data["order"] = $order;

            $option["orderby"] = $sort." ".$order;




            $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
            $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
            $option["orderby"] = $sort." ".$order;

            if($this->input->get('so_no')!="")
            {
                $where["roh.so_no"] = $this->input->get('so_no');
            }
            if($this->input->get('hold_reason')!="")
            {
                $where["sohr.reason"] = $this->input->get('hold_reason');
            }
            if($this->input->get('hold_date')!="")
            {
                $where["sohr.create_on"] = $this->input->get('hold_date');
            }
            if($this->input->get('hold_by')!="")
            {
                $where["sohr.create_by"] = $this->input->get('hold_by');
            }
            if($this->input->get('release_reason')!="")
            {
                $where["roh.release_reason"] = $this->input->get('release_reason');
            }
            if($this->input->get('release_date')!="")
            {
                $where["roh.create_on"] = $this->input->get('release_date');
            }
            if($this->input->get('release_at')!="")
            {
                $where["roh.create_at"] = $this->input->get('release_at');
            }
            if($this->input->get('release_by')!="")
            {
                $where["roh.create_by"] = $this->input->get('release_by');
            }

            $option["limit"] = $pconfig['per_page'] = 20;
            if ($option["limit"])
            {
                $option["offset"] = $this->input->get("per_page");
            }

            $_SESSION["LISTPAGE"] = base_url()."report/order_release_report/index?".$_SERVER['QUERY_STRING'];
            $_SESSION["QUERY_STRING"] = $_SERVER['QUERY_STRING'];
            $data['list'] = $this->release_order_report_model->get_obj_list($where, $option);

            //var_dump($this->db->last_query());
            $option['num_rows'] = 1;
            $data['total'] = $this->release_order_report_model->get_obj_list($where, $option);


            $_SESSION["LISTPAGE"] = base_url()."report/order_release_report/index?".$_SERVER['QUERY_STRING'];
            $pconfig['base_url'] = $_SESSION["LISTPAGE"];
            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->initialize($pconfig);
        }

        $this->load->view('report/release_order_report',$data);
    }


    public function export_csv()
    {
        if($this->input->post('is_query'))
        {
            $data["posted"] = 1;

            if($_POST["start_date"]["order_create"])
            {
                $_SESSION['start_date'] = $_POST["start_date"]["order_create"];
                $where["roh.create_on >="] = $_POST["start_date"]["order_create"] . " 00:00:00";
            }
            if($_POST["end_date"]["order_create"])
            {
                $_SESSION['end_date'] = $_POST["end_date"]["order_create"];
                $where["roh.create_on <="] = $_POST["end_date"]["order_create"] . " 23:59:59";
            }
            $data['output'] = $this->release_order_report_model->get_csv($where);
            $data['filename'] = 'release_order_report'.$start_date.'-'.$end_date.'.csv';
            $this->load->view('output_csv.php', $data);
        }
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

/* End of file order_release_report.php */
/* Location: ./system/application/controllers/report/order_release_report.php */