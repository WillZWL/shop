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

    public function get_platform_by_lang($where = [], $option = [])
    {
        return $this->getDao()->get_platform_by_lang($where, $option);
    }

    public function getPlatformListWithCountryId($country_id = "")
    {
        return $this->getDao()->getPlatformListWithCountryId($country_id);
    }

    public function getPlatformListWithLangId($lang_id = "")
    {
        return $this->getDao()->getPlatformListWithLangId($lang_id);
    }

    public function getPlatformTypeList($where = [], $option = [])
    {
        return $this->getDao()->getPlatformTypeList($where, $option);
    }

    public function getSellingPlatformWithLangId($where = [], $option = [])
    {
        return $this->getDao()->getSellingPlatformWithLangId($where, $option);
    }

    public function getPlatformListWithAllowSellCountry($type = "")
    {
        return $this->getDao()->getPlatformListWithAllowSellCountry($type);
    }
}
