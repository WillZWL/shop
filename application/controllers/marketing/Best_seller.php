<?php

class Best_seller extends MY_Controller
{

    private $app_id = "MKT0014";
    private $lang_id = "en";

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url','directory','notice'));
        $this->load->model('marketing/best_seller_model');
        $this->load->library('service/pagination_service');
    }

    public function main($catid="", $level="", $platform="")
    {
        if($platform)
        {
            $data["display"] = 1;
        }
        else
        {
            $data["display"] = 0;
        }
        $sub_app_id = $this->_get_app_id()."01";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        $data["catid"] = $catid;
        $data["level"] = $level;
        $data["platform"] = $platform;
        $data['platform_id_list'] = $this->best_seller_model->get_platform_id_list(array("type"=>"WEBSITE"), array("orderby"=>"id ASC"));
        $this->load->view('marketing/best_seller/bs_index', $data);
    }

    public function index()
    {
        $where = array();
        $option = array();

        $_SESSION["LISTPAGE"] = base_url()."marketing/best_seller/?".$_SERVER['QUERY_STRING'];

        $where["name"] = $this->input->get("name");
        $where["description"] = $this->input->get("description");
        $where["level"] = $this->input->get("level");
        $where["status"] = $this->input->get("status");
        $where["manual"] = $this->input->get("manual");
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
            $sort = "name";

        if (empty($order))
            $order = "asc";

        $option["orderby"] = $sort." ".$order;


        $data = $this->best_seller_model->get_cat_list_index($where,$option);

        if($data["list"] === FALSE)
        {
            $_SESSION["NOTICE"] = "Error line:".__LINE__." list_error";
        }
        else
        {
            unset($_SESSION["NOTICE"]);
        }
        $pconfig['total_rows'] = $data['total'];
        $this->pagination_service->initialize($pconfig);

        $data["notice"] = notice($lang);

        $data["refresh"] = $this->input->get("refresh");
        $data["added"] = $this->input->get("added");
        $data["updated"] = $this->input->get("updated");

        $data["showall"] = $this->input->get("showall");
        $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
        $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
        $data["searchdisplay"] = ($where["name"]=="" && $where["description"]=="" && $where["level"]=="" && $where["status"]=="" && $where["manual"])?'style="display:none"':"";

        $sub_app_id = $this->_get_app_id()."04";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;
        $data["notice"] = notice($lang);
        $this->load->view('marketing/best_seller/bs_list_index', $data);
    }

    public function view()
    {
        $cat = $this->input->get('cat_id');
        $scat = $this->input->get('sub_cat_id');
        $sscat = $this->input->get('sub_sub_cat_id');
        if($cat == "")
        {
            $this->index();
            exit;
        }

        $key = $cat;
        $level = 1;
        if($scat != "")
        {
            if($sscat != "")
            {
                $level = 3;
                $key = $sscat;
            }
            else
            {
                $level = 2;
                $key = $scat;
            }
        }
        $data["level"] =  $level;
        $data["catid"] = $key;
        $this->load->view('marketing/best_seller/bs_view',$data);
    }

    public function view_left($catid="", $level="", $platform="")
    {
        if($catid == "")
        {
            $catid = $this->input->get('catid');
        }
        if($level == "")
        {
            $level = $this->input->get('level');
        }
        if($platform == "")
        {
            $platform = $this->input->get('platform');
        }
        $where = array();
        $option = array();
        $sub_app_id = $this->_get_app_id()."02";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        if (($sku = $this->input->get("sku")) != "" || ($prod_name = $this->input->get("name")) != "")
        {

            $data["search"] = 1;
            if ($sku != "")
            {
                $where["sku"] = $sku;
            }

            if ($prod_name != "")
            {
                $where["name"] = $prod_name;
            }

            $where["listing_status"] = "1";
            $where["sourcing_status"] = "A";
            $where["website_quantity"] = "1";
            $where["weblist"] = "1";
            switch($level)
            {
                case "1":
                if($this->input->get('cat') != 0)
                {
                    $where["cat_id"] = $catid;
                }
                break;

                case "2":
                $where["sub_cat_id"] = $catid;
                break;

                case "3":
                $where["sub_sub_cat_id"] = $catid;
                break;

                default:
                break;
            }

            $sort = $this->input->get("sort");
            $order = $this->input->get("order");

            $limit = '20';

            $pconfig['base_url'] = current_url()."?".$_SERVER['QUERY_STRING'];
            $option["limit"] = $pconfig['per_page'] = $limit;

            if ($option["limit"])
            {
                $option["offset"] = $this->input->get("per_page");
            }

            if (empty($sort))
                $sort = "sku";

            if (empty($order))
                $order = "ASC";

            $option["orderby"] = $sort." ".$order;
            $option["pricegtzero"] = "1";
            $option["selling_platform"] = $platform;

            $data["objlist"] = $this->best_seller_model->get_product_list($where, $option);
            $data["total"] = $this->best_seller_model->get_product_list_total($where, $option);

            $pconfig['total_rows'] = $data['total'];
            $this->pagination_service->set_show_count_tag(TRUE);
            $this->pagination_service->msg_br = TRUE;
            $this->pagination_service->initialize($pconfig);

            $data["notice"] = notice($lang);

            $data["sortimg"][$sort] = "<img src='".base_url()."images/".$order.".gif'>";
            $data["xsort"][$sort] = $order=="asc"?"desc":"asc";
        }
        $data["catid"] = $catid;
        $data["level"] = $level;
        $data["platform"] = $platform;
        $this->load->view('marketing/best_seller/bs_view_left',$data);
    }

    public function view_right($catid="", $level="", $platform="")
    {
        $limit = $data["limit"] = $this->best_seller_model->get_list_limit();

        if($catid == "")
        {
            $this->index();
            exit;
        }
        if($this->input->post('posted'))
        {
            $err = 0;

            $input = $this->input->post('cat');
            $this->best_seller_model->trans_start();

            $ret = $this->best_seller_model->delete_bs(array("catid"=>$catid,"type"=>"BS","mode"=>'M', "platform_id"=>$platform));
            if($ret === FALSE)
            {
                $_SESSION["NOTICE"] = "Error line:".__LINE__." update_failed";
            }

            foreach($input as $key=>$v)
            {
                if($v != "")
                {
                    $action = "insert";
                    $obj = $this->best_seller_model->get_vo();
                    $obj->set_catid($catid);
                    $obj->set_type('BS');
                    $obj->set_rank($key);
                    $obj->set_selection($v);
                    $obj->set_mode('M');

                    $obj->set_platform_id($platform);
                    $ret = $this->best_seller_model->insert($obj);
                    if($ret === FALSE)
                    {
                        $_SESSION["NOTICE"] = "Error line:".__LINE__." update_failed";
                        $err++;
                        break;
                    }
                    else
                    {
                            unset($_SESSION["NOTICE"]);
                    }
                }
            }
            if(!$err)
            {
                $this->best_seller_model->trans_complete();
            }
        }
        $sub_app_id = $this->_get_app_id()."03";
        include_once(APPPATH."language/".$sub_app_id."_".$this->_get_lang_id().".php");
        $data["lang"] = $lang;

        $count = $this->best_seller_model->get_count($catid,'M', $platform);
        $cnt = 0;

        if($count === FALSE)
        {
            $this->index();
            exit;
        }

        if(!$count)
        {
            for($i = 1; $i <= $limit; $i++)
            {
                //automatic
                $obj = $this->best_seller_model->get_best_seller($catid, $i, $platform);
                //echo $this->db->last_query()."  ".$this->db->_error_message();

                $value[$i] = $lang["not_assigned"];
                $name[$i] = "";
            }
        }
        else
        {
            $list = $this->best_seller_model->get_list_w_name($catid,'M','BS',$platform);
            //var_dump($this->db->last_query());

            for($i = 1; $i <= $limit ; $i++)
            {
                $obj = $this->best_seller_model->get_best_seller($catid,$i, $platform);

                if(isset($list[$i]))
                {
                    $value[$i] = $list[$i]->get_selection();
                    $name[$i] = $list[$i]->get_name();
                    $cnt++;
                }
                else
                {
                    $value[$i] = $lang["not_assigned"];
                    $name[$i] = "";
                }
            }
        }

        $count = $this->best_seller_model->get_count($catid,'A', $platform);
        $acnt = 0;

        if($count === FALSE)
        {
            $this->index();
            exit;
        }

        if(!$count)
        {
            for($i = 1; $i <= $limit; $i++)
            {
                $obj = $this->best_seller_model->get_best_seller($catid,$i, $platform);
                //echo $this->db->last_query()."  ".$this->db->_error_message();
                $avalue[$i] = $lang["not_assigned"];
                $aname[$i] = "";
            }
        }
        else
        {
            $list = $this->best_seller_model->get_list_w_name($catid,'A','BS',$platform);
            //echo $this->db->last_query();
            for($i = 1; $i <=$limit ; $i++)
            {
                $obj = $this->best_seller_model->get_best_seller($catid,$i, $platform);
                //echo $this->db->last_query();
                //echo "   ".$this->best_seller_model->_error_message();

                if(isset($list[$i]))
                {
                    $avalue[$i] = $list[$i]->get_selection();
                    $aname[$i] = $list[$i]->get_name();
                    $acnt++;
                }
                else
                {
                    $avalue[$i] = $lang["not_assigned"];
                    $aname[$i] = "";
                }
            }
        }

        $data["aname"] = $aname;
        $data["avalue"] = $avalue;
        $data["name"] = $name;
        $data["value"] = $value;

        $oname = $name;
        $ovalue = $value;

        if($cnt < $limit)
        {
            foreach($avalue as $key=>$val)
            {
                if($cnt < $limit && !in_array($val,$ovalue))
                {
                    $ovalue[++$cnt] = $val;
                    $oname[$cnt] = $aname[$key];
                }
            }
        }

        $data["oname"] = $oname;
        $data["ovalue"] = $ovalue;

        $data["catid"] = $catid;
        $data["level"] = $level;
        $data["platform"] = $platform;
        $data["notice"] = notice($lang);
        $this->load->view('marketing/best_seller/bs_view_right',$data);
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