<?php
namespace ESG\Panther\Service;

abstract class PlatformPriceService extends BaseService
{
    private $dto_classname;
    private $dto;
    private $platform;
    private $price;
    private $cost;
    private $supplier_service;
    private $warehouse_service;

    private $show_decl_vat = false;

    public function __construct()
    {
        parent::__construct();
        include_once APPPATH . "libraries/service/Supplier_service.php";
        $this->set_supplier_service(new Supplier_service());
        include_once APPPATH . "libraries/service/Warehouse_service.php";

        $this->show_decl_vat = false;
        if (in_array("admin", $_SESSION['user']["role_id"]) or in_array("alan", $_SESSION['user']["role_id"]))
            $this->show_decl_vat = true;
    }

    public function set_supplier_service(Base_service $svc)
    {
        $this->supplier_service = $svc;
    }

    abstract public function get_dto();

    abstract public function set_dto(Base_dto $obj);

    public function get_dto_classname()
    {
        return $this->dto_classname;
    }

    public function set_dto_classname($classname)
    {
        $this->dto_classname = $classname;
    }

    public function get_warehouse_service()
    {
        return $this->warehouse_service;
    }

    public function set_warehouse_service(Base_service $svc)
    {
        $this->warehouse_service = $svc;
    }
}


