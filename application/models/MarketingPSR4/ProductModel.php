<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\ProductService;

class ProductModel extends \CI_Model
{
	public $productService;

    public function __construct()
    {
        parent::__construct();
        $this->productService = new ProductService;
    }

    public function getProductInfo($where = [], $option = [])
    {
        return $this->productService->getProductInfo($where, $option);
    }

    public function getCreateProductOptions()
    {
        return $this->productService->getCreateProductOptions();
    }

    public function getProductCategoryReport()
    {
        $where = array();
        $where['m.ext_sku is not null'] = NULL;

        $option = array();
        $option['limit'] = -1;
        $option['orderby'] = 'm.ext_sku';

        $result = array();
        $result['filename'] = 'product_category_report.csv';
        $result['output'] = $this->productService->getProductCategoryReport($where, $option);

        return $result;
    }

}
