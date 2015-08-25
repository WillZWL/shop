<?php

class Stock_valuation extends MY_Controller
{

    private $appId = "RPT0001";
    private $lang_id = "en";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('report/stock_valuation_model');
        $this->load->helper(array('url'));
        //$this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
    }

    public function query()
    {
        $data['lang'] = $this->_load_parent_lang();
        if ($this->input->post('is_query')) {
            $sku = $this->input->post('sku');
            $prod_name = $this->input->post('name');

            $data['output'] = $this->stock_valuation_model->get_csv($sku, $prod_name);
            $data['filename'] = 'report.csv';

            $this->load->view('output_csv.php', $data);
        }
    }

    private function _load_parent_lang()
    {
        $sub_app_id = $this->getAppId() . "00";
        include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->_get_lang_id() . ".php");

        return $lang;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function index()
    {
        $data['lang'] = $this->_load_parent_lang();
        //$this->model->get_csv($sku, $prod_name);

        $this->load->view('report/stock_valuation', $data);

//      if ($frame == "top")
//      {
//          $_SESSION["LISTPAGE"] = current_url()."?".$_SERVER['QUERY_STRING'];
//          $this->load->view('supply/purchaser/purchaser_index_v', $data);
//      }
//      else
//      {
//          $where = array();
//          $option = array();
//
//          if (($sku = $this->input->get("sku")) != "" || ($prod_name = $this->input->get("name")) != "")
//          {
//
//              if ($sku != "")
//              {
//                  $where["sku"] = $sku;
//              }
//
//              if ($prod_name != "")
//              {
//                  $where["name"] = $prod_name;
//              }
//
//              $sort = $this->input->get("sort");
//              $order = $this->input->get("order");
//
//              $limit = '20';
//
//              $pconfig['base_url'] = current_url()."?".$_SERVER['QUERY_STRING'];
//              $option["limit"] = $pconfig['per_page'] = $limit;
//
//              if ($option["limit"])
//              {
//                  $option["offset"] = $this->input->get("per_page");
//              }
//
//              if (empty($sort))
//                  $sort = "sku";
//
//              if (empty($order))
//                  $order = "asc";
//
//              $option["orderby"] = $sort." ".$order;
//
//              $data["objlist"] = $this->purchaser_model->get_product_list($where, $option);
//              $data["total"] = $this->purchaser_model->get_product_list_total($where);
//              $pconfig['total_rows'] = $data['total'];
//              $this->pagination_service->set_show_count_tag(TRUE);
//              $this->pagination_service->msg_br = TRUE;
//              $this->pagination_service->initialize($pconfig);
//
//              $data["notice"] = notice($lang);
//
//              $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
//              $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
//          }
//          $this->load->view('supply/purchaser/purchaser_left_v', $data);
//      }
    }
}


