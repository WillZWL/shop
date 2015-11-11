<?php
namespace ESG\Panther\Service;

class DelayedOrderService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllMinorDelayOrder($where = [], $option = [])
    {
        return $this->getDao('DelayedOrder')->getAllMinorDelayOrder($where, $option);
    }

    public function hasOosStatus($where = [], $option = [])
    {
        return $this->getDao('DelayedOrder')->hasOosStatus($where, $option);
    }

    public function getDelayOrder($where = [], $option = [])
    {
        return $this->getDao('DelayedOrder')->getDelayOrder($where, $option);
    }

    public function isDelayOrder($so_no)
    {
        $where = $option = [];
        $where["deor.so_no"] = $so_no;
        $where["deor.status not in (3, 4)"] = NULL;
        $option["limit"] = 1;
        return $this->getDao('DelayedOrder')->isDelayOrder($where, $option);
    }
}
