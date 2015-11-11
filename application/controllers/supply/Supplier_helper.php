<?php

class Supplier_helper extends MY_Controller
{
    private $appId = "SUP0001";

    public function __construct()
    {
        parent::__construct(FALSE);
        $this->load->model('supply/supplier_model');
        $this->load->helper('url');
        $this->load->helper('notice');
        $this->load->helper('object');
        $this->load->library('service/pagination_service');
    }

    public function js_supplist($active_only = 0)
    {
        $where = array();
        if ($active_only == 1)
            $where["status"] = 1;
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->supplier_model->get_list("dao", $where, array("orderby" => "name ASC", "limit" => -1));
        foreach ($objlist as $obj) {
            $sid = $obj->get_id();
            $name = str_replace("'", "\'", $obj->get_name());
            $sourcing_region = str_replace("'", "\'", $obj->get_sourcing_reg());
            $currency_id = str_replace("'", "\'", $obj->get_currency_id());
            $creditor = str_replace("'", "\'", $obj->get_creditor());
            $slist[] = "'" . $sid . "':['" . $name . "', '" . $currency_id . "','" . $sourcing_region . "','" . $creditor . "']";
        }
        $js = "supplist = {" . implode(", ", $slist) . "};";
        $js .= "
            function ChangeSupp(val, obj, span_obj)
            {
                if (obj)
                {
                    obj.value = val == '' ? '' :supplist[val][1];
                }
                if (span_obj)
                {
                    span_obj.innerHTML = val == '' ? '' :supplist[val][1] + ' ';
                }
            }

            function InitSupp(obj)
            {
                for (var i in supplist){
                    obj.options[obj.options.length]=new Option(supplist[i][0], i);
                }
            }";
        echo $js;
    }

    public function js_currency()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->supplier_model->get_service_list("currency", array(), array("orderby" => "id ASC"));
        foreach ($objlist as $obj) {
            $sid = $obj->get_id();
            $name = $obj->get_name();
            $slist[] = "'" . $sid . "':'" . $name . "'";
        }
        $js = "currencylist = {" . implode(", ", $slist) . "};";
        $js .= "
            function ChangeCurr(val, obj)
            {
                obj.value = val == '' ? '' :val;
            }

            function InitCurr(obj)
            {
                for (var i in currencylist){
                    obj.options[obj.options.length]=new Option('('+i+') ' + currencylist[i], i);
                }
            }";
        echo $js;
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
