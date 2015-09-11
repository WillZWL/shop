<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\CustomClassificationDao;
use ESG\Panther\Dao\CustomClassificationMappingDao;
use ESG\Panther\Dao\ProductCustomClassificationDao;

class CustomClassService extends BaseService
{

    function __construct()
    {
        parent::__construct();
        $this->setDao(new CustomClassificationDao);
        $this->setProductCustomClassificationDao(new ProductCustomClassificationDao);
        $this->setCustomClassificationMappingDao(new CustomClassificationMappingDao);
    }

    public function setProductCustomClassificationDao($dao)
    {
        $this->pcc_dao = $dao;
    }

    public function setCustomClassificationMappingDao($dao)
    {
        $this->ccm_dao = $dao;
    }

    public function getOption($where = [])
    {
        return $this->getDao()->getOption($where);
    }

    public function getProductCustomClass($where = [])
    {
        return $this->getProductCustomClassificationDao()->get($where);
    }

    public function getProductCustomClassificationDao()
    {
        return $this->pcc_dao;
    }

    public function updateProductCustomClass($obj)
    {
        return $this->getProductCustomClassificationDao()->update($obj);
    }

    public function includeProductCustomClass_vo()
    {
        return $this->getProductCustomClassificationDao()->get();
    }

    public function addProductCustomClass($obj)
    {
        return $this->getProductCustomClassificationDao()->insert($obj);
    }

    public function getProductCustomClassList($where = [], $option = [])
    {
        $data["pcclist"] = $this->getProductCustomClassificationDao()->getProductCustomClassList($where, $option);
        $option["limit"] = -1;
        $allRecord = $this->getProductCustomClassificationDao()->getProductCustomClassList($where, $option);
        $data["total"] = count((array) $allRecord);
        return $data;
    }

    public function getCustomClassMappingList($where = [], $option = [])
    {
        $data["pcclist"] = $this->getCustomClassificationMappingDao()->getList($where, $option);
        $data["total"] = $this->getCustomClassificationMappingDao()->getNumRows($where);
        return $data;
    }

    public function getCustomClassificationMappingDao()
    {
        return $this->ccm_dao;
    }

    public function getFullProductCustomClassBySku($where = [], $option = [])
    {
        return $this->getProductCustomClassificationDao()->getAllProductCustomClassList($where, $option);
    }

    public function getHsBySubcatAndCountry($where = [], $option = [])
    {
        return $this->getCustomClassificationMappingDao()->getHsBySubcatAndCountry($where, $option);
    }

    public function update($obj)
    {
        return $this->getDao()->update($obj);
    }

    public function insert($obj)
    {
        return $this->getDao()->insert($obj);
    }

    public function saveCustomClassMapping($ccmap, $i, $value, $name)
    {
        $ccObj = $this->getDao()->get(['country_id' => $ccmap[$i]['country'], 'code' => $ccmap[$i]['code']]);

        if ($ccObj) {
            $ccmObj = $this->getCustomClassificationMappingDao()->get(['sub_cat_id' => $value, 'country_id' => $ccmap[$i]['country']]);
            $ccmVo = $this->getCustomClassificationMappingDao()->get();
            $action = "";
            if (!$ccmObj) {
                $action = "insert";
                $ccmObj = clone($ccmVo);
                $ccmObj->setSubCatId($value);
                $ccmObj->setCountryId($ccmap[$i]['country']);
                $ccmObj->setCustomClassId($ccObj->getId());
            } else {
                $action = "update";
                $ccmObj->setCustomClassId($ccObj->getId());
            }

            if ($this->getCustomClassificationMappingDao()->$action($ccmObj) === FALSE) {
                $error_message = __LINE__ . "category.php " . $action . " Error. " . $this->getCustomClassificationMappingDao()->db->_error_message();
                $_SESSION["NOTICE"] = $error_message;
            }

        } else {
            $ccVo = $this->getDao()->get();
            $action = "insert";
            $ccObj = clone($ccVo);
            $ccObj->setCountryId($ccmap[$i]['country']);
            $ccObj->setCode($ccmap[$i]['code']);
            $ccObj->setDescription($name);
            $ccObj->setDutyPcent($ccmap[$i]['duty']);

            if ($this->getDao()->$action($ccObj) === FALSE) {
                $error_message = __LINE__ . "category.php " . $action . " Error. " . $this->getDao()->db->_error_message();
                $_SESSION["NOTICE"] = $error_message;
            }
            $action = "";
            $ccmObj = $this->getCustomClassificationMappingDao()->get(['sub_cat_id' => $value, 'country_id' => $ccmap[$i]['country']]);
            $ccmVo = $this->getCustomClassificationMappingDao()->get();
            if (!$ccmObj) {
                $action = "insert";
                $ccmObj = clone($ccmVo);
                $ccmObj->setSubCatId($value);
                $ccmObj->setCountryId($ccmap[$i]['country']);
                $ccmObj->setCustomClassId($ccObj->getId());
            } else {
                $action = "update";
                $ccmObj->setCustomClassId($ccObj->getId());
            }

            if ($this->getCustomClassificationMappingDao()->$action($ccmObj) === FALSE) {
                $error_message = __LINE__ . "category.php " . $action . " Error. " . $this->getCustomClassificationMappingDao()->db->_error_message();
                $_SESSION["NOTICE"] = $error_message;
            }
        }
    }
}


