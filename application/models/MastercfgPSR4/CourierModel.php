<?php
use ESG\Panther\Service\CourierService;
use ESG\Panther\Service\CurrencyService;
class CourierModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->courierService = new CourierService;
        $this->currencyService = new CurrencyService;
    }

    public function getCourierList($where = array(), $option = array())
    {
        $data["courierlist"] = $this->courierService->getList($where, $option);
        $data["total"] = $this->courierService->getNumRows($where);
        return $data;
    }

    public function getCourier($where = array())
    {
        return $this->courierService->get($where);
    }

    public function update_courier($obj)
    {
        return $this->courierService->update($obj);
    }

    public function include_courier_vo()
    {
        return $this->courierService->includeVo();
    }

    public function addCourier(Base_vo $obj)
    {
        return $this->courierService->insert($obj);
    }

    public function getCurrencyList($where = array())
    {
        return $this->currencyService->getList($where, $option);
    }
}