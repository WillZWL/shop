<?php
class Freight_helper extends MY_Controller
{
    private $app_id="MST0009";

    public function __construct()
    {
        parent::__construct(FALSE);
        $this->load->model('mastercfg/freight_model');
        $this->load->helper('url');
        $this->load->helper('notice');
        $this->load->helper('object');
        $this->load->library('service/pagination_service');
        $this->load->library('service/context_config_service');
    }

    public function js_freight_cat()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->freight_model->get_freight_cat_list(array(), array("orderby"=>"name ASC", "limit"=>-1));
        foreach ($objlist as $obj)
        {
            $sid = str_replace("'", "\'", $obj->get_id());
            $name = str_replace("'", "\'", $obj->get_name());
            $weight = str_replace("'", "\'", $obj->get_weight());
            $slist[] = "'".$sid."':['".$name."', '".$weight."']";
        }
        $js = "fcatlist = {".implode(", ", $slist)."};";
        $js .= "
            function ChangeFCat(val, obj)
            {
                obj.value = val == '' ? '' :fcatlist[val][1];
            }

            function InitFCat(obj)
            {
                for (var i in fcatlist){
                    obj.options[obj.options.length]=new Option(fcatlist[i][0], i);
                }
            }";
        echo $js;
    }

    public function _get_app_id(){
        return $this->app_id;
    }
}