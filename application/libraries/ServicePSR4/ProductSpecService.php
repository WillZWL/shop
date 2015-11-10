<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ProductSpecDao;
use ESG\Panther\Dao\ProductSpecDetailsDao;
use ESG\Panther\Dao\ProductSpecGroupDao;
use ESG\Panther\Dao\CategoryProductSpecDao;
use ESG\Panther\Dao\UnitDao;
use ESG\Panther\Service\LanguageService;

class ProductSpecService extends BaseService
{

    private $psg_dao;
    private $psd_dao;
    private $unit_dao;

    public function __construct()
    {
        parent::__construct();
        $this->setDao(new ProductSpecDao);
        $this->setProductSpecGroupDao(new ProductSpecGroupDao);
        $this->setProductSpecDetailsDao(new ProductSpecDetailsDao);
        $this->setCategoryProductSpecDao(new CategoryProductSpecDao);
        $this->setUnitDao(new UnitDao);
        $this->languageService = new LanguageService;
    }

    public function setCategoryProductSpecDao($dao)
    {
        $this->cps_dao = $dao;
    }

    public function getProdSpecGroupList($where, $option)
    {
        return $this->getProductSpecGroupDao()->getList($where, $option);
    }

    public function getProductSpecGroupDao()
    {
        return $this->psg_dao;
    }

    public function setProductSpecGroupDao($dao)
    {
        $this->psg_dao = $dao;
    }

    public function getProdSpecGroup($where)
    {
        return $this->getProductSpecGroupDao()->get($where);
    }

    public function getProdSpec($where)
    {
        return $this->getDao()->get($where);
    }

    public function getProdSpecList($where, $option)
    {
        return $this->getDao()->getList($where, $option);
    }

    public function getNoOfRowPsl($where)
    {
        return $this->getDao()->getNumRows($where);
    }

    public function addProdSpec($prod_spec_obj)
    {
        return $this->getDao()->insert($prod_spec_obj);
    }

    public function updateProdSpec($prod_spec_obj)
    {
        return $this->getDao()->update($prod_spec_obj);
    }

    public function getProductSpecWithSku($sku, $lang_id)
    {
        $data = [];
        if ($ps_list = $this->getProductSpecDetailsDao()->getProductSpecWithSku($sku, $lang_id)) {
            foreach ($ps_list as $obj) {
                $data[$obj->getPsgFuncId()][$obj->getPsFuncId()] = $obj;
            }
        }
        return $data;
    }

    public function getProductSpecDetailsDao()
    {
        return $this->psd_dao;
    }

    public function setProductSpecDetailsDao($dao)
    {
        $this->psd_dao = $dao;
    }

    public function getCatProdSpecList($where, $option)
    {
        return $this->getCategoryProductSpecDao()->getList($where, $option);
    }

    public function getCategoryProductSpecDao()
    {
        return $this->cps_dao;
    }

    public function getFullCpsList($cat_id)
    {
        return $this->getCategoryProductSpecDao()->getFullCpsList($cat_id);
    }

    public function getCps($where = [])
    {
        return $this->getCategoryProductSpecDao()->get($where);
    }

    public function insertCps($obj)
    {
        return $this->getCategoryProductSpecDao()->insert($obj);
    }

    public function updateCps($obj)
    {
        return $this->getCategoryProductSpecDao()->update($obj);
    }

    public function getFullPsdWithLang($sub_cat_id, $sku, $lang_id)
    {
        return $this->getProductSpecDetailsDao()->getFullPsdWithLang($sub_cat_id, $sku, $lang_id);
    }

