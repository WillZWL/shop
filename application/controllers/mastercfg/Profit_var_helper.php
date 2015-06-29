<?php
class Profit_var_helper extends MY_Controller
{
    private $app_id="MST0004";

    public function __construct()
    {
        parent::__construct(FALSE);
        $this->load->helper(array('url', 'notice'));
        $this->load->model('mastercfg/profit_var_model');
        $this->load->library('input');
    }

    public function js_platformlist()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->profit_var_model->get_selling_platform_list();
        foreach ($objlist as $obj)
        {
            $sid = str_replace("'", "\'", $obj->get_id());
            $name = str_replace("'", "\'", $obj->get_name());
            $slist[] = "'".$sid."':'".$name."'";
        }
        $js = "platformlist = {".implode(", ", $slist)."};";
        $js .= "
            function InitPlatform(obj)
            {
                for (var i in platformlist){
                    obj.options[obj.options.length]=new Option(platformlist[i], i);
                }
            }";
        echo $js;
    }

    public function _get_app_id(){
        return $this->app_id;
    }
}
