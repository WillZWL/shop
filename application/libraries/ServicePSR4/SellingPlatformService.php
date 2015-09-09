<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\SellingPlatformDao;

class SellingPlatformService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new SellingPlatformDao);
    }

    public function get_platform_by_lang($where = array(), $option = array())
    {
        return $this->getDao()->get_platform_by_lang($where, $option);
    }

    public function get_platform_list_w_country_id($country_id = "")
    {
        return $this->getDao()->get_platform_list_w_country_id($country_id);
    }

    public function get_platform_list_w_lang_id($lang_id = "")
    {
        return $this->getDao()->get_platform_list_w_lang_id($lang_id);
    }

    public function get_platform_type_list($where = array(), $option = array())
    {
        return $this->getDao()->get_platform_type_list($where, $option);
    }

    public function get_selling_platform_w_lang_id($where = array(), $option = array())
    {
        return $this->getDao()->get_selling_platform_w_lang_id($where, $option);
    }

    public function get_platform_list_w_allow_sell_country($type = "")
    {
        return $this->getDao()->get_platform_list_w_allow_sell_country($type);
    }

}

?>