    public function updateResponsePsdList($sku, $sub_cat_id, $response_psd_list)
    {
        if ($response_psd_list) {
            foreach ($response_psd_list AS $lang_id => $psd_list) {
                foreach ($psd_list AS $psd_id => $psd_array) {
                    $old_text = $old_start_value = $old_end_value = $psd_action = '';
                    $psd_obj = $this->getProductSpecDetailsDao()->get(["ps_id" => $psd_id, "cat_id" => $sub_cat_id, "prod_sku" => $sku, "lang_id" => $lang_id]);
                    if ($psd_obj) {
                        $old_text = $psd_obj->getText();
                        $old_start_value = $psd_obj->getStartValue();
                        $old_end_value = $psd_obj->getEndValue();
                    } else {
                        $psd_obj = $this->getProductSpecDetailsDao()->get();
                        $psd_obj->setPsId($psd_id);
                        $psd_obj->setCatId($sub_cat_id);
                        $psd_obj->setProdSku($sku);
                        $psd_obj->setLangId($lang_id);
                    }
                    foreach ($psd_array AS $unit_id => $psd_value_array) {
                        $unit_obj = $this->getUnitDao()->get(['id' => $unit_id]);
                        $unit_standardize_value = $unit_obj->getStandardizeValue();
                        foreach ($psd_value_array AS $psd_key => $psd_value) {
                            switch ($psd_key) {
                                case 'text':
                                    if ($old_text) {
                                        if ($psd_value) {
                                            if ($old_text != $psd_value) {
                                                $psd_obj->setText($psd_value);
                                                $psd_action = 'update';
                                            }
                                        } else {
                                            $psd_action = 'delete';
                                        }
                                    } else {
                                        if ($psd_value) {
                                            $psd_obj->setText($psd_value);
                                            $psd_obj->setCpsUnitId($unit_id);
                                            $psd_action = 'insert';
                                        }
                                    }
                                    break;
                                case 'start_value':
                                    if ($old_start_value) {
                                        if ($psd_value) {
                                            if ($old_start_value != $psd_value) {
                                                $psd_obj->setStartValue($psd_value);
                                                $psd_obj->setStartStandardizeValue($unit_standardize_value * $psd_value);
                                                $psd_action = 'update';
                                            }
                                        } else {
                                            $psd_action = 'delete';
                                        }
                                    } else {
                                        if ($psd_value) {
                                            $psd_obj->setStartValue($psd_value);
                                            $psd_obj->setStartStandardizeValue($unit_standardize_value * $psd_value);
                                            $psd_obj->setCpsUnitId($unit_id);
                                            $psd_action = 'insert';
                                        }
                                    }
                                    break;
                                case 'end_value':
                                    if ($old_end_value) {
                                        if ($psd_value) {
                                            if ($old_end_value != $psd_value) {
                                                $psd_obj->setEndValue($psd_value);
                                                $psd_obj->setEndStandardizeValue($unit_standardize_value * $psd_value);
                                                $psd_action = 'update';
                                            }
                                        } else {
                                            $psd_action = 'delete';
                                        }
                                    } else {
                                        if ($psd_value) {
                                            $psd_obj->setEndValue($psd_value);
                                            $psd_obj->setEndStandardizeValue($unit_standardize_value * $psd_value);
                                            $psd_obj->setCpsUnitId($unit_id);
                                            $psd_action = 'insert';
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                    if ($psd_action) {
                        if (!$this->getProductSpecDetailsDao()->$psd_action($psd_obj)) {
                            $_SESSION["NOTICE"] = $this->db->_error_message();
                            break;
                        }
                    }
                }
            }
        }
        return;
    }

    public function getUnitDao()
    {
        return $this->unit_dao;
    }

    public function setUnitDao($dao)
    {
        $this->unit_dao = $dao;
    }

    public function populateToAllLang($ps_id, $sub_cat_id, $sku, $lang_id)
    {
        $lang_list = $this->languageService->getList(["status" => 1], []);
        if ($obj = $this->getProductSpecDetailsDao()->get(["ps_id" => $ps_id, "cat_id" => $sub_cat_id, "prod_sku" => $sku, "lang_id" => $lang_id])) {
            foreach ($lang_list AS $lang_obj) {
                if ($lang_obj->getLangId() != $lang_id) {
                    if ($target_obj = $this->getProductSpecDetailsDao()->get(["ps_id" => $ps_id, "cat_id" => $sub_cat_id, "prod_sku" => $sku, "lang_id" => $lang_obj->getLangId()])) {
                        $action = "update";
                    } else {
                        $target_obj = $this->getProductSpecDetailsDao()->get();
                        $action = "insert";
                    }
                    set_value($target_obj, $obj);
                    $target_obj->setLangId($lang_obj->getLangId());
                    $this->getProductSpecDetailsDao()->$action($target_obj);
                }
            }
        } else {
            foreach ($lang_list AS $lang_obj) {
                if ($lang_obj->getLangId() != $lang_id) {
                    if ($target_obj = $this->getProductSpecDetailsDao()->get(["ps_id" => $ps_id, "cat_id" => $sub_cat_id, "prod_sku" => $sku, "lang_id" => $lang_obj->getLangId()])) {
                        $action = "delete";
                        $this->getProductSpecDetailsDao()->$action($target_obj);
                    }
                }
            }
        }
    }

    public function saveProdSpec($cpsObjList, $cat_id)
    {
        foreach ($cpsObjList AS $ps_id => $cps_array) {
            $cpsObj = $this->getCps(['cat_id' => $cat_id, 'ps_id' => $ps_id]);
            if ($cpsObj) {
                $cps_action = "updateCps";
            } else {
                $cps_action = "insertCps";
                $cpsObj = $this->getCps();
                $cpsObj->setPsId($ps_id);
                $cpsObj->setCatId($cat_id);
                $cpsObj->setUnitId($cps_array['unit_id']);
            }
            $cpsObj->setPriority($cps_array['priority']);
            $cpsObj->setStatus($cps_array['status']);
            if ($this->$cps_action($cpsObj) === FALSE) {
                $_SESSION["NOTICE"] = "Error: " . __LINE__ . ": " . $this->db->_error_message();
            }
        }
    }
}


