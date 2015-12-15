<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ExtCategoryMappingDao;
use ESG\Panther\Dao\CategoryMappingDao;
use ESG\Panther\Service\CategoryMappingService;

class ExtCategoryMappingService extends BaseService
{
    private $category_mapping_srv;

    public function __construct()
    {
        parent::__construct();
        $this->categoryMappingService = new CategoryMappingService;
    }

    public function get_cat_list($where = [], $option = [], $classname = "")
    {
        return $this->dao->get_cat_list($where = [], $option = [], $classname = "");
    }

    public function get_category_mapping_srv()
    {
        return $this->categoryMappingService;
    }

    public function getGoogleCategoryMappingList($where = [], $option = [])
    {
        return $this->getDao('ExtCategoryMapping')->getGoogleCategoryMappingList($where, $option);
    }

    public function getCategoryCombination($where = [], $option = [])
    {
        return $this->getDao('ExtCategoryMapping')->getCategoryCombination($where, $option);
    }

    public function createNewExtCategoryMapping($obj)
    {
        $newObj = new \ExtCategoryMappingVo();

        // id come from VB is not reliable, should use auto-increment id
        $newObj->setId((string) $obj->id);
        $this->updateExtCategoryMapping($newObj, $obj);

        return $newObj;
    }

    public function updateExtCategoryMapping($newObj, $oldObj)
    {
        $newObj->setExtParty((string) $oldObj->ext_party);
        $newObj->setCategoryId((string) $oldObj->category_id);
        $newObj->setExtId((string) $oldObj->ext_id);
        $newObj->setCountryId((string) $oldObj->country_id);
        $newObj->setStatus((string) $oldObj->status);
    }

}

?>
