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

}
