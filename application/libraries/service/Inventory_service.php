<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Inventory_service extends Base_service
{
    private $vpi_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Inventory_dao.php");
        $this->set_dao(new Inventory_dao());
        include_once(APPPATH."libraries/dao/V_prod_inventory_dao.php");
        $this->set_vpi_dao(new V_prod_inventory_dao());
    }

    public function get_vpi_dao()
    {
        return $this->vpi_dao;
    }

    public function set_vpi_dao(Base_dao $dao)
    {
        $this->vpi_dao = $dao;
    }

    public function get_inventory($where=array())
    {
        return $this->get_dao()->get_inventory_list($where);
    }

    public function get_stock_valuation($where=array())
    {
        return $this->get_dao()->get_stock_valuation($where);
    }

    public function set_surplus_quantity($sku, $qty)
    {
        return $this->get_dao()->set_surplus_quantity($sku, $qty);
    }
}
