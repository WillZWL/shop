<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\WarehouseService;

abstract class PlatformPriceService extends BaseService
{
    private $dtoClassname;
    private $dto;
    private $platform;
    private $price;
    private $cost;
    private $supplier_service;

    private $show_decl_vat = false;

    public function __construct()
    {
        parent::__construct();
        //include_once APPPATH . "libraries/service/Supplier_service.php";

        $this->warehouseService = new WarehouseService;

        $this->show_decl_vat = false;
        if (in_array("admin", $_SESSION['user']["role_id"]) or in_array("alan", $_SESSION['user']["role_id"]))
            $this->show_decl_vat = true;
    }

    abstract public function getDto();

    abstract public function setDto($obj);

    public function getDtoClassname()
    {
        return $this->dtoClassname;
    }

    public function setDtoClassname($classname)
    {
        $this->dtoClassname = $classname;
    }
}


