<?php
namespace ESG\Panther\Service;

class CustomClassService extends BaseService
{

    function __construct()
    {
        parent::__construct();
    }

    public function getOption($where = [])
    {
        return $this->getDao('CustomClassification')->getOption($where);
    }

    public function getProductCustomClass($where = [])
    {
        return $this->getDao('ProductCustomClassification')->get($where);
    }

    public function updateProductCustomClass($obj)
    {
        return $this->getDao('ProductCustomClassification')->update($obj);
    }

    public function includeProductCustomClass_vo()
    {
        return $this->getDao('ProductCustomClassification')->get();
    }

    public function addProductCustomClass($obj)
    {
        return $this->getDao('ProductCustomClassification')->insert($obj);
    }

    public function getProductCustomClassList($where = [], $option = [])
    {
        $data["pcclist"] = $this->getDao('ProductCustomClassification')->getProductCustomClassList($where, $option);
        $option["limit"] = -1;
        $allRecord = $this->getDao('ProductCustomClassification')->getProductCustomClassList($where, $option);
        $data["total"] = count((array) $allRecord);
        return $data;
    }

    public function getCustomClassMappingList($where = [], $option = [])
    {
        $data["pcclist"] = $this->getDao('CustomClassificationMapping')->getList($where, $option);
        $data["total"] = $this->getDao('CustomClassificationMapping')->getNumRows($where);
        return $data;
    }

    public function getFullProductCustomClassBySku($where = [], $option = [])
    {
        return $this->getDao('ProductCustomClassification')->getAllProductCustomClassList($where, $option);
    }

    public function getHsBySubcatAndCountry($where = [], $option = [])
    {
        return $this->getDao('CustomClassificationMapping')->getHsBySubcatAndCountry($where, $option);
    }

    public function update($obj)
    {
        return $this->getDao('CustomClassification')->update($obj);
    }

    public function insert($obj)
    {
        return $this->getDao('CustomClassification')->insert($obj);
    }

    public function saveCustomClassMapping($ccmap, $i, $value, $name)
    {
        $ccObj = $this->getDao('CustomClassification')->get(['country_id' => $ccmap[$i]['country'], 'code' => $ccmap[$i]['code']]);

        if ($ccObj) {
            $ccmObj = $this->getDao('CustomClassificationMapping')->get(['sub_cat_id' => $value, 'country_id' => $ccmap[$i]['country']]);
            $ccmVo = $this->getDao('CustomClassificationMapping')->get();
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

            if ($this->getDao('CustomClassificationMapping')->$action($ccmObj) === FALSE) {
                $error_message = __LINE__ . "category.php " . $action . " Error. " . $this->getDao('CustomClassificationMapping')->db->_error_message();
                $_SESSION["NOTICE"] = $error_message;
            }

        } else {
            $ccVo = $this->getDao('CustomClassification')->get();
            $action = "insert";
            $ccObj = clone($ccVo);
            $ccObj->setCountryId($ccmap[$i]['country']);
            $ccObj->setCode($ccmap[$i]['code']);
            $ccObj->setDescription($name);
            $ccObj->setDutyPcent($ccmap[$i]['duty']);

            if ($this->getDao('CustomClassification')->$action($ccObj) === FALSE) {
                $error_message = __LINE__ . "category.php " . $action . " Error. " . $this->getDao('CustomClassification')->db->_error_message();
                $_SESSION["NOTICE"] = $error_message;
            }
            $action = "";
            $ccmObj = $this->getDao('CustomClassificationMapping')->get(['sub_cat_id' => $value, 'country_id' => $ccmap[$i]['country']]);
            $ccmVo = $this->getDao('CustomClassificationMapping')->get();
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

            if ($this->getDao('CustomClassificationMapping')->$action($ccmObj) === FALSE) {
                $error_message = __LINE__ . "category.php " . $action . " Error. " . $this->getDao('CustomClassificationMapping')->db->_error_message();
                $_SESSION["NOTICE"] = $error_message;
            }
        }
    }
}


