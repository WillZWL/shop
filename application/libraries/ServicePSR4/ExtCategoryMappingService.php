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
        $this->setDao(new ExtCategoryMappingDao);
        $this->setCategoryMappingDao(new CategoryMappingDao);
        $this->categoryMappingService = new CategoryMappingService;
    }

    public function get_cat_list($where = [], $option = [], $classname = "")
    {
        return $this->dao->get_cat_list($where = [], $option = [], $classname = "");
    }

    public function getCategoryMappingDao()
    {
        return $this->categoryMappingDao;
    }

    public function setCategoryMappingDao($dao)
    {
        $this->categoryMappingDao = $dao;
    }

    public function get_category_mapping_srv()
    {
        return $this->categoryMappingService;
    }

    public function get_google_category_mapping_list($where = [], $option = [])
    {
        return $this->getDao()->getCategoryCombination($where, $option);
    }

    public function get_category_combination($where = [], $option = [])
    {
        return $this->getDao()->getCategoryCombination($where, $option);
    }

}

?>
