<?php

class PaymentGateway extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function jsPmgwlist()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->sc['PaymentGateway']->getDao('PaymentGateway')->getList([], ["orderby" => "name ASC", "limit" => -1]);
        foreach ($objlist as $obj) {
            $sid = str_replace("'", "\'", $obj->getPaymentGatewayId());
            $name = str_replace("'", "\'", $obj->getName());
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

    public function getAppId()
    {
        return $this->appId;
    }
}


