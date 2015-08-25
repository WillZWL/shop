<?php

class Selling_platform extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(FALSE);
        $this->load->model('mastercfg/selling_platform_model');
    }

    public function get_js()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $list = $this->selling_platform_model->get_list(array(), array("order by" => "id asc", "limit" => -1));
        $js_array .= 'platform = {';
        $size = 0;
        foreach ($list as $key => $obj) {
            $size++;
            $js_array .= "'" . ($key + 1) . "':['" . $obj->get_id() . "','" . htmlentities(addslashes($obj->get_name())) . "'],";
        }
        $js_array = ereg_replace(",$", "};", $js_array);

        $js_array .= "\n\n" . 'platform_count = ' . $size . ';' . "\n\n";
        echo $js_array;
    }

    public function _get_lang_id()
    {
        return $this->lang_id;
    }

    public function getAppId()
    {
        return $this->appId;
    }
}

?>