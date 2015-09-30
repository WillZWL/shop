<?php
namespace ESG\Panther\Service;

class QuickSearchService extends BaseService
{
    public function __construct()
    {
        parent::__construct();

        $this->soService = new SoService;
        $this->soPriorityScoreService = new SoPriorityScoreService;
    }

    public function getOrderNote($where = [], $option = [])
    {
        return $this->getDao('OrderNotes')->getList($where, $option);
    }

    public function saveExpectDeliveryDate($so_no, $expect_delivery_date)
    {
        $inputValue = ["expect_delivery_date" => $expect_delivery_date];
        $update_result = $this->updateCsOrderQuery($so_no, $inputValue);

        if ($expect_delivery_date !== false) {
            $order_note_obj = $this->getOrderNotes();
            $order_note_obj->setSoNo($so_no);
            $order_note_obj->setType("O");
            $order_note_obj->setNote("FM New EDD - " . $expect_delivery_date);
            $this->soService->getDao('OrderNotes')->insert($order_note_obj);

            // duplicate to other split child orders
            if ($so_obj = $this->soService->getDao('So')->get(["so_no" => $so_no])) {
                $split_so_group = $so_obj->getSplitSoGroup();

                if ($split_so_group && $so_no != $split_so_group) {
                    if ($split_child_list = $this->soService->getDao('So')->getList(["split_so_group" => $split_so_group, "so_no != $so_no" => null, "status != 0" => NULL])) {
                        foreach ($split_child_list as $key => $childobj) {
                            $update_result = $this->updateCsOrderQuery($childobj->getSoNo(), $inputValue);

                            $order_note_obj = $this->getOrderNotes();
                            $order_note_obj->setSoNo($childobj->getSoNo());
                            $order_note_obj->setType("O");
                            $order_note_obj->setNote("FM New EDD - " . $expect_delivery_date);
                            $this->soService->getDao('OrderNotes')->insert($order_note_obj);
                        }
                    }
                }
            }
        }
    }

    public function saveChasingOrder($so_no, $chasing_order)
    {
        $inputValue = ["chasing_order" => $chasing_order];
        $this->updateCsOrderQuery($so_no, $inputValue);

        // duplicate to other split child orders
        if ($so_obj = $this->soService->getDao('So')->get(["so_no" => $so_no])) {
            $split_so_group = $so_obj->getSplitSoGroup();

            if ($split_so_group && $so_no != $split_so_group) {
                if ($split_child_list = $this->soService->getDao('So')->getList(["split_so_group" => $split_so_group, "so_no != $so_no" => null, "status != 0" => NULL])) {
                    $error_mssage = "";
                    foreach ($split_child_list as $key => $childobj) {
                        $this->updateCsOrderQuery($childobj->getSoNo(), $inputValue);
                    }
                }
            }
        }
    }

    public function updateCsOrderQuery($so_no, $inputValue)
    {
        $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
        if (isset($inputValue["chasing_order"])) {
            $so_obj->setCsCustomerQuery(($so_obj->getCsCustomerQuery() & ~1) | $inputValue["chasing_order"]);
        }
        if (isset($inputValue["expect_delivery_date"])) {
            $so_obj->setExpectDeliveryDate($inputValue["expect_delivery_date"]);
        }
        return $this->getDao('So')->update($so_obj);
    }

    public function saveOrderNotes($so_no, $note)
    {
        $vo = $this->getOrderNotes();
        $obj = clone $vo;
        $obj->setSoNo($so_no);
        $obj->setType('O');
        $obj->setNote($note);
        $ret = $this->soService->getDao('OrderNotes')->insert($obj);
        if ($ret === FALSE) {
            $_SESSION["NOTICE"] = "add_note_failed";
        }
    }

    public function getPriorityScore($so_no, $biz_type)
    {
        $result = ["score" => 0, "highlight" => 0];

        $so_obj = $this->getDao('So')->get(["so_no" => $so_no]);
        $days = $this->soService->getDays(strtotime($so_obj->getOrderCreateDate()), mktime());
        $margin_score = $this->soPriorityScoreService->hitMarginRule($so_no, $biz_type, $days, true);

        if ($margin_score > 0) {
            $result["highlight"] = $margin_score;
        }

        $result["score"] = $this->soService->getPriorityScore($so_no);

        return $result;
    }

    public function getOrderHistory($where = [])
    {
        return $this->getDao('OrderStatusHistory')->getListWithUsername($where);
    }

    public function getOrderNotes($where = [])
    {
        if (empty($where)) {
            return $this->getDao('OrderNotes')->get();
        } else {
            return $this->getDao('OrderNotes')->getListWithName($where);
        }
    }

    public function prepareLinkedOrders($so_obj)
    {
    $where = ["so.status >=" => 1];
        $option = ["limit" => -1, "orderby" => "so_no"];
        if (($so_obj->getParentSoNo() != null) && ($so_obj->getParentSoNo() != "")) {
            $where["so.parent_so_no"] = $so_obj->getParentSoNo();
            $first_so_no = $so_obj->getParentSoNo();
        } else {
            $where["so.parent_so_no"] = $so_obj->getSoNo();
            $first_so_no = $so_obj->getSoNo();
        }
        $so_list = $this->getDao('So')->getSoWithReason($where, $option);
        $first_so = $this->getDao('So')->getSoWithReason(array("so.so_no" => $first_so_no));

        if ($first_so) {
            if (sizeof((array)$so_list) > 0)
                return array_merge((array)$first_so, (array)$so_list);
            else
                return [];
        }
    }
}
