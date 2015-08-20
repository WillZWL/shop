<?php
namespace AtomV2\Models\Marketing;

use AtomV2\Service\ProductService;

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
}
