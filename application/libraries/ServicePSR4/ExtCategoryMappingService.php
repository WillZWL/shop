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

}

?>
