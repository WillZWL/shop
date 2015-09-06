<?php
namespace AtomV2\Service;

use AtomV2\Dao\CurrencyDao;

class CurrencyService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->setDao(new CurrencyDao);
        include_once(APPPATH . "helpers/price_helper.php");
    }

    public function getSignWIdKey()
    {
        $data = [];
        if ($objlist = $this->getDao()->getList([], ["limit" => -1])) {
            foreach ($objlist as $obj) {
                $data[$obj->getId()] = $obj->getSign();
            }
        }
        return $data;
    }

    public function getNameWIdKey()
    {
        $data = [];
        if ($objlist = $this->getDao()->getList([], ["limit" => -1])) {
            foreach ($objlist as $obj) {
                $data[$obj->getCurrencyId()] = $obj->getName();
            }
        }
        return $data;
    }

    public function getListWKey($where = [], $option = [])
    {
        $data = [];
        if ($objlist = $this->getDao()->getList($where, $option)) {
            foreach ($objlist as $obj) {
                $data[$obj->getId()] = $obj;
            }
        }
        return $data;
    }

    public function preLoadCurrencyList($currency_id = NULL)
    {
        $data = [];
        $where = [];

        if ($currency_id) {
            $where["id"] = $currency_id;
        }

        if ($objlist = $this->getDao()->getList($where, ["limit" => -1])) {
            foreach ($objlist as $obj) {
                $curr_id = $obj->getId();
                $data[$curr_id] = [
                    "sign" => $obj->getSign(),
                    "sign_pos" => $obj->getSignPos(),
                    "dec_place" => $obj->getDecPlace(),
                    "dec_point" => $obj->getDecPoint(),
                    "thousands_sep" => $obj->getThousandsSep()
                ];
            }
        }
        return $data;
    }

    public function roundUpOf($currency_id)
    {
        return $this->getDao()->getRoundUp($currency_id);
    }

    public function getPlatformCurrency($platform)
    {
        return $this->getDao()->getByPlatform($platform);
    }

    public function getSign($platform)
    {
        return $this->getDao()->getSign($platform);
    }

}


