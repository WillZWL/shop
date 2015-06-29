<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Db_cache_product_feed_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        include_once(APPPATH . "libraries/dao/Cache_product_feed_dao.php");
        $this->set_dao(new Cache_product_feed_dao());
    }

    public function save_xml_skype_feed($data = NULL)
    {
        $this->get_dao()->save_xml_skype_feed($data);
    }

    public function get_xml_skype_feed($data = NULL)
    {
        return $this->get_dao()->get_xml_skype_feed($data);
    }
}
/* End of file Db_cache_product_feed_service.php */
/* Location: ./system/application/libraries/service/Cache_product_feed_service.php */