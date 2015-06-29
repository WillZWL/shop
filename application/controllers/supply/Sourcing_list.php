<?php
class Sourcing_list extends MY_Controller
{

    private $app_id="SUP0003";
    private $lang_id="en";


    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'notice', 'object', 'operator'));
        $this->load->library('service/pagination_service');
        $this->load->model('order/so_model');
        $this->load->model('marketing/product_model');
    }

    public function index($type="")
    {
        $sub_app_id = $this->_get_app_id()."00";

        $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];

        if ($this->input->post("posted"))
        {
            $rsresult = "";
            $shownotice = 0;
            $cur_time = date("Y-m-d H:i:s");
            foreach ($_POST["check"] as $rssku)
            {
                $g_where["list_date"] = $this->input->post("list_date");
                $g_where["item_sku"] = $rssku;
                $g_where["modify_on <"] = $cur_time;
                $success = 0;
                if (($src_obj = $this->product_model->product_service->get_sl_dao()->get($g_where))!==FALSE)
                {
                    set_value($src_obj, $_POST["src"][$rssku]);
                    if ($this->product_model->product_service->get_sl_dao()->update($src_obj))
                    {
                        $success = 1;
                    }
                }
                if (!$success)
                {
                    $shownotice = 1;
                }
                $rsresult .= "{$rssku} -> {$success}\\n";
            }
            if ($shownotice)
            {
                $_SESSION["NOTICE"] = $rsresult;
            }
            redirect(current_url()."?".$_SERVER['QUERY_STRING']);
        }

        if ($type != "print")
        {
            $where = array();
            $option = array();

            $submit_search = 0;

            if ($this->input->get("master_sku") != "")
            {
                $where["ext_sku LIKE "] = "%".$this->input->get("master_sku")."%";
            }

            if ($this->input->get("item_sku") != "")
            {
                $where["item_sku LIKE "] = "%".$this->input->get("item_sku")."%";
            }

            if ($this->input->get("prod_name") != "")
            {
                $where["p.name LIKE "] = "%".$this->input->get("prod_name")."%";
            }

            if ($this->input->get("sourcing_status") != "")
            {
                $where["sourcing_status"] = $this->input->get("sourcing_status");
            }

            if ($this->input->get("requested_qty") != "")
            {
                fetch_operator($where, "requested_qty", $this->input->get("requested_qty"));
            }

            if ($this->input->get("sourced_qty") != "")
            {
                fetch_operator($where, "sourced_qty", $this->input->get("sourced_qty"));
            }

            if ($this->input->get("comments") != "")
            {
                $where["comments LIKE "] = "%".$this->input->get("comments")."%";
            }

            if (($type != "csv") && ($type != "xml"))
            {
                $limit = '20';

                $pconfig['base_url'] = $_SESSION["LISTPAGE"];
                $option["limit"] = $pconfig['per_page'] = $limit;
                if ($option["limit"])
                {
                    $option["offset"] = $this->input->get("per_page");
                }
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

        }

        if (empty($sort))
            $sort = "prod_name";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort." ".$order;

        $data["mindate"] = $this->product_model->product_service->get_sl_dao()->get_min_date();
        $data["maxdate"] = $this->product_model->product_service->get_sl_dao()->get_max_date();
        $data["list_date"] = $where["list_date"] = $this->input->get("list_date")?$this->input->get("list_date"):$data["maxdate"];
        if (($type != "csv") && ($type != "xml"))
        {
            $src_list = $this->product_model->product_service->get_sorucing_list($where, $option);
            $data["objlist"] = $src_list["objlist"];
            $data["platform"] = $src_list["platform"];
            $data["total"] = $this->product_model->product_service->get_sorucing_list($where, array("num_rows"=>1));

            if (!$type)
            {
                $pconfig['total_rows'] = $data['total'];
                $this->pagination_service->set_show_count_tag(TRUE);
                $this->pagination_service->initialize($pconfig);
            }

            include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
            $data["lang"] = $lang;

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
            $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
    //      $data["searchdisplay"] = ($where["brand_name"]=="" && $where["regions"]=="")?'style="display:none"':"";
            $data["searchdisplay"] = "";
            $this->load->view('supply/sourcing_list/sourcing_list'.($type?"_print":"").'_v', $data);
        }
        else
        {
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"VB_sourcing_list_" . $where["list_date"] . "." . $type . "\"");
            if ($type == "csv")
                $gen_csv_par = 1;
            else if ($type == "xml")
            {
                header('Content-type: text/xml');
                $gen_csv_par = 2;
            }
            $this->product_model->product_service->get_sorucing_list($where, $option, $gen_csv_par);
        }
    }

    public function import($listdate)
    {
        if (!empty($_FILES["csv_file"]["tmp_name"]))
        {
            if ($rs = $this->product_model->product_service->import_sorucing_list($listdate, $_FILES["csv_file"]["tmp_name"]))
            {
                $_SESSION["NOTICE"] = $rs;
            }
            @unlink($_FILES["csv_file"]["tmp_name"]);
        }
        redirect($_SESSION["LISTPAGE"]);
    }

    public function download()
    {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"sourcing_list_import_example.csv\"");
        echo "SKU,SRC_QTY,COMMENTS";
    }

    public function _get_app_id(){
        return $this->app_id;
    }

    public function _get_lang_id(){
        return $this->lang_id;
    }
}

/* End of file purchaser.php */
/* Location: ./system/application/controllers/supply/purchaser.php */