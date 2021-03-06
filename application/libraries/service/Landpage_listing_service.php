<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

abstract class Landpage_listing_service extends Base_service
{
    const LIMIT = 10;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        include_once(APPPATH . "libraries/dao/Landpage_listing_dao.php");
        $this->set_dao(new Landpage_listing_dao());
        include_once(APPPATH . "libraries/service/Category_service.php");
        $this->category_service = new Category_service();
        include_once(APPPATH . "libraries/service/Selling_platform_service.php");
        $this->selling_platform_service = new Selling_platform_service();
    }

    public function get_limit()
    {
        return self::LIMIT;
    }

    public function __autoload()
    {
        $this->get_dao()->include_vo();
    }

    public function gen_listing()
    {
        $this->init_data(); // Initialize the data;
        $platform_list = $this->selling_platform_service->get_list(array(), array("orderby" => "id ASC", "limit" => -1));
        foreach ($platform_list AS $obj) {
            $platform = $obj->get_id();
            //$this->gen_listing_for_home($platform);
            for ($level = 1; $level <= 3; $level++) {
                // Get the top page result
                $cat_list = $this->category_service->get_list(array('level' => $level, 'status' => 1, 'id >' => 0));
                // Get all levels of category result
                foreach ($cat_list as $cat) {
                    $this->gen_listing_by_cat($cat->get_id(), $cat->get_level(), $platform);
                }
            }
        }
    }

    public function init_data()
    {
    }

    public function gen_listing_by_cat($cat_id = "", $cat_level = "", $platform)
    {
        if (empty($cat_id) || empty($cat_level) || empty($platform)) {
            return;
        }

        $filter_column = '';
        switch ($cat_level) {
            case 1:
                $filter_column = 'cat_id';
                break;
            case 2:
                $filter_column = 'sub_cat_id';
                break;
        }

        if ($prod_list = $this->_get_product_list($filter_column, $cat_id, $platform)) {
            $this->get_dao()->update_rank_w_prod_list($cat_id, $this->get_type(), $prod_list, $platform);
        }
    }

    abstract protected function _get_product_list($filter_column = '', $cat_id = '');

    // Please implement at the sub-class level.

    abstract public function get_type();

    public function gen_listing_for_home($platform)
    {
        $prod_list = $this->_get_product_list_for_home($platform);
        if ($prod_list) {
            $this->get_dao()->update_rank_w_prod_list(0, $this->get_type(), $prod_list, $platform);
        }
    }

    abstract protected function _get_product_list_for_home();
}


