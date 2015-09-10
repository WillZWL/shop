<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\RaProdCatService;
use ESG\Panther\Service\CategoryService;
use ESG\Panther\Service\WsgbPriceService;

class RaProdCatModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->raProdCatService = new RaProdCatService;
        $this->categoryService = new CategoryService;
        $this->wsgbPriceService = new WsgbPriceService;
        // $this->load->library('service/warrantyService');
    }

    public function autoload()
    {
        $this->raProdCatService->getDao()->get();
    }

    public function getRaObj($id = "")
    {
        if ($id == "") {
            $ret = $this->raProdCatService->getDao()->get();
        } else {
            $ret = $this->raProdCatService->getDao()->get(["ss_cat_id" => $id]);
        }

        return $ret;
    }

    public function insert($data)
    {
        return $this->raProdCatService->getDao()->insert($data);
    }

    public function update($data)
    {
        return $this->raProdCatService->getDao()->update($data);
    }

    public function  getScatList()
    {
        return $this->categoryService->getDao()->getList(["level" => "2"], ["limit" => -1]);
    }

    public function  getSscatList()
    {
        return $this->categoryService->getLao()->getList(["level" => "3"], ["limit" => -1]);
    }

    public function getSscatProd($id)
    {
        return $this->wsgbPriceService->getProductListWithProfit(["sub_sub_cat_id" => $id]);
    }

    public function get_raprod_prod_obj($prodid = "")
    {
        if ($prodid <> "") {
            return $this->ra_prod_prod_service->get_dao()->get(["prod_id" => $prodid]);
        } else {
            return $this->ra_prod_prod_service->get_dao()->get([]);
        }
    }

    public function getWarrantyCatList()
    {
        return $this->categoryService->getWarrantyCatList();
    }

    public function getWarrantyBySku($sku = "", $platform_id = "")
    {
        return $this->warrantyService->getWarrantyBySku($sku, $platform_id);
    }
}
