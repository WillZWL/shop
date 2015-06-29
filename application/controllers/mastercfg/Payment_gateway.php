<?php

class Payment_gateway extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(FALSE);
        $this->load->model('mastercfg/payment_gateway_model');
    }

    public function js_pmgwlist()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->payment_gateway_model->get_list(array(), array("orderby" => "name ASC", "limit" => -1));
        foreach ($objlist as $obj) {
            $sid = str_replace("'", "\'", $obj->get_id());
            $name = str_replace("'", "\'", $obj->get_name());
            $slist[] = "'" . $sid . "':'" . $name . "'";
        }
        $js = "pmgwlist = {" . implode(", ", $slist) . "};";
        $js .= "
            function InitPMGW(obj)
            {
                for (var i in pmgwlist){
                    obj.options[obj.options.length]=new Option(pmgwlist[i], i);
                }
            }";
        echo $js;
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

/* End of file payment_gateway.php */
/* Location: ./system/application/controllers/mastercfg/payment_gateway.php */