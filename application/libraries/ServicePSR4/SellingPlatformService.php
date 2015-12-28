<?php
namespace ESG\Panther\Service;

class SellingPlatformService extends BaseService
{
    public function getPlatformByLang($where = [], $option = [])
    {
        return $this->getDao('SellingPlatform')->getPlatformByLang($where, $option);
    }

    public function getPlatformListWithCountryId($country_id = "")
    {
        return $this->getDao('SellingPlatform')->getPlatformListWithCountryId($country_id);
    }

    public function getPlatformListWithLangId($lang_id = "")
    {
        return $this->getDao('SellingPlatform')->getPlatformListWithLangId($lang_id);
    }

    public function getPlatformTypeList($where = [], $option = [])
    {
        return $this->getDao('SellingPlatform')->getPlatformTypeList($where, $option);
    }

    public function getSellingPlatformWithLangId($where = [], $option = [])
    {
        return $this->getDao('SellingPlatform')->getSellingPlatformWithLangId($where, $option);
    }

    public function getPlatformListWithAllowSellCountry($type = "")
    {
        return $this->getDao('SellingPlatform')->getPlatformListWithAllowSellCountry($type);
    }
}
