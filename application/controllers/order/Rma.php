<?php
class Rma extends MY_Controller
{

    private $app_id="ORD0003";
    private $lang_id="en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/so_model');
        $this->load->helper('url');
        $this->load->helper('notice');
        $this->load->helper('object');
        $this->load->model('mastercfg/country_model');
        $this->load->library('service/pagination_service');
    }

    public function index()
    {
        $sub_app_id = $this->_get_app_id()."00";

        $_SESSION["LISTPAGE"] = base_url()."order/rma/?".$_SERVER['QUERY_STRING'];

        $where = array();
        $option = array();

        if ($this->input->get("id"))
        {
            $where["id"] = $this->input->get("id");
        }
        if ($this->input->get("so_no"))
        {
            $where["so_no"] = $this->input->get("so_no");
        }
        if ($this->input->get("client_id"))
        {
            $where["client_id"] = $this->input->get("client_id");
        }
        if ($this->input->get("product_returned"))
        {
            $where["product_returned LIKE"] = "%".$this->input->get("product_returned")."%";
        }
        if ($this->input->get("category"))
        {
            $where["category"] = $this->input->get("category");
        }
        if ($this->input->get("reason"))
        {
            $where["reason"] = $this->input->get("reason");
        }
        if ($this->input->get("action_request"))
        {
            $where["action_request"] = $this->input->get("action_request");
        }
        if ($this->input->get("status"))
        {
            $where["status"] = $this->input->get("status");
        }
        $sort = $this->input->get("sort");
        $order = $this->input->get("order");

        $limit = '20';

        $pconfig['base_url'] = $_SESSION["LISTPAGE"];
        $option["limit"] = $pconfig['per_page'] = $limit;
        if ($option["limit"])
        {
            $option["offset"] = $this->input->get("per_page");
        }

        if (empty($sort))
            $sort = "id";

        if (empty($order))
            $order = "desc";

        $option["orderby"] = $sort." ".$order;

        $data["objlist"] = $this->so_model->get_list("rma_dao", $where, $option);
        $pconfig['total_rows'] = $this->so_model->get_num_rows("rma_dao", $where);

        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        $this->pagination_service->set_show_count_tag(TRUE);
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
        $data["searchdisplay"] = "";
        $this->load->view('order/rma/rma_index_v', $data);
    }

    public function view($id="")
    {
        if ($id)
        {
            $sub_app_id = $this->_get_app_id()."01";

            $data["rma_obj"] = $this->so_model->get("rma_dao", array("id"=>$id));
            if($data["rma_obj"])
            {
                $rma_so_no_arr = explode("-", $data["rma_obj"]->get_so_no());
                if(count($rma_so_no_arr)>1)
                {
                    $so_no = $rma_so_no_arr[1];
                }
                else
                {
                    $so_no = $data["rma_obj"]->get_so_no();
                }
            }
            $data["rma_obj"]->set_so_no($so_no);
            $data["client_obj"] = $this->so_model->get("client_dao", array("id"=>$data["rma_obj"]->get_client_id()));
            $data["so_obj"] = $this->so_model->get("dao", array("so_no"=>$so_no));
            $data["rma_notes_obj"] = $this->so_model->get_list("rma_notes_dao", array("rma_id"=>$id, "so_no"=>$data["rma_obj"]->get_so_no()), array("orderby"=>"create_on desc"));
            $data["rma_history_obj"] = $this->so_model->get_list("rma_history_dao", array("rma_id"=>$id, "so_no"=>$data["rma_obj"]->get_so_no()), array("orderby"=>"id desc"));

            include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
            $data["lang"] = $lang;
            $data["notice"] = notice($lang);

            $this->load->view('order/rma/rma_detail_v', $data);
        }
        else
        {
            redirect(base_url()."order/rma/");
        }
    }

    public function add_note($id = "")
    {
        if($rma_obj = $this->so_model->get("rma_dao", array("id"=>$id)))
        {
            if($rma_obj)
            {
                $rma_so_no_arr = explode("-", $rma_obj->get_so_no());
                if(count($rma_so_no_arr)>1)
                {
                    $so_no = $rma_so_no_arr[1];
                }
                else
                {
                    $so_no = $rma_obj->get_so_no();
                }
            }

            if($this->input->post("addnote"))
            {
                $rma_notes_obj = $this->so_model->get_rma_notes_vo($so_no);
                $rma_notes_obj->set_rma_id($id);
                $rma_notes_obj->set_so_no($so_no);
                $rma_notes_obj->set_note($this->input->post("note"));
                $rma_notes_obj->set_status(1);
                if($this->so_model->insert_rma_notes($rma_notes_obj))
                {
                    redirect(base_url()."order/rma/view/".$id);
                }
                else
                {
                    $_SESSION["NOTICE"] = "Failed to insert RMA notes";
                    redirect(base_url()."order/rma/view/".$id);
                }
            }
        }
        else
        {
            $_SESSION["NOTICE"] = "RMA not found";
            redirect(base_url()."order/rma/");
        }
    }

    public function update_rma_status($id = "")
    {
        if($rma_obj = $this->so_model->get("rma_dao", array("id"=>$id)))
        {
            if($rma_obj)
            {
                $rma_so_no_arr = explode("-", $rma_obj->get_so_no());
                if(count($rma_so_no_arr)>1)
                {
                    $so_no = $rma_so_no_arr[1];
                }
                else
                {
                    $so_no = $rma_obj->get_so_no();
                }
            }

            if($this->input->post("update_status"))
            {
                $rma_history_obj = $this->so_model->get_rma_history_vo($so_no);
                $rma_history_obj->set_rma_id($id);
                $rma_history_obj->set_so_no($so_no);
                $rma_history_obj->set_status($this->input->post("rma_status"));
                if($this->so_model->insert_rma_history($rma_history_obj))
                {
                    redirect(base_url()."order/rma/view/".$id);
                }
                else
                {
                    $_SESSION["NOTICE"] = "Failed to update RMA history";
                    redirect(base_url()."order/rma/view/".$id);
                }
            }
        }
        else
        {
            $_SESSION["NOTICE"] = "RMA not found";
            redirect(base_url()."order/rma/");
        }
    }

    public function rma_addr_js()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);

        $sell_to_list = $this->country_model->get_sell_to_list();
        $carr = array();
        foreach($sell_to_list as $country)
        {
            $carr[] = "'".$country->get_id()."':['".strtolower(substr($country->get_fc_id(),0,2))."','".$country->get_name()."']";
        }

        $js = "var rmacountrylist = {".implode(",",$carr)."}";
        unset($carr);
        $js .= "

                function get_content(country_id )
                {

                    t = country_id?rmacountrylist[country_id][0]:'';
                    switch(t)
                    {
                        case 'us':
                        addr = '<b>AMS Fulfillment/ Valuebasket Ltd</b><br />29120 Commerce Center Drive, #2<br />Valencia, CA 91355<br />USA';
                        break;

                        case 'uk':
                        addr = '<b>International Logistics Group Ltd c/o Valuebasket Ltd</b><br/>Unit 2D Gatwick Gate<br>Charlwood Road<br>Lowfield Heath<br>West Sussex<br>RH11 0TG<br>United Kingdom';
                        break;

                        case 'hk':
                        addr = '<b>ALN Ltd c/o Valuebasket Ltd</b><br/>Block B 7/F Great Wall Factory Building.,<br/>11 Cheung Shun Street,<br/>Lai Chi Kok,<br/>Hong Kong';
                        break;

                        default:
                        addr = '';
                        break;
                    }

                    if(addr)
                    {
                        document.write(addr);
                        //$('#rmaaddr').html(addr);
                    }
                }

                function draw_cl(select)
                {
                    document.write(\"<option value=''></option>\");
                    var selected = '';
                    for(var i in rmacountrylist)
                    {
                        selected = select == i?'SELECTED':'';
                        document.write('<option value='+i+' '+selected+'>'+rmacountrylist[i][1]+'</option>');
                    }
                }
                ";

                echo $js;
    }


    public function delete($id="")
    {
        if (($rma_vo = $this->rma_model->get_rma(array("id"=>$id))) === FALSE)
        {
            $_SESSION["NOTICE"] = "submit_error";
        }
        else {
            if (empty($rma_vo))
            {
                $_SESSION["NOTICE"] = "rma_not_found";
            }
            else
            {
                if (!$this->rma_model->inactive_rma($rma_vo))
                {
                    $_SESSION["NOTICE"] = "submit_error";
                }
            }
        }
        if (isset($_SESSION["LISTPAGE"]))
        {
            redirect($_SESSION["LISTPAGE"]);
        }
        else
        {
            redirect(current_url());
        }

    }

    public function _get_app_id(){
        return $this->app_id;
    }

    public function _get_lang_id(){
        return $this->lang_id;
    }
}

/* End of file rma.php */
/* Location: ./system/application/controllers/rma.php */