<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\CategoryService;
use ESG\Panther\Service\WebsiteService;
use ESG\Panther\Service\PlatformBizVarService;
use ESG\Panther\Service\SellingPlatformService;
use ESG\Panther\Service\CurrencyService;
use ESG\Panther\Service\CustomClassService;
use ESG\Panther\Service\ProductService;
use ESG\Panther\Service\PriceService;
use ESG\Panther\Service\LanguageService;
use ESG\Panther\Service\ProductSpecService;
use ESG\Panther\Service\UnitService;
use ESG\Panther\Service\SubCatPlatformVarService;

class CategoryModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->categoryService = new CategoryService;
        $this->websiteService = new WebsiteService;
        $this->platformBizVarService = new PlatformBizVarService;
        $this->sellingPlatformService = new SellingPlatformService;
        $this->currencyService = new CurrencyService;
        $this->customClassService = new CustomClassService;
        $this->productService = new ProductService;
        $this->priceService = new PriceService;
        $this->languageService = new LanguageService;
        $this->unitService = new UnitService;
        $this->subCatPlatformVarService = new SubCatPlatformVarService;
        $this->productSpecService = new ProductSpecService;
    }

    public function saveProdSpec($cpsObjList, $cat_id)
    {
        return $this->productSpecService->saveProdSpec($cpsObjList, $cat_id);
    }

    public function getProductForCategoryPage($platformId, $catId, $catLevel, $brandId, &$sort, &$rpp, &$page, $langId)
    {
        $where = [];
        $where['pr.platform_id'] = $platformId;
        $where['p.status'] = 2;

        switch ($catLevel) {
            case 1:
                $where['p.cat_id'] = $catId;
                break;
            case 2:
                $where['p.sub_cat_id'] = $catId;
                break;
            case 3:
                $where['p.sub_sub_cat_id'] = $catId;
                break;
            default:
        }

        if ($brandId) {
            $where['br.id'] = $brandId;
        }

        if (!$sort) {
            $sort = 'priority_asc';
        }

        switch ($sort) {
            case 'pop_desc':
                $option["orderby"] = "pr.sales_qty desc";
                break;
            case 'price_asc':
                $option["orderby"] = "pr.price ASC";
                break;
            case 'price_desc':
                $option["orderby"] = "pr.price DESC";
                break;
            case 'latest_asc':
                $option["orderby"] = "sc.priority asc, p.create_on ASC";
                break;
            case 'latest_desc':
                $option["orderby"] = "sc.priority asc, p.create_on DESC";
                break;
            //#2580, sort by priority of the sub_category
            case 'priority_asc':
                $option["orderby"] = "sc.priority asc, pr.sales_qty desc";
                break;
            default:
                $option["orderby"] = "sc.priority asc, p.create_on DESC";
                break;
        }

        #SBF2580, push all the Arriving stock to bottom before Out of stock
        $option["orderby"] = "is_arr asc, " . $option["orderby"];

        #SBF1905, push all the Out of stock to bottom
        $option["orderby"] = "is_oos asc, " . $option["orderby"];


        if (!$rpp) {
            $rpp = 12;
        }

        if (!$page) {
            $page = 1;
        }

        $option['limit'] = $rpp;
        $option['offset'] = $rpp * ($page - 1);

        $total = $this->getWebsiteCatPageProductList($where, ["num_rows" => 1]);
        if ($sku_list = $this->getWebsiteCatPageProductList($where, $option)) {
            $obj_list = $this->priceService->getListingInfoList($sku_list, $platformId, $langId, []);
        }

        return array("total" => $total, "sku_list" => $sku_list, "obj_list" => $obj_list, "criteria" => $where);
    }

    public function getlistcount($level, $id = "")
    {
        return $this->categoryService->getListWithChildCount($level, $id);
    }

    public function getList($where = [])
    {
        return $this->categoryService->getList($where);
    }

    public function getParent($level, $id)
    {
        return $this->categoryService->getParent($level, $id);
    }

    public function updateCategory($obj)
    {
        return $this->categoryService->update($obj);
    }

    public function addCategory($obj)
    {
        return $this->categoryService->add($obj);
    }

    public function autoload()
    {
        $this->categoryService->loadVo();
    }

    public function autoloadScpv()
    {
        $this->subCatPlatformVarService->loadVo();
    }

    public function getProductBySscat($id)
    {
        return $this->productService->getDao('Product')->getList(["sub_sub_cat_id" => $id]);
    }

    public function countProduct($id)
    {
        return $this->productService->getDao('Product')->getNumRows(["sub_sub_cat_id" => $id]);
    }

    public function getCatListIndex($where, $option)
    {
        return $this->categoryService->getCatListIndex($where, $option);
    }

    public function getScpvObj($where = [])
    {
        return $this->subCatPlatformVarService->getDao('SubCatPlatformVar')->get($where);
    }

    public function getReplaceScpvObj($where = [])
    {
        return $this->platformBizVarService->getDao('PlatformBizVar')->get($where);
    }

    public function getScpvObjNew()
    {
        return $this->subCatPlatformVarService->getDao('SubCatPlatformVar')->get();
    }

    public function insertScpv($obj)
    {
        return $this->subCatPlatformVarService->getDao('SubCatPlatformVar')->insert($obj);
    }

    public function updateScpv($obj)
    {
        return $this->subCatPlatformVarService->getDao('SubCatPlatformVar')->update($obj);
    }

    public function getSellingPlatform($where = [], $option = [])
    {
        return $this->sellingPlatformService->getList($where, $option);
    }

    public function getCurrencyList()
    {
        return $this->currencyService->getDao('Currency')->getList();
    }

    public function getCustomClassList()
    {
        return $this->customClassService->getDao('CustomClassification')->getList();
    }

    public function getCustomClassListWithPlatformId($platform_id = "WEBHK")
    {
        return $this->customClassService->getDao('CustomClassification')->getCustomClassListWithPlatformId($platform_id);
    }

    public function getWebsiteCatPageProductList($where = [], $option = [])
    {
        return $this->productService->getWebsiteCatPageProductList($where, $option);
    }

    public function getCatFilterGridInfo($level, $where = [], $option = [])
    {
        return $this->categoryService->getCatFilterGridInfo($level, $where, $option);
    }

    public function getBrandFilterGridInfo($where = [], $option = [])
    {
        return $this->categoryService->getBrandFilterGridInfo($where, $option);
    }

    public function getDisplayList($catid, $type = "cat", $brand = "", $platform_id = "", $min_price = "", $max_price = "")
    {
        return $this->categoryService->getDisplayList($catid, $type, $brand, $platform_id, $min_price, $max_price);
    }

    public function getVideoDisplayList($catid, $type = "cat", $brand = "", $platform_id = "", $min_price = "", $max_price = "")
    {
        return $this->categoryService->getVideoDisplayList($catid, $type, $brand, $platform_id, $min_price, $max_price);
    }

    public function getDisplayCatlist($catid)
    {
        return $this->categoryService->getDisplayCatlist($catid);
    }

    public function getlistcnt($level, $id, $status)
    {
        return $this->categoryService->getDao('Category')->getChildWithCount($level, $id, $status);
    }

    public function getColourCode()
    {
        return $this->categoryService->getColourList();
    }

    public function getListedCat($platform_id = "")
    {
        return $this->categoryService->getListedCat($platform_id);
    }

    public function getFullCatList()
    {
        return $this->categoryService->getFullCatList();
    }

    public function getListedCatTree($platform_id = "WEBHK")
    {
        return $this->categoryService->getListedCatTree($platform_id);
    }

    public function getCategory($where = [])
    {
        return $this->categoryService->getCategory($where);
    }

    public function getProdSpecGroupList($where = [], $option = [])
    {
        return $this->productSpecService->getProdSpecGroupList($where, $option);
    }

    public function getUnitList($where = [], $option = [])
    {
        return $this->unitService->getUnitList($where, $option);
    }

    public function getUnitTypeList($where = [], $option = [])
    {
        return $this->unitService->getUnitTypeList($where, $option);
    }

    public function getProdSpec($where = [])
    {
        return $this->productSpecService->getProdSpec($where);
    }

    public function getProdSpecList($where = [], $option = [])
    {
        return $this->productSpecService->getProdSpecList($where, $option);
    }

    public function getNoOfRowPsl($where = [])
    {
        return $this->productSpecService->getNoOfRowPsl($where);
    }

    public function addProdSpec($prod_spec_obj)
    {
        return $this->productSpecService->addProdSpec($prod_spec_obj);
    }

    public function updateProdSpec($prod_spec_obj)
    {
        return $this->productSpecService->updateProdSpec($prod_spec_obj);
    }

    public function getCatExtObj($where = [])
    {
        return $this->categoryService->getCatExtObj($where);
    }

    public function getCatExtList($where = [], $option = [])
    {
        return $this->categoryService->getCatExtList($where, $option);
    }	

    public function getCatMenuList($where = [], $option = [])
    {
        return $this->categoryService->getCatMenuList($where, $option);
    }

    public function getCatContObj($where = [])
    {
        return $this->categoryService->getCatContObj($where);
    }

    public function getCatContList($where = [], $option = [])
    {
        return $this->categoryService->getCatContList($where, $option);
    }

    public function getCatProdSpecList($where = [], $option = [])
    {
        return $this->productSpecService->getCatProdSpecList($where, $option);
    }

    public function getFullCpsList($cat_id)
    {
        return $this->productSpecService->getFullCpsList($cat_id);
    }

    public function getCps($where = [])
    {
        return $this->productSpecService->getCps($where);
    }

    public function insertCps($obj)
    {
        return $this->productSpecService->insertCps($obj);
    }

    public function updateCps($obj)
    {
        return $this->productSpecService->updateCps($obj);
    }

    public function getParentCatId($cat_id)
    {
        return $this->categoryService->getParentCatId($cat_id);
    }

    public function getCatUrl($cat_id, $relative_path = FALSE)
    {
        return $this->websiteService->getCatUrl($cat_id, $relative_path);
    }

    public function getProdUrl($sku, $relative_path = FALSE)
    {
        return $this->websiteService->getProdUrl($sku, $relative_path);
    }

    public function getCatInfoWithLang($where = [], $option = [])
    {
        return $this->categoryService->getCatInfoWithLang($where, $option);
    }
}
