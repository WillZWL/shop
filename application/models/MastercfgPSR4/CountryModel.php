<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\CountryService;

class CountryModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->countryService = new CountryService;
    }

    public function getSellToList($lang_id = 'en')
    {
        return $this->countryService->getDao()->get_all_available_country_w_correct_lang($lang_id);
    }

    // public function get($dao, $where = "")
    // {
    //     $method = "get" . $dao . "Dao";
    //     if (is_array($where)) {
    //         return $this->countryService->$method()->get($where);
    //     } else {
    //         return $this->countryService->$method()->get();
    //     }
    // }

    public function getList($dao, $where = [], $option = [])
    {
        $method = "get" . $dao . "Dao";
        if ($option["num_rows"] == 1) {
            return $this->countryService->$method()->getNumRows($where);
        } else {
            return $this->countryService->$method()->getList($where, $option);
        }
    }

    public function getListWRmaFc($where = [], $option = [])
    {
        return $this->countryService->getDao('Country')->getListWRmaFc($where, $option);
    }

    // public function update($dao, $obj)
    // {
    //     $method = "get" . $dao . "Dao";
    //     return $this->countryService->$method()->update($obj);
    // }

    public function insert($dao, $obj)
    {
        $method = "get" . $dao . "Dao";
        return $this->countryService->$method()->insert($obj);
    }

    public function getCountryNameInLang($lang_id = "", $front_end = "", $platform_restricted = "")
    {
        $where["l.lang_id"] = $lang_id;

        if ($front_end) {
            $where["c.status"] = 1;
            $where["c.allow_sell"] = 1;
        }

        switch ($platform_restricted) {
            case "WSUS":
            case "WEBUS":
                $where["c.id"] = PLATFORMCOUNTRYID;
                break;
        }

        $option["orderby"] = "ce.name, c.name";
        $option["limit"] = -1;

        return $this->countryService->getCountryExtDao()->getCountryNameInLang($where, $option);
    }

    public function getRmaFcList($lang = "en")
    {
        return $this->countryService->getDao()->getRmaCountryList($lang);
    }

}
