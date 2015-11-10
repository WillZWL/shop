<?php
namespace ESG\Panther\Models\Order;

use ESG\Panther\Service\SoService;
use ESG\Panther\Service\RefundService;

class CreditCheckModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->soService = new SoService;
        $this->refundService = new RefundService;
    }

    public function getList($dao, $where = [], $option = [])
    {
        return $this->getDao($dao)->getList($where, $option);
    }

    public function getNumRows($dao, $where = [])
    {
        return $this->getDao($dao)->getNumRows($where);
    }

    public function get($dao, $where = [])
    {
        return $this->getDao($dao)->get($where);
    }

    public function update($dao, $obj)
    {
        return $this->getDao($dao)->update($obj);
    }

    public function add($dao, $obj)
    {
        return $this->getDao($dao)->insert($obj);
    }

    public function includeVo($dao)
    {
        return $this->getDao($dao)->get();
    }

    public function createRefundFromCommunicationCenter($so_no = "", $refund_parameter = array())
    {
        if (($so_no == '') || (empty($refund_parameter))) {
            return FALSE;
        } else {
            return $this->refundService->createRefundFromCommunicationCenter($so_no, $refund_parameter);
        }
    }

    public function getCreditCheckList($where = array(), $option = array(), $type = "")
    {
        return $this->soService->getCreditCheckList($where, $option, $type);
    }

    public function getCreditCheckListCount($where = array(), $option = array(), $type = "")
    {
        return $this->soService->getDao('So')->getCreditCheckList($where, $option, $type);
    }

    public function getPmgwCardList($where = array(), $option = array())
    {
        return $this->soService->getDao('PmgwCard')->getList($where = array(), $option = array());
    }

    public function getOrderNote($where = array(), $option = array())
    {
        return $this->soService->getDao('OrderNotes')->getList($where, $option);
    }

    public function addOrderNote($so_no, $notes)
    {
        $obj = $this->soService->getDao('OrderNotes')->get();
        $obj->set_so_no($so_no);
        $obj->set_type('O');
        $obj->set_note($notes);
        return $this->soService->getDao('OrderNotes')->insert($obj);
    }
}
