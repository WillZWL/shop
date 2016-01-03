<?php
namespace ESG\Panther\Models\Marketing;

use ESG\Panther\Service\ComplementaryAccService;
use ESG\Panther\Service\ProductService;

class ComplementaryAccModel extends \CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->ComplementaryAccService = new ComplementaryAccService;
        $this->ProductService = new ProductService;
    }

    public function getAccessoryCatidArr()
    {
        return $this->ComplementaryAccService->getAccessoryCatidArr();
    }

    public function getProductList($where = array(), $option = array())
    {
        return $this->ProductService->getDao('Product')->getListWithName($where, $option, "Product_list_w_name_dto");
    }

    public function getProductListTotal($where = array())
    {
        return $this->ProductService->getDao('Product')->getListWithName($where, array("num_rows" => 1));
    }

    /*public function getList($service, $where = array(), $option = array())
    {
        $service = $service . "_service";
        return $this->$service->get_list($where, $option);
    }*/

    public function checkCat($sku = "", $is_ca = true)
    {
        return $this->ComplementaryAccService->checkCat($sku, $is_ca);
    }

}
