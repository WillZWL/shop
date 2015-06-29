<?php

class Exchange_rate_helper extends MY_Controller
{
    private $app_id = "MST0003";

    public function __construct()
    {
        parent::__construct(FALSE);
        $this->load->model('mastercfg/exchange_rate_model');
        $this->load->helper(array('url', 'notice'));
        $this->load->library('input');
        $this->title = 'Region Information';
        $this->load->library('service/log_service');
        $this->load->library('service/authorization_service');
        $this->load->library('service/context_config_service');
        $this->currency_list = $this->exchange_rate_model->get_active_currency_list(array(), array("orderby" => "name ASC"));
    }

    public function js_xratelist($to_currency = "")
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $xratelist = $this->exchange_rate_model->exchange_rate_service->exchange_rate_dao->get_list($to_currency == "" ? array() : array("to_currency_id" => $to_currency));
        foreach ($xratelist as $obj) {
            $fid = str_replace("'", "\'", $obj->get_from_currency_id());
            $tid = str_replace("'", "\'", $obj->get_to_currency_id());
            $rate = str_replace("'", "\'", $obj->get_rate());
            $jsxratelist[$fid][] = "'" . $tid . "':" . $rate;
        }
        foreach ($jsxratelist as $jsfid => $jsrate) {
            $jsxrate[] = "'" . $jsfid . "': {" . (implode(", ", $jsrate)) . "}";
        }
        $js = "xratelist = {" . implode(", ", $jsxrate) . "};";
        $js .= "
            function exc(from, to, val, obj)
            {
                var new_val = val * xratelist[from][to];
                if (obj)
                {
                    obj.value = new_val.toFixed(4);
                }
                else
                {
                    return new_val.toFixed(4);
                }
            }";
        echo $js;
    }

    public function _get_app_id()
    {
        return $this->app_id;
    }
}